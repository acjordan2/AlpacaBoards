<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$sitename} - Post Message</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="templates/default/js/base.js?27">
</script>
</head>

<body class="regular">
  <div class="body">
{include file="navigation.tpl"}

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Post Message</h1>
{if $preview_message == TRUE}
	<form action="postmsg.php" method="post">
    <input type="hidden" name="message" value="
---
Save The Internet - http://act2.freepress.net/letter/two_million/
&lt;b&gt;FlashGot For Chrome - LL50a03&lt;/b&gt;" />
    <input type="hidden" name="h" value="37944" />
    <input type="hidden" name="topic" value="{$topic_id}" />
    {if $message_id != NULL}<input type="hidden" name="id" value="{$message_id}" />{/if}
    <div class="message">
		{$p_message}
	</div>
    <input type="submit" name="submit" value="Post This Message" />
  </form>
{/if}
    <form action="postmsg.php" method="post">
      <span style="color: #ff0000">
		  <b>The rules:</b> Don't be an ass hat. I will ban
	  </span><br />
      <br />
      {if isset($new_topic)}
      To create a new topic, enter a title for the topic below and create the first message.<br />
		<input type="text" name="title" value="" maxlength="80" size="60" /><br />
    (Between 5 and 80 characters in length)<br /><br />
      {elseif $is_link == TRUE}
      <b>Current Link:</b> 
      <a href="linkme.php?l={$link_id}" 
				target="_blank">{$link_title}</a><br />
      (Click to open a new window with the current messages)<br />
      <br />
      <input type="hidden" name="link" value="{$link_id}" />
      {else}
      <b>Current Topic:</b> 
      <a href="showmessages.php?topic={$topic_id}&amp;board={$board_id}" 
				target="_blank">{$topic_title}</a><br />
      (Click to open a new window with the current messages)<br />
      <br />
      <input type="hidden" name="topic" value="{$topic_id}" />
      {/if}
     {if isset($message_id)}<input type="hidden" name="id" value="{$message_id}" />{/if}
      <input type="hidden" name="board" value="{$board_id}" /> 
      <b>Your Message</b><br />
      Enter your message text below.<br />
      <textarea cols="100" rows="20" name="message" id="message">
{if $e_message != NULL}{$e_message}{elseif isset($quote)}<quote msgid="t,{$quote_topic},{$quote_id}@{$quote_revision}">{$quote_message}</quote>{$signature}{else}{$signature}{/if}</textarea><br />
      <br />

      <div>
        <input type="hidden" name="h" value="937eb" /> 
        <input type="submit" name="preview" value="Preview Message" /> 
        <input type="submit" name="submit" value="Post Message" /> 
        <!--<input type="button" value="Upload Image" onclick=
			"new upload_form($('message'), this.parentNode, 7764709); 
			this.style.display = 'none'" />-->
        </div>
    </form><br />
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
