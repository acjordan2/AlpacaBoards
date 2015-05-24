{include file="header.tpl"}
	<h1>Tag: {$taginfo.title}</h1>
	<br />
	<b>Added by:</b> <a href="{$base_url}/profile.php?user={$taginfo.user_id}">{$taginfo.username}</a><br />
	<b>Date Created:</b> {$taginfo.created|date_format:$dateformat} <br />
	<b>Parents:</b>
    <span>{if $taginfo.parents|@count > 0}
        {foreach from=$taginfo.parents key=header item=tag}
        <a href="{$base_url}/tags.php?tag=[{$tag.title|replace:" ":"_"}]">{$tag.title}</a>&nbsp;
        {/foreach}
        {else}None{/if}
    </span>
    <br/>
	<b>Children:</b> 
    <span>{if $taginfo.children|@count > 0}
        {foreach from=$taginfo.children key=header item=tag}
        <a href="{$base_url}/tags.php?tag=[{$tag.title|replace:" ":"_"}]">{$tag.title}</a>&nbsp;
        {/foreach}
        {else}None{/if}
    </span>
    <br />
    {if $mod_tag_edit}<b>Options:</b> <a href="{$base_url}/tags.php?tag=[{$taginfo.title|replace:" ":"_"}]&amp;edit">Edit</a>{/if}
	<br />
    <br />
	<b>Description:</b> {$taginfo.description}
	<br />
    <br />
	{include file="footer.tpl"}
</body>
</html>
