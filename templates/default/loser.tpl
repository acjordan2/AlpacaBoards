<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$sitename} - Loser</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
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

    <h1>Stats for {$p_username}</h1>

    <div class="userbar">
      <a href="profile.php?user={$user_id}">{$username} ({$karma})</a>: <a href=
      "http://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div>

    <table class="grid">
      <tr>
        <th colspan="2">Links</th>
      </tr>

      <tr>
        <td>Links added</td>

        <td>0</td>
      </tr>

      <tr>
        <td>Votes accumulated</td>

        <td></td>
      </tr>

      <tr>
        <td>Average link rating</td>

        <td>?</td>
      </tr>

      <tr>
        <th colspan="2">Forums</th>
      </tr>

      <tr>
        <td>Messages posted</td>

        <td>{$messages_posted}</td>
      </tr>

      <tr>
        <td>Topics created</td>

        <td>{$topics_created}</td>
      </tr>

      <tr>
        <td>Posts in best topic</td>

        <td>{$posts_best}</td>
      </tr>

      <tr>
        <td>No-reply topics created</td>

        <td>{$no_reply}</td>
      </tr>
    </table><br />
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
