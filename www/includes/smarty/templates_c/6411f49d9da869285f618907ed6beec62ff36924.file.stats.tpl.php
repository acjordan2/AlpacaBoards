<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 18:45:37
         compiled from "/var/www/Sper.gs/www/templates/default/stats.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2634475955175e7c1e2dff0-70968220%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6411f49d9da869285f618907ed6beec62ff36924' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/stats.tpl',
      1 => 1366653505,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2634475955175e7c1e2dff0-70968220',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'user_num' => 0,
    'links_num' => 0,
    'message_num' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5175e7c1e717c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5175e7c1e717c')) {function content_5175e7c1e717c($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Stats</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
  <!--<script type="text/javascript" src="https://static.endoftheinter.net/base.js?27">~-->
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
	<h1>Stats</h1>
	<br />
	<br />
	<b>Total Users:</b> <?php echo $_smarty_tpl->tpl_vars['user_num']->value;?>
<br />
	<b>Total Links:</b> <?php echo $_smarty_tpl->tpl_vars['links_num']->value;?>
<br />
	<b>Total Messags:</b> <?php echo $_smarty_tpl->tpl_vars['message_num']->value;?>
<br />
	<br />
	<br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
 
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

<?php }} ?>