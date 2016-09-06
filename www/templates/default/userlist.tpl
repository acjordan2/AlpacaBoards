{include file="header.tpl"}
	<h1>User List</h1>
	<form method="GET" action="">
		<input type="text" name="user" value="{$user_search}" />
		<input type="submit" value="Search" />
	</form>
	<br />
	<div class="userbar">
		<a href="{$base_url}/profile.php?user={$user_id}">{$username} ({$karma})</a>: 
		<span id="userbar_pms" style="display:none">
			<a href="{$base_url}/inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
		</span>
		<a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>
{literal}
	<!--<script type="text/javascript">
	//<![CDATA[
		onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
	//]]>
	</script>-->
{/literal}
	<div class="infobar">
		{if $current_page > 1} <span><a href="{$base_url}/userlist.php?page=1">First Page</a> |</span>{/if}
		{if $current_page > 2}<span><a href="{$base_url}/userlist.php?page={$current_page - 1}">Prev Page</a> |</span>{/if}
		Page {$current_page} of <span>{$page_count}</span> 
		{if $current_page < $page_count - 1}<span>| <a href="{$base_url}/userlist.php?page={$current_page + 1}">Next Page</a></span> {/if}
		{if $current_page < $page_count}<span>| <a href="{$base_url}/userlist.php?page={$page_count}">Last Page</a></span>{/if}
	</div>
	<table class="grid">
	<tr>
		<th>Username</th>
		<th>Date Joined</th>
		<th>Last Action</th>
		<th>Karma</th>
	</tr>
{foreach from=$userlist key=header item=table}
	<tr>
		<td><a href="{$base_url}/profile.php?user={$table.user_id}">{$table.username}</a></td>
		<td>{$table.account_created|date_format:$dateformat}</td>
        	<td>{$table.last_active|date_format:$dateformat}</td>
        	<td>{$table.karma}</td>
	</tr>
{/foreach}
	</table>
	<div class="infobar">Page: {assign var="i" value=1}{while $i <= $page_count}
		{if $i == $current_page}{$i} {if $i<$page_count}|{/if}{else}<a href="{$base_url}/userlist.php?page={$i}">{$i}</a> {if $i < $page_count}| {/if}{/if}{assign var="i" value=$i+1}{/while}
	</div>
	<br />
	<br />
	{include file="footer.tpl"}
	</div>
</body>
</html>

