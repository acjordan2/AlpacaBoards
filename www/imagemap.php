<?php
/*
 * imagemap.php
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
require "includes/ImageMap.class.php";
require "includes/Topic.class.php";

if ($auth == true) {
    $im = new ImageMap($db);
    if (isset($_GET['hash'])) {
        $images = $im->getImageMapByHash($_GET['hash']);
        $filename = htmlentities(@array_shift($im->getFileNameFromHash($_GET['hash'])));
        $sha1_sum = urlencode($_GET['hash']);
        $smarty->assign("sha1_sum", $sha1_sum);
    } else {
        $filename = "User ".$authUser->getUsername();
        $images = $im->getImageMapForUser($authUser->getUserID());
    }
    $page_title = "Image Map";
    $display = "imagemap.tpl";
    $smarty->assign("images", $images);
    $smarty->assign("filename", $filename);
    include "includes/deinit.php";
} else {
    include "includes/404.php";
}
