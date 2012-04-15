<?php
/*
 * Config.ini.php
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

$time = explode(' ', microtime());
$start = $time[1] + $time[0];

set_include_path(get_include_path().PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT']."/includes/smarty");

##Database Settings
define("DATABASE_TYPE", "mysql");
define("DATABASE_HOST", "localhost");
define("DATABASE_NAME", "spergs3");
define("DATABASE_USER", "andrew");
define("DATABASE_PASS", "xC4sL7p8");

##Sitewide Settings
define("DOMAIN", "vlad.local");
define("SITENAME", "Sper.gs");

##Security Settings
define("SALT_SIZE", 16);
define("USE_SSL", FALSE);
define("SITE_KEY", "12JHA85FBlFmI@cw*hKqj9cM0fwo!h%0A7ey0u3oEUP@znM#"); //DO NOT CHANGE ONCE SITE GOES LIVE
define("HASH_INTERATIONS", 1000); //DO NOT CHANGE ONCE SITE GOES LIVE
$allowed_tags =  array("\<b\>","\<\/b\>",
					 "\<strong\>","\<\/strong\>",
					 "\<i\>","\<\/i\>",
					 "\<em\>","\<\/em\>",
					 "\<u\>","\<\/u\>",
					 "\<strike\>","\<\/strike\>",
					 "\<s\>","\<\/s\>",
					 "\<del\>","\<\/del\>",
					 "\<spoiler\>", "\<\/spoiler\>",
					 "\<br\>", "\<br \/\>",
					 "\<br\/\>",
					 "\<pre\>", "\<\/pre\>",
					 "\<quote msgid=\"t,(\d+),(\d+)@(\d+)\"\>", "\<\/quote\>",
					 "\<img src=\"https?:\/\/i\.(minus|imgur)\.com\/([A-Za-z0-9_.-]+)\"( \/)?\>");
					 
$allowed_tags = array("search" => $allowed_tags,
					  "replace" => array(
								"<b>", "</b>",
								"<strong>", "</strong>",
								"<i>", "</i>",
								"<em>", "</em>",
								"<u>", "</u>",
								"<strike>", "</strike>",
								"<s>", "</s>",
								"<del>", "</del>",
								"<spoiler>", "</spoiler>",
								"<br/>", "<br />", "<br>",
								"<pre>", "</pre>",
								"<quote msgid=\"t,$1,$2@$3\">", "</quote>",
								"<img src=\"http://i.$1.com/$2\" />"
								)
						);

##Authentication Cookie Names
define("AUTH_KEY1", "sessionid");
define("AUTH_KEY2", "sessionkey");

##Template Engine Variables
define("TEMPLATE_DIR", $_SERVER['DOCUMENT_ROOT']."/templates");
define("TEMPLATE_CACHE", $_SERVER['DOCUMENT_ROOT']."/includes/smarty/cache");
define("TEMPLATE_CONFIG", $_SERVER['DOCUMENT_ROOT']."/includes/smarty/configs");
define("TEMPLATE_COMPILE", $_SERVER['DOCUMENT_ROOT']."/includes/smarty/templates_c");
define("DATE_FORMAT", "%m/%d/%Y %H:%M:%S");

?>
