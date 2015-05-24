{include file="header.tpl"}
	<h1>User Information Page</h1>
	<div class="userbar">
		<a href="{$base_url}/profile.php?user={$user_id}">{$username} ({$karma})</a>: 
		<span id="userbar_pms" style="display:none">
			<a href="{$base_url}/inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
		</span>
		<a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>
	<h2><em>{$message}</em></h2>
	<h3>Reason</h3>
	{$reason}
	<br />
	<br />
{include file="footer.tpl"}
</div>
</body>
</html>
