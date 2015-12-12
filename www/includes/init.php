<?php
/*
 * init.php
 * 
 * Copyright (c) 2015 Andrew Jordan
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

require_once("functions.php");

// Get requested resource from the URI, remove base bath and URL params
$script_name = array_pop(explode("/", $_SERVER["SCRIPT_FILENAME"]));
$request_uri = array_filter(explode($script_name, array_shift(explode("?", $_SERVER['REQUEST_URI']))));


if ($script_name == "index.php") {
    $request_uri = array_filter(explode("index.php",array_shift(explode("?", $_SERVER['REQUEST_URI']))));

    if (sizeof($request_uri) == 1 ) {
        $request_uri = "index.php";
    }
}
if ((sizeof($request_uri) > 1 && !defined('SOMETHING_SCREWEY'))) {

    // Something screwey happened
    define("SOMETHING_SCREWEY", true);
    include "init.php";
    $smarty->assign("base_url", "//".$site->getDomain().rtrim(array_shift(explode($script_name, $_SERVER['REQUEST_URI'])), "/"));
    include("403.php");

}

$time = explode(' ', microtime());
$start = $time[1] + $time[0];

require_once("Config.ini.php");
require_once("ConnectionFactory.class.php");
require_once("User.class.php");
require_once("smarty/Smarty.class.php");
require_once("Autolink.inc.php");
require_once("CSRFGuard.class.php");
require_once("Site.class.php");

require_once("localization/en_US/messages.inc.php");
require_once("localization/en_US/labels.inc.php");


$ls = gmdate("D, d M Y H:i:s") . " GMT";
$es =  gmdate("D, d M Y H:i:s", 1)." GMT";

header("Expires: $es");
header("Last-Modified: $ls");
header("Pragma: no-cache");
header("Cache-Control: no-cache, private, no-store, must-revalidate, max-stale=0, post-check=0, pre-check=0");
header("X-Frame-Options: SAMEORIGIN");
header("Content-Security-Policy: default-src 'none'; script-src 'self'; object-src 'none'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; media-src 'self'; frame-src 'self'; font-src 'self'; connect-src 'self'");
header("X-Content-Security-Policy: default-src 'none'; script-src 'self'; object-src 'none'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; media-src 'self'; frame-src 'self'; font-src 'self'; connect-src 'self'");
header("X-WebKit-CSP: default-src 'none'; script-src 'self'; object-src 'none'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; media-src 'self'; frame-src 'self'; font-src 'self'; connect-src 'self'");

if (file_exists(Site::getRootPath()."/includes/Database.ini.php")) {
    require_once("Database.ini.php");
    $db = ConnectionFactory::getInstance()->getConnection();
} elseif (!isset($install)) {
    header("Location: ./install/");
    exit();
}

$site = new Site();
$sitekey = base64_decode($site->getSiteKey());
$sitename = htmlentities($site->getSiteName());
$root_path = $site->getRootPath();


$base_url = $site->getBaseUrl();
define("SITENAME", $sitename);
define("BASEURL", $site->getDomain().$site->getBaseUrl());
define("ROOTPATH", $root_path);
define("BASE_IMAGE_URL", $site->getImagePath());

##Template Engine Variables
define("TEMPLATE_CONFIG", $root_path."/includes/smarty/configs");
define("TEMPLATE_COMPILE", $root_path."/includes/smarty/templates_c");
define("DATE_FORMAT_SMARTY", "%m/%d/%Y %l:%M:%S %p");
define("DATE_FORMAT", "n/j/Y g:i:s A");


// Templating System Setup
$smarty = new Smarty();

$smarty->template_dir = Site::getConstant("TEMPLATE_DIR")."/default";
$smarty->compile_dir = Site::getConstant("TEMPLATE_COMPILE");
$smarty->cache_dir = Site::getConstant("TEMPLATE_CACHE");
$smarty->config_dir = Site::getConstant("TEMPLATE_CONFIG");
$smarty->assign("sitename", SITENAME);
$smarty->assign("domain", $site->getDomain());
$smarty->assign("dateformat", DATE_FORMAT_SMARTY);
$smarty->assign("board_id", 42);
$smarty->assign("base_url", $base_url);
$smarty->assign("base_image_url", BASE_IMAGE_URL);
$smarty->assign("sm_labels", $GLOBAL['locale_labels']);

if (isset($_COOKIE[AUTH_KEY1]) && isset($_COOKIE[AUTH_KEY2])) {
    $session_salt = $_COOKIE[AUTH_KEY1].$_COOKIE[AUTH_KEY2];
} else {
    $session_salt = null;
}

$options = array(
    "domain" => $site->getDomain(),
    "ssl" => USE_SSL,
    "session_salt" => $session_salt
);
$csrf = new CSRFGuard($site->getSiteKey(), $options);

$authUser = new User($site);

$auth = false;

if (isset($_COOKIE[AUTH_KEY1]) && isset($_COOKIE[AUTH_KEY2])) {
    $auth = $authUser->authenticateWithCookie();
}
if ($auth == true) {
    if (($site->getDomain() == null) && ($authUser->getAccessLevel() == 1) && !isset($_COOKIE['redirect'])) {
        setcookie("redirect", true);
        header("Location: ./siteoptions.php?domain");
        exit();
    }
    $smarty->assign("username", $authUser->getUsername());
    $smarty->assign("user_id", $authUser->getUserID());
    $authUser->awardCredit();
    $smarty->assign("karma", $authUser->getKarma());
    if ($authUser->getStatus() == -1) {
        $message = "You have been banned";
        $display = "no_access.tpl";
        $page_title = "You have been banned";
        $smarty->assign("message", $message);
        $smarty->assign("reason", htmlentities($authUser->getDisciplineReason()));
        include "deinit.php";
    }
}
