{include file="header.tpl"}
	<h1>Report Link</h1>
{if isset($m)}
	<h2><em>Link Reported</em></h2>
{else}
	<form action="{$base_url}/linkreport.php?l={$link_id}" method="post">
		<span style="color: #ff0000">
			<b>The rules:</b> Don't be an ass hat. I will ban
		</span>
		<br />
		<br />
{if isset($error_msg)}
		<em>Error: {$error_message}</em>
{/if}
		<b>Current Link:</b> 
		<a href="{$base_url}/linkme.php?l={$link_id}" target="_blank">{$link_title}</a><br />
		(Click to open a new window with the current messages)
		<br />
		<br />
	 	<input type="hidden" name="link" value="{$link_id}" />
{if isset($message_id)}
		<input type="hidden" name="id" value="{$message_id}" />
{/if}
		<input type="hidden" name="board" value="{$board_id}" /> 
		<b>Reason</b><br />
		Enter the reason for reporting this link below. Abusers will be banned.<br />
		<textarea cols="100" rows="20" name="reason" id="reason"></textarea>
      		<br />
		<div>
			<input type="hidden" name="token" value="{$token}" /> 
        	<input type="submit" name="submit" value="Report Link" /> 
		</div>
	</form>
	<br />
{/if}
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
