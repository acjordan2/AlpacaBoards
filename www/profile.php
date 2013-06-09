<?php
/*
 * profile.php
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
require("includes/Parser.class.php");
if($auth == TRUE){
	$user_id = @$_GET['user'];
	if(is_null($user_id))
		$user_id=$authUser->getUserID();
	else
		$user_id=$_GET['user'];
		
	if(is_numeric($user_id)){
		$profile_user = new User($db, $user_id);
		if($profile_user->doesExist()){
			if(isset($_GET['mod_action']) && $authUser->checkPermissions($_GET['mod_action'])){
				require("includes/CSRFGuard.class.php");
				$csrf = new CSRFGuard();
				$smarty->assign("token", $csrf->getToken());
				$smarty->assign("mod_action", $_GET['mod_action']);
				switch($_GET['mod_action']){
					case "user_ban":
						if($profile_user->getStatus() == -1)
							$message = "User has already been banned!";
						$action_taken = "User has been banned";
						if($authUser->getUserID() == $profile_user->getUserID())
							$message = "You can't ban youself!";
						if(isset($message))
							$smarty->assign("message", $message);
						else{
							if(isset($_POST['submit'])){
								if($csrf->validateToken($_REQUEST['token'])){
									if($authUser->setStatus(-1, $profile_user->getUserID(), $action_taken, $_POST['description'])){
										$message = $profile_user->getUsername()." has been banned";
										$smarty->assign("message", $message);
									}
								}
							}
						}
						$smarty->assign("p_user_id", $profile_user->getUserID());
						$smarty->assign("p_username", $profile_user->getUsername());
						$smarty->assign("action_taken", $action_taken);
						$page_title = "Ban ".$profile_user->getUsername();
						break;
				}
				$display = "mod_actions.tpl";
			}
			else{
				
				$avatar = $profile_user->getAvatar();
				if(!is_null($avatar)){
					$smarty->assign("avatar", $avatar[0]."/".urlencode($avatar[1]));
					$smarty->assign("avatar_width", $avatar[2]);
					$smarty->assign("avatar_height", $avatar[3]);
				}
				
				$parser = new Parser($db);
				$signature = str_replace("\n" ,"<br />", $pre_html_purifier->purify($profile_user->getSignature()));
				$parser->loadHTML($signature);
				$parser->parse();
				$signature = $post_html_purifier->purify($parser->getHTML());
				$signature = Parser::cleanUp($signature);
				
				$quote = str_replace("\n" ,"<br />", $pre_html_purifier->purify($profile_user->getQuote()));
				$parser->loadHTML($quote);
				$parser->parse();
				$quote = $post_html_purifier->purify($parser->getHTML());
				$quote = Parser::cleanUp($quote);
				
				$smarty->assign("p_username", $profile_user->getUsername());
				$smarty->assign("p_user_id", $profile_user->getUserID());
				$smarty->assign("p_status", $profile_user->getStatus());
				$smarty->assign("p_karma", $profile_user->getKarma());
				$smarty->assign("good_karma", $profile_user->getGoodKarma());
				$smarty->assign("bad_karma", $profile_user->getBadKarma());
				$smarty->assign("contribution_karma", $profile_user->getContributionKarma());
				$smarty->assign("credit", $profile_user->getCredits());
				$smarty->assign("created", $profile_user->getAccountCreated());
				$smarty->assign("last_active", $profile_user->getLastActive());
				$smarty->assign("signature", $signature);
				$smarty->assign("quote", $quote);
				$smarty->assign("instant_messaging", $authUser->getInstantMessaging());
				$smarty->assign("public_email", $authUser->getEmail());
				
				$smarty->assign("access_level", $authUser->getAccessLevel());
				$smarty->assign("access_title", $authUser->getAccessTitle());
				$smarty->assign("mod_user_ban", $authUser->checkPermissions("user_ban"));
				$display = "profile.tpl";
				$page_title = "User Profile - ".$profile_user->getUsername();
			}
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
