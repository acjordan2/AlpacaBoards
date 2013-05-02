<?php
/*
 * install.php
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
require("Install.inc.php");

$failed = FALSE;

#Minimum Requirements
$req_php_version = 053000;

#Currently Installed
$inst_php_version = PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION.".".PHP_RELEASE_VERSION;

if($req_php_version < PHP_VERSION_ID)
	$php_version = true;
if(!defined('PDO::ATTR_DRIVER_NAME')){
	$pdo_installed = FALSE;
}else
	$pdo_installed = TRUE;

$curl_installed = function_exists('curl_version');

@$template_perms = file_put_contents(TEMPLATE_COMPILE."/test.txt", "write_test");
@$include_perms = file_put_contents($root_path."/includes/test.txt", "write_test");
@$install_perms = file_put_contents($root_path."/install/test.txt", "write_test");

@unlink(TEMPLATE_COMPILE."/test.txt");
@unlink($root_path."/includes/test.txt");
@unlink($root_path."/install/test.txt");

$magic_quotes = get_magic_quotes_gpc();
?>
<html>
<head>
	<title>Install</title>
</head>
<body>
	<div style="text-aligh:center;">
	<table border="1">
		<tr>
			<th>Required</th>
			<th>Installed</th>
			<th>Status</th>
		</tr>
		<tr>
			<td>PHP >5.3</td>
			<td><?php print $inst_php_version; ?></td>
			<td style="background-color: <?php if($php_version) print "green"; else print "red"; ?>"><?php if($php_version) print "OK"; else{print "Failed"; $failed=TRUE;}?></td>
		</tr>
		<tr>
			<td>PHP PDO Extension</td>
			<td><?php if($pdo_installed) print "Yes"; else print "No" ?></td>
			<td style="background-color: <?php if($pdo_installed) print "green"; else print "red"; ?>"><?php if($pdo_installed) print "OK";else{print "Failed"; $failed=TRUE;}?></td>
		</tr>
		<tr>
			<td>PHP cURL Extension</td>
			<td><?php if($curl_installed) print "Yes"; else print "No" ?></td>
			<td style="background-color: <?php if($curl_installed) print "green"; else print "red"; ?>"><?php if($curl_installed) print "OK";else{print "Failed"; $failed=TRUE;}?></td>
		</tr>
		<tr>
			<td>Template Directory Writeable</td>
			<td><?php if($template_perms) print "Yes"; else print "No" ?></td>
			<td style="background-color: <?php if($template_perms) print "green"; else print "red"; ?>"><?php if($template_perms) print "OK";else{print "Failed"; $failed=TRUE;}?></td>
		</tr>
		<tr>
			<td>Install Directory Writeable</td>
			<td><?php if($install_perms) print "Yes"; else print "No" ?></td>
			<td style="background-color: <?php if($install_perms) print "green"; else print "red"; ?>"><?php if($install_perms) print "OK";else{print "Failed"; $failed=TRUE;}?></td>
		</tr>
		<tr>
			<td>Includes Directory Writeable</td>
			<td><?php if($include_perms) print "Yes"; else print "No" ?></td>
			<td style="background-color: <?php if($include_perms) print "green"; else print "red"; ?>"><?php if($include_perms) print "OK";else{print "Failed"; $failed=TRUE;}?></td>
		</tr>
		<tr>
			<td>Magic Quotes Disabled</td>
			<td><?php if(!$magic_quotes) print "Yes"; else print "No" ?></td>
			<td style="background-color: <?php if(!$magic_quotes) print "green"; else print "red"; ?>"><?php if(!$magic_quotes) print "OK";else{print "Failed"; $failed=TRUE;}?></td>
		</tr>
	</table>
	<br />
	<br />
	<?php if(!$template_perms) print "run chmod 777 ".TEMPLATE_COMPILE."<br />";
		if(!$install_perms) print "run chmod 777 ".$root_path."/install<br />";
		if(!$include_perms) print "run chmod 777 ".$root_path."/includes<br />";
		if($magic_quotes) print "Magic quotes can cause unexpected bahavior and should be disabled, instructions can be found <a href=\"http://php.net/manual/en/security.magicquotes.disabling.php\" target=\"_blank\">here</a>";
		  if(!$failed){
	 ?>
		<form action="step2.php" method="get">
			<input type="submit" value="Next Step" />
		</form>
	 <?php } ?>
	</div>
</body>
</html>
