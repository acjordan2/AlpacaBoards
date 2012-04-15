<?php
require_once("includes/init.php");
if($auth == TRUE){
	header('HTTP/1.1 404 Not Found');
	$display = "404.tpl";
	require_once("includes/deinit.php");
}else
	header("Location: /?r=".rawurlencode($_SERVER['REQUEST_URI']));
?>
