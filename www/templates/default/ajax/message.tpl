<div class="message-container" id="m{$message_id}">
        <div class="message-top">
            <b>From:</b> <a href="./profile.php?user={$user_id}">{$username}</a> | 
            <b>Posted:</b> {$posted|date_format:$dateformat} | 
            {if isset($filter)}
                <a href="./showmessages.php?board={$board_id}&amp;topic={$topic_id}">Unfilter</a>
            {else}
                <a href="./showmessages.php?board={$board_id}&amp;topic={$topic_id}&amp;u={$user_id}">Filter</a>
            {/if}
            | <a href="./message.php?id={$message_id}&amp;topic={$topic_id}&amp;r={$revision_id}">Message Detail
            {if $revision_id > 1} 
                ({$revision_id} edits)
            {elseif $revision_id == 1} 
                ({$revision_id} edit)
            {/if}
            </a> |
            <a href="./postmsg.php?board={$board_id}&amp;topic={$topic_id}&amp;quote={$message_id}" 
                onclick="return quickpost_quote('t,{$topic_id},{$message_id}@{$revision_id}');">Quote</a>
        </div>
        <table class="message-body">
            <tr>
                <td msgid="t,{$topic_id},{$message_id}@{$revision_id}" class="message">
                    {$message}
                </td>
                <td class="userpic">
                    <div class="userpic-holder">
                        {if $avatar != NULL}<img src="./templates/default/images/grey.gif" data-original="{$base_image_url}/t/{$avatar}" width="{$avatar_width}" height="{$avatar_height}" />{/if}
                    </div>
                </td>
            </tr>
        </table>
    </div>