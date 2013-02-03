<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <title>{$sitename} - {$topic_title}</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type="image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href="//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href="templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="templates/default/js/jquery.min.js" charset="utf-8"></script>
</head>

<body class="regular">
  <div class="body">
    {include file="navigation.tpl"}

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>{$board_title}</h1>

    <h2>{$topic_title}</h2>
	{if isset($status_message) && $status_message != NULL}<br /><h3 style="text-align:center"><em>{$status_message}</em></h3><br />{/if}
    <div class="userbar">
      <a href="/profile.php?user={$user_id}">{$username} ({$karma})</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href="boardlist.php">Board List</a> |
      <a href="/showtopics.php?board=42">Topic List</a> | <a href=
      "/postmsg.php?board={$board_id}&amp;topic={$topic_id}">Post New Message</a><!-- | <a href=
      "//boards.endoftheinter.net/showmessages.php?board=42&amp;topic=7758474&amp;h=76f03"
      onclick="return !tagTopic(this, 7758474, true)">Tag</a> | <a href=
      "//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>-->
      {if $action != NULL} | <a href="/showmessages.php?board={$board_id}&topic={$topic_id}&sticky=1&token={$token}" onclick="confirm('Are you sure you want to pin this topic?');">{$action[0].name}</a>{/if}
    </div><script type="text/javascript">
    {literal}
//<![CDATA[
    //onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>
    {/literal}
    </script>

    <div class="infobar" id="u0_2">
      {if $current_page > 1} <span><a href="/showmessages.php?board=42&amp;topic={$topic_id}&amp;page=1">First Page</a> |</span>{/if}
	  {if $current_page > 2}<span><a href="/showmessages.php?board=42&amp;topic={$topic_id}&amp;page={$current_page - 1}">Prev Page</a> |</span>{/if}
      Page {$current_page} of <span>{$page_count}</span> 
      {if $current_page < $page_count - 1}<span>| <a href="/showmessages.php?board=42&amp;topic={$topic_id}&amp;page={$current_page + 1}">Next Page</a></span> {/if}
      {if $current_page < $page_count}<span>| <a href="/showmessages.php?board=42&amp;topic={$topic_id}&amp;page={$page_count}">Last Page</a></span>{/if}
    </div>

    <div id="u0_1">
		{$i=5}
{foreach from=$messages key=header item=table}
      <div class="message-container" id="m{$table.message_id}">
        <div class="message-top">
          <b>From:</b> <a href="/profile.php?user={$table.user_id}">
          {$table.username}</a> | <b>Posted:</b> {$table.posted|date_format:$dateformat} | {if isset($filter)}<a href=
          "/showmessages.php?board={$board_id}&amp;topic={$topic_id}">
          Unfilter{else}<a href=
          "/showmessages.php?board={$board_id}&amp;topic={$topic_id}&amp;u={$table.user_id}">
          Filter</a>{/if} | <a href=
          "/message.php?id={$table.message_id}&amp;topic={$topic_id}&amp;r={$table.revision_id}">Message Detail{if $table.revision_id > 1} ({$table.revision_id} edits){elseif $table.revision_id == 1} ({$table.revision_id} edit){/if}</a> |
          <a href="/postmsg.php?board={$board_id}&amp;topic={$topic_id}&amp;quote={$table.message_id}" onclick=
          "return quickpost_quote('t,{$topic_id},{$table.message_id}@{$table.revision_id}');">Quote</a>
        </div>

        <table class="message-body">
          <tr>
            <td msgid="t,{$topic_id},{$table.message_id}@{$table.revision_id}" class="message">{$table.message|replace:"<!--\$i-->":$i++}</td>

            <td class="userpic">
              <div class="userpic-holder">
               
               <!-- <span class="img-placeholder" style="width:150px;height:131px" id=
                "u0_{$i}"></span> -->
                <img src="/templates/default/images/grey.gif" data-original="https://i.sper.gs/i/t/7805f50352da7b2b878b645408ed669f/lueshi.jpg" width="150" height="156" />
                <script type="text/javascript">
					{literal}
				/*
				//<![CDATA[
                onDOMContentLoaded(function(){new ImageLoader($("u0_{/literal}{$i}{literal}"), "/templates/default/images/LUEshi.jpg", 150, 131)})
                //]]>*/
                {/literal}
                </script></a>{if $table.user_id == 1016}<br /><em><b>Glorious Super Admin Master Race</b></em>{/if}
              </div>
            </td>
          </tr>
        </table>
      </div>
 {$i = $i+1}
{/foreach}
    <div class="infobar" id="u0_3">Page: {assign var="k" value=1}{while $k <= $page_count}
      {if $k == $current_page}{$k} {if $k<$page_count}|{/if}{else}<a href="/showmessages.php?board=42&amp;topic={$topic_id}&amp;page={$k}">{$k}</a> {if $k < $page_count}| {/if}{/if}{assign var="k" value=$k+1}{/while}
    </div>

    <div class="infobar" id="u0_4">
      <!--There are currently 2 people reading this topic.-->
      There {if $num_readers < 2}is{else}are{/if} currently {$num_readers} {if $num_readers < 2}person{else}people{/if} reading this topic
    </div><!--<script type="text/javascript">
    {literal}
//<![CDATA[
    //onDOMContentLoaded(function(){new TopicManager(7758474, 1, 471, $("u0_1"), [new uiPagerBrowser($("u0_2"), "\/\/boards.endoftheinter.net\/showmessages.php?board=42&topic=7758474", 471, 1), new uiPagerEnum($("u0_3"), "\/\/boards.endoftheinter.net\/showmessages.php?board=42&topic=7758474", 471, 1)], $("u0_4"), ["144115188083614346",471], 0)})
    //]]>

    {/literal}
    </script>--><br />
    <br />
	{include file="footer.tpl"}
<a id="qptoggle" href="#">
	<span id="open">+</span>
	<span id="close" style="display:none">-</span>
</a>
<div id="pageexpander" style="height:280px;display:none;"></div>
<div id="quickpost" style="display:none;">
	<form method="POST" action="/postmsg.php" name="quickposts" id="quickposts">
			<input type="hidden" name="topic" value="{$topic_id}" />
			<input type="hidden" name="h" value="76f03" />
			<b>Your Message:</b><br />
			<textarea id="qpmessage" name="message">

---
				{$p_signature}
			</textarea><br />
			<input type="submit" value="Post Message" name="submit"/>
	</form>
</div> 
  <script type="text/javascript" src="templates/default/js/jquery.lazyload.min.js" charset="utf-8"></script>
  <script type="text/javascript" src="templates/default/js/jquery.base.js" charset="utf-8"></script>
</body>
</html>
