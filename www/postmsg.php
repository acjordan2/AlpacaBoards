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
 
require "includes/init.php" ;
require "includes/Board.class.php";
require "includes/Topic.class.php";
require "includes/Message.class.php";
require "includes/Link.class.php";
require "includes/Parser.class.php";
require "includes/Tag.class.php";

if ($auth === true) {
    $smarty->assign("token", $csrf->getToken());
    if (is_numeric(@$_REQUEST['topic'])) {
        $topic_id = intval($_REQUEST['topic']);
        $parser = new Parser();
        $topic = new Topic($authUser, $parser);
        $topic->loadTopic($topic_id);
        $smarty->assign("topic_id", $topic_id);
        $smarty->assign("topic_title", htmlentities($topic->getTopicTitle()));
        $smarty->assign("signature", "\n---\n".htmlentities($authUser->getSignature()));
        $smarty->assign("board_id", "42");
        if(is_numeric(@$_GET['quote'])){
            $message = new Message($db, intval($_GET['quote']));
            if($message->doesExist()){
                $smarty->assign("quote", true);
                $smarty->assign("quote_id", htmlentities($_GET['quote']));
                $smarty->assign("quote_topic", $message->getTopicID());
                $message_body = explode("\n---", $message->getMessage());
                $smarty->assign("quote_message", htmlentities(substr($message_body[0], 0, strlen($message_body[0]))));
                $smarty->assign("quote_revision", $message->getRevisionID());
            }
            else
                require("404.php");
        }
        elseif(is_numeric(@$_REQUEST['id'])){
            $message_id = intval($_REQUEST['id']);
            $smarty->assign("message_id", $message_id);
            $message = new Message($db, $message_id, $aUser_id=$authUser->getUserID());
            if($message->doesExist() && $message->isDeleted() == 0){
                $message_body = $message->getMessage();
                $smarty->assign("e_message", htmlentities($message_body));
            }else
                require("404.php");
        }
        elseif(!is_null(@$_GET['quote']))
            require("404.php");
            
        if(@$_POST['preview'] == "Preview Message"){
            $smarty->assign("preview_message", TRUE);
            $message = new Message($db, 13);
            $smarty->assign("p_message", autolink($message->formatComments(closeTags(str_replace("\n", "<br/>", htmlentities($_POST['message'], $allowed_tags))))));
        }
        elseif(@$_POST['submit'] == "Post Message"){
            if($csrf->validateToken($_REQUEST['token'])){            
                if(is_numeric($message_id))
                    $message_id = intval(@$_REQUEST['id']);
                if($topic->postMessage($_POST['message'], $message_id)){
                    $topic->getMessages();
                    header("Location: ./showmessages.php?board=".$board_id."&topic=".$topic_id."&page=1");
                }
            }
        }
        if(!is_null(@$_POST['message']))
            $smarty->assign("e_message", htmlentities(@$_POST['message']));
        $display = "postmsg.tpl";
    }
    elseif(is_numeric(@$_REQUEST['link'])){
        $link_id = $_REQUEST['link'];
        $smarty->assign("is_link", TRUE);
        $smarty->assign("preview_message", FALSE);
        $link = new Link($db, $authUser->getUserID(), $link_id);
        $link_data = $link->getLink();
        $smarty->assign("link_id", $link_id);
        $smarty->assign("link_title", $link_data['title']);
        $display = "postmsg.tpl";
        $smarty->assign("signature", "\n---\n".htmlentities($authUser->getSignature()));
        $smarty->assign("e_message", (htmlentities(@$_POST['message'])));
        if(is_numeric(@$_GET['quote'])){
            $message = new Message($db, intval($_GET['quote']), null, TRUE);
            if($message->doesExist()){
                $smarty->assign("quote", TRUE);
                $smarty->assign("quote_id", htmlentities($_GET['quote']));
                $smarty->assign("quote_topic", $message->getLinkID());
                $message_body = explode("\n---", $message->getMessage());
                $smarty->assign("quote_message", htmlentities(substr($message_body[0], 0, strlen($message_body[0])-1)));
                $smarty->assign("quote_revision", $message->getRevisionID());
            }
            else
                require("404.php");
        }
        elseif(is_numeric(@$_REQUEST['id'])){
            $message_id = intval($_REQUEST['id']);
            $smarty->assign("message_id", $message_id);
            $message = new Message($db, $message_id, $aUser_id=$authUser->getUserID(), TRUE);
            if($message->doesExist()){
                $message_body = $message->getMessage();
                $smarty->assign("e_message", htmlentities($message_body));
            }else
                require("404.php");
        }
        if(@$_POST['preview'] == "Preview Message"){
            $smarty->assign("preview_message", TRUE);
            $message = new Message($db, 13);
            $smarty->assign("p_message", autolink($message->formatComments(closeTags(str_replace("\n", "<br/>", htmlentities($_POST['message'], $allowed_tags))))));
        }
        elseif(@$_POST['submit'] == "Post Message"){
            if($csrf->validateToken($_REQUEST['token'])){            
                if(is_numeric($message_id))
                    $message_id = intval(@$_REQUEST['id']);
                if($link->postMessage($_POST['message'], $message_id))
                    header("Location: ./linkme.php?l=".$link_id);
            }
        }
        if(!is_null(@$_POST['message']))
            $smarty->assign("e_message", htmlentities(@$_POST['message']));
    }
    else {
       $smarty->assign("preview_message", FALSE);
       $smarty->assign("new_topic", TRUE);
       $smarty->assign("board_id", 42);
       $smarty->assign("signature", "\n---\n".htmlentities($authUser->getSignature()));
       $smarty->assign("e_message", htmlentities(@$_POST['message']));
       $display = "postmsg.tpl";
       if(@$_POST['preview'] == "Preview Message"){
            $smarty->assign("preview_message", TRUE);
            $message = new Message($db, 13);
            $smarty->assign("p_message", autolink($message->formatComments(closeTags(str_replace("\n", "<br/>", htmlentities($_POST['message'], $allowed_tags))))));
        }
       elseif(@$_POST['submit'] == "Post Message"){
           
           if(strlen(trim($_POST['title'])) < 5 || strlen(trim($_POST['title'])) > 80)
                $smarty->assign("error_message", "Title must be between 5 and 80 characters");
           else{
               //$board = new Board($db, intval($_REQUEST['board']), $authUser->getUserID());
               //$newTopic = $board->createTopic(trim($_POST['title']), @$_POST['message']);
               $topic_title = (trim($_POST['title']));
               $message = $_POST['message'];
               $tags = explode(",", $_POST['tags']);

               $parser = new Parser();
               $tagEngine = new Tag($authUser->getUserID());
               $topic = new Topic($authUser, $parser);

               $newTopic = $topic->createTopic($topic_title, $tags, $message);
               if($newTopic)
                    header("Location: ./showmessages.php?topic=".$newTopic);
                exit();
           }
       }
   }
}
else
    require("404.php");
$page_title = "Post Message";
require("includes/deinit.php");
?>
