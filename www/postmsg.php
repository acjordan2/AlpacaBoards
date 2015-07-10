<?php
/*
 * postmsg.php
 * 
 * Copyright (c) 2012 Andrew Jordan
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
require "includes/Message.class.php";
require "includes/Link.class.php";
require "includes/Parser.class.php";
require "includes/Tag.class.php";

if ($auth === true) {

	$salt_string = "postmsg";
    if (isset($_REQUEST['topic'])) {
        $salt_string .= "topic".$_REQUEST['topic'];
	}
	elseif (isset($_REQUEST['link'])) {
		$salt_string .= "link".$_REQUEST['link'];
    } 
    $csrf->setPageSalt($salt_string);

    $parser = new Parser();
    $tag = new Tag($authUser->getUserId());

    $message = "";
    $query_string = "?";
    $edit = false;
    $new_topic = false;

    if (isset($_REQUEST['topic']) && is_numeric($_REQUEST['topic'])) {
        $topic_id = intval($_REQUEST['topic']);
        $query_string .= "topic=".$topic_id;
        try {
            $topic = new Topic($authUser, $parser, $topic_id);
            if ($topic->hasTag("Archived")) {
                include "403.php";
            }
            // Add a quote to the message post
            if (isset($_GET['quote']) && is_numeric($_GET['quote'])) {
                try {
                    $quote_id = $_GET['quote'];
                    $query_string .= "&quote=".$quote_id;
                    $quote = new Message($site, $quote_id);
                    $quote_body = $quote->getMessage(true);
                    $message .= $quote_body;

                } catch (exception $e) {
                    // Message ID for quote does not exist
                    include "404.php";
                }
            } elseif (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
                $message_id = $_REQUEST['id'];
                $query_string .= "&id=".$message_id;
                try {
                    $message_edit = new Message($site, $message_id);
                    if ($message_edit->getState() == 0 && $message_edit->getUserId() == $authUser->getUserId()) {
                        $message = $message_edit->getMessage();
                        $edit = true;
                        $smarty->assign("message_id", $message_id);
                    } else {
                        include "403.php";
                    }
                } catch (exception $e) {
                    // Message ID does not exist
                    include "404.php";
                }
            }

            if (isset($_POST['submit'])) {
                // Post new message
                if ($csrf->validateToken($_POST['token'])) {
                    $message = $_POST['message'];
                    if (isset($_POST['preview']) && $_POST['preview'] == true) {
                        $message = base64_decode($message);
                    }
                    if (isset($message_id) && $edit == true) {
                        $message_edit->editMessage($message);
                    } else {
                        $post = $topic->postMessage($message);
                    }

                    if ($post === true) {
                        header("Location: ./showmessages.php?topic=".$topic_id);
                        exit();
                    } else {
                        $smarty->assign("post_again", $post);
                    }
                }
            }

            $smarty->assign("topic_title", htmlentities($topic->getTopicTitle()));
            $smarty->assign("topic", $topic->getTopicID());

        } catch (exception $e) {
            include "404.php";
        }
    } elseif (isset($_GET['link']) && is_numeric($_GET['link'])) {
        try {
            $link_id = intval($_GET['link']);
            $link = new Link($authUser, $parser, $tag, $link_id);

            $query_string .= "link=".$link_id;


            // Add a quote to the message post
            if (isset($_GET['quote']) && is_numeric($_GET['quote'])) {
                try {
                    $quote_id = $_GET['quote'];
                    $query_string .= "&quote=".$quote_id;
                    $quote = new Message($site, $quote_id);
                    $quote_body = $quote->getMessage(true);
                    $message .= $quote_body;

                } catch (exception $e) {
                    // Message ID for quote does not exist
                    include "404.php";
                }
            } elseif (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
                $message_id = $_REQUEST['id'];
                $query_string .= "&id=".$message_id;
                try {
                    $message_edit = new Message($site, $message_id, null, 2);
                    if ($message_edit->getState() == 0 && $message_edit->getUserId() == $authUser->getUserId()) {
                        $message = $message_edit->getMessage();
                        $edit = true;
                        $smarty->assign("message_id", $message_id);
                    } else {
                        include "403.php";
                    }
                } catch (exception $e) {
                    // Message ID does not exist
                    include "404.php";
                }
            }

            if (isset($_POST['submit'])) {
                // Post new message
                if ($csrf->validateToken($_POST['token'])) {
                    $message = $_POST['message'];
                    if (isset($_POST['preview']) && $_POST['preview'] == true) {
                        $message = base64_decode($message);
                    }
                    if (isset($message_id) && $edit == true) {
                        $message_edit->editMessage($message);
                    } else {
                        $link->postMessage($message);
                    }
                    header("Location: ./linkme.php?l=".$link_id);
                    exit();
                }
            }


            $smarty->assign("link_title", htmlentities($link->getTitle()));
            $smarty->assign("link_id", $link->getLinkId());
            $smarty->assign("is_link", true);
        } catch (Exception $e) {
            include "404.php";
        }
    } else {
		// New Topic
		$smarty->assign("new_topic", true);
		if (isset($_POST['submit'])) {
			if (isset($_POST['preview']) && $_POST['preview'] == true) {
				$title = trim(base64_decode($_POST['title']));
				$message = base64_decode($_POST['message']);
			} else {
				$title = trim($_POST['title']);
				$message = $_POST['message'];
			}
			if(strlen($title) < 5 || strlen($title) > 80) {
				$smarty->assign("error_message", "Title must be between 5 and 80 characters");
			} else {
				$tags = explode(",", $_POST['tags']);
				$topic = new Topic($authUser, $parser);
				$topic_id = $topic->createTopic($title, $tags, $message);
				if ($topic_id) {
					header("Location: ./showmessages.php?topic=".$topic_id);
					exit();
				}		
			}
		}
	}

    if (isset($_POST['preview'])) {
        if ($csrf->validateToken($_POST['token'])) {
            // Preview message before posting
            $preview_message = $parser->parse($_POST['message']);
            $smarty->assign("preview_message", $preview_message);
            $smarty->assign("preview_message_raw", base64_encode($_POST['message']));
			if (isset($_POST['title'])) {
				$smarty->assign("topic_title", htmlentities($_POST['title']));
				$smarty->assign("preview_topic_title", base64_encode($_POST['title']));
			}
        }
    }

    if (isset($_POST['message'])) {
        $message = $_POST['message'];
    } elseif ($edit == false) {
        $message .= $authUser->getSignature();
    }

    $smarty->assign("query_string", $query_string);
    $smarty->assign("token", $csrf->getToken());
    $smarty->assign("message", htmlentities($message));

    $page_title = "Post Message";
    $display = "postmsg.tpl";
    include "includes/deinit.php";
} else {
    include "403.php";
}
