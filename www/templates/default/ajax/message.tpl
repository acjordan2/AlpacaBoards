{foreach from=$message_data key=header item=table}
<div class="message-container" id="m{$table.message_id}">
        <div class="message-top">
            <b>From:</b> <a href="./profile.php?user={$table.user_id}">{$table.username}</a> | 
            <b>Posted:</b> {$table.posted|date_format:$dateformat} | 
            {if isset($filter)}
                <a href="./showmessages.php?topic={$topic_id}">Unfilter</a>
            {else}
                <a href="./showmessages.php?topic={$topic_id}&amp;u={$user_id}">Filter</a>
            {/if}
            | <a href="./message.php?id={$table.message_id}&amp;topic={$topic_id}&amp;r={$table.revision_id}">Message Detail
            {if $table.revision_id > 1} 
                ({$table.revision_id} edits)
            {elseif $table.revision_id == 1} 
                ({$table.revision_id} edit)
            {/if}
            </a> |
            <a href="./postmsg.php?board={$board_id}&amp;topic={$topic_id}&amp;quote={$table.message_id}" 
                onclick="return quickpost_quote('t,{$topic_id},{$table.message_id}@{$table.revision_id}');">Quote</a>
        </div>
        <table class="message-body">
            <tr>
                <td msgid="t,{$topic_id},{$table.message_id}@{$table.revision_id}" class="message">
                    {$table.message}
                </td>
                <td class="userpic">
                    <div class="userpic-holder">
                        {if $table.avatar != NULL}<a href="./imagemap.php?hash={$table.sha1_sum}"><img src="./templates/default/images/grey.gif" data-original="{$base_image_url}/t/{$table.sha1_sum}/{$table.filename}" width="{$table.thumb_width}" height="{$table.thumb_height}" /></a>{/if}
                        {if $table.level == 1}<center style="padding: 4px 2px;"><b style="color:{$table.title_color}">{$table.title}</b></center>{/if}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    {/foreach}