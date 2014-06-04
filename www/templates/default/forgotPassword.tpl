<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">	
	<head>
		<title>Reset Password</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="./templates/default/css/login.css" />
	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		<div class="login">
			<div class="message">{if isset($message)}{$message}{/if} </div>
			<form action="" method="POST" autocomplete="off">
				{if isset($change)}
					<label>
						<span>Password: </span>
						<input class="text" type="password" name="new" value=""/>
					</label>
					<label>
						<span>Confirm Password: </span>
						<input class="text" type="password" name="new2" value=""/>
					</label>
					{else}
					<label>
						<span>Username: </span>
						<input class="text" type="text" name="username" value="">
					</label>
				{/if}
				<input type="submit" value="Submit">
			</form>
		</div>
		<div class="options">
             <a href="./index.php">{$sm_labels.login}</a> | <a href="./passwordReset.php">{$sm_labels.forgot_password}</a>
        </div>
	</body>
</html>
