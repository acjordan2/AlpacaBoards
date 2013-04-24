{include file="header.tpl"}
	<h1>Stats for {$p_username}</h1>
	<div class="userbar">
		<a href="./profile.php?user={$user_id}">{$username} ({$karma})</a>: 
		<a href="http://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>
	<table class="grid">
		<tr>
			<th colspan="2">Links</th>
		</tr>
		<tr>
			<td>Links added</td>
		        <td>{$num_links}</td>
		</tr>
		<tr>
			<td>Votes accumulated</td>
			<td>{$num_votes}</td>
		</tr>
		<tr>
			<td>Average link rating</td>
			<td>{$vote_avg|string_format:"%.2f"}</td>
		</tr>
		<tr>
			<th colspan="2">Forums</th>
		</tr>
		<tr>
        		<td>Messages posted</td>
        		<td>{$messages_posted}</td>
		</tr>
		<tr>
			<td>Topics created</td>
        		<td>{$topics_created}</td>
		</tr>
		<tr>
			<td>Posts in best topic</td>
			<td>{$posts_best}</td>
		</tr>
		<tr>
			<td>No-reply topics created</td>
			<td>{$no_reply}</td>
		</tr>
	</table>
	<br />
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
