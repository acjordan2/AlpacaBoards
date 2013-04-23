<?php
/*
 * invite.php
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
require("includes/PHPMailer.class.php");
require("includes/CSRFGuard.class.php");
if($auth == TRUE){
	$csrf = new CSRFGuard();
	$smarty->assign("token", $csrf->getToken());
	if(isset($_POST['email']) && $csrf->validateToken(@$_POST['token']) == TRUE && $authUser->validateEmail($_POST['email']) == TRUE){
		
		$invite_code = override\websafeEncode(override\random(32));
		
		$sql = "INSERT INTO InviteTree (invite_code, email, invited_by, created)
		VALUES ('$invite_code', ?, ".$authUser->getUserID().", ".time().")";
		$statement = $db->prepare($sql);
		$statement->execute(array($_POST['email']));
		
		$mail = new PHPMailer();
		$email = $_POST['email'];
		$mail->From = "no-reply@".DOMAIN;
		$mail->FromName = "Do Not Reply";
		$mail->AddAddress($email);

		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
		$mail->IsHTML(true);                                  // set email format to HTML

		$mail->Subject = "You have been invited to ".SITENAME;
		$mail->Body    = "The user ".$authUser->getUsername()." has invited you to join ".SITENAME." and has specified this address ("
						  .$email.") as your email. If you do not know this person, please disregard.<br /><br />".
						  "<br />To confirm your invite, click on the folowing link:<br /><br /> ".
						  "http://".DOMAIN."/register.php?code=$invite_code<br /><br />".
						  "After you register, you will be able to use your account. Please take note that if you do not ".
						  "use this invite in the next 3 days, it will expire.";
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

		if(!$mail->Send())
		   $message = "Error sending invite";
		else
		   $message = "Invite has been sent";
	}
	$smarty->assign("message", $message);
	$display = "invite.tpl";
	require("includes/deinit.php");
}
else
	require("404.php");

?>
