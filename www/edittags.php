<?php
/*
 * edittags.php
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
require "includes/Parser.class.php";
require "includes/Topic.class.php";
require "includes/Tag.class.php";

if ($auth === true) {
    if (isset($_GET['topic']) && is_numeric($_GET['topic'])) {
        $topic_id = $_GET['topic'];
        $parser = new Parser();
        $csrf->setPageSalt("edittags".$topic_id);
    
        try {
            $topic = new Topic($authUser, $parser, $topic_id);      
            $tag = new Tag($authUser->getUserId());
            
            if ($authUser->getUserId() === $topic->getTopicCreator()) {
                if (isset($_POST['submit'])) {
                    if ($csrf->validateToken($_POST['token'])) {
                        $tag_edit = explode(",", $_POST['tags']);
                        $new_tags = array();
                        for ($i=0; $i<count($tag_edit); $i++) {
                            $tmp = explode(":", $tag_edit[$i]);
                            if (count($tmp) < 2) {
                                $new_tags[] = $tag_edit[$i];
                            }
                        }
                        $tag->editTags($topic_id, 1, $new_tags);
                        $smarty->assign("message", "Changes Saved");
                    }
                }
            
                $current_tags = $tag->getObjectTags($topic_id, 1);
                $taglist = array();
                foreach($current_tags as $c_tag) {
                    $taglist[] = $c_tag['tag_id'].":".$c_tag['title'];
                }
                $smarty->assign("token", $csrf->getToken()); 
                $smarty->assign("topic", $topic_id);
                $smarty->assign("topic_title", $topic->getTopicTitle());
                $smarty->assign("current_tags", $current_tags);
                $smarty->assign("tags", implode(",", $taglist));

                $display = "edittags.tpl";
                $page_title = "Change Topic Tags";
                include "includes/deinit.php";
            } else {
                // User tried to edit tags to a topic they did not create
                include "403.php";
            }
        } catch (Exception $e) {
            // Topic does not exist
            include "404.php";
        }     
    } else {
        // No parameters set, so there is nothing to do
        include "404.php";
    }
} else {
    // User tried to access without authentication
    include "403.php";
}
