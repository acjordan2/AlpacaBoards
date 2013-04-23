{include file="header.tpl"}
	<h1>Add teh link!</h1>
	<br />
	<form action="./addlink.php{if isset($link_edit)}?edit={$link_id}{/if}" method="POST">
		<input type="hidden" name="token" value="{$token}">
		{if isset($error)}
			<span style="color: #ff0000;">
				<b>Error:</b> {$error}
			</span>
			<br />
			<br />
		{/if}
		<b>Link Title</b><br />
		<input type="text" name="title" value="{if isset($title)}{$title}{/if}" maxlength="80" size="60">
		<br />
		<br />
		<b>Link URL</b><br />
		<input type="text" id="lurl" name="lurl" value="{if isset($lurl)}{$lurl}{/if}" maxlength="200" size="60">
		<input type="checkbox" id="nourl" name="nourl" onchange="document.getElementById('lurl').disabled=!(document.getElementById('lurl').disabled); document.getElementById('lurl').readonly=!(document.getElementById('lurl').readonly)">
		<small>(No URL Required)</small>
		<br />
		<br />
		<b>Link Categories</b><br />
		{$i=1}
		<table>
			<tr>
{foreach from=$categories key=header item=table}
			<td>
				<input type="checkbox" name="{$table.name}" value="1" />{$table.name}</td>
		{if $i % 4 == 0}
			</tr>
			<tr>
		{/if}
{$i= $i + 1}
{/foreach}
			</tr>
		</table>
		<br />
		<br />
		<b>Link Description</b><br />
		Enter a link description. Make it good!<br />
		<textarea cols="100" rows="20" name="description" id="description">
			{if isset($description)}{$description}{/if}
		</textarea>
		<br />
		<br />
		<input type="submit" name="addlink" value="Add Link">
	</form>
</form>
{include file="footer.tpl"}
</div>
</body>
</html>
