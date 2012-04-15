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
 
require("includes/init.php");
require("includes/Board.class.php");
require("includes/Topic.class.php");
require("includes/Message.class.php");

if($auth == TRUE){
	if(is_numeric(@$_REQUEST['topic'])){
		$smarty->assign("preview_message", FALSE);
		$smarty->assign("quote", FALSE);
		$smarty->assign("new_topic", FALSE);
		$topic_id = intval($_REQUEST['topic']);
		$topic = new Topic($db, $topic_id, $authUser->getUserID());
		if(!$topic->doesExist())
			require("404.php");
		$smarty->assign("topic_id", $topic_id);
		$smarty->assign("topic_title", override\htmlentities($topic->getTopicTitle()));
		$smarty->assign("signature", "\n---\n".override\htmlentities($authUser->getSignature()));
		$smarty->assign("board_id", $topic->getBoardID());
		if(is_numeric(@$_GET['quote'])){
			$message = new Message($db, intval($_GET['quote']));
			if($message->doesExist()){
				$smarty->assign("quote", TRUE);
				$smarty->assign("quote_id", override\htmlentities($_GET['quote']));
				$smarty->assign("quote_topic", $message->getTopicID());
				$message_body = explode("\n---", $message->getMessage());
				$smarty->assign("quote_message", override\htmlentities(substr($message_body[0], 0, strlen($message_body[0])-1)));
				$smarty->assign("quote_revision", $message->getRevisionID());
			}
			else
				require("404.php");
		}
		elseif(is_numeric(@$_REQUEST['id'])){
			$message_id = intval($_REQUEST['id']);
			$smarty->assign("message_id", $message_id);
			$message = new Message($db, $message_id, $aUser_id=$authUser->getUserID());
			if($message->doesExist()){
				$message_body = $message->getMessage();
				$smarty->assign("e_message", override\htmlentities($message_body, $allowed_tags));
			}else
				require("404.php");
		}
		elseif(!is_null(@$_GET['quote']))
			require("404.php");
			
		if(@$_POST['preview'] == "Preview Message"){
			print $message_id;
			$smarty->assign("preview_message", TRUE);
			$message = new Message($db, 13);
			$smarty->assign("p_message", override\makeURL($message->formatComments(override\closeTags(str_replace("\n", "<br/>", override\htmlentities($_POST['message'], $allowed_tags))))));
		}
		elseif(@$_POST['submit'] == "Post Message"){
			if(is_numeric($message_id))
				$message_id = intval(@$_REQUEST['id']);
			if($topic->postMessage($_POST['message'], $message_id))
				header("Location: /showmessages.php?board=".$board_id."&topic=".$topic_id);
		}
		if(!is_null(@$_POST['message']))
			$smarty->assign("e_message", (override\htmlentities(@$_POST['message'])));
		$display = "postmsg.tpl";
	}
   elseif(is_numeric(@$_REQUEST['board'])){
	   $smarty->assign("preview_message", FALSE);
	   $smarty->assign("quote", FALSE);
	   $smarty->assign("new_topic", TRUE);
	   $smarty->assign("board_id", intval($_REQUEST['board']));
	   $smarty->assign("signature", "\n---\n".override\htmlentities($authUser->getSignature()));
	   $smarty->assign("e_message", (override\htmlentities(@$_POST['message'])));
	   $display = "postmsg.tpl";
	   if(@$_POST['preview'] == "Preview Message"){
			$smarty->assign("preview_message", TRUE);
			$message = new Message($db, 13);
			$smarty->assign("p_message", override\makeURL($message->formatComments(override\closeTags(str_replace("\n", "<br/>", override\htmlentities($_POST['message'], $allowed_tags))))));
		}
	   elseif(@$_POST['submit'] == "Post Message"){
		   
		   if(strlen(@$_POST['title']) < 5 || strlen(@$_POST['title']) > 80)
				$smarty->assign("error_message", "Title must be between 5 and 80 characters");
		   else{
			   $board = new Board($db, intval($_REQUEST['board']), $authUser->getUserID());
			   $newTopic = $board->createTopic(@$_POST['title'], @$_POST['message']);
			   if($newTopic)
					header("Location: /showmessages.php?board=".intval($_REQUEST['board_id'])."&topic=".$newTopic);
		   }
	   }
   }
   else
		require("404.php");
}
else
	require("404.php");

require("includes/deinit.php");
?>
