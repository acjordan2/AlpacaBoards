<?php
/*
 * messages.api.php
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

include "includes/Link.class.php";

$action = $request[$class]['action'];

switch($action) {
    case "vote":
        $required_fields = array(
            "action",
            "value",
            "link_id",
            "token"
        );
        foreach ($request[$class] as $key => $value) {
            if (!in_array($key, $required_fields)) {
                $output = array("error" => "invalid JSON object");
            } else {
                $r_key = array_search($key, $required_fields);
                unset($required_fields[$r_key]);
            }
        }
        if (count($required_fields) > 0) {
            $output = array("error" => "invalid JSON object");
        } else {
            $csrf->setPageSalt("linkme".$request[$class]['link_id']);
            if ($csrf->validateToken($request[$class]['token'])) {
                if(is_numeric($request[$class]['link_id']) 
                  &&  is_numeric($request[$class]['value'])
                  && $request[$class]['value'] >= 0
                  && $request[$class]['value'] <= 10) {
                    try {
                        $link = new Link($authUser, null, null, $request[$class]['link_id']);
                        if($link->getLinkUserID() != $authUser->getUserID()) {
                            $link->setVote($request[$class]['value']);
                            $output = $link->getVotes();
                            $output['message'] = "Vote Added!";
                        } else {
                            $output = array("error" => "You can't vote on your own link");
                        }
                         
                    } catch (Exception $e) {
                        // invalid link id provided
                        $output = array("error" => "invalid JSON object");
                    }
                } else {
                    // Invalid input (ie vote not in between 1 and 10. Link_id is not numeric, etc)
                    $output = array("error" => "invalid JSON object");
                }
            } else {
                // CSRF token does not validate
                $output = array("error" => "invalid JSON object");
            }
        }
        break;
    default:
        $output = array("error" => "invalid JSON object");
}
