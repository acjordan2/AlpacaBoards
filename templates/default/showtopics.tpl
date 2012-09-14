<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$sitename} - {$board_title}</title>
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

    <h1>{$board_title}</h1>

    <div class="userbar">
      <a href="/profile.php?user={$user_id}">{$username} ({$karma})</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href="/boardlist.php">Board List</a>
      | <a href="/postmsg.php?board={$board_id}">Create New Topic</a> | <a href=
      "/search.php?board=42">Search</a> | <a href=
      "/showtopics.php?board=42&amp;sd&amp;h=e2292">Set Default</a> | <a href=
      "//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div>
{literal}
<script type="text/javascript">
//<![CDATA[
    onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>
    </script>
{/literal}

    <div class="infobar">
      {if $current_page > 1} <span><a href="/showtopics.php?board=42&amp;page=1">First Page</a> |</span>{/if}
	  {if $current_page > 2}<span><a href="/showtopics.php?board=42&amp;page={$current_page - 1}">Prev Page</a> |</span>{/if}
      Page {$current_page} of <span>{$page_count}</span> 
      {if $current_page < $page_count - 1}<span>| <a href="/showtopics.php?board=42&amp;page={$current_page + 1}">Next Page</a></span> {/if}
      {if $current_page < $page_count}<span>| <a href="/showtopics.php?board=42&amp;page={$page_count}">Last Page</a></span>{/if}
    </div>

    <table class="grid">
      <tr>
        <th>Topic</th>

        <th>Created By</th>

        <th>Msgs</th>

        <th>Last Post</th>
      </tr>
{foreach from=$stickyList key=header item=table}
	 <tr>
        <td>
			<a href=
        "//{$domain}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}"><b><div class="sticky">{$table.title}</div></b></a></td>
        <td><a href="//{$domain}/profile.php?user={$table.user_id}">{$table.username}</a></td>

        <td>{$table.number_of_posts}{if $table.history > 0} (<a href="//{$domain}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}#m{$table.last_message}">+{$table.history}</a>){/if}</td>

        <td>{$table.posted|date_format:$dateformat}</td>
      </tr>
{/foreach}

{foreach from=$topicList key=header item=table}
	 <tr>
        <td>
			<a href=
        "//{$domain}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}">{$table.title}</a></td>
        <td><a href="//{$domain}/profile.php?user={$table.user_id}">{$table.username}</a></td>

        <td>{$table.number_of_posts}{if $table.history > 0} (<a href="//{$domain}/showmessages.php?board={$board_id}&amp;topic={$table.topic_id}#m{$table.last_message}">+{$table.history}</a>){/if}</td>

        <td>{$table.posted|date_format:$dateformat}</td>
      </tr>
{/foreach}
    </table>

    <div class="infobar">Page: {assign var="i" value=1}{while $i <= $page_count}
      {if $i == $current_page}{$i} {if $i<$page_count}|{/if}{else}<a href="/showtopics.php?board=42&amp;page={$i}">{$i}</a> {if $i < $page_count}| {/if}{/if}{assign var="i" value=$i+1}{/while}
    </div>

    <div class="infobar">
      There {if $num_readers < 2}is{else}are{/if} currently {$num_readers} {if $num_readers < 2}person{else}people{/if} reading this board.
    </div><br />
    <br />
	{include file="footer.tpl"}
  </div>
 {literal}
 <script type="text/javascript">
//<![CDATA[
  function get_cozdiv() {
  cozdiv = document.getElementById('cozpop');
  if (cozdiv) return cozdiv;

  cozdiv = document.createElement('img');
  cozdiv.setAttribute('id','cozpop');
  cozdiv.setAttribute( 'style', 'position:fixed;z-index:99999;top:30%;right:45%;margin:0;padding:0;border:#000 1px solid;background:#fff;width:10%;display:none;');
  cozdiv.setAttribute('src','http://static.endoftheinter.net/images/cosby.jpg');
  cozdiv.addEventListener('click', hide_cozpop, false);
  document.body.appendChild(cozdiv);
  return cozdiv;
  }
  function show_cozpop(e) {
  if ('m'== String.fromCharCode(e.charCode).toLowerCase()) get_cozdiv().style.display = 'inline';
  }

  function hide_cozpop(e) {
  get_cozdiv().style.display = 'none';
  }
  document.addEventListener('keypress', show_cozpop, false);
  //]]>
  </script>
</body>
</html>
{/literal}
