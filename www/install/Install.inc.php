<?php
$install = true;

require_once("../includes/Site.class.php");

$root_path = Site::getRootPath();

define("TEMPLATE_COMPILE", Site::getConstant("TEMPLATE_COMPILE"));

if (file_exists($root_path."/includes/Database.ini.php")) {
    require_once("../includes/Database.ini.php");
}

require_once("../includes/Config.ini.php");
require_once("../includes/Override.inc.php");

if(file_exists($root_path."/install/install.lock"))
	die("Remove the install.lock file from the install directory to continue");
?>
