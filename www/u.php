<?php
/*
 * u.php
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
require "includes/Upload.class.php";
require "includes/PHPThumbnailer/ThumbLib.inc.php";

// Check authentication
if ($auth == true) {
    if (isset($_POST['token'])) {
        // Valdate token
        if ($csrf->validateToken($_POST['token'])) {
            // Upload new picture
            $upload = new Upload($db, $authUser->getUserID());
            $image = $upload->uploadImage($_FILES['file']);
            // Get list of user uploads
            $image_uploads = $authUser->getUploads();
            for ($i=0; $i<sizeof($image_uploads); $i++) {
                $image_uploads[$i]['extension'] 
                    = end(explode(".", $image_uploads[$i]['filename']));
                $image_uploads[$i]['filename'] 
                    = urlencode(
                        substr(
                            $image_uploads[$i]['filename'], 0, -1*
                            (strlen($image_uploads[$i]['extension'])+1)
                        )
                    );
            }
            $smarty->assign("images", $image_uploads);
        }
        
    }
    
    $smarty->assign("token", $csrf->getToken());

    // Set page template
    $page_title = "Upload";
    $display = "u.tpl";
    include "includes/deinit.php";
} else {
    include "404.php";
}
?>
