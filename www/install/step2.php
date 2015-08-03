<?php
/*
 * step2.php
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

$message = NULL;
$finished = FALSE;
global $db;

function check_db($host, $name, $user, $pass){
    try{
        $GLOBALS['db'] = new PDO("mysql:host=".$host.";dbname=".$name,
                            $user, $pass);
        return TRUE;
    }catch(PDOException $e){
        return FALSE;
    }
    
}

function write_config($db_host, $db_name, $db_user, $db_pass, $path){
    $filename = "Database.ini.php";
    $content = "<?php\n";
    $content .= "##Database Settings\n";
    $content .= "define(\"DATABASE_TYPE\", \"mysql\");\n";
    $content .= "define(\"DATABASE_HOST\", \"$db_host\");\n";
    $content .= "define(\"DATABASE_NAME\", \"$db_name\");\n";
    $content .= "define(\"DATABASE_USER\", \"$db_user\");\n";
    $content .= "define(\"DATABASE_PASS\", \"$db_pass\");\n";
    $content .= "?>";
    return file_put_contents($path."/includes/".$filename, $content);
}

function import_sql(){
    try{
        $GLOBALS['db'] = new PDO(DATABASE_TYPE.":host=".DATABASE_HOST.";dbname=".DATABASE_NAME,DATABASE_USER, DATABASE_PASS);
        $sql = "";
        $file_handle = fopen("schema.sql", "r");
        while(!feof($file_handle)){
            $sql .= fgets($file_handle);
        }
        $sql .= "\nUPDATE SiteOptions SET sitekey = \"".base64_encode(mcrypt_create_iv(64, MCRYPT_DEV_URANDOM))."\";";
        $GLOBALS['db']->exec($sql);
    }catch(PDOException $e){
        print $e->getMessage();
    }
}

if(isset($_POST['database'])){
    $db_host = @strip_tags($_POST['db_host']);
    $db_name = @strip_tags($_POST['db_name']);
    $db_user = @strip_tags($_POST['db_username']);
    $db_pass = @$_POST['db_pass'];
    $db_pass_confirm = @$_POST['db_pass_confirm'];
    
    if($db_pass != $db_pass_confirm){
        $message = "Database passwords don't match";
    }
    else{
        if(check_db($db_host, $db_name, $db_user, $db_pass)){
            $message = "Connection Successful<br />";
            if(write_config($db_host, $db_name, $db_user, $db_pass, $root_path))
                $message .= "Config file written succesfully<br />";
                $finished = TRUE;
        }else{
            $message = "Error connecting to database, please check your details<br />";
        }
    }
}
if(isset($_POST['write_db'])){
    import_sql();
    $import = true;
}
?>
<html>
<head>
    <title>Install - Step 2</title>
</head>
<body>
<div id="message"><?php print $message; ?></div>
<?php if(!$finished && !isset($import)){ ?>
<form action="" method="POST">
        <table>
        <tr>
            <th colspan="2">Database Info</th>
        </tr>
        <tr>
            <td>Database Host</td>
            <td><input type="text" name="db_host" value="<?php @print htmlentities($db_host) ?>"/></td>
        </tr>
        <tr>
            <td>Database Name</td>
            <td><input type="text" name="db_name" value="<?php @print htmlentities($db_name); ?>"/></td>
        </tr>
        <tr>
            <td>Database Username</td>
            <td><input type="text" name="db_username" value="<?php @print htmlentities($db_user); ?>"/></td>
        </tr>
        <tr>
            <td>Database Password</td>
            <td><input type="password" name="db_pass" autocomplete="off"/></td>
        </tr>
        <tr>
            <td>Database Password (confirm)</td>
            <td><input type="password" name="db_pass_confirm" autocomplete="off"/></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Save" name="database"/></td>
        </tr>
    </table>
</form>
<?php }elseif(!isset($import)){ ?>
Note: this will delete any existing tables in the specified database and overwrite the data. This cannot be undone! 
<form action="" method="post">
    <input type="submit" name="write_db" value="Write Database Tables" />
</form>
<?php } ?>
<?php if(isset($import)){ ?>
Database successfully written!<br />
<a href="./step3.php"><input type="button" value="Step 3"/></a>
<?php } ?>
</body>
</html>

