<?php
/*
 * showtopics.php
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
require "includes/Board.class.php";
require "includes/Tag.class.php";
require "includes/Parser.class.php";
require "includes/Topic.class.php";

// Check authentication
if ($auth === true) {
    if (!is_int(@$_GET['board'])) {
        $board_id = 42;
    } else {
        $board_id = $_GET['board'];
    }
    if (!is_numeric(@$_GET['page'])
        || @$_GET['page'] == null
    ) {
        $current_page = 1;
    } else {
        $current_page = intval($_GET['page']);
    }
    // Default board 42
    $board = new Board($db, 42, $authUser->getUserID());
    $tags = new Tag($authUser->getUserID());
    $topic_list = $board->getTopics($current_page);
    if (isset($_GET['tags'])) {
        $topic_list = $tags->getContent($_GET['tags'], 1);
        $title = $_GET['tags'];
    } else {
        $title = "Everything";
        $parser = new Parser();
        $topic = new Topic($authUser, $parser);
        $topic_list = $topic->getTopics();
    }
    if ($current_page == 1) {
        $sticky_list = $board->getStickiedTopics();
    }
    $display = "showtopics.tpl";
    $page_title = $board->getTitle();
    if (count($topic_list) == 0) {
        include "404.php";
    } else {
        $smarty->assign("topicList", $topic_list);
        if (isset($sticky_list)) {
            $smarty->assign("stickyList", $sticky_list);
        }
        $search = array('[', ']', "_");
        // Set template variables
        $smarty->assign("username", $authUser->getUsername());
        $smarty->assign("board_id", $board_id);
        $smarty->assign("board_title", htmlentities(str_replace($search, " ", $title)));
        $smarty->assign("page_count", $board->getPageCount());
        $smarty->assign("current_page", $current_page);
        $smarty->assign("num_readers", $board->getReaders());
    }
} else {
    include "404.php";
}

require "includes/deinit.php";
?>
