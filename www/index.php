<?php
/*
 * index.php
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


require "includes/init.php";	

if ($auth === true) {
    $message = "";
    $display = "login.tpl";
    $page_title = "Login";
    if (@$_GET['m'] == 1) {
        $message = "Account Created!";
    }
    if (isset($_POST['username']) || isset($_POST['password'])) {
        $message = "Invalid username or password.".
                   " <a href=\"./passwordReset.php\">Forgot password</a>?";
    }
    $smarty->assign("username", htmlentities(@$_POST['username']));
    $smarty->assign("message", $message);
    include "includes/deinit.php";
} else {
    if (isset($_SESSION['redirect'])) {
        $r = $_SESSION['redirect'];
        unset($_SESSION['redirect']);
        header("Location: ".$r);
    } else {
        header("Location: ./main.php");
    }
}
?>

