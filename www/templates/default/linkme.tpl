{include file="header.tpl"}
	<h1>{$title}</h1>
    {if $tags|@count > 0}
    <h2>
        <div>
            {foreach from=$tags key=header item=tag}
            <a href="./links.php?tags=[{$tag.title|replace:' ':'_'}]">{$tag.title}</a>
            {$p_count = $tag.parents|@count}
            {$i = 0}
            {if $p_count > 0}
            <span style="font-size:12px;">
                ({foreach from=$tag.parents key=header item=parent}<a href="./links.php?tags=[{$parent.title|replace:' ':'_'}]">{$parent.title}</a>{if $i<$p_count-1 AND $p_count>1}, {/if}{$i = $i +1}{/foreach})
            </span>{/if}{/foreach}
        </div>
    </h2>{/if}
	<br />
	{$url}
	<br />
	<br />
	<b>Added by:</b> <a href="./profile.php?user={$link_user_id}">{$link_username}</a><br />
	<b>Date:</b> {$created|date_format:$dateformat}<br />
	<b>Code:</b> <a href="./linkme.php?l=SS{$link_id}">{$short_code}</a><br />
 	<b>Hits:</b> {$hits}<br />
	<b>Rating:</b> <span id="rating">{$rating|string_format:"%.2f"}</span>/10 (based on <span id="NumberOfVotes">{$NumberOfVotes}</span> votes)<br />
	<b>Rank:</b> <span id="rank">{$rank|string_format:"%.0f"}</span><br />
	<b>Share:</b> <a href="./ss.php?l={$short_code}">{$domain}/ss.php?l={$short_code}</a><br /><br />
	<b>Categories:</b> {foreach from=$tags key=header item=tag}{$tag.title}, {/foreach}
	<form action="./linkme.php?l={$link_id}" method="POST" id="link_fav">
		<b>Options:</b>
		{if isset($link_favorite)}<button name="f" id="f" value="0">Remove from Favorites</button>
		{else}<button name="f" id="f" value="1">Add to Favorites</button>{/if}
		<input type="hidden" name="action" value="link_fav" />
		<input type="hidden" name="l" value="{$link_id}" />
		<input type="hidden" name="token" value="{$token}" /> | 
		<a href="./linkreport.php?l={$link_id}">Report Link</a>
        {if $user_id == $link_user_id}| <a href="./addlink.php?edit={$link_id}">Edit link</a>{/if}
	</form>
{if $user_id != $link_user_id}
	<form action="./linkme.php?l={$link_link_id}" method="POST" id="link_vote">
		<b>Vote:</b>{for $i=0; $i<=10; $i++}<button id="v" name="v" value="{$i}">{$i}</button>{/for}
		<input type="hidden" name="action" value="link_vote" />
		<input type="hidden" name="l" value="{$link_id}" />
		<input type="hidden" name="token" value="{$token}" /><br />
	</form>
   	<br /><span id="message"><b>{if isset($message)}{$message}{/if}</b></span><br />
{/if}
	<br />
	<br />
	<b>Description:</b> {$description}
	<br />
	<br />
	<br />
	<br />
	<div class="userbar">
		<a href="./profile.php?user={$user_id}">{$username} ({$karma})</a>:
      		<span id="userbar_pms" style="display:none">
		<a href="./inbox.php">
			Private Messages (<span id="userbar_pms_count">0</span>)
		</a> 
		|</span> 
		<a href="./postmsg.php?link={$link_id}">Post New Message</a> |
		<a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>
	<div class="infobar" id="u0_2">
        {if $current_page > 1} <span><a href="./linkme.php?l={$link_id}&amp;page=1">First Page</a> |</span>{/if}
        {if $current_page > 2}<span><a href="./linkme.php?l={$link_id}&amp;page={$current_page - 1}">Prev Page</a> |</span>{/if}
        Page {$current_page} of <span>{$page_count}</span> 
        {if $current_page < $page_count - 1}<span>| <a href="./linkme.php?l={$link_id}&amp;page={$current_page + 1}">Next Page</a></span> {/if}
        {if $current_page < $page_count}<span>| <a href="./linkme.php?l={$link_id}&amp;page={$page_count}">Last Page</a></span>{/if}
    </div>
{$i = 5}
{foreach from=$messages key=header item=table}
	<div class="message-container" id="m{$table.message_id}">
		<div class="message-top">
		<b>From:</b> <a href="./profile.php?user={$table.user_id}">{$table.username}</a> |
        	<b>Posted:</b> {$table.posted|date_format:$dateformat} | 
		<a href="./message.php?id={$table.message_id}&amp;link={$link_id}&amp;r={$table.revision_id}">
			Message Detail{if $table.revision_id > 1} ({$table.revision_id} edits){elseif $table.revision_id == 1} ({$table.revision_id} edit){/if}
		</a> | 
		<a href="./postmsg.php?link={$link_id}&amp;quote={$table.message_id}" onclick="return quickpost_quote('l,{$link_id},{$table.message_id}@{$table.revision_id}');">Quote</a>
	</div>
	<table class="message-body">
		<tbody>
			<tr>
				<td msgid="l,{$link_id},{$table.message_id}@{$table.revision_id}" class="message">{$table.message|replace:"<!--\$i-->":$i++}</td>
				<td class="userpic">
					<div class="userpic-holder">
                        {if $table.sha1_sum != NULL}<a href="./imagemap.php?hash={$table.sha1_sum}"><img src="./templates/default/images/grey.gif" data-original="{$base_image_url}/t/{$table.sha1_sum}/{$table.filename}" width="{$table.thumb_width}" height="{$table.thumb_height}" /></a>{/if}
                        {if $table.level == 1}<center style="padding: 4px 2px;"><b style="color:{$table.title_color}">{$table.title}</b></center>{/if}
                    </div>
				</td>
			</tr>
        	</tbody>
	</table>
</div>
{$i = $i+1}
{/foreach}

    <div class="infobar" id="u0_3">Page: 
    {assign var="k" value=1}
    {while $k <= $page_count}
        {if $k == $current_page}{$k} 
            {if $k<$page_count}|{/if}
            {else}
                <a href="./linkme.php?l={$link_id}&amp;page={$k}">{$k}</a> 
                {if $k < $page_count}| {/if}
        {/if}
        {assign var="k" value=$k+1}
    {/while}
    </div>
    <br />
    <br />
	{include file="footer.tpl"}
<a id="qptoggle" href="#">
	<span id="open">+</span>
	<span id="close" style="display:none">-</span>
</a>
<div id="pageexpander" style="height:280px;display:none;"></div>
<div id="quickpost" style="display:none;">
	<form method="POST" action="./postmsg.php" name="quickposts" id="quickposts">
			<input type="hidden" name="link" value="{$link_id}" />
			<input type="hidden" name="token" value="{$token}" />
			<b>Your Message:</b><br />
			<textarea id="qpmessage" name="message">

---
				{$p_signature}
			</textarea><br />
			<input type="submit" value="Post Message" name="submit"/>
	</form>
</div> 
</body>
</html>
