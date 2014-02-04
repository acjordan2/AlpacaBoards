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
        $message = new MessageRevision($db, $message_id, $revision_no, $link);
        if ($message->doesExist()) {
            // Get message contents and parse them
            // for HTML tags. 
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
                    "topic_title", htmlentities($message->getTopicTitle())
                );
                $smarty->assign(
                    "link_title", htmlentities($message->getLinkTitle())
                );
                $smarty->assign(
                    "board_title", htmlentities($message->getBoardTitle())
                );
                $smarty->assign("topic_id", $topic_id);
                $smarty->assign("board_id", $message->getBoardID());
                $smarty->assign("revision_no", $message->getRevisionID());
                $smarty->assign("message_id", $message_id);
                $smarty->assign("m_user_id", $message->getUserID());
                $smarty->assign("m_username", $message->getUsername());
                $smarty->assign("token", $csrf->getToken());
                $smarty->assign("m_avatar", $message->getAvatar());

                // Set template page
                $display = "message.tpl";
                $page_title = "Message Detail";
                include "includes/deinit.php";
            }
        } else {
            include "404.php";
        }
    } else {
        include "404.php";
    }
} else {
    include "404.php";
}

?>
