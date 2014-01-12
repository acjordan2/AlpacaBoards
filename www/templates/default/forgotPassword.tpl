<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">	
	<head>
		<title>Reset Password</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		<form action="" method="POST" autocomplete="off">
		{if isset($message)}{$message}{/if}<br />
		<br /> 
		 <fieldset style="width:250px;">
			<br />
			{if isset($change)}
			Password:	
			<input type="password" name="new" value="" style="width:100%" />
			Confirm Password:
			<input type="password" name="new2" value="" style="width:100%" />
			{else}
			Username:<br />
			<input type="text" name="username" value="" style="width:100%;">
			{/if}
			<br /><br />
			<input type="submit" value="Submit">
		  </fieldset>
		</form>
		<br />
		<a href="./register.php">Sign up</a>
	</body>
</html>
