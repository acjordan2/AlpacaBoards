<?php
/*
 * init.php
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

require_once("Config.ini.php");
require_once("User.class.php");
require_once("Smarty.class.php");
require_once("Override.inc.php");
require_once("CSRFGuard.class.php");
require_once("Site.class.php");

$ls = gmdate("D, d M Y H:i:s") . " GMT";
$es =  gmdate("D, d M Y H:i:s", 1)." GMT";

header("Expires: $es");
header("Last-Modified: $ls");
header("Pragma: no-cache");
header("Cache-Control: no-cache, private, no-store, must-revalidate, max-stale=0, post-check=0, pre-check=0");
header("X-Frame-Options: SAMEORIGIN");


#Initiate database connection
try{
	// Database Setup 
	$db = new PDO(DATABASE_TYPE.":host=".DATABASE_HOST.";dbname=".DATABASE_NAME,
						DATABASE_USER, DATABASE_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
	$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
	
    $site = new Site($db);

    $sql_site = "SELECT sitename, sitekey FROM SiteOptions";
    $statement_site = $db->query($sql_site);
    $results_site = $statement_site->fetch();
    $sitekey = base64_decode($site->getSiteKey());
    $sitename = htmlentities($site->getSiteName());
    
    define("SITE_KEY", $sitekey);
    define("SITENAME", $sitename);

	// Templating System Setup
	$smarty = new Smarty();
	$smarty->template_dir = TEMPLATE_DIR."/default";
	$smarty->compile_dir = TEMPLATE_COMPILE;
	$smarty->cache_dir = TEMPLATE_CACHE;
	$smarty->config_dir = TEMPLATE_CONFIG;
	$smarty->assign("sitename", SITENAME);
	$smarty->assign("domain", DOMAIN);
	$smarty->assign("dateformat", DATE_FORMAT_SMARTY);
	$smarty->assign("board_id", 42);
	$smarty->assign("base_image_url", BASE_IMAGE_URL);
	
    $csrf = new CSRFGuard();

	$authUser = new User($db);

    if(isset($_POST['token']) && isset($_POST['username']) && isset($_POST['password'])){
        if($csrf->validateToken($_POST['token'])) {
	       $auth = $authUser->checkAuthentication(@$_POST['username'], @$_POST['password']);
           if ($auth == true) { 
                $csrf->resetToken();
           }
        } else {
            $auth = false;
        }
    } else {
        $auth = $authUser->checkAuthentication();
    }
	if($auth == true){
		$smarty->assign("username", $authUser->getUsername());
		$smarty->assign("user_id", $authUser->getUserID());
		$authUser->awardKarma();
		$smarty->assign("karma", $authUser->getKarma());
		if($authUser->getStatus() == -1){
			$message = "You have been banned";
			$display = "no_access.tpl";
			$page_title = "You have been banned";
			$smarty->assign("message", $message);
			$smarty->assign("reason", htmlentities($authUser->getDisciplineReason()));
			include "deinit.php";
		}
	}
} catch(PDOException $e){
	//die($e->getMessage());
	print "Error :(";
}
?>
