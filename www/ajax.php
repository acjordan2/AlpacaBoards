<?php
/*
 * ajax.php
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

require("includes/init.php");
require("includes/CSRFGuard.class.php");

if($auth == TRUE){
	$output = "";
	$csrf = new CSRFGuard();
	$token = @$_POST['token'];

	$action = explode("_", @$_POST['action']);
	$section = @$action[0];
	$action = @$action[1];
	switch($section){
		case "link":
			require("includes/Link.class.php");
			$link_id = @$_POST['l'];
			if(!is_numeric($link_id))
				$output = "{\"error\": \"Invalid Link ID\"}";
			else
				$link = new Link($db, $authUser->getUserID(), $link_id);
				if($link->doesExist()){
					switch($action){
						case "vote":
							if(is_numeric(@$_POST['v']) && @$_POST['v'] >= 0 && @$_POST['v'] <= 10 && $link->getLinkUserID() !=$authUser->getUserID()){
								if($csrf->validateToken(@$_REQUEST['token'])){
									$link->vote($_POST['v']);
									$output = $link->getVotes();
									$output['message'] = "Vote Added!";
								}			
							}

							break;
						case "fav":
							if((@$_POST['f'] === "1" || @$_POST['f'] === "0") && $csrf->validateToken(@$_REQUEST['token'])){
								if($link->addToFavorites($_POST['f'])){
									if($_POST['f'] === "1"){
										$output['f'] = "Remove from Favorites";
										$output['message'] = "Added to favorites!";
									}
									elseif($_POST['f'] === "0"){
										$output['f'] = "Add to Favorites";
										$output['message'] = "Removed from favorites!";
									}
								}
							}	
							break;
					}
				}
			break;
	}
	print json_encode($output);

}
else
	require("404.php");
?>