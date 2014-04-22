<?php
/*
 * addlink.php
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
require "includes/Link.class.php";
require "includes/Parser.class.php";

// Check authentication
if ($auth === true) {
    // Edit existing link
    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
        $link_edit = new Link($db, $authUser->getUserID(), $_GET['edit']);
        $link_edit_data = $link_edit->getLink();
        // Check to make sure the link
        // creator is the one editing the link
        if ($link_edit_data['user_id'] == $authUser->getUserID()) {
            // Assign template variables
            $smarty->assign("title", htmlentities($link_edit_data['title']));
            $smarty->assign(
                "description",
                htmlentities($link_edit_data['raw_description'])
            );
            $smarty->assign("lurl", htmlentities($link_edit_data['url2']));
            $smarty->assign("link_edit", true);
            $smarty->assign("link_id", $link_edit_data['link_id']);
            if (isset($_POST['token'])) {
                // Validate provided data
                $error_msg = "";
                if (isset($_POST['lurl'])) {
                    if (!validateURL($_POST['lurl'])) {
                        // Make sure URL is valid
                        $error_msg = "Please enter a valid URL<br />";
                    } elseif ($link_edit->checkURLExist($_POST['lurl'])) {
                        // Prevent duplicate links
                        $error_msg = "A link with that URL already exists";
                    }
                    $smarty->assign("lurl", htmlentities($_POST['lurl']));
                }
                if (isset($_POST['title'])) {
                    // Check title lenght and remove blank
                    // characters from the end.
                    $smarty->assign("title", htmlentities($_POST['title']));
                    if (strlen($_POST['title']) < 5
                        || strlen($_POST['title'] > 80)
                    ) {
                        $error_msg .= "The title must be between 5 and 80<br />";
                    }
                }
                if (isset($_POST['description'])) {
                    $smarty->assign(
                        "description",
                        htmlentities($_POST['description'])
                    );
                    if (strlen($_POST['description']) < 5) {
                        $error_msg .=
                            "Description must be long than 5 characters<br />";
                    }
                }
                if ($error_msg=="") {
                    // Validate anti-CSRF token
                    if ($csrf->validateToken($_POST['token'])) {
                        if ($link_edit->updateLink($_REQUEST)) {
                            header(
                                "Location: ./linkme.php?l=".$link_edit->getLinkID()
                            );
                            exit();
                        }
                    } else {
                        $error_msg = "There was a problem processing your request.
                            Please try again";
                    }
                }
                $smarty->assign("error", $error_msg);
            }
        } else {
            include "404.php";
        }
        
    } else {
        // Add new link
        $links = new Link($db, $authUser->getUserID());
        if (isset($_POST['title'])
            && isset($_POST['description'])
            && isset($_POST['token'])
        ) {
            $error_msg = "";
            // Validate provided data
            if (isset($_POST['lurl'])) {
                if (!validateURL($_POST['lurl'])) {
                    $error_msg = "Please enter a valid URL<br />";
                } elseif ($links->checkURLExist($_POST['lurl'])) {
                    // Prevent duplicate links
                    $error_msg = "A link with that URL already exists";
                }
                $smarty->assign("lurl", htmlentities($_POST['lurl']));
            }
            if (isset($_POST['title'])) {
                $smarty->assign("title", htmlentities($_POST['title']));
                if (strlen($_POST['title']) < 5 || strlen($_POST['title'] > 80)) {
                    $error_msg .= "The title must be between 5 and 80<br />";
                }
            }
            if (isset($_POST['description'])) {
                $smarty->assign(
                    "description",
                    htmlentities($_POST['description'])
                );
                if (strlen($_POST['description']) < 5) {
                    $error_msg .= "Description must be long than 5 characters<br />";
                }
            }
            if ($error_msg=="") {
                // Validate anti-CSRF token
                if ($csrf->validateToken($_POST['token'])) {
                    if ($links->addLink($_REQUEST)) {
                        header("Location: ./linkme.php?l=".$links->getLinkID());
                        exit();
                    }
                } else {
                    $error_msg = "There was a problem processing your request. 
                        Please try again";
                }
            }
            $smarty->assign("error", $error_msg);
        }
    }
    // Assign template variables
    $smarty->assign("categories", Link::getCategories($db));
    $smarty->assign("token", $csrf->getToken());

    // Set template page
    $display = "addlink.tpl";
    $page_title = "Add Link";
    include "includes/deinit.php";
} else {
    include "404.php";
}
