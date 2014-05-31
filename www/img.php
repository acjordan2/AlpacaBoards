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

if ($auth === true) {
    $sha1_sum = $_GET['hash'];
    $sql = "SELECT UploadLog.filename FROM UploadedImages
        LEFT JOIN UploadLog using(image_id) WHERE sha1_sum = :sha1_sum";
    $statement = $db->prepare($sql);
    $statement->execute(array("sha1_sum" => $sha1_sum));
    if ($statement->rowCount() > 0) {
        $results = $statement->fetchAll();
        $smarty->assign("sha1_sum", urlencode($sha1_sum));
        $smarty->assign("filename", urlencode($results[0]['filename']));
        $page_title = htmlentities($results[0]['filename']);
        $display = "img.tpl";
        include "includes/deinit.php";
    } else {
        include "404.php";
    }
} else {
    include "403.php";
}
