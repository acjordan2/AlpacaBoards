{include file="header.tpl"}
	<h1>{$board_title}</h1>
	{include file="userbar.tpl"}
{literal}
	<script type="text/javascript">
		//<![CDATA[
		onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
		//]]>
	</script>
	{/literal}
	<div class="infobar">
		{if $current_page > 1} <span><a href="./showtopics.php?board=42&amp;page=1">First Page</a> |</span>{/if}
		{if $current_page > 2}<span><a href="./showtopics.php?board=42&amp;page={$current_page - 1}">Prev Page</a> |</span>{/if}
		Page {$current_page} of <span>{$page_count}</span> 
		{if $current_page < $page_count - 1}<span>| <a href="./showtopics.php?board=42&amp;page={$current_page + 1}">Next Page</a></span> {/if}
		{if $current_page < $page_count}<span>| <a href="./showtopics.php?board=42&amp;page={$page_count}">Last Page</a></span>{/if}
	</div>

	<table class="grid">
		<tr>
			<th>Topic</th>
			<th>Created By</th>
			<th>Msgs</th>
			<th>Last Post</th>
		</tr>
{foreach from=$stickyList key=header item=table}
		<tr>
			<td>
				<a href="./showmessages.php?board={$board_id}&amp;topic={$table.topic_id}">
					<b><div class="sticky">{$table.title}</div></b>
				</a>
			</td>
        		<td>
				<a href="./profile.php?user={$table.user_id}">{$table.username}</a>
			</td>
			<td>
				{$table.number_of_posts}
				{if $table.history > 0} 
				(<a href="./showmessages.php?board={$board_id}&amp;topic={$table.topic_id}{if $table.page > 0}&amp;page={$table.page}{/if}#m{$table.last_message}">+{$table.history}</a>)
				{/if}
			</td>
			<td>
				{$table.posted|date_format:$dateformat}
			</td>
		</tr>
{/foreach}
{foreach from=$topicList key=header item=table}
		<tr>
			<td>
				<a href="./showmessages.php?board={$board_id}&amp;topic={$table.topic_id}">{$table.title}</a>
			</td>
        		<td>
				<a href="./profile.php?user={$table.user_id}">{$table.username}</a>
			</td>
			<td>
				{$table.number_of_posts}
				{if $table.history > 0} 
				(<a href="./showmessages.php?board={$board_id}&amp;topic={$table.topic_id}{if $table.page > 1}&amp;page={$table.page}{/if}#m{$table.last_message}">+{$table.history}</a>)
				{/if}
			</td>
			<td>
				{$table.posted|date_format:$dateformat}
			</td>
		</tr>
{/foreach}
	</table>
	<div class="infobar">
		Page: {assign var="i" value=1}
		{while $i <= $page_count}
	      		{if $i == $current_page}{$i}
				{if $i<$page_count}|{/if}
				{else}
					<a href="./showtopics.php?board=42&amp;page={$i}">{$i}</a> 
				{if $i < $page_count}| {/if}
			{/if}
			{assign var="i" value=$i+1}
		{/while}
	</div>
	<div class="infobar">
		There {if $num_readers < 2}is{else}are{/if} currently {$num_readers} {if $num_readers < 2}person{else}people{/if} reading this board.
	</div>
	<br />
	<br />
	{include file="footer.tpl"}
	</div>
 {literal}
	<script type="text/javascript">
		//<![CDATA[
		function get_cozdiv() {
			cozdiv = document.getElementById('cozpop');
			if (cozdiv) return cozdiv;

			cozdiv = document.createElement('img');
			cozdiv.setAttribute('id','cozpop');
			cozdiv.setAttribute( 'style', 'position:fixed;z-index:99999;top:30%;right:45%;margin:0;padding:0;border:#000 1px solid;background:#fff;width:10%;display:none;');
			cozdiv.setAttribute('src','http://static.endoftheinter.net/images/cosby.jpg');
			cozdiv.addEventListener('click', hide_cozpop, false);
			document.body.appendChild(cozdiv);
			return cozdiv;
		}
		function show_cozpop(e) {
			if ('m'== String.fromCharCode(e.charCode).toLowerCase()) get_cozdiv().style.display = 'inline';
		}

		function hide_cozpop(e) {
			get_cozdiv().style.display = 'none';
		}
		document.addEventListener('keypress', show_cozpop, false);
		//]]>
	</script>
</body>
</html>
{/literal}
