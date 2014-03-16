<?php
$install = true;

require_once("../includes/functions.php");
require_once("../includes/Config.ini.php");
require_once("../includes/Override.inc.php");

$root_path = get_root_path();

if(file_exists($root_path."/install/install.lock"))
	die("Remove the install.lock file from the install directory to continue");
?>
