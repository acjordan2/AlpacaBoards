<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />

  <title>{$sitename} - Links</title>
  <link rel="icon" href="https://static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "https://static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
    <script type="text/javascript" src="templates/default/js/base.js?27"></script>
</head>
<body class="regular">
  <div class="body">
	{include file="navigation.tpl"}

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Links</h1>

    <div class="userbar">
      <a href="/profile.php?user={$user_id}">{$username} ({$karma})</a>:
      <span id="userbar_pms" style="display:none"><a href=
      "https://links.endoftheinter.net/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href=
      "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3#"
      onclick="return toggle_spoiler(document.getElementById('links_cat_filt'))">Edit
      category filters</a> | <a href=
      "https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div>


    <div class="infobar">
      Page 1 of <span>1</span> <span style="display:none">| <a href=
      "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=2">
      Next Page</a></span> <span style="display:none">| <a href=
      "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=1">
      Last Page</a></span>
    </div>

    <table class="grid">
      <tbody>
        <tr>
          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=2&amp;sortd=1">
          Title</a></th>
          
          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">
          Added By:</a></th>

          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">
          Date</a></th>

          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=4&amp;sortd=2">
          Rating</a></th>

          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=6&amp;sortd=2">
          Rank</a></th>
        </tr>

{foreach from=$links key=header item=table}
        <tr class="r0">
          <td><a href="/linkme.php?l={$table.link_id}">{$table.title}</a></td>
			
		  <td><a href="/profile.php?user={$table.user_id}">{$table.username}</a></td>
          <td>{$table.created|date_format:$dateformat}</td>

          <td>{$table.rating|string_format:"%.2f"}/10 (based on {$table.NumberOfVotes} votes)</td>

          <td>{$table.rank|string_format:"%.0f"}</td>

        </tr>
{/foreach}
      </tbody>
    </table>

    <div class="infobar">
      Page: 1
    </div><br />
    <br />
    {include file="footer.tpl"}
  </div>

  <div style="display: none;" id="hiddenlpsubmitdiv"></div><script type=
  "text/javascript">{literal}
//<![CDATA[
  try{for(var lastpass_iter=0; lastpass_iter < document.forms.length; lastpass_iter++){ var lastpass_f = document.forms[lastpass_iter]; if(typeof(lastpass_f.lpsubmitorig2)=="undefined"){ lastpass_f.lpsubmitorig2 = lastpass_f.submit; lastpass_f.submit = function(){ var form=this; var customEvent = document.createEvent("Event"); customEvent.initEvent("lpCustomEvent", true, true); var d = document.getElementById("hiddenlpsubmitdiv"); for(var i = 0; i < document.forms.length; i++){ if(document.forms[i]==form){ d.innerText=i; } } d.dispatchEvent(customEvent); form.lpsubmitorig2(); } } }}catch(e){}
  //]]>
  {/literal}</script>
</body>
</html>
