<?php
/*
 * loser.php
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
if($auth == TRUE){
	$user_id = @$_GET['user'];
	if(is_null($user_id))
		$user_id=$authUser->getUserID();
	else
		$user_id=$_GET['user'];
		
	if(is_numeric($user_id)){
		$stat_user = new User($db, $user_id);
		if($stat_user->doesExist()){
			$smarty->assign("p_username", $stat_user->getUsername());
			$smarty->assign("messages_posted", $stat_user->getNumberOfPosts());
			$smarty->assign("topics_created", $stat_user->getNumberOfTopics());
			$smarty->assign("posts_best", $stat_user->getPostsInBestTopic());
			$smarty->assign("no_reply", $stat_user->getNoReplyTopics());
			$smarty->assign("num_links", $stat_user->getNumberOfLinks());
			$smarty->assign("num_votes", $stat_user->getNumberOfVotes());
			$smarty->assign("vote_avg", $stat_user->getVoteAverage());
			$display = "loser.tpl";
			$page_title = "Loser";
			require("includes/deinit.php");
		}else
			require("404.php");
	}
	else{
		require("404.php");
	}
}else
	require("404.php");
?>
