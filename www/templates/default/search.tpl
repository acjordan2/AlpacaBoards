{include file="header.tpl"}
	<h1>Search Links</h1>
	<div class="userbar">
		<a href="./profile.php?user={$user_id}">{$username} ({$karma})</a>:
			<span id="userbar_pms" style="display:none">
				<a href="https://links.endoftheinter.net/inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
			</span>
		<a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>


	<div class="infobar">
	Page 1 of <span>1</span> 
	<span style="display:none">| 
		<a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=2">Next Page</a>
	</span>
	<span style="display:none">| 
		<a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=1">
		Last Page</a>
	</span>
	</div>
	<br />
	<form action="./links.php" method="get">
		<input id="mode" type="hidden" value="search" name="mode"/>
		<input id="q" style="font-size:24px" type="text" name="q" />
		<input style="font-size:24px" type="submit" value="Submit" />
	</form>
	Tips
	<ul>
		<li>This will search both the <b>title</b> and <b>description</b> of a link, no posts will be searched</li>
		<li>For now, only links with <b>all</b> your search terms will be shown</li>
	</ul>
	More search options to come later. 
	<br />
	<br />
	{include file="footer.tpl"}
 	</div>
</body>
</html>
