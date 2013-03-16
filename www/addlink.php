<?php
/*
 * addlink.php
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
require("includes/Link.class.php");
require("includes/CSRFGuard.class.php");
print override\validateURL("asdfasdfasdfadsf");
if($auth == TRUE){
	$csrf = new CSRFGuard();
	$links = new Link($db, $authUser->getUserID());
	if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['token'])){
		if(TRUE){
				$error_msg = "";
				if(isset($_POST['lurl'])){
						if(!override\validateURL($_POST['lurl']))
							$error_msg = "Please enter a valid URL<br />";
						elseif($links->checkURLExist($_POST['lurl']))
							$error_msg = "A link with that URL already exists";
					$smarty->assign("lurl", override\htmlentities($_POST['lurl']));
				}
				if(isset($_POST['title'])){
					$smarty->assign("title", override\htmlentities($_POST['title']));
					if(strlen($_POST['title']) < 5 || strlen($_POST['title'] > 80))
						$error_msg .= "The title must be between 5 and 80<br />";
				}
				if(isset($_POST['description'])){
					$smarty->assign("description", override\htmlentities($_POST['description']));
					if(strlen($_POST['description']) < 5)
						$error_msg .= "Description must be long than 5 characters<br />";
				}
				if($error_msg=="")
					if($links->addLink($_REQUEST))
						header("Location: /linkme.php?l=".$links->getLinkID());
				$smarty->assign("error", $error_msg);		
		}
	}
	$smarty->assign("categories", Link::getCategories($db));
	$smarty->assign("token", $csrf->getToken());
	$display = "addlink.tpl";
	require("includes/deinit.php");
}else
	require("404.php");
?>
