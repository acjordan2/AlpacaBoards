<?php
/*
 * linkme.php
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
require "includes/Tag.class.php";

// Check authentication
if ($auth === true) {
    $link_id = @$_GET['l'];
    // Check if provided link id is valid
    if (is_numeric($link_id)) {
        $tag = new Tag($authUser->getUserId());
        $parser = new Parser();

        try {
        	$link = new Link($authUser, $parser, $tag, $link_id);
			$csrf->setPageSalt("linkme".$link_id);
            if (!is_numeric(@$_GET['page']) || @$_GET['page'] == null) {
                $current_page = 1;
            } else {
                $current_page = intval($_GET['page']);
            }
            // Submit link vote between
            // 0 and 10
            if (is_numeric(@$_POST['v'])
                && @$_POST['v'] >= 0
                && @$_POST['v'] <= 10
                && $link_data['user_id']!=$authUser->getUserID()
            ) {
                // Validate anti-CSRF token
                if ($csrf->validateToken(@$_REQUEST['token'])) {
                    $link->vote($_POST['v']);
                    // Redirect to link page with
                    // success message to avoid the
                    // annoying message from browsers
                    // when a POST request is submitted.
                    header("Location: ./linkme.php?l=$link_id&v=".@$_POST['v']);
                    exit();
                }
            }
            if (is_numeric(@$_GET['v'])
                && (@$_GET['v'] >= 0
                && @$_GET['v'] <= 10)
            ) {
                    $smarty->assign("message", "Vote Added!");
            }
            // Add link to favorites
            if ((@$_POST['f'] === "1"
                || @$_POST['f'] === "0")
                && $csrf->validateToken(@$_REQUEST['token'])
            ) {
                if ($link->addToFavorites($_POST['f'])) {
                    // Redirect to link page with
                    // success message to avoid the
                    // annoying message from browsers
                    // when a POST request is submitted.
                    header("Location: ./linkme.php?l=$link_id&f=".$_POST['f']);
                    exit();
                }
            }
            if (@$_GET['f'] === "1" || @$_GET['f'] === "0") {
                $status_message = ($_GET['f'] === "1") ?
                        "Added to favorites!" : "Removed from favorites";
                $smarty->assign("message", $status_message);
            }

            // Assign link data to template varibles
            $messages = $link->getMessages($current_page);
            $smarty->assign("link_id", $link->getLinkId());
            $smarty->assign("title", htmlentities($link->getTitle()));
            $smarty->assign("url", autolink($link->getUrl()));
            $smarty->assign("link_username", $link->getLinkUsername());
            $smarty->assign("link_user_id", $link->getLinkUserId());
            $smarty->assign("created", $link->getCreated());
            $smarty->assign("hits", $link->getHitCount());
            $smarty->assign("rating", $link->getRating());
            $smarty->assign("rank", $link->getRank());
            $smarty->assign("NumberOfVotes", $link->getNumberOfVotes());
            $smarty->assign("short_code", $link->getShortCode());
            $smarty->assign("description", $parser->parse($link->getDescription()));
            $smarty->assign("tags", $tag->getObjectTags($link->getLinkId(), 2));
            $smarty->assign("messages", $messages);
            $smarty->assign(
                "signature",
                str_replace(
                    "\r\n",
                    "\\n",
                    addslashes(
                        str_replace("+", " ", ($authUser->getSignature()))
                    )
                )
            );
            $smarty->assign("p_signature", htmlentities($authUser->getSignature()));
            $smarty->assign("token", $csrf->getToken());
            $smarty->assign("page_count", $link->getPageCount());
            $smarty->assign("current_page", $current_page);
            if ($link->isFavorite()) {
                $smarty->assign("link_favorite", true);
            }
            $display = "linkme.tpl";
            $page_title = htmlentities($link->getTitle());
            include "includes/deinit.php";
        } catch (Exception $e){
            include "404.php";
        }
    } elseif ($link_id == "random") {
        // Select random link
        $sql = "SELECT Links.link_id FROM Links WHERE Links.active=0";
        $links = $db->query($sql);
        $links_data = $links->fetchAll();
        header(
            "Location: ./linkme.php?l=".
            $links_data[mt_rand(0, sizeof($links_data)-1)][0]
        );
        exit();
    } else {
        include "404.php";
    }

} else {
    include "404.php";
}
