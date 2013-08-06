<?php

/*
 * editprofile.php
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
require("includes/Upload.class.php");
require("includes/Parser.class.php");
require("includes/CSRFGuard.class.php");
require("includes/PHPThumbnailer/ThumbLib.inc.php");

if($auth == TRUE){
	$csrf = new CSRFGuard();
	$avatar = "";
	if(isset($_POST['go'])){
			$public_email = $_POST['email'];
			$private_email = $_POST['pemail'];
			$im = $_POST['im'];
			$sig = $_POST['sig'];
			$quote_r = $_POST['quote'];
			
			if($csrf->validateToken(@$_REQUEST['token'])){
				if($authUser->validateEmail($public_email))
					$authUser->setEmail($public_email);
				else
					$error[0] = "Email address not valid";
				if(is_null($private_email))
					$error[1] = "Private email cannot be left blank";
				elseif($authUser->validateEmail($private_email))
					$authUser->setPrivateEmail($private_email);
				else
					$error[1] = "Private email address is not valid";
				$authUser->setInstantMessaging($im);
				$authUser->setSignature($sig);
				$authUser->setQuote($quote_r);
				if(!is_null($_FILES['picture'])){
					$upload = new Upload($db, $authUser->getUserID());
					$avatar = $upload->uploadImage($_FILES['picture']);
					if($avatar){
						$authUser->setAvatar($avatar['uploadlog_id']);
					}
				}
			}
	}

	$avatar = $authUser->getAvatar();
	if(!is_null($avatar)){
		$smarty->assign("avatar", $avatar['sha1_sum']."/".urlencode($avatar['filename'].".".$avatar['extension']));
		$smarty->assign("avatar_width", $avatar['width']);
		$smarty->assign("avatar_height", $avatar['height']);
	}

	
	$smarty->assign("username", $authUser->getUsername());
	$smarty->assign("public_email", htmlentities($authUser->getEmail()));
	$smarty->assign("private_email", htmlentities($authUser->getPrivateEmail()));
	$smarty->assign("instant_messaging", htmlentities($authUser->getInstantMessaging()));
	$smarty->assign("signature", htmlentities($authUser->getSignature()));
	$smarty->assign("quote", htmlentities($authUser->getQuote()));
	$smarty->assign("token", $csrf->getToken());

	$display = "editprofile.tpl";
	$page_title = "Edit Profile";
	require("includes/deinit.php");

}else
	require("404.php");

?>
