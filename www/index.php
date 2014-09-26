<?php
/*
 * index.php
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

// Check authentication
if ($auth === false) {
    // Show login page
    $message = "";
    $display = "login.tpl";
    $page_title = "Login";

    // If redirected from register.php
    // show "Account Created" message
    if (@$_GET['m'] == 1) {
        $message = $GLOBALS['locale_messages']['account']['created'];
    }

    // If an incorrect username or password was
    // provided, show error message
    if (isset($_POST['username']) || isset($_POST['password'])) {
        $message = $GLOBALS['locale_messages']['login']['error'].
                   " <a href=\"./passwordReset.php\">Forgot password</a>?";
    }
    $smarty->assign("username", htmlentities(@$_POST['username']));
    $smarty->assign("message", $message);
    $smarty->assign("token", $csrf->getToken());
    include "includes/deinit.php";
} else {
    // If the suer is logged in, redirect
    // to last visted page if it exists.
    // Otherwise, redirect to main.
    session_set_cookie_params(0, "/", DOMAIN, USE_SSL, true);
    session_name("r");
    session_start();
    if (isset($_SESSION['redirect'])) {
        $r = $_SESSION['redirect'];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        if ($r == urldecode($_GET['r'])) {
            header("Location: ".$r);
        } else {
            header("Location: ./main.php");
        }
        exit();
    } else {
        header("Location: ./main.php");
        exit();
    }
}
