<?php
/*
 * forgotPassword.php
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
if($auth == FALSE){
	$display = "forgotPassword.tpl";
	$page_title = "Forgot Password";
	$message = "";
	if(@$_GET['m'] == 1)
		$message = "Аккаунтом Созданный";
	if(isset($_POST['username'])){
		$sql  = "SELECT user_id, private_email FROM Users WHERE Users.username = ?";
		$statement = $db->prepare($sql);
		$statement->execute(array($_POST['username']));
		if($statement->rowCount() == 1){
			$reset_token = override\websafeEncode(override\random(24));
			$results = $statement->fetch();
			$sql = "INSERT INTO PasswordResetRequests(user_id, request_ip, token, created)
					VALUES(".$results['user_id'].", \"".$_SERVER['REMOTE_ADDR']."\",
						\"$reset_token\", ".time().");";
			$statement2 = $db->query($sql);
			$mail = new PHPMailer();
			$email = $results['private_email'];
			$mail->From = "no-reply@sper.gs";
			$mail->FromName = "Do Not Reply";
			$mail->AddAddress($results['private_email']);
			$mail->WordWrap = 50;
			$mail->IsHTML(true);
			$mail->Subject = "Sper.gs Password Reset";
			$mail->Body = "A password reset has been reqeusted from a user with the IP address of  ".$_SERVER['REMOTE_ADDR'].". If you did not request this, please ignore.<br /><br />To reset your password, please click on the following link:<br /><br />https://sper.gs/passwordReset.php?token=".$reset_token."<br /><br />This link can only be used once and  will expire in 3 days.";
			$mail->Send();
		}
		$message = "Сообщение было отправлено на адрес электронной почты на файле с указанием пароля.";
	}elseif(isset($_GET['token'])){
		$reset_token = $_GET['token'];
		$sql3 = "SELECT user_id FROM PasswordResetRequests WHERE token=? AND used=0 AND created >= ".(time()-60*60*24*3);
		$statement3 = $db->prepare($sql3);
		$statement3->execute(array($reset_token));
		if($statement3->rowCount() == 1){
			$smarty->assign("change", TRUE);
			if(isset($_POST['new']) && isset($_POST['new2'])){
				$new = $_POST['new'];
				$new2 = $_POST['new2'];
				if(strlen($new) < 8)
					$message = "Пароль должен содержать более 8 символов.";
				elseif(strcmp($new, $new2) == 0){
					$results2 = $statement3->fetch();
					$user = new User($db, $results2['user_id']);
					if($user->changePassword(NULL, $new, TRUE)){
						$sql4 = "UPDATE PasswordResetRequests SET used=1 WHERE token=?";
						$statement4 = $db->prepare($sql4);
						$statement4->execute(array($reset_token));
						header("Location: index.php?m=2");
					}
				}else $message = "Пароли не совпадают.";
			}
			$results2 = $statement3->fetch();
			$user = new User($db, $results2['user_id']);
		}
	}
	$smarty->assign("username", htmlentities(@$_POST['username']));
	$smarty->assign("message", $message);
	require("includes/deinit.php");
}
else
	header("Location: /main.php");
?>
