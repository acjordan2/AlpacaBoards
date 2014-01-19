<?php
/*
 * stats.php
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
    // Get number of Users
    $sql_get_users = "SELECT COUNT(user_id) as count FROM Users";
    $query_users = $db->query($sql_get_users);
    $user_num = $query_users->fetch();

    // Get number of Links
    $sql_get_links = "SELECT COUNT(link_id) as count from Links";
    $query_links = $db->query($sql_get_links);
    $link_num = $query_links->fetch();

    // Get number of Messages
    $sql_get_messages = "SELECT COUNT(message_id) FROM Messages";
    $query_messages = $db->query($sql_get_messages);
    $message_num = $query_messages->fetch();

    // Assign template variables
    $smarty->assign("user_num", $user_num[0]);
    $smarty->assign("links_num", $link_num[0]);
    $smarty->assign("message_num", $message_num[0]);

    // Set page template
    $display = "stats.tpl";
    $page_title = "Stats";
    include "includes/deinit.php";
} else {
    include "404.php";
}
?>
