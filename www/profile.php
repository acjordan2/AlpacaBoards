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
if($auth == TRUE){
	$user_id = @$_GET['user'];
	if(is_null($user_id))
		$user_id=$authUser->getUserID();
	else
		$user_id=$_GET['user'];
		
	if(is_numeric($user_id)){
			$profile_user = new User($db, $user_id);
			if($profile_user->doesExist()){
			$smarty->assign("p_username", $profile_user->getUsername());
			$smarty->assign("p_user_id", $profile_user->getUserID());
			$smarty->assign("p_karma", $profile_user->getKarma());
			$smarty->assign("good_karma", $profile_user->getGoodKarma());
			$smarty->assign("bad_karma", $profile_user->getBadKarma());
			$smarty->assign("created", $profile_user->getAccountCreated());
			$smarty->assign("last_active", $profile_user->getLastActive());
			$smarty->assign("signature", override\htmlentities($profile_user->getSignature()));
			$smarty->assign("quote", override\htmlentities($profile_user->getQuote()));
			$smarty->assign("instant_messaging", '');
			$smarty->assign("public_email", '');
			
			$display = "profile.tpl";
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
