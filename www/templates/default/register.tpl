<!DOCTYPE HTML>
<html>
	<head>
		<title>{$sitename} - Register</title>
	</head>
	<body>
		<h1>Register</h1>
		<br />
		{if isset($message)}{$message}{/if}
		<br />
		<br />
		<form action="" method="POST" autocomplete="OFF">
		  <fieldset style="width:250px;">
			<legend><small>Enter your details:</small></legend>
			<br />
			<input type="hidden" name="token" value="{$token}" />
			{if isset($invite)}<input type="hidden" name="invite_code" value="{$invite_code}" />{/if}
			Desired Username:<br />
			<input type="text" name="username" style="width:100%;" />
			<br /><br />
			Email:<br />
			<input type="text" name="email" style="width:100%;" />
			<br /><br />
			Password:<br />
			<input type="password" name="password" style="width:100%;" />
			<br /><br />
			Password (Again):<br />
			<input type="password" name="password2" style="width:100%;" />
			<br /><br />
			{if !isset($invite)}Invite Code:<br />
			<input type="text" name="invite_code" style="width:100%;" />
			<br /><br />{/if}
			<input type="submit" value="Register">
		  </fieldset>
		</form>
		<br />
	</body>
</html>
