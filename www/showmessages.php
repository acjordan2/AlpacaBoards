<?php
/*
 * showmessages.php
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
require "includes/Topic.class.php";
require "includes/Parser.class.php";
require "includes/Tag.class.php";

if ($auth == true) {
    if (is_numeric($_GET['topic'])) {
        $topic_id = $_GET['topic'];
        // Verify topic
        try {
            $parser = new Parser();
            $topic = new Topic($authUser, $parser);
            $topic->loadTopic($topic_id);
            if (!is_numeric(@$_GET['page']) || @$_GET['page'] == null) {
                $current_page = 1;
            } else {
                $current_page = intval($_GET['page']);
            }
            // Sticky topic
            if (@$_GET['sticky'] == 1) {
                // Validate CSRF token
                if ($csrf->validateToken($_GET['token']) == true) {
                    //Check if slots are available
                    $sql4 = "SELECT COUNT(StickiedTopics.topic_id) 
                        FROM StickiedTopics WHERE StickiedTopics.created > "
                        .(time()-(60*60*24))." OR StickiedTopics.mod = 1
                        GROUP BY StickiedTopics.topic_id";
                    $statement4 = $db->query($sql4);
                    if ($statement4->rowCount() > 4) {
                        $status_message = "Only 5 topics can be pinned at a time";
                    } else {
                        //Check if the user has available stickies
                        $sql = "SELECT Inventory.inventory_id FROM Inventory 
                            LEFT JOIN ShopTransactions USING(transaction_id) 
                            WHERE ShopTransactions.user_id=".$authUser->getUserID().
                            "AND ShopTransactions.item_id=5";
                        $statement = $db->query($sql);
                        $inventory_id = $statement->fetch();
                        if ($statement->rowCount()) {
                            // Check to make sure topic isn't already stickied
                            $sql2 = "SELECT StickiedTopics.sticky_id 
                                FROM StickiedTopics WHERE StickiedTopics.topic_id=? 
                                AND StickiedTopics.created > ".(time() - 60*60*24);
                            $statement2 = $db->prepare($sql2);
                            $statement2->execute(array($topic_id));
                            if ($statement2->rowCount() < 1) {
                                $topic->pinTopic();
                                $sql3 = "DELETE FROM Inventory 
                                    WHERE inventory_id=".$inventory_id[0];
                                $statement3 = $db->query($sql3);
                                $status_message = "Topic pinned!";
                            } else {
                                $status_message = "Topic has already been pinned!";
                            }
                        } else {
                            $status_message = "You have to buy that!";
                        }
                    }
                }
                $smarty->assign("status_message", $status_message);
            }
            $messages = $topic->getMessages($current_page, @$_GET['u']);
            if (isset($_GET['u']) && is_numeric($_GET['u'])) {
                $smarty->assign("filter", true);
            }
            // Set template variables
            $smarty->assign("messages", $messages);
            $smarty->assign(
                "signature",
                str_replace(
                    "\r\n",
                    "\\n",
                    addslashes(
                        str_replace("+", " ", $authUser->getSignature())
                    )
                )
            );

            $tag = new Tag($authUser->getUserID());

            $smarty->assign("p_signature", htmlentities($authUser->getSignature()));
            $smarty->assign("topic_id", intval($topic_id));
            $smarty->assign(
                "topic_title",
                htmlentities($topic->getTopicTitle())
            );
            $smarty->assign("board_title", "Life, the Universe, and Everything");
            $smarty->assign("board_id", "42");
            $smarty->assign("page_count", $topic->getPageCount());
            $smarty->assign("current_page", $current_page);
            $smarty->assign("num_readers", $topic->getReaders());
            $smarty->assign("token", $csrf->getToken());
            $smarty->assign("action", $authUser->checkInventory(1));
            $smarty->assign("topic_tags", $tag->getObjectTags($_GET['topic'], 1));
            $display = "showmessages.tpl";
            $page_title = $topic->getTopicTitle();
            include "includes/deinit.php";
        } catch (Exception $e) {
            include "404.php";
        }
    } else {
        include "404.php";
    }
} else {
    include "403.php";
}
