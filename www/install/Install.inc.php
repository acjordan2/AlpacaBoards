<?php
$install = true;

require_once("../includes/Site.class.php");

$root_path = Site::getRootPath();

if(!defined("TEMPLATE_COMPILE"))
    define("TEMPLATE_COMPILE", Site::getConstant("TEMPLATE_COMPILE"));

if (file_exists($root_path."/includes/Database.ini.php")) {
    require_once("../includes/Database.ini.php");
}

require_once("../includes/Config.ini.php");

if(file_exists($root_path."/install/install.lock"))
	die("Remove the install.lock file from the install directory to continue");
?>
