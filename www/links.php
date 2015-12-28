<?php
/*
 * links.php
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
require "includes/Tag.class.php";
require "includes/Parser.class.php";

// Check authentication
if ($auth === true) {

    $parser = new Parser();
    $tag = new Tag($authUser->getUserId());

    // Set template display for listing links
    $page_title = "Links";
    $display = "links.tpl";
    $links = new Link($authUser, $parser, $tag);

    // Get sort order
    switch (@$_GET['mode']) {
        case "topvoted":
            $order=1;
            break;
        case "new":
            $order=2;
            break;
        case "topvotedweek":
            $order=3;
            break;
        default:
            $order=4;
    }
    if (@$_GET['mode']=="fav") {
        $link_list = $links->getFavorites();
    } elseif (@$_GET['mode']=="search") {
        // Set template page for searching
        $display = "search.tpl";
        $link_list = "";
        if (isset($_GET['q']) && strlen(@$_GET['q']) >= 3) {
            
            // Use the Sphinx search API
            include "includes/sphinxapi.php";
            $sp = new SphinxClient();

            $sp->setServer("localhost", 3312);
            $sp->SetMatchMode(SPH_MATCH_ALL);
            $sp->SetArrayResult(true);
            
            $sphinx_status = $sp->status();
            if ($sphinx_status[0][1] > 1) {
                $results = $sp->Query(@$_GET['q'], 'links');
            } else {

                $keyword = "%".$_GET['q']."%";
                $sql = "SELECT Links.link_id as id FROM Links WHERE 
                    Links.title LIKE ? OR Links.Description LIKE ?";
                $statement = $db->prepare($sql);
                $statement->execute(array($keyword, $keyword));
                $results['matches'] = $statement->fetchAll();
            }

            $link_data = array();
            // Get link data based on search results
            for ($i=0; $i<sizeof($results['matches']); $i++) {
                $link_data[$i]
                    = array_pop($links->getLinks(1, "[id:".$results['matches'][$i]['id'].']'));
            }

            // Set template page for results
            $link_list = $link_data;
            $display = "links.tpl";
            $page_title = "Links";
        }
    } else {
        if (isset($_GET['tags'])) {
            //$link_list = $links->getLinkListByTag($_GET['tags']);
            $link_list = $tag->getContent($_GET['tags'], 2);
        } else {
            $link_list = $links->getLinks(1);
        }
    }
    $smarty->assign("links", $link_list);
    include "includes/deinit.php";
} else {
    include "404.php";
}
