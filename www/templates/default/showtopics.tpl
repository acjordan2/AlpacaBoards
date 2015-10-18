{include file="header.tpl"}
    <h1>{$board_title}</h1>
    {include file="userbar.tpl"}
{literal}
    <script type="text/javascript">
        //<![CDATA[
        //onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
        //]]>
    </script>
    {/literal}
    <div class="infobar">
        {if $current_page > 1} <span><a href="{$base_url}/showtopics.php?{if isset($tag_url)}tags={$tag_url}&amp;{/if}page=1">First Page</a> |</span>{/if}
        {if $current_page > 2}<span><a href="{$base_url}/showtopics.php?{if isset($tag_url)}tags={$tag_url}&amp;{/if}page={$current_page - 1}">Prev Page</a> |</span>{/if}
        Page {$current_page} of <span>{$page_count}</span> 
        {if $current_page < $page_count - 1}<span>| <a href="{$base_url}/showtopics.php?{if isset($tag_url)}tags={$tag_url}&amp;{/if}page={$current_page + 1}">Next Page</a></span> {/if}
        {if $current_page < $page_count}<span>| <a href="{$base_url}/showtopics.php?{if isset($tag_url)}tags={$tag_url}&amp;{/if}page={$page_count}">Last Page</a></span>{/if}
    </div>

    <table class="grid">
        <tr>
            <th>Topic</th>
            <th>Created By</th>
            <th>Msgs</th>
            <th>Last Post</th>
        </tr>
{if isset($stickyList)}
{foreach from=$stickyList key=header item=table}
        <tr>
            <td>
                <a href="{$base_url}/showmessages.php?topic={$table.topic_id}">
                    <b><div class="sticky">{$table.title}</div></b>
                </a>
            </td>
                <td>
                <a {if ($table.user_id > 0)}href="{$base_url}/profile.php?user={$table.user_id}"{/if}>{$table.username}</a>
            </td>
            <td>
                {$table.number_of_posts}
                {if $table.history > 0} 
                (<a href="{$base_url}/showmessages.php?topic={$table.topic_id}{if $table.page > 0}&amp;page={$table.page}{/if}#m{$table.last_message}">+{$table.history}</a>)
                {/if}
            </td>
            <td>
                {$table.posted|date_format:$dateformat}
            </td>
        </tr>
{/foreach}
{/if}
{foreach from=$topicList key=header item=table}
        <tr>
            <td>
            <div class="fl">
                <a href="{$base_url}/showmessages.php?topic={$table.topic_id}">{$table.title}</a>
            </div>
            <div class="fr">
                        {foreach from=$table.tags item=tags}<a href="{$base_url}/showtopics.php?tags=[{$tags.title|replace:' ':'_'}]">{$tags.title}</a> {/foreach}
                    </div>
            </td>
                <td>
                <a{if ($table.user_id) > 0} href="{$base_url}/profile.php?user={$table.user_id}"{/if}>{$table.username}</a>
            </td>
            <td>
                {$table.number_of_posts}
                {if $table.history > 0} 
                (<a href="{$base_url}/showmessages.php?topic={$table.topic_id}{if $table.page > 1}&amp;page={$table.page}{/if}#m{$table.last_message}">+{$table.history}</a>)
                {/if}
            </td>
            <td>
                {$table.posted|date_format:$dateformat}
            </td>
        </tr>
{/foreach}
    </table>
    <div class="infobar">
        Page: {assign var="i" value=1}
        {while $i <= $page_count}
                  {if $i == $current_page}{$i}
                {if $i<$page_count}|{/if}
                {else}
                    <a href="{$base_url}/showtopics.php?{if isset($tag_url)}tags={$tag_url}&amp;{/if}page={$i}">{$i}</a> 
                {if $i < $page_count}| {/if}
            {/if}
            {assign var="i" value=$i+1}
        {/while}
    </div>
    <div class="infobar">
        There {if $num_readers < 2}is{else}are{/if} currently {$num_readers} {if $num_readers < 2}person{else}people{/if} reading this board.
    </div>
    <br />
    <br />
    {include file="footer.tpl"}
    </div>
</body>
</html>
