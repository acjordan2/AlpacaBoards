{include file="header.tpl"}
	<h1>{$page_title}</h1>
{if isset($message)}
	<h2><em>{$message}</em></h2>
{else}
	<form action="" method="post">
{if isset($error_msg)}
		<em>Error: {$error_msg}</em><br />
{/if}
		<b>Action Taken:</b> 
		{$action_taken}
		<br />
		<br />
	 	<input type="hidden" name="user" value="{$p_user_id}" />
		<b>Reason</b><br />
		Enter the reason for banning {$p_username}. Be as descriptive as possible 
		and link to infringing behavior if applicable.<br />
		<textarea cols="100" rows="20" name="description" id="description"></textarea>
      		<br />
		<div>
			<input type="hidden" name="token" value="{$token}" /> 
        	<input type="submit" name="submit" value="Ban {$p_username}" /> 
		</div>
	</form>
	<br />
{/if}
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
