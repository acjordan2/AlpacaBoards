<?php
/*
 * ajax.php
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
require "includes/Tag.class.php";

// Check authentication
if ($auth == true) {
    $output = "";

    // Create an anti Cross-Site request
    // forgery token
    $token = @$_POST['token'];

    // Spilt action; format is <sectom>_<action>
    // ex link_vote for link voting.
    $action = explode("_", @$_REQUEST['action']);
    $section = @$action[0];
    $action = @$action[1];

    // Check for different sections
    switch($section){
        // Get link related actions
        case "link":
            include "includes/Link.class.php";
            $tagObject = new Tag($authUser->getUserId());
            $link_id = @$_POST['l'];
			$csrf->setPageSalt("linkme".$link_id);
            if (!is_numeric($link_id)) {
                $link = new Link($authUser);
                switch($action) {
                    case "tags":
                        header('content-type: application/json; charset=utf-8');
                        $output = array("tags" => $tagObject->getTags("[type:0|2][title:".$_GET['q']."]"));
                        break;
                    case "checkParentTag":
                        $tags_tmp = explode(":", $_POST['tags']);
                        if (count($tags_tmp) > 1) {
                            $i = 1;
                        } else {
                            $i = 0;
                        }

                        $tags = explode(",", $tags_tmp[$i]);
                        $output = $link->checkParentTag($tags);
                        break;
                    default:
                        $output = "{\"error\": \"Invalid Link ID\"}";
                }
            } 
            break;
    } // End switch($section)

    // JSON output
    print json_encode($output);
} else {
    include "404.php";
}
