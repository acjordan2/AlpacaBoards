<?php
/*
 * edittags.php
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
require "includes/Topic.class.php";
require "includes/Tag.class.php";

if ($auth === true) {
	
	if (isset($_GET['topic_id']) && is_numeric($_GET['topic_id'])) {
		$topic_id = $_GET['topic_id'];
		$parser = new Parser();

		$topic = new Topic($authUser, $parser, $topic_id);		

		$tag = new Tag($authUser->getUserId());

		$current_tags = $tag->getObjectTags($topic_id, 1);
		$smarty->assign("topic_title", $topic->getTopicTitle());
		$smarty->assign("current_tags", $current_tags);

		$display = "edittags.tpl";
		$page_title = "Edit Topic Tags";
		include "includes/deinit.php";
	}
} else {
	include "403.php";
}
