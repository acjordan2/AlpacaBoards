<?php /* Smarty version Smarty-3.1.7, created on 2012-04-15 18:51:32
         compiled from "/home/kalphak/public_html/boards/templates/default/loser.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6942775554f8b5acf60c763-37087668%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '866a4232fea5e3c16a7792750606cbd51dfee88a' => 
    array (
      0 => '/home/kalphak/public_html/boards/templates/default/loser.tpl',
      1 => 1334533509,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6942775554f8b5acf60c763-37087668',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f8b5acf67844',
  'variables' => 
  array (
    'sitename' => 0,
    'p_username' => 0,
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
    'messages_posted' => 0,
    'topics_created' => 0,
    'posts_best' => 0,
    'no_reply' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f8b5acf67844')) {function content_4f8b5acf67844($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Loser</title>
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

    <h1>Stats for <?php echo $_smarty_tpl->tpl_vars['p_username']->value;?>
</h1>

    <div class="userbar">
      <a href="profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>: <a href=
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

        <td><?php echo $_smarty_tpl->tpl_vars['messages_posted']->value;?>
</td>
      </tr>

      <tr>
        <td>Topics created</td>

        <td><?php echo $_smarty_tpl->tpl_vars['topics_created']->value;?>
</td>
      </tr>

      <tr>
        <td>Posts in best topic</td>

        <td><?php echo $_smarty_tpl->tpl_vars['posts_best']->value;?>
</td>
      </tr>

      <tr>
        <td>No-reply topics created</td>

        <td><?php echo $_smarty_tpl->tpl_vars['no_reply']->value;?>
</td>
      </tr>
    </table><br />
    <br />
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>