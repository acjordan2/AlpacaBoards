<?php
/*
 * api.php
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

if ($auth === true) {
    header("Content-Type: application/json");

    $api_folder = "includes/api/";
    $allowed_includes = preg_grep('/^([^.])/', scandir($api_folder));

    $request = json_decode(file_get_contents('php://input'), true);
    
    if (sizeof($request) > 0) {
        $class = key($request);

        if(in_array($class.".api.php", $allowed_includes)) {
            include $api_folder.$class.".api.php";
            print json_encode($output);
        } else {
            print json_encode(array("error" => "invalid JSON object"));
        }
    } else {
        print json_encode(array("error" => "invalid JSON object"));
    }
} else {
    include "403.php";
}
