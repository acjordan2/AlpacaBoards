<?php
/*
 * tags.php
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

if ($auth === true) {
    $tag = new Tag($authUser->getUserID());
    $mod_tag_edit = $authUser->checkPermissions("tag_edit");
    $mod_tag_create = $authUser->checkPermissions("tag_create");
    
    if (isset($_GET['tag'])) {
        $salt = $_GET['tag'];
    } else {
        $salt = "createTag";
    }

    $csrf->setPageSalt($salt);
    
    $smarty->assign("mod_tag_create", $mod_tag_create);
    $smarty->assign("token", $csrf->getToken());
    if (isset($_GET['tag']) && !is_null($_GET['tag'])) {
        // See info for specific tag
        $search = array("[", "]", "_");
        $replace = array("", "", " ");

        $title = str_replace($search, $replace, $_GET['tag']);
        $taginfo = $tag->getTagInfoByTitle($title);
        if (count($taginfo) > 1) {
            $taginfo['parents'] = $tag->getParents($taginfo['tag_id']);
            $taginfo['children'] = $tag->getChildren($taginfo['tag_id']);
            $smarty->assign("taginfo", $taginfo);

           
            $smarty->assign("mod_tag_edit", $mod_tag_edit);

            if ($mod_tag_edit && isset($_GET['edit'])) {
                $page_title = "Edit Tag: ".$taginfo['title'];
                $display = "edittag.tpl";
            } else {
                $page_title = "Tag Info: ".$taginfo['title'];
                $display = "taginfo.tpl";
            }
        } else {
            include "404.php";
        }

    } elseif (isset($_REQUEST['create'])) {

        $message = "";
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 1) {
                $message = "Tag created";
            } else {
                $message = "Tag already exists";
            }
        }

        // Does the user have permission to create tags

        if ($mod_tag_create == true) {
            if (isset($_POST['create'])) {
                if ($csrf->validateToken($_POST['token'])) {
                    $status = $tag->createTag($_POST['title']);
                    header("Location: ./tags.php?create&status=".$status);
                }
            }
            $taginfo = array("title" => "");
            $smarty->assign("message", $message);
            $smarty->assign("taginfo", $taginfo);
            $smarty->assign("create_tag", true);
            $smarty->assign("token", $csrf->getToken());
            $display = "edittag.tpl";
            $page_title = "Create Topical Tag";
        } else {
            include "403.php";
        }

    } else {
        // View tag list
        $q = null;
        $filter = null;
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
            $filter = "[title:$q]";
        }
        $taglist = $tag->getTags($filter);
        for ($i=0; $i<count($taglist); $i++) {
            $taglist[$i]['parents'] = $tag->getParents($taglist[$i]['id']);
            $sql_objectCount = "SELECT COUNT(tag_id) as count FROM Tagged 
                WHERE Tagged.type = 1 AND tag_id = ".$taglist[$i]['id'];
            $statement = $db->query($sql_objectCount);
            $count = $statement->fetch();
            $taglist[$i]["count"] = $count[0];
            $statement->closeCursor();

            $sql_lastPost = "SELECT MAX(Messages.posted) as posted FROM Messages 
                LEFT JOIN Tagged ON Tagged.data_id = Messages.topic_id
                WHERE Messages.revision_no = 0 AND Tagged.type = 1 AND Tagged.tag_id = ".$taglist[$i]['id'];
            $statement_lastPost = $db->query($sql_lastPost);
            $posted = $statement_lastPost->fetch();
            $taglist[$i]['posted'] = $posted[0];
            $statement_lastPost->closeCursor();
        }
        $smarty->assign("tag_search", htmlentities($q));
        $smarty->assign("taglist", $taglist);
        $smarty->assign("page_count", 1);
        $smarty->assign("current_page", 1);
        $display = "tags.tpl";
        $page_title = "Tags";
    }
    include "includes/deinit.php";
} else {
    include "403.php"; // User not logged in
}
