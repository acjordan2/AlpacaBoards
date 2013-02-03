<?php
/*
 * shop.php
 * 
 * Copyright (c) 2012 Andrew Jordan
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
 
require("includes/init.php");
require("includes/Shop.class.php");
require("includes/CSRFGuard.class.php");
if($auth == TRUE){
	$item_id = @$_GET['item'];
	$shop = new Shop($db, $authUser->getUserID());
	$smarty->assign("credits", $authUser->getCredits());
	if(is_numeric($item_id)){
		$item = $shop->getItem($item_id);
		$smarty->assign("item", $item);
		$csrf = new CSRFGuard();
		$smarty->assign("csrf_token", $csrf->getToken());
		if(@$_POST['submit'] == "Purchase" && is_numeric(@$_POST['item']) && $csrf->validateToken(@$_POST['token']) && $authUser->getCredits() > $item['price'])
			if($shop->purchaseItem($item_id))
				header("Location: ./inventory.php");
		$display = "item.tpl";
	}
	elseif($item_id != '')
		require("404.php");
	else{
		$smarty->assign("items", $shop->getItems());
		$display = "shop.tpl";
	}
	require("includes/deinit.php");
}else
	require("404.php");
?>
