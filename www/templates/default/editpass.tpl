{include file="header.tpl"}
	<h1>Edit Profile</h1>
{foreach $message as $msg}
	<h2><em>{$msg}</em></h2>
{/foreach}<br />
	<form action="./editpass.php" method="post" autocomplete="off">
		<table class="grid">
		<tr>
			<th colspan="2">Change Password for {$username}</th>
		</tr>
		<tr>
			<td>Confirm Your Current Password</td>
			<td><input type="password" name="old" size="30" /></td>
		</tr>
		<tr>
			<td>Enter A New Password</td>
			<td><input type="password" name="new" size="30" /></td>
		</tr>
		<tr>
			<td>Confirm Your New Password</td>
			<td><input type="password" name="new2" size="30" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="go" value="Make Changes" /></td>
        	</tr>
		</table>
	</form>
	<br />
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
