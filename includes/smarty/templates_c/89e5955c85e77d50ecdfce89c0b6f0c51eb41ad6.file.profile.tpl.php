<?php /* Smarty version Smarty-3.1.7, created on 2012-04-15 18:36:44
         compiled from "/home/kalphak/public_html/boards/templates/default/profile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7163139824f8b30f75088f5-61992459%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89e5955c85e77d50ecdfce89c0b6f0c51eb41ad6' => 
    array (
      0 => '/home/kalphak/public_html/boards/templates/default/profile.tpl',
      1 => 1334533003,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7163139824f8b30f75088f5-61992459',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f8b30f75cb65',
  'variables' => 
  array (
    'sitename' => 0,
    'p_username' => 0,
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
    'p_karma' => 0,
    'p_user_id' => 0,
    'good_karma' => 0,
    'bad_karma' => 0,
    'created' => 0,
    'dateformat' => 0,
    'last_active' => 0,
    'signature' => 0,
    'quote' => 0,
    'public_email' => 0,
    'instant_messaging' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f8b30f75cb65')) {function content_4f8b30f75cb65($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/kalphak/public_html/boards/includes/smarty/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="generator" content=
  "HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />

  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - User Profile - <?php echo $_smarty_tpl->tpl_vars['p_username']->value;?>
</title>
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
    <?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>User Information Page</h1>

    <div class="userbar">
      <a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href=
      "//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div><script type="text/javascript">
//<![CDATA[
    onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>
    </script>

    <table class="grid">
      <tr>
        <th colspan="2">Current Information for <?php echo $_smarty_tpl->tpl_vars['p_username']->value;?>
</th>
      </tr>

      <tr>
        <td>User Name</td>

        <td><?php echo $_smarty_tpl->tpl_vars['p_username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['p_karma']->value;?>
)</td>
      </tr>

      <tr>
        <td>User ID</td>

        <td><?php echo $_smarty_tpl->tpl_vars['p_user_id']->value;?>
</td>
      </tr>

      <tr>
        <td>Total Karma</td>

        <td><?php echo $_smarty_tpl->tpl_vars['p_karma']->value;?>
</td>
      </tr>

      <tr>
        <td><a href="karmalist.php?user=<?php echo $_smarty_tpl->tpl_vars['p_user_id']->value;?>
&amp;type=2">Good Karma</a></td>

        <td><?php echo $_smarty_tpl->tpl_vars['good_karma']->value;?>
</td>
      </tr>

      <tr>
        <td><a href="karmalist.php?user=<?php echo $_smarty_tpl->tpl_vars['p_user_id']->value;?>
&amp;type=1">Bad Karma</a></td>

        <td><?php echo $_smarty_tpl->tpl_vars['bad_karma']->value;?>
</td>
      </tr>

      <tr>
        <td><a href="links.php?mode=user&amp;userid=<?php echo $_smarty_tpl->tpl_vars['p_user_id']->value;?>
&amp;type=3">Contribution
        Karma</a></td>

        <td>0</td>
      </tr>

      <tr>
        <td>Account Created</td>

        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['created']->value,$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
      </tr>

      <tr>
        <td>Last Active</td>

        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['last_active']->value,$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
      </tr>

      <tr>
        <td>Signature</td>

        <td><?php echo $_smarty_tpl->tpl_vars['signature']->value;?>
</td>
      </tr>

      <tr>
        <td>Quote</td>
			<?php echo $_smarty_tpl->tpl_vars['quote']->value;?>

        <td></td>
      </tr>

      <tr>
        <td>Email Address</td>
			<?php echo $_smarty_tpl->tpl_vars['public_email']->value;?>

        <td></td>
      </tr>

      <tr>
        <td>Instant Messaging</td>
			<?php echo $_smarty_tpl->tpl_vars['instant_messaging']->value;?>

        <td></td>
      </tr>

      <tr>
        <td>Picture</td>

        <td><a target="_blank" imgsrc=
        "/templates/default/images/LUEshi.jpg"
        href=
        "/templates/default/images/LUEshi.jpg">
        <span class="img-placeholder" style="width:395px;height:400px" id=
        "u0_1"></span><script type="text/javascript">
//<![CDATA[
        onDOMContentLoaded(function(){new ImageLoader($("u0_1"), "\/templates\/default\/images\/LUEshi.jpg", 395, 400)})
        //]]>
        </script></a></td>
      </tr>
      <tr>
        <th colspan="2">More Options</th>
      </tr>
<?php if ($_smarty_tpl->tpl_vars['user_id']->value==$_smarty_tpl->tpl_vars['p_user_id']->value){?>
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
        <td colspan="2"><a href="links.php?mode=user&amp;userid=<?php echo $_smarty_tpl->tpl_vars['p_user_id']->value;?>
">View Links I've
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
    <?php }?>
    <tr>
        <td colspan="2"><a href="loser.php?user=<?php echo $_smarty_tpl->tpl_vars['p_user_id']->value;?>
">View <?php if ($_smarty_tpl->tpl_vars['user_id']->value==$_smarty_tpl->tpl_vars['p_user_id']->value){?>My<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['p_username']->value;?>
's<?php }?> Stats</a></td>
    </tr>
    </table><br />
    <br />
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>