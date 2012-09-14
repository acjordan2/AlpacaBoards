<?php
/*
 * showmessages.php
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
require("includes/Topic.class.php");
if($auth == TRUE){
	if(is_numeric($_GET['topic'])){
		$topic_id = $_GET['topic'];
		$topic = new Topic($db, $topic_id, $authUser->getUserID());
		if($topic->doesExist()){
			$messages = $topic->getMessages();
			$smarty->assign("messages", $messages);
			$smarty->assign("signature", (str_replace("\r\n", "\\n", urlencode($authUser->getSignature()))));
			$smarty->assign("p_signature", override\htmlentities($authUser->getSignature()));
			$smarty->assign("topic_id", intval($topic_id));
			$smarty->assign("topic_title", override\htmlentities($topic->getTopicTitle()));
			$smarty->assign("board_title", $topic->getBoardTitle());
			$smarty->assign("board_id", $topic->getBoardID());
			$display = "showmessages.tpl";
			require("includes/deinit.php");
		}
		else
			require("404.php");
	}
	else{
		require("404.php");
	}
}
else
	require("404.php");

?>
