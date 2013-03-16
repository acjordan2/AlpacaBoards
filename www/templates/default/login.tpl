<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">	
	<head>
		<title>Логин</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		{if $message != NULL}{$message}{/if}<br />
		<br />
		<form action="" method="POST" autocomplete="off">
		  <fieldset style="width:250px;">
			<br />
			Логин:<br />
			<input type="text" name="username" value="{$username}" style="width:100%;">
			<br /><br />
			Пароль:<br />
			<input type="password" name="password" style="width:100%;">
			<br /><br />
			<input type="submit" value="Логин">
		  </fieldset>
		</form>
		<br />
		<a href="/register.php">Создать Логин</a> | <a href="/passwordReset.php">Забыли пароль</a>
	</body>
</html>
