<?php
/*
 * step3.php
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
require("../includes/init.php");

$message = null;
$finished = false;

if(isset($_POST['sitename'])) {
    $site->updateSiteOptions(
        $_POST['sitename'],
        (int)$_POST['registration'],
        (int)$_POST['invites']
    );
    $site->setDomain($_POST['domain']);
    header("Location: ./step4.php");
}

?>
<html>
<head>
    <title>Install - Step 3</title>
</head>
<body>
<div id="message"><?php print $message; ?></div>
<form action="" method="POST">      
        <fieldset style="width:250px;">
            <legend><small>Site Settings:</small></legend>
            <br />
            Site Name:<br />
            <input type="text" name="sitename" style="width:100%;" value="<?php print SITENAME ?>" />
            <br /><br />
            Domain:<br />
            <input type="text" name="domain" style="width:100%;" value="<?php print DOMAIN ?>"/>
            <br /><br />
            Registration:<br />
            <select name="registration">
                    <option value="0">Disabled</option>
                    <option value="1">Invite Only</option>
                    <option value="2">Open</option>
                </select>
            <br /><br />
            Invites:<br />
            <select name="invites">
                    <option value="0">Disabled</option>
                    <option value="1">Can be bought</option>
                    <option value="2">Open</option>
                </select>
            <br /><br />
            <input type="submit" value="Next">
          </fieldset>
        </form>
</form>
</body>
</html>

