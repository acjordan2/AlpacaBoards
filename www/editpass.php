<?php
/*
 * userlist.php
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
	$error_msg[0] = "Password updated!";
	$error_msg[1] = "Passwords must be at least 8 characters long";
	$error_msg[2] = "Passwords don't match";
	$error_msg[4] = "Old password is incorrect";
	$message = array();
	
	if(isset($_GET['error'])){
		if(@$_GET['error'] == 1)
			$message[] = $error_msg[1];
		elseif(@$_GET['error'] == 2)
			$message[] = $error_msg[2];
		elseif(@$_GET['error'] == 3){
			$message[] = $error_msg[1];
			$message[] = $error_msg[2];
		}
		elseif(@$_GET['error'] == 4){
			$message[] = $error_msg[4];
		}
		elseif(@$_GET['error'] == 0)
			$message[] = $error_msg[0];
	}
	
	$smarty->assign("message", $message);
	if(isset($_POST['old']) && isset($_POST['new']) && isset($_POST['new2'])){
		$old = $_POST['old'];
		$new = $_POST['new'];
		$new2 = $_POST['new2'];
		$error = 0;
		if(strlen($new) < 8){
			$error += 1;
		}
		if(strcmp($new, $new2) != 0){
			$error += 2;
		}
		if(strlen($old) == 0)
			$error = 4;
		elseif($error == 0)
			if(!$authUser->changePassword($_POST['old'], $new))
					$error = 4;
					
		header("Location: ./editpass.php?error=$error");

	}
	$display = "editpass.tpl";
	$page_title = "Edit Password";
	require("includes/deinit.php");

}else
	require("404.php");
?>

