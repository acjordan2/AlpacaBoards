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
            $link_id = @$_POST['l'];
            if (!is_numeric($link_id)) {
                $link = new Link($db);
                switch($action) {
                    case "tags":
                        header('content-type: application/json; charset=utf-8');
                        $output = array("tags" => $link->getTags(@$_GET['q']));
                        print json_encode($output);
                        die();
                        break;
                    case "checkParentTag":
                        $tags_tmp = explode(":", $_POST['tags']);
                        if (count($tags_tmp) > 1) {
                            $i = 1;
                        } else {
                            $i = 2;
                        }
                        $tags = explode(",", $tags_tmp[1]);
                        $output = $link->checkParentTag($tags);
                        break;
                    default:
                        $output = "{\"error\": \"Invalid Link ID\"}";
                }
            } else {
                $link = new Link($db, $authUser->getUserID(), $link_id);
                
                // Confirm provided link id is valid
                if ($link->doesExist()) {
                    // Link actions
                    switch($action){
                        // Link voting
                        case "vote":
                            if (is_numeric(@$_POST['v'])
                                && @$_POST['v'] >= 0
                                && @$_POST['v'] <= 10
                                && $link->getLinkUserID() !=$authUser->getUserID()
                            ) {
                                // Validate CSRF token
                                if ($csrf->validateToken(@$_REQUEST['token'])) {
                                    $link->vote($_POST['v']);
                                    $output = $link->getVotes();
                                    $output['message'] = "Vote Added!";
                                }
                            }

                            break;
                        // Add links to favorites
                        case "fav":
                            if ((@$_POST['f'] === "1" || @$_POST['f'] === "0")
                                && $csrf->validateToken(@$_REQUEST['token'])
                            ) {
                                if ($link->addToFavorites($_POST['f'])) {
                                    if ($_POST['f'] === "1") {
                                        $output['f'] = "Remove from Favorites";
                                        $output['message'] = "Added to favorites!";
                                    } elseif ($_POST['f'] === "0") {
                                        $output['f'] = "Add to Favorites";
                                        $output['message'] = "Removed from favorites!";
                                    }
                                }
                            }
                            break;
                    }
                }
                break;
            }
        case "topic":
            include "includes/Topic.class.php";
            include "includes/Parser.class.php";
            switch($action){
                case "subscribe":
                    //$json = json_decode(file_get_contents("php://input"), true);
                    $topic = new Topic($db, $_GET['topic_id'], $authUser->getUserID());
                    $message_data = $topic->pollMessage();
                    if (!is_null($message_data)) {
                        $smarty->assign("topic_id", $message_data[0]['topic_id']);
                        $smarty->assign("message", $message_data[0]['message']);
                        $smarty->assign("message_id", $message_data[0]['message_id']);
                        $smarty->assign("user_id", $message_data[0]['user_id']);
                        $smarty->assign("username", $message_data[0]['username']);
                        $smarty->assign("posted", $message_data[0]['posted']);
                        $smarty->assign("revision_id", $message_data[0]['revision_id']);
                        $smarty->assign("avatar", $message_data[0]['avatar']);
                        $output = $smarty->fetch("ajax/message.tpl");
                    } else {
                        $output = null;
                    }
                    break;
            } // End switch($action) for topic
            break;
    } // End switch($section)

    // JSON output
    print json_encode($output);
} else {
    include "404.php";
}
