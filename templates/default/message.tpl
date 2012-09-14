<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$sitename} - Message Detail</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type="image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href="//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href="/templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="/templates/default/js/base.js?27">
</script>
</head>

<body class="regular">
  <div class="body">
	{include file="navigation.tpl"}
    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Message Detail</h1>{if $link != TRUE}<b>Board:</b> {$board_title}<br />
    <b>Topic:</b> 
    <a href="/showmessages.php?board={$board_id}&amp;topic={$topic_id}">{$topic_title}</a>
	{else}<b>Link:</b> 
    <a href="/linkme.php?l={$topic_id}">{$link_title}</a>
    {/if}
    <div class="message-container" id="m{$message_id}">
      <div class="message-top">
        <b>From:</b> <a href="/profile.php?user={$m_user_id}">{$m_username}</a> |
        <b>Posted:</b> {$posted|date_format:$dateformat}
      </div>

      <table class="message-body">
        <tr>
          <td msgid="t,{$topic_id},{$message_id}@{$revision_no}" class="message">{$message}</td>
          <td class="userpic">
            <div class="userpic-holder">
              <a href=
              "/templates/default/images/LUEshi.jpg">
              <span class="img-placeholder" style="width:148px;height:150px" id=
              "u0_1"></span><script type="text/javascript">{literal}
//<![CDATA[
              onDOMContentLoaded(function(){new ImageLoader($("u0_1"), "/templates/default/images/LUEshi.jpg", 148, 150)})
              //]]>
              {/literal}</script></a>
            </div>
          </td>
        </tr>
      </table>
    </div><br />
    {if $user_id == $m_user_id}
    <form method="get" action="/postmsg.php" style="display:inline;">
      <input type="hidden" name="id" value="{$message_id}" /><input type="hidden" name=
      "{if $link==TRUE}link{else}topic{/if}" value="{$topic_id}" />{if $link!=TRUE}<input type="hidden" name="board" value=
      "{$board_id}" />{/if}<input type="submit" value="Edit this message" />
    </form>

    <form method="post" action="/message.php?id={$message_id}&amp;{if $link == TRUE}link={$topic_id}{else}topic={$topic_id}{/if}&amp;r={$revision_no}"
    style="display:inline;">
      <input type="hidden" name="h" value="6912d" /><input type="hidden" name="action"
      value="1" /><!--<input type="submit" value="Delete this message" onclick=
      "return confirm(&quot;Are you sure you want to delete this message&quot;)" />-->
    </form><br />
    <br />
	{/if}
    <h3>Revisions</h3>
    
	{foreach from=$revision_history item='table'}
		#{$table.revision_no + 1} {if $revision_no == $table.revision_no}<b>{else}<a href="/message.php?id={$message_id}&amp;{if $link==TRUE}link={$topic_id}&amp;link=1{else}topic={$topic_id}{/if}&amp;r={$table.revision_no}">{/if}: 
		{$table.posted|date_format:$dateformat}{if $revision_no == $table.revision_no}</b>{else}</a>{/if}<br />
	{/foreach}
    <br />
    <br />
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
