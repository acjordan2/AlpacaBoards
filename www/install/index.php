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

function passCompatCheck() {
    $hash = '$2y$04$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
    $test = crypt("password", $hash);
    $pass = $test == $hash;
    return $pass;
}

function checkWrite($directory) {
    $perm = file_put_contents($directory."/test.txt", "write_test");
    unlink($directory."/test.txt");
    return $perm;
}

$failed = false;

#Minimum Requirements
$req_php_version = 053070;
$functions = array(
    "curl_version" => "PHP5 cURL Extension",
    "mcrypt_create_iv" => "PHP5 mcrypt Extension",
    "json_encode" => "PHP5 JSON extension",
    "imagecreatefromgif" => "PHP GD Image Library",
    "mysql_connect" => "MySQL Extension"
);

$directories = array(
    TEMPLATE_COMPILE => "Template Directory is Writeable",
    $root_path."/includes" => "Includes Directory is Writeable",
    $root_path."/install" => "Install Directory is Writeable",
    $root_path."/usercontent" => "User Content Directory is Writeable"
);

#Currently Installed
$inst_php_version = PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION.".".PHP_RELEASE_VERSION;

if($req_php_version < PHP_VERSION_ID)
    $php_version = true;
if(!defined('PDO::ATTR_DRIVER_NAME')){
    $pdo_installed = FALSE;
}else
    $pdo_installed = TRUE;

$magic_quotes = get_magic_quotes_gpc();
?>
<html>
<head>
    <title>Install</title>
</head>
<body>
    <table border="1">
        <tr>
            <th>Required</th>
            <th>Installed</th>
            <th>Status</th>
            <th>Notes</th>
        </tr>
        <tr>
            <td>PHP >5.3.7</td>
            <td><?php print $inst_php_version; ?></td>
            <td style="background-color: <?php if ($php_version) print "green"; else print "red"; ?>"><?php if($php_version) print "OK"; else{print "Failed"; $failed=true;}?></td>
        </tr>
        <tr>
            <td>Magic Quotes Disabled</td>
            <td><?php if(!$magic_quotes) print "Yes"; else print "No" ?></td>
            <td style="background-color: <?php if(!$magic_quotes) print "green"; else print "red"; ?>"><?php if(!$magic_quotes) print "OK";else{print "Failed"; $failed=true;}?></td>
        	<?php if($magic_quotes) print "<td>Magic quotes can cause unexpected bahavior and should be disabled, instructions can be found <a href=\"http://php.net/manual/en/security.magicquotes.disabling.php\" target=\"_blank\">here</a></td>";?>
        </tr>
        <tr>
            <td>PHP PDO Extension</td>
            <td><?php if($pdo_installed) print "Yes"; else print "No" ?></td>
            <td style="background-color: <?php if($pdo_installed) print "green"; else print "red"; ?>"><?php if($pdo_installed) print "OK";else{print "Failed"; $failed=true;}?></td>
        </tr>
        <?php
        foreach($functions as $key => $value){
        	$f_check = function_exists($key);
        	print "<tr>";
            print "<td>$value</td>";
            print "<td>";
            if($f_check){
            	print "Yes"; 
            } else {
            	print "No";
            }
            print "</td><td style=\"background-color:";
            if ($f_check) {
            	print "green"; 
            } else { 
            	print "red"; 
            }
            print "\">";
            if($f_check) { 
            	print "OK";
            } else {
            	print "Failed";
            	$failed = true;
            	if ($key == "mcrypt_create_iv"){
            		print "</td><td>If you're having trouble on Ubuntu, follow <a href=\"http://stackoverflow.com/a/19447669\" target=\"blank\">these instructions</a> after installing the extension.";

            	}
            }
            print "</td></tr>";
        }
        $p_check = passCompatCheck();
    	print "<tr>";
        print "<td>PHP password_hash Compatibility</td>";
        print "<td>";
        if($p_check){
        	print "Yes";
        } else {
        	print "No";
        }
        print "</td><td style=\"background-color:";
        if ($p_check) {
        	print "green";
        } else { 
        	print "red";
        }
        print "\">";
        if ($p_check) {
        	print "OK";
        } else {
        	print "Failed";
        	print "</td><td>Your version of PHP is not compatible with the password_hash function, please upgrade to at least 5.3.7";
        	$failed = true;
        }
        print "</td></tr>";
        foreach ($directories as $key => $value) {
        	$d_check = checkWrite($key);
        	print "<tr>";
            print "<td>$value</td>";
            print "<td>";
            if($d_check){
            	print "Yes";
            } else {
            	print "No";
            }
            print "</td><td style=\"background-color:";
            if ($d_check) {
            	print "green";
            } else { 
            	print "red";
            }
            print "\">";
            if ($d_check) {
            	print "OK";
            } else {
            	print "Failed";
            	print "</td><td>Run <pre>sudo chmod a+w ".$key."</pre>";
                $failed = true;
                if (substr($key, -11, 11) == "usercontent"){
                    print "</td><td>Add -R option to recursively chmod the usercontent directory.";
                }
            }
            print "</td></tr>";
        }
        ?>
    </table>
    <br />
    <br />
    <?php 
          if(!$failed){
     ?>
        <form action="step2.php" method="get">
            <input type="submit" value="Next Step" />
        </form>
     <?php } ?>
    </div>
</body>
</html>
