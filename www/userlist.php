<?php
/*
 * userlist.php
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
if ($auth == true) {
    if (!is_numeric(@$_GET['page']) || @$_GET['page'] == null) {
        $current_page = 1;
    } else {
        $current_page = intval($_GET['page']);
    }
    // Get user list
    $query = @$_GET['user'];
    $userlist = User::getUserList($db, $current_page, $query);
    $page_count = User::$page_count;
    if($page_count == 0) {
        $page_count = 1;
    }

    // Set template variables
    $smarty->assign("userlist", $userlist);
    $smarty->assign("page_count", $page_count);
    $smarty->assign("current_page", $current_page);

    // Set template page
    $display = "userlist.tpl";
    $page_title = "User List";
    $smarty->assign("user_search", override\htmlentities($query));
    include "includes/deinit.php";
} else {
    include "404.php";
}
?>

