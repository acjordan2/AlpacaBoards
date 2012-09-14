<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>End of the Internet - Message History</title>
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

    <h1>Message History</h1>

    <div class="userbar">
      <a href="/profile.php?user={$user_id}">{$username} ({$karma})</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href="/history.php?b">Sort By Topic's
      Last Post</a> | <a href="/history.php?archived">Archived Topics</a> | <a href="#"
      onclick=
      "$('search_bar').style.display = ($('search_bar').style.display == 'none') ? 'block' : 'none'; return false;">
      Search</a> | <a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div><script type="text/javascript">{literal}
//<![CDATA[
    onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>{/literal}
    </script>

    <div class="userbar" id="search_bar" style="display: none;">
      Search:

      <form style="display: inline;" action="history.php" method="get">
        <input type="hidden" name="userid" value="18026" /> <input type="text" name="q"
        value="" size="25" /> &nbsp; <input type="submit" value="Submit" />
      </form>
    </div>

    <div class="infobar">
      Page 1 of <span>1</span> <span style="display:none">| <a href=
      "/history.php?page=2">Next Page</a></span> <span style="display:none">| <a href=
      "/history.php?page=1">Last Page</a></span>
    </div>

    <table class="grid">
      <tr>
        <th>Board</th>

        <th>Topic</th>

        <th>Msgs</th>

        <th>Your Last Post</th>

        <th>Last Post</th>
      </tr>
{foreach from=$topicList key=header item=table}
	 <tr class="r0">
        
        <td>
			{$table.board_title}
        </td>
        <td>
			<a href=
        "//{$domain}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}">{$table.title}</a></td>

        <td>{$table.number_of_posts}{if $table.history > 0} (<a href="//{$domain}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}#m{$table.last_message}">+{$table.history}</a>){/if}</td>
		
		<td>{$table.u_last_posted|date_format:$dateformat}</td>
		
        <td>{$table.last_post|date_format:$dateformat}</td>
      </tr>
{/foreach}
    </table>

    <div class="infobar">
      Page: 1
    </div><br />
    <br />
    <small><b>Time Taken:</b> 0.0785s <b>sqlly stuff:</b> 5.61% <b>Server load:</b> 1.01
    =D &mdash; End of the Internet, LLC &copy; 2012</small>
  </div>
</body>
</html>
