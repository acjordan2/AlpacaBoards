<!DOCTYPE HTML>
<html>
    <head>
        <title>{$sitename} - Register</title>
        <link rel="stylesheet" type="text/css" href="./templates/default/css/login.css" />
        <script type="text/javascript">
            function checkField(){
                
            }
        </script>
    </head>
    <body>
        <div class="login">
            <div class="message">{if isset($message)}<br />{$message}{/if}</div>
            <form action="register.php{if isset($invite)}?code={$invite_code}{/if}" method="POST" autocomplete="OFF">
                <input type="hidden" name="token" value="{$token}" />
                <label>
                    <span>Username: </span> 
                    <input class="text" type="text" name="username" value="{if isset($username)}{$username}{/if}"/>
                </label>
                <label>    
                    <span>Email: </span>
                    <input class="text" type="text" name="email" value="{if isset($email)}{$email}{/if}" />
                </label>    
                <label>
                    <span>Password: </span>
                    <input class="text" type="password" name="password" id="password"/>
                </label>
                <label>                
                    <span>Password (Again): </span> 
                    <input class="text" type="password" name="password2" id="password2"/>
                </label>
                {if $registration_status == 1}
                {if isset($invite)}<input type="hidden" name="invite_code" value="{$invite_code}" />
                {else}
                <label>
                    <span>Invite Code: </span> 
                    <input type="text" name="invite_code" value="" />
                </label>{/if}
                {/if}
                <input type="submit" value="Register">
            </form>
        </div>
        <div class="options">
            <a href="./index.php">{$sm_labels.login}</a> | <a href="./register.php">{$sm_labels.sign_up}</a> 
        </div>
    </body>
</html>
