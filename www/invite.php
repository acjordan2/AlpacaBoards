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
    $invites = false;
    if ($site->getInviteStatus() == 0) {
        $message = "Invites are disabled";
    } elseif ($site->getInviteStatus() == 1) {
        $inventory = $authUser->getInventory(4);
        if (sizeof($inventory) > 0) {
            $invites = true;
            if (isset($_POST['email'])
                && $csrf->validateToken(@$_POST['token']) == true
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
            && $csrf->validateToken(@$_POST['token']) == true
            && $authUser->validateEmail($_POST['email']) == true
        ) {
            if ($authUser->inviteUser($_POST['email'])) {
                    $message = "Invite has been sent!";
            } else {
                $message = "Error sending invite";
            }
        }
    }
    // Set template variables
    $smarty->assign("message", @$message);
    $smarty->assign("invite_status", $invites);
    $smarty->assign("token", $csrf->getToken());
    // Set template page
    $display = "invite.tpl";
    $page_title = "Send Invite";
    include "includes/deinit.php";
} else {
    include "404.php";
}
