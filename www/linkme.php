<?php
/*
 * linkme.php
 * 
 * Copyright (c) 2013 Andrew Jordan
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
require("includes/CSRFGuard.class.php");
require("includes/Parser.class.php");

if($auth == TRUE){
	$link_id = @$_GET['l'];
	if(is_numeric($link_id)){
		$link = new Link($db, $authUser->getUserID(), $link_id);
		if($link->doesExist()){
			$link_data = $link->getLink();
			$csrf = new CSRFGuard();
			if(is_numeric(@$_POST['v']) && @$_POST['v'] >= 0 && @$_POST['v'] <= 10 && $link_data['user_id']!=$authUser->getUserID()){
				if($csrf->validateToken(@$_REQUEST['token'])){
					$link->vote($_POST['v']);
					$link_data = $link->getLink();
					header("Location: ./linkme.php?l=$link_id&v=".@$_POST['v']);
					$smarty->assign("message", "Vote Added!");
				}
			}
			if((@$_POST['f'] === "1" || @$_POST['f'] === "0") && $csrf->validateToken(@$_REQUEST['token'])){
				if($link->addToFavorites($_POST['f']))
					$status_message = ($_POST['f'] == 1) ? "Added to favorites!" : "Removed from favorites";
					$smarty->assign("message", $status_message);
			}
			$messages = $link->getMessages();
			$smarty->assign("link_data", $link_data);
			$smarty->assign("link_id", $link_id);
			$smarty->assign("messages", $messages);
			$smarty->assign("signature", (str_replace("\r\n", "\\n", addslashes(str_replace("+", " ", ($authUser->getSignature()))))));
			$smarty->assign("p_signature", override\htmlentities($authUser->getSignature()));
			$smarty->assign("token", $csrf->getToken());
			if($link->isFavorite())
				$smarty->assign("link_favorite", TRUE);
			$display = "linkme.tpl";
			$page_title = $link_data['title'];
			require("includes/deinit.php");
		}else require("404.php");
	}elseif($link_id=="random"){
		$sql = "SELECT Links.link_id FROM Links WHERE Links.active=0";
		$links = $db->query($sql);		
		$links_data = $links->fetchAll();
		header("Location: ./linkme.php?l=".$links_data[mt_rand(0, sizeof($links_data)-1)][0]);
	}
	else
		require("404.php");

}else
	require("404.php");
?>

