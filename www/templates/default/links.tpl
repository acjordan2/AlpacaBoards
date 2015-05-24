{include file="header.tpl"}
	<h1>Links</h1>

	<div class="userbar">
		<a href="{$base_url}/profile.php?user={$user_id}">{$username} ({$karma})</a>:
		<span id="userbar_pms" style="display:none">
			<a href="{$base_url}/inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a>
		|</span> 
		<a href="{$base_url}/links.php?mode=user&amp;userid=18026&amp;type=3#"onclick="return toggle_spoiler(document.getElementById('links_cat_filt'))">Edit category filters</a> | 
		<a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>


	<div class="infobar">
		Page 1 of <span>1</span> <span style="display:none">| 
		<a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=2">Next Page</a></span>
		<span style="display:none">
			| <a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=1">Last Page</a>
		</span>
	</div>

	<table class="grid">
		<tbody>
			<tr>
				<th>
					<a href="{$base_url}/links.php?mode=user&amp;userid=&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=2&amp;sortd=1">Title</a>
				</th>
				<th>
					<a href="{$base_url}/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">Added By:</a>
				</th>
				<th>
					<a href="{$base_url}/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">Date</a>
				</th>
				<th>
					<a href="{$base_url}/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=4&amp;sortd=2">Rating</a>
				</th>
				<th>
					<a href="{$base_url}/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=6&amp;sortd=2">Rank</a>
				</th>
        		</tr>
{foreach from=$links key=header item=table}
			<tr class="r0">
				<td>
					<div class="fl"><a href="{$base_url}/linkme.php?l={$table.link_id}">{$table.title}</a></div>
                    <div class="fr">
                        {foreach from=$table.tags item=tags}<a href="{$base_url}/links.php?tags=[{$tags.title|replace:' ':'_'}]">{$tags.title}</a> {/foreach}
                    </div>
				</td>	
				<td>
					<a href="{$base_url}/profile.php?user={$table.user_id}">{$table.username}</a>
				</td>
			  	<td>
					{$table.created|date_format:$dateformat}
				</td>
				<td>
					{$table.rating|string_format:"%.2f"}/10 (based on {$table.NumberOfVotes} votes)
				</td>
				<td>
					{$table.rank|string_format:"%.0f"}
				</td>
			</tr>
{/foreach}
		</tbody>
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
