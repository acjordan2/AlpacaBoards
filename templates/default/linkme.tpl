<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
  <title>{$sitename} - {$link_data.title}</title>
  <link rel="icon" href="https://static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "https://static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
    <script type="text/javascript" src="templates/default/js/base.js?27"></script>
</script>
</head>

<body class="regular">
  <div class="body">
	{include file="navigation.tpl"}

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>
    <h1>{$link_data.title}</h1><br />
    <br />
    {$link_data.url}<br />
    <br />
    <b>Added by:</b> <a href=
    "/profile.php?user={$link_data.user_id}">{$link_data.username}</a><br />
    <b>Date:</b> {$link_data.created|date_format:$dateformat}<br />
    <b>Code:</b> <a href="/linkme.php?l=SS{$link_data.link_id}">SS{$link_data.code}</a><br />
    <b>Hits:</b> {$link_data.hits}<br />
    <b>Rating:</b> {$link_data.rating|string_format:"%.2f"}/10 (based on {$link_data.NumberOfVotes} votes)<br />
    <b>Rank:</b> {$link_data.rank|string_format:"%.0f"}<br />
    <b>Share:</b> <a href="/ss.php?l={$link_data.code}">{$domain}/ss.php?l=SS{$link_data.code}</a><br /><br />
    <b>Categories:</b> {$link_data.categories}<br />
    {if $user_id == $link_data.user_id}
    <!--
    <b>Options:</b> <a href=
    "https://links.endoftheinter.net/linkme.php?h=53178&amp;f=1&amp;l=332364">Add to
    favorites</a> | <a href="/add.php?edit=$link_data.link_id">Edit
    link</a> | <a href="/linkreport.php?l={$link_data.link_id}">Report
    Link</a>--><br />
    {else}
    <b>Vote:</b> {for $i=0; $i<11; $i++}<a href="/linkme.php?l={$link_data.link_id}&v={$i}&token={$token}">{$i}</a> {/for}<br />
   	<br /><b>{$message}</b><br />
    {/if}
    <br />
    <b>Description:</b> {$link_data.description}
	<br />
	<br />
	<br />
	<br />
    <div class="userbar">
      <a href="/profile.php?user={$user_id}">{$username} ({$karma})</a>:
      <span id="userbar_pms" style="display:none"><a href=
      "https://links.endoftheinter.net/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href=
      "/postmsg.php?link={$link_id}">Post New Message</a> |
      <a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div>

    <div class="infobar">
      Page 1 of <span>1</span> <span style="display:none">| <a href=
      "/linkme.php?l={$link_id}&amp;page=2">Next
      Page</a></span> <span style="display:none">| <a href=
      "/linkme.php?l={$link_id}&amp;page=1">Last
      Page</a></span>
    </div>
{$i = 5}
{foreach from=$messages key=header item=table}
    <div class="message-container" id="m{$table.message_id}">
      <div class="message-top">
        <b>From:</b> <a href=
        "/profile.php?user={$table.user_id}">{$table.username}</a> |
        <b>Posted:</b> {$table.posted|date_format:$dateformat} | <a href=
        "/message.php?id={$table.message_id}&amp;topic={$link_id}&amp;r={$table.revision_no}&amp;link=1">
        Message Detail{if $table.revision_no > 1} ({$table.revision_id} edits){elseif $table.revision_no == 1} ({$table.revision_no} edit){/if}</a> | <a href=
        "/postmsg.php?link={$link_id}&amp;quote={$table.message_id}"
        onclick="return QuickPost.publish('quote', this);">Quote</a>
      </div>

      <table class="message-body">
        <tbody>
          <tr>
            <td msgid="l,{$link_id},{$table.message_id}@{$table.revision_no}" class="message">{$table.message|replace:"<!--\$i-->":$i++}</td>

            <td class="userpic">
              <div class="userpic-holder">
                <a href=
                "/templates/default/images/LUEshi.jpg">
                <span class="img-loaded" style="width:150px;height:131px" id=
                "u0_{$i}"><img src=
                "/templates/default/images/LUEshi.jpg"
                width="150" height="131" /></span></a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
{$i = $i+1}
{/foreach}

    <div class="infobar">
      Page: 1
    </div>
    <br />
    <br />
	{include file="footer.tpl"}

        <form method="post" action="/postmsg.php" class="quickpost" id="u0_{$i+1}" name="u0_{$i+1}">
      <input type="hidden" name="topic" value="{$topic_id}" /><input type="hidden" name="h"
      value="76f03" /><a href="#" class="quickpost-nub" id="u0_{$i+2}" name=
      "u0_{$i+2}"><span class="open">+</span><span class="close">-</span></a>

      <div class="quickpost-canvas">
        <div id="u0_{$i+6}"></div>

        <div class="quickpost-body">
          <b>Your Message</b><br />
          <textarea id="u0_{$i+7}" name="message">

{$p_signature}
</textarea>
<script type="text/javascript">
{literal}
//<![CDATA[
          $("u0_{/literal}{$i+7}{literal}").value = "\n---\n{/literal}"+unescape("{$signature}");
          //]]>
          </script><br />
          <!--<input type="submit" value="Preview Message" id="u0_{$i+3}" name="preview" />-->
          <input type="submit" value="Post Message" id="u0_{$i+4}" name="submit" />
          <!--<input type="button" value="Upload Image" id="u0_{$i+5}" />-->
        </div>
      </div><a href="#" class="quickpost-grip" id="u0_{$i+4}" name=
      "u0_{$i+4}">&nbsp;</a><script type="text/javascript">
//<![CDATA[
      {literal}onDOMContentLoaded(function(){new QuickPost(7758474, $("u0_{/literal}{$i+1}"), unescape("{$signature}"){literal}, $("u0_{/literal}{$i+2}{literal}"), $("u0_{/literal}{$i+3}{literal}"), $("u0_{/literal}{$i+4}{literal}"), $("u0_{/literal}{$i+5}{literal}"), $("u0_{/literal}{$i+6}{literal}"), $("u0_{/literal}{$i+7}{literal}"))})
      //]]>
      {/literal}
      </script>
    </form>
  </div>

  <div style="display: none;" id="hiddenlpsubmitdiv"></div>
</body>
</html>
