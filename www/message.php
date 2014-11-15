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

if ($auth === true) {
    if (isset($_GET['id']) && (isset($_GET['topic']) || isset($_GET['link']))) {
        if (isset($_GET['topic'])) {
            $parent_id = intval($_GET['topic']);
            $type = 1;
        } elseif (isset($_GET['link'])) {
            $parent_id = intval($_GET['link']);
            $type = 2;
        }

        if (isset($_GET['r'])) {
            $revision_no = intval($_GET['r']);
        } else {
            $revision_no = 0;
        }

        $message_id = intval($_GET['id']);

        $csrf->setPageSalt("message".$message_id.$authUser->getUserId());

        try {
            $message = new Message($site, $message_id, $revision_no, $type);

            // Helps prevent enumerating messages easily since both the topic/link id
            // and the message ID need to be known
            if ($message->getParentId() == $parent_id) {

                $mod_message_delete = $authUser->checkPermissions("topic_delete_message");

                // Delete message
                if (isset($_POST['action'])) {
                    $action = $_POST['action'];
                    if ($action == 1
                        && $authUser->getUserId() == $message->getUserId()
                        && $message->getState() == 0) {
                        // Deleted by a user
                        $message->delete($action);
                    } elseif ($action == 1
                        && $mod_message_delete == 1
                        && $message->getState() == 0) {
                        // Deleted by a moderator
                        $message->delete($action, $authUser->getUserId(), $_POST['reason']);
                    }
                }

                $tag = new Tag($authUser->getUserId());
                $tag_list = $tag->getObjectTags($parent_id, $type + 1);

                $parser = new Parser();
                $message_content = $parser->parse($message->getMessage());

                $user_id = $message->getUserId();
                $username = $message->getUsername();

                if (isset($_GET['output'])) {
                    if ($_GET['output'] == "json") {
                        $signature = explode("---", $message->getMessage());
                        $content = array(
                            "id" => $message_id,
                            "topic" => $parent_id,
                            "r" => $message->getRevisionID(),
                            "message" => trim($signature[0]),
                            "signature" => @$signature[1]
                        );
                        header("Content-Type: application/json");
                        print json_encode($content);
                    }
                } else {

                    $smarty->assign("message", $message_content);
                    $smarty->assign("type", $type);
                    $smarty->assign("posted", $message->getPosted());
                    $smarty->assign("revision_history",  $message->getRevisions());
                    $smarty->assign(
                        "title",
                        htmlentities($message->getTitle())
                    );

                    $message_user = new User($site, $message->getUserId());

                    $smarty->assign("parent_id", $parent_id);
                    $smarty->assign("message", $message_content);
                    $smarty->assign("revision_no", $message->getRevisionId());
                    $smarty->assign("message_id", $message_id);
                    $smarty->assign("m_user_id", $user_id);
                    $smarty->assign("m_username", $username);
                    $smarty->assign("token", $csrf->getToken());
                    $smarty->assign("message_deleted", $message->getState());
                    $smarty->assign("m_avatar", $message_user->getAvatar());
                    $smarty->assign("mod_message_delete", $mod_message_delete);

                    $display = "message.tpl";
                    $page_title = "Message Detail";
                    include "includes/deinit.php";
                }
            } else {
                include "403.php";
            }
        } catch (exception $e) {
            print $e->getMessage();
            include "404.php";
        }
    } else {
        include "404.php";
    } 
} else {
    include "403.php";
}
