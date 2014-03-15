<?php
/*
 * siteoptions.php
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
require "includes/Parser.class.php";

// Check authentication
if ($auth === true) {
    print $authUser->checkPermissions("site_options");
    if ($authUser->checkPermissions("site_options")) {
        if (isset($_POST['token'])) {
            if ($csrf->validateToken($_POST['token'])) {
                $site->updateSiteOptions(
                    $_POST['sitename'],
                    (int)$_POST['registration'],
                    (int)$_POST['invites']
                );
                header("location: ./siteoptions.php?m=1");
                exit();
            }
        }
        if (@$_GET['m'] == 1) {
            $message = "Site Options Updated!";
            $smarty->assign("message", $message);
        }
        $smarty->assign("token", $csrf->getToken());
        $smarty->assign("registration_status", $site->getRegistrationStatus());
        $smarty->assign("invite_status", $site->getInviteStatus());

        $page_title = "Site Options";
        $display = "siteoptions.tpl";
        include "includes/deinit.php";
    } else {
        include "403.php"; // User does not have access to this page
    }
} else {
    include "403.php"; // User not logged in
}
