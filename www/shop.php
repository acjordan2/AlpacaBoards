<?php
/*
 * shop.php
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
require "includes/Shop.class.php";

// Check authentication
if ($auth == true) {
    // Verify shop itme
    $item_id = @$_GET['item'];
    $shop = new Shop($db, $authUser->getUserID());
    $smarty->assign("credits", $authUser->getCredits());
    if (is_numeric($item_id)) {
        $item = $shop->getItem($item_id);
        $smarty->assign("item", $item);

        // Create new anti-CSRF token
        $smarty->assign("csrf_token", $csrf->getToken());
        if (@$_POST['submit'] == "Purchase" 
            // Purchase item
            && is_numeric(@$_POST['item']) 
            && $csrf->validateToken(@$_POST['token']) 
            && $authUser->getCredits() > $item['price']
        ) {
            if($shop->purchaseItem($item_id))
                header("Location: ./inventory.php");
        }
        // Set template display page
        $display = "item.tpl";
        $page_title = "Purchase ".$item['name'];
    } elseif ($item_id != '') {
        include "404.php";
    } else {
        // set template display page
        $smarty->assign("items", $shop->getItems());
        $display = "shop.tpl";
        $page_title = "Token Shop";
    }
    include "includes/deinit.php";
} else {
    include "404.php";
}
?>
