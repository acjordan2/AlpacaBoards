{include file="header.tpl"}
	<h1>Send Invite</h1>
{if $message != NULL}<h2><em>{$message}</em></h2><br />{/if}
{if $invite_status != 0}
	<form action="invite.php" method="post" autocomplete="off">
		<input type="hidden" name="token" value="{$token}" />
		<table class="grid">
			<tr>
				<th colspan="2">Invite User</th>
			</tr>
			<tr>
				<td>E-Mail</td>
          			<td><input type="text" name="email" size="30" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="go" value="Send Invite" /></td>
			</tr>
		</table>
	</form>
{/if}
	<br />
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
