<?php /* Smarty version Smarty-3.1.7, created on 2012-07-23 23:17:43
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/invite.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1909075192500e21e7b8c035-25764830%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5741f4fc4abe1539fc52dda75b4aac6ccd317b10' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/invite.tpl',
      1 => 1343103389,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1909075192500e21e7b8c035-25764830',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'message' => 0,
    'token' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_500e21e7e5d7c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_500e21e7e5d7c')) {function content_500e21e7e5d7c($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="generator" content=
  "HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />

  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Send Invite</title>
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
	<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Send Invite</h1>
<?php if ($_smarty_tpl->tpl_vars['message']->value!=null){?><h2><em><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</em></h2><br /><?php }?>
    <form action="invite.php" method="post" autocomplete="off">
	  <input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
" />
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
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>