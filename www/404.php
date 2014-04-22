<?php
/*
 * 404.php
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

require_once "includes/init.php";
 
if ($auth === true) {
    // Send 404 HTTP header
    header('HTTP/1.1 404 Not Found');
    // Set template page
    $display = "404.tpl";
    $page_title = "Page Not Found";
    include_once "includes/deinit.php";
} else {
    // Set session varible with URL
    // to redirect to after login
    session_set_cookie_params(0, "/", DOMAIN, USE_SSL, true);
    session_name("r");
    session_start();
    $uri = $_SERVER['REQUEST_URI'];
    $_SESSION['redirect'] = trim(urldecode($uri));
    header("Location: ./");
    exit();
}
