<?php
/*
 * invite.php
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
require "includes/PHPMailer.class.php";

// Check authentication 
if ($auth == true) {
    $csrf->setPageSalt("invite".$authUser->getUserId());
    $invites = false;
    if ($site->getInviteStatus() == 0) {
        $message = "Invites are disabled";
    } elseif ($site->getInviteStatus() == 1) {
        $inventory = $authUser->getInventory(4);
        if (sizeof($inventory) > 0) {
            $invites = true;
            if (isset($_POST['email'])
                && $csrf->validateToken(@$_POST['token'], "invite") == true
            ) {
                if ($authUser->inviteUser($_POST['email'], $inventory[0]['transaction_id'])) {
                    $message = "Invite has been sent!";
                } else {
                    $message = "Error sending invite";
                }
            }
        } else {
            $invites = false;
            $message = "you do not have any invites to give,
                they can be purchased in the <a href=\"./shop.php\">token shop</a>";
        }

    } elseif ($site->getInviteStatus() == 2) {
        $invites = true;
        if (isset($_POST['email'])
            && $csrf->validateToken(@$_POST['token'], "invite") == true
            && $authUser->validateEmail($_POST['email']) == true
        ) {
            $invite_code = CSRFGuard::websafeEncode(
                mcrypt_create_iv(33, MCRYPT_DEV_URANDOM)
            );

            $sql = "INSERT INTO InviteTree (invite_code, email, invited_by, created)
                VALUES ('$invite_code', :email, ".$authUser->getUserId().", ".time().")";
            $statement = $db->prepare($sql);
            $statement->bindParam(":email", $_POST['email']);
            $statement->execute();


            $mail = new PHPMailer();
            $email = $_POST['email'];
            $mail->From = "no-reply@".DOMAIN;
            $mail->FromName = "Do Not Reply";
            $mail->AddAddress($email);

            $mail->WordWrap = 50;
            $mail->IsHTML(true);

            $mail->Subject = "You have been invited to ".$site->getSiteName();
            $mail->Body    = "The user ".$authUser->getUsername()." has invited you to
                join ".$site->getSiteName()." and has specified this address (".$email.") as your 
                email. If you do not know this person, please disregard.<br /><br />
                <br />To confirm your invite, click on the folowing link:<br /><br />
                ".BASEURL."/register.php?code=$invite_code<br /><br />
                After you register, you will be able to use your account. Please take 
                note that if you do not use this invite in the next 3 days, 
                it will expire.";
            $mail->AltBody = "The user ".$authUser->getUsername()." has invited you to
                join ".$site->getSiteName()." and has specified this address (".$email.") as your 
                email. If you do not know this person, please disregard.\n\n
                To confirm your invite, click on the folowing link:\n\n
                ".BASEURL."/register.php?code=$invite_code\n\n
                After you register, you will be able to use your account. Please take 
                note that if you do not use this invite in the next 3 days, 
                it will expire.";

            if ($mail->Send()) {
                    $message = "Invite has been sent!";
            } else {
                $message = "Error sending invite";
            }
        }
    }
    // Set template variables
    $smarty->assign("message", @$message);
    $smarty->assign("invite_status", $invites);
    $smarty->assign("token", $csrf->getToken("invite"));
    // Set template page
    $display = "invite.tpl";
    $page_title = "Send Invite";
    include "includes/deinit.php";
} else {
    include "404.php";
}
