{include file="header.tpl"}
	<h1>Add teh link!</h1>
	<br />
	<form action="{$base_url}/addlink.php{if isset($link_edit)}?edit={$link_id}{/if}" method="POST" id="add">
		<input type="hidden" name="token" value="{$token}">
		{if isset($error)}
			<span style="color: #ff0000;">
				<b>Error:</b> {$error}
			</span>
			<br />
			<br />
		{/if}
        {if isset($post_again)}<em>Error: You must wait {$post_again} seconds before posting again</em><br />{/if}
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
                <td>
                <input type='text' id="tags" name="tags" style="width: 500px;" value="{if isset($tags)}{$tags}{/if}"/>
                </td>
			</tr>
		</table>
		<br />
		<br />
		<b>Link Description</b><br />
		Enter a link description. Make it good!<br />
		<textarea cols="100" rows="20" name="description" id="description">{if isset($description)}{$description}{/if}</textarea>
		<br />
		<br />
		<input type="submit" name="addlink" value="{if isset($link_edit)}Save{else}Add Link{/if}" id="save">
	</form>
</form>
{include file="footer.tpl"}
</div>
</body>
</html>
