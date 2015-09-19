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
$install = true;
require("../includes/init.php");

$message = null;
$finished = false;

if(isset($_POST['username']) && isset($_POST['email']) && 
    isset($_POST['password']) && isset($_POST['password2'])){
    try{
        $message = "";
        if(strlen($_POST['password']) < 8)
            $message = "Password must be at least 8 characters<br />";
        if($_POST['password'] != $_POST['password2'])
            $message .= "Password don't match<br />";
        if(!$authUser->validateEmail($_POST['email']))
            $message .= "Please enter a valid email address";
        if($message == ""){
            $status = $authUser->createUser($_POST['username'], $_POST['password'], $_POST['email']);
            switch($status){
                case -1:
                    $message = "That username already exists<br />";
                    break;
                case -2:
                    $message = "Invalid invite code. Invite codes are case sensitive<br />";
                    break;
                case 1:
                    $sql_setLevel = "UPDATE Users SET level=1 WHERE username=?";
                    $statement_setLevel = $db->prepare($sql_setLevel);
                    $statement_setLevel->execute(array($_POST['username']));
                    file_put_contents("install.lock", "");
                    header("Location: ../index.php?m=1");
                    break;
            }
        }
    }
    catch(PDOException $e){
        print $e->getMessage();
    }
}


?>
<html>
<head>
    <title>Install - Step 4</title>
</head>
<body>
<div id="message"><?php print $message; ?></div>
<form action="" method="POST">    
        <fieldset style="width:250px;">
            <legend><small>Create Administrative User:</small></legend>
            <br />
            Username:<br />
            <input type="text" name="username" style="width:100%;" />
            <br /><br />
            Email:<br />
            <input type="text" name="email" style="width:100%;" />
            <br /><br />
            Password:<br />
            <input type="password" name="password" style="width:100%;" autocomplete="off"/>
            <br /><br />
            Password (Again):<br />
            <input type="password" name="password2" style="width:100%;" autocomplete="off"/>
            <br /><br />            
            <input type="submit" value="Register">
          </fieldset>
        </form>
</form>
</body>
</html>

