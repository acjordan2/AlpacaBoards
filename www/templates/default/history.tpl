{include file="header.tpl"}
	<h1>Message History</h1>
	<div class="userbar">
		<a href="{$base_url}/profile.php?user={$user_id}">{$username} ({$karma})</a>: 
		<span id="userbar_pms" style="display:none">
			<a href="{$base_url}/inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
		</span> 
		<a href="{$base_url}/history.php?b">Sort By Topic's Last Post</a> | 
		<a href="{$base_url}/history.php?archived">Archived Topics</a> | 
		<a href="#" onclick="$('search_bar').style.display = ($('search_bar').style.display == 'none') ? 'block' : 'none'; return false;"> Search</a> | 
		<a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>
{literal}
	<script type="text/javascript">
		//<![CDATA[
		    onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
		//]]>
	</script>
{/literal}
	<div class="userbar" id="search_bar" style="display: none;">
		Search:
		<form style="display: inline;" action="history.php" method="get">
			<input type="hidden" name="userid" value="18026" /> 
			<input type="text" name="q" value="" size="25" /> &nbsp; 
			<input type="submit" value="Submit" />
		</form>
	</div>
	<div class="infobar">
		Page 1 of <span>1</span> 
		<span style="display:none">| 
			<a href="{$base_url}/history.php?page=2">Next Page</a>
		</span> 
		<span style="display:none">| 
		<a href="{$base_url}/history.php?page=1">Last Page</a></span>
	</div>
	<table class="grid">
	<tr>
		<th>Board</th>
		<th>Topic</th>
		<th>Msgs</th>
		<th>Your Last Post</th>
		<th>Last Post</th>
	</tr>
{foreach from=$topicList key=header item=table}
	<tr class="r0">
        	<td>
			{$table.board_title}
        	</td>
        	<td>
			<a href="{$base_url}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}">{$table.title}</a>
		</td>
        	<td>
			{$table.number_of_posts}
			<!--
			{if $table.history > 0} 
				(<a href="{$base_url}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}#m{$table.last_message}">+{$table.history}</a>)
			{/if}
			-->
		</td>
		
		<td>{$table.u_last_posted|date_format:$dateformat}</td>
		<td>{$table.last_post|date_format:$dateformat}</td>
	</tr>
{/foreach}
	</table>
	<div class="infobar">
		Page: 1
	</div>
	<br />
	<br />
	{include file="footer.tpl"}
  </div>
</body>
</html>
