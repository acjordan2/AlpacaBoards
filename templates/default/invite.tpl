<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="generator" content=
  "HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />

  <title>{$sitename} - Send Invite</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "templates/default/css/nblue.css?18" />
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

    <h1>Send Invite</h1>
{if $message != NULL}<h2><em>{$message}</em></h2><br />{/if}
    <form action="invite.php" method="post" autocomplete="off">
	  <input type="hidden" name="token" value="{$token}" />
      <table class="grid">
        <tr>
          <th colspan="2">Invite User</th>
        </tr>

        <tr>
          <td>E-Mail</td>

          <td><input type="text" name="email" size="30" /></td>
        </tr>

        <tr>
          <td colspan="2"><input type="submit" name="go" value="Send Invite" /></td>
        </tr>
      </table>
    </form><br />
    <br />
	{include file="footer.tpl"}
  </div>
</body>
</html>
