<?php
/*
 * message.php
 * 
 * Copyright (c) 2014 Andrew Jordan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the 
 * "Software"), to deal in the Software without restriction, including 
 * without limitation the rights to use, copy, modify, merge, publish, 
 * distribute, sublicense, and/or sell copies of the Software, and to 
 * permit persons to whom the Software is furnished to do so, subject to 
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be 
 * included in all copies or substantial portions of the Software. 
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
require "includes/init.php";
require "includes/Parser.class.php";
require "includes/Message.class.php";
require "includes/Tag.class.php";

function in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle)
            ||(is_array($item)
            && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

// Check authetication
if ($auth === true) {
    // Check that message ID is valid
    if (is_numeric(@$_GET['id']) && is_numeric(@$_GET['topic'])) {

        if (is_numeric(@$_GET['r'])) {
            $revision_no = intval($_GET['r']);
        } else {
            $revision_no = 0;
        }
        if (@$_GET['link'] == 1) {
            $link = true;
        } else {
            $link = false;
        }
        $message_id = intval($_GET['id']);
        $topic_id = intval($_GET['topic']);

        $csrf_salt = "message".$message_id.$topic_id.$authUser->getUserId();
        $csrf->setPageSalt($csrf_salt);
        try {
            $message = new Message($site, $message_id, $revision_no, $link);
            $mod_message_delete = $authUser->checkPermissions("topic_delete_message");
            // Get message contents and parse them
            // for HTML tags.
            if (isset($_POST['action'])) {
                if (($_POST['action'] == 1 && $authUser->getUserID() == $message->getUserID() && $message->getState() == 0)) {
                    if ($csrf->validateToken($_POST['token'])) {
                        $message->delete($_POST['action']);
                    }
                } elseif (($_POST['action'] == 1 && $mod_message_delete == 1 && $message->getState() == 0)) {
                    if ($csrf->validateToken($_POST['token'])) {
                        $message->delete(2, $authUser->getUserId(), @$_POST['reason']);
                    }
                }
            }

            $tag = new Tag($authUser->getUserId());
            $tags = $tag->getObjectTags($topic_id, 1);

            if (in_array_r("Anonymous", $tags)) {
                $anonymous = true;
                $user_id = "-1";
                $username = "Human #1";
            } else {
                $anonymous = false;
                $username = $message->getUsername();
                $user_id = $message->getUserId();
            }

            $message_content = $message->getMessage();
            $parser = new Parser($db);
            $message_content = $parser->parse($message_content);
            
            // Sperate signature from message body
            $signature = explode("---", $message_content);
            
            // JSON output for AJAX calls
            // May move to ajax.php
            if (@$_GET['output'] == "json") {
                $signature = explode("---", $message->getMessage());
                $content = array("id" => $message_id,
                                "topic" => $topic_id,
                                "r" => $message->getRevisionID(),
                                "message" => $signature[0],
                                "signature" => @$signature[1]);
                header("Content-Type: application/json");
                print json_encode($content);
            } else {
                // Assign message data to template
                // varibles
                $smarty->assign("message", $message_content);
                $smarty->assign("link", $link);
                $smarty->assign("posted", $message->getPosted());
                $smarty->assign("revision_history", $message->getRevisions());
                $smarty->assign(
                    "topic_title",
                    htmlentities($message->getTitle())
                );
                $smarty->assign(
                    "link_title",
                    htmlentities($message->getTitle())
                );

                $message_user = new User($site, $message->getUserId());

                $smarty->assign("topic_id", $topic_id);
                $smarty->assign("revision_no", $message->getRevisionID());
                $smarty->assign("message_id", $message_id);
                $smarty->assign("m_user_id", $user_id);
                $smarty->assign("m_username", $username);
                $smarty->assign("token", $csrf->getToken());
                $smarty->assign("message_deleted", $message->getState());
                $smarty->assign("m_avatar", $message_user->getAvatar());
                $smarty->assign("mod_message_delete", $mod_message_delete);

                // Set template page
                $display = "message.tpl";
                $page_title = "Message Detail";
                include "includes/deinit.php";
            }
        } catch (exception $e) {
            print $e;
            include "404.php";
        }
    } else {
        include "404.php";
    }
} else {
    include "404.php";
}
