{include file="header.tpl"}
	<h1>Edit Profile</h1>
{foreach $message as $msg}
	<h2><em>{$msg}</em></h2>
{/foreach}<br />
	<form action="./editpass.php" method="post" autocomplete="off">
		<table class="grid">
		<tr>
			<th colspan="2">{$sm_labels.change_password} {$username}</th>
		</tr>
		<tr>
			<td>{$sm_labels.old_password}</td>
			<td><input type="password" name="old" size="30" /></td>
		</tr>
		<tr>
			<td>{$sm_labels.new_password}</td>
			<td><input type="password" name="new" size="30" /></td>
		</tr>
		<tr>
			<td>{$sm_labels.confirm_password}/td>
			<td><input type="password" name="new2" size="30" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="go" value="{$sm_labels.make_changes}" /></td>
        	</tr>
		</table>
	</form>
	<br />
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
