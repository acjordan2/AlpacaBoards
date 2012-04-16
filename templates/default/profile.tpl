<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="generator" content=
  "HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />

  <title>{$sitename} - User Profile - {$p_username}</title>
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

    <h1>User Information Page</h1>

    <div class="userbar">
      <a href="/profile.php?user={$user_id}">{$username} ({$karma})</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href=
      "//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div><script type="text/javascript">{literal}
//<![CDATA[
    onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>
    {/literal}</script>

    <table class="grid">
      <tr>
        <th colspan="2">Current Information for {$p_username}</th>
      </tr>

      <tr>
        <td>User Name</td>

        <td>{$p_username} ({$p_karma})</td>
      </tr>

      <tr>
        <td>User ID</td>

        <td>{$p_user_id}</td>
      </tr>

      <tr>
        <td>Total Karma</td>

        <td>{$p_karma}</td>
      </tr>

      <tr>
        <td><a href="karmalist.php?user={$p_user_id}&amp;type=2">Good Karma</a></td>

        <td>{$good_karma}</td>
      </tr>

      <tr>
        <td><a href="karmalist.php?user={$p_user_id}&amp;type=1">Bad Karma</a></td>

        <td>{$bad_karma}</td>
      </tr>

      <tr>
        <td><a href="links.php?mode=user&amp;userid={$p_user_id}&amp;type=3">Contribution
        Karma</a></td>

        <td>0</td>
      </tr>

      <tr>
        <td>Account Created</td>

        <td>{$created|date_format:$dateformat}</td>
      </tr>

      <tr>
        <td>Last Active</td>

        <td>{$last_active|date_format:$dateformat}</td>
      </tr>

      <tr>
        <td>Signature</td>

        <td>{$signature}</td>
      </tr>

      <tr>
        <td>Quote</td>
			{$quote}
        <td></td>
      </tr>

      <tr>
        <td>Email Address</td>
			{$public_email}
        <td></td>
      </tr>

      <tr>
        <td>Instant Messaging</td>
			{$instant_messaging}
        <td></td>
      </tr>

      <tr>
        <td>Picture</td>

        <td><a target="_blank" imgsrc=
        "/templates/default/images/LUEshi.jpg"
        href=
        "/templates/default/images/LUEshi.jpg">
        <span class="img-placeholder" style="width:395px;height:400px" id=
        "u0_1"></span><script type="text/javascript">{literal}
//<![CDATA[
        onDOMContentLoaded(function(){new ImageLoader($("u0_1"), "\/templates\/default\/images\/LUEshi.jpg", 395, 400)})
        //]]>
        {/literal}</script></a></td>
      </tr>
      <tr>
        <th colspan="2">More Options</th>
      </tr>
{if $user_id == $p_user_id}
<!--
      <tr>
        <td colspan="2"><a href="editprofile.php">Edit My Profile</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="editdisplay.php">Edit My Site Display Options</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="editpass.php">Edit My Password</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="history.php">View My Posted Messages</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="links.php?mode=user&amp;userid={$p_user_id}">View Links I've
        Added</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="links.php?mode=comments">View My LUElink Comment
        History</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="mytokens.php?user=18026">View My Available
        Tokens</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="loser.php?userid=18026">View My Stats</a></td>
      </tr>
-->
      <tr>
        <td colspan="2"><a href="shop.php">Enter The Token Shop</a></td>
      </tr>
      
      <tr>
        <td colspan="2"><a href="inventory.php">View My Inventory</a></td>
      </tr>
<!--
      <tr>
        <td colspan="2"><a href="showfavorites.php">View My Tagged Topics</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="inbox.php">Check My Private Messages</a></td>
      </tr>

      <tr>
        <td colspan="2">View My Wiki Pages: <a href=
        "//wiki.endoftheinter.net/index.php/Adrek">Community Page</a> | <a href=
        "//wiki.endoftheinter.net/index.php/User:Adrek">User Page</a></td>
      </tr>

      <tr>
        <td colspan="2"><a href="imagemap.php">View My Image Map Entry</a></td>
      </tr>-->
    {/if}
    <tr>
        <td colspan="2"><a href="loser.php?user={$p_user_id}">View {if $user_id == $p_user_id}My{else}{$p_username}'s{/if} Stats</a></td>
    </tr>
    </table><br />
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
