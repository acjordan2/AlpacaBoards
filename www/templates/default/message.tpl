{include file="header.tpl"}
	<h1>Message Detail</h1>
{if $type == 1}
	<b>Topic:</b> 
	<a href="./showmessages.php?topic={$parent_id}">{$title}</a>
{else}
	<b>Link:</b> 
	<a href="./linkme.php?l={$parent_id}">{$title}</a>
{/if}
	<div class="message-container" id="m{$message_id}">
		<div class="message-top">
        		<b>From:</b> <a href="./profile.php?user={$m_user_id}">{$m_username}</a> |
        		<b>Posted:</b> {$posted|date_format:$dateformat}
		</div>
		<table class="message-body">
			<tr>
				<td msgid="t,{$parent_id},{$message_id}@{$revision_no}" class="message">{$message}</td>
				<td class="userpic">
					<div class="userpic-holder">
						{if $m_avatar != NULL}<a href="./imagemap.php?hash={$m_avatar.sha1_sum}"><img src="./templates/default/images/grey.gif" data-original="{$base_image_url}/t/{$m_avatar.sha1_sum}/{$m_avatar.filename}.jpg" width="{$m_avatar.thumb_width}" height="{$m_avatar.thumb_height}" /></a>{/if}
					</div>
				</td>
	        	</tr>
		</table>
        <br />
{if $user_id == $m_user_id && $message_deleted == 0}
    <form method="get" action="./postmsg.php" style="display:inline;">
        <input type="hidden" name="id" value="{$message_id}" />
        <input type="hidden" name="{if $type == 2}link{else}topic{/if}" value="{$parent_id}" />
        <input type="submit" value="Edit this message" />
    </form>
    <form method="post" action="./message.php?id={$message_id}&amp;{if $type == 2}link={$parent_id}{else}topic={$parent_id}{/if}&amp;r={$revision_no}" style="display:inline;">
        <input type="hidden" name="token" value="{$token}" />
        <input type="hidden" name="action"value="1" />
        <input type="submit" value="Delete this message" onclick="return confirm(&quot;Are you sure you want to delete this message&quot;)" />
    </form>
    <br />
    <br />
{/if}
	</div>{if $mod_message_delete && $message_deleted == 0}
    <br />
    <br />
    <form method="post" action="./message.php?id={$message_id}&amp;{if $type == 2}link={else}topic={/if}{$parent_id}&amp;r={$revision_no}">
        <input type="hidden" name="token" value="{$token}" /><b>Moderation Options:</b><br />
        <select name="action">
            <option value="1">Delete</option>
            <!--<option value="3">Unpin</option>-->
            <!--<option value="4">Close</option>-->
        </select><br /><br />
        <b>Reason:</b><br />
        <textarea cols="60" rows="4" id="u0_2" name="reason"></textarea>
        <input type="submit" value="Submit" />
    </form>{/if}
	<br />
{if $message_deleted == 0}
	<h3>Revisions</h3>    
{foreach from=$revision_history item='table'}
		#{$table.revision_no + 1}: 
		{if $revision_no == $table.revision_no}<b>
		{else}<a href="./message.php?id={$message_id}&amp;
		{if $type == 2}link={$parent_id}{else}topic={$parent_id}{/if}&amp;r={$table.revision_no}">{/if}
		{$table.posted|date_format:$dateformat}
		{if $revision_no == $table.revision_no}</b>{else}</a>{/if}<br />
{/foreach}
{/if}
	<br />
	<br />
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
