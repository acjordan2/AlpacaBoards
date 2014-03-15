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


$root_path = get_root_path();

set_include_path(get_include_path().PATH_SEPARATOR.$root_path."/includes/smarty");

if (file_exists($root_path."/includes/Database.ini.php")) {
    require_once("Database.ini.php");
} elseif (!isset($install)) {
    header("Location: ./install/");
}


##Sitewide Settings
//define("DOMAIN", ""); // Override default settings
define("BASE_IMAGE_URL", "./usercontent/i");

##Search Settings
define("SEARCHD_PATH", "/usr/bin/searchd");
define("INDEXER_PATH", "/usr/bin/indexer");
define("SPHINX_HOST", "localhost");
define("SPHINX_PORT", 3312);
define("SPHINX_CONFIG", "/var/www/Sper.gs/sphinx/sphinx.conf");

##Security Settings
define("SALT_SIZE", 16);
define("USE_SSL", false);
define("HASH_INTERATIONS", 1000); //DO NOT CHANGE ONCE SITE GOES LIVE

##Authentication Cookie Names
define("AUTH_KEY1", "sessionid");
define("AUTH_KEY2", "sessionkey");

##Template Engine Variables
define("TEMPLATE_DIR", $root_path."/templates");
define("TEMPLATE_CACHE", $root_path."/includes/smarty/cache");
define("TEMPLATE_CONFIG", $root_path."/includes/smarty/configs");
define("TEMPLATE_COMPILE", $root_path."/includes/smarty/templates_c");
define("DATE_FORMAT_SMARTY", "%m/%d/%Y %l:%M:%S %p");
define("DATE_FORMAT", "n/j/Y g:i:s A");
