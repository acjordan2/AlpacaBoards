<?php
/*
 * links.php
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
require("includes/Link.class.php");

if($auth == TRUE){
	$page_title = "Links";
	$display = "links.tpl";
	$links = new Link($db, $authUser->getUserID());
	switch(@$_GET['mode']){
		case "topvoted":
			$order=1;
			break;
		case "new":
			$order=2;
			break;
		case "topvotedweek":
			$order=3;
			break;
		default:
			$order=4;
	}
	if(@$_GET['mode']=="fav")
			$link_list = $links->getFavorites();
	elseif(@$_GET['mode']=="search"){
		$display = "search.tpl";
		$link_list = "";
		if(isset($_GET['q']) && strlen(@$_GET['q']) >= 3){
			require "includes/sphinxapi.php";
			$sp = new SphinxClient();

			$sp->setServer("localhost", 3312);
			$sp->SetMatchMode(SPH_MATCH_ALL);
			$sp->SetArrayResult(true);

			$results = $sp->Query(@$_GET['q'], 'links');
			$link_data = Array();
			for($i=0; $i<sizeof($results['matches']); $i++){
					$link_data_array = $links->getLinkListByID($results['matches'][$i]['id']);
					if($link_data_array['link_id'] != null){
						$link_data[$i]['link_id'] = $link_data_array['link_id'];
						$link_data[$i]['user_id'] = $link_data_array['user_id'];
						$link_data[$i]['title'] = override\htmlentities($link_data_array['title']);
						$link_data[$i]['created'] = $link_data_array['created'];
						$link_data[$i]['NumberOfVotes'] = $link_data_array['NumberOfVotes'];
						$link_data[$i]['rank'] = $link_data_array['rank'];
						$link_data[$i]['rating'] = $link_data_array['rating'];
						$link_data[$i]['username'] = $link_data_array['username'];
					}
			}
			$link_list = $link_data;
			$display = "links.tpl";
			$page_title = "Links";
		}
	}
	else
		$link_list = $links->getLinkList($order);
	$smarty->assign("links", $link_list);
	require("includes/deinit.php");
}else
	require("404.php");
?>
