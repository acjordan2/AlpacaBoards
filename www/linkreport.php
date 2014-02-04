<?php
/*
 * linkreport.php
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
if ($auth == true) {
    $link_id = @$_GET['l'];
    if (is_numeric($link_id)) {
        $link = new Link($db, $authUser->getUserID(), $link_id);

        // Check that provided link ID is valid
        if ($link->doesExist()) {
            $link_data = $link->getLink();

            $smarty->assign("link_id", $link_id);
            $smarty->assign("link_title", $link_data['title']);
            $smarty->assign("token", $csrf->getToken());

            if (isset($_POST['token'])) {
                if (strlen($_POST['reason']) < 5) {
                    $error_msg = "Reason must be longer than 5 characters";
                } else {
                    // Validate CSRF token
                    if ($csrf->validateToken($_POST['token'])) {
                        $link->reportLink($_POST['reason']);
                        // Redirect to a success message
                        header("Location: ./linkreport.php?m=1");
                    }
                }
            }
            // Set template page
            $display = "linkreport.tpl";
            $page_title = "Report Link";
            include "includes/deinit.php";
        } else {
            include "404.php";
        }
    } elseif (isset($_GET['m']) && $_GET['m'] == 1) {
            $smarty->assign("m", 1);
            $display = "linkreport.tpl";
            include "includes/deinit.php";
    } else {
        include "404.php";
    }
} else {
    include "404.php";
}
?>
