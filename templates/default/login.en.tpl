<!DOCTYPE HTML>
<html>
	<head>
		<title>Sper.gs</title>
	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		<h1>Login</h1>
		<br />
		{$message}<br />
		<br />
		<form action="" method="POST" autocomplete="off">
		  <fieldset style="width:250px;">
			<legend><small>Enter your details:</small></legend>
			<br />
			Username:<br />
			<input type="text" name="username" value="{$username}" style="width:100%;">
			<br /><br />
			Password:<br />
			<input type="password" name="password" style="width:100%;">
			<br /><br />
			<input type="submit" value="Login">
		  </fieldset>
		</form>
		<br />
		<a href="/register.php">Register</a>
	</body>
</html>
