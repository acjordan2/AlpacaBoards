<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$sitename} - Report Link</title>
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

    <h1>Report Link</h1>
    
    {if isset($m)}<h2><em>Link Reported</em></h2>
    {else}
    <form action="linkreport.php?l={$link_id}" method="post">
      <span style="color: #ff0000">
		  <b>The rules:</b> Don't be an ass hat. I will ban
	  </span><br />
      <br />
      {if isset($error_msg)}<em>Error: {$error_message}</em>{/if}
      <b>Current Link:</b> 
      <a href="linkme.php?l={$link_id}" 
				target="_blank">{$link_title}</a><br />
      (Click to open a new window with the current messages)<br />
      <br />
      <input type="hidden" name="link" value="{$link_id}" />
     {if isset($message_id)}<input type="hidden" name="id" value="{$message_id}" />{/if}
      <input type="hidden" name="board" value="{$board_id}" /> 
      <b>Reason</b><br />
      Enter the reason for reporting this link below. Abusers will be banned.<br />
      <textarea cols="100" rows="20" name="reason" id="reason"></textarea>
      <br />

      <div>
        <input type="hidden" name="token" value="{$token}" /> 
        <input type="submit" name="submit" value="Report Link" /> 
        </div>
    </form><br />{/if}
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
