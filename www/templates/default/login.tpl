<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">    
    <head>
        <title>{$sitename} - {$page_title}</title>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="{$base_url}/css.php" />
    </head>
    <body onload="document.getElementsByTagName('input')[0].focus();">
        <div class="login">
            <div class="message">{if $message != NULL}{$message}{/if}</div>
            <form action="" method="POST" autocomplete="off">          
                <label>
                    <span>{$sm_labels.username}: </span>
                    <input class="text" type="text" name="username" value="{$username}">
                </label>
                 <label>
                    <span>{$sm_labels.password}: </span>
                    <input class="text" type="password" name="password">
                 </label>
                <input type="submit" value="Login">
              <input type="hidden" name="token" value="{$token}" />
            </form>
        </div>
        <div class="options">
             <a href="{$base_url}/register.php">{$sm_labels.sign_up}</a> | <a href="{$base_url}/passwordReset.php">{$sm_labels.forgot_password}</a>
        </div>
    </body>
</html>
