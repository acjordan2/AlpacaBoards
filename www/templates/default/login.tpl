<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">    
    <head>
        <title>{$sitename} - {$page_title}</title>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="./templates/default/css/login.css" />
    </head>
    <body onload="document.getElementsByTagName('input')[0].focus();">
        {if $message != NULL}{$message}{/if}<br />
        <div class="login">
            <form action="" method="POST" autocomplete="off">          
                {$sm_labels.username}:
                <input class="text" type="text" name="username" value="{$username}">
                <br />
                {$sm_labels.password}:
                <input class="text" type="password" name="password">
                <br />
                <input type="submit" value="Login">
              <input type="hidden" name="token" value="{$token}" />
            </form>
        </div>
        <div class="options">
             <a href="./register.php">{$sm_labels.sign_up}</a> | <a href="./passwordReset.php">{$sm_labels.forgot_password}</a>
        </div>
    </body>
</html>
