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
    $csrf->setPageSalt("siteoptions");
    if ($authUser->checkPermissions("site_options")) {
        if (isset($_POST['token'])) {
            if ($csrf->validateToken($_POST['token'])) {
                $site->updateSiteOptions(
                    $_POST['sitename'],
                    (int)$_POST['registration'],
                    (int)$_POST['invites']
                );
                if (!$site->setDomain($_POST['domain'])) {
                    header("Location: ./siteoptions.php?m=2");
                    exit();
                } else {
                    header("location: ./siteoptions.php?m=1");
                    exit();
                }
            }
        }
        if (@$_GET['m'] == 1) {
            $message = "Site Options Updated!";
            $smarty->assign("message", $message);
        }
        if (@$_GET['m'] == 2) {
            $message = "Invalid domain!";
            $smarty->assign("message", $message);
        }
        if (isset($_GET['domain'])) {
            $smarty->assign("message", "Please check your domain settings");
            if ($site->getDomain() == null) {
                if (verify_domain($_SERVER['HTTP_HOST'])) {
                    $domain = htmlentities(trim($_SERVER['HTTP_HOST']));
                } else {
                    $domain = "";
                }
                $needs_save = true;
                $smarty->assign("needs_save", true);
            } else {
                $domain = $site->getDomain();
            }
        } else {
            $domain = $site->getDomain();
        }
        $smarty->assign("token", $csrf->getToken("siteoptions"));
        $smarty->assign("domain", htmlentities($domain));
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
