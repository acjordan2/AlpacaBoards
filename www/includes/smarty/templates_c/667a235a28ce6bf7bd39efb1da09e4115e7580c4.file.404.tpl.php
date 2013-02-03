<?php /* Smarty version Smarty-3.1.7, created on 2013-01-18 13:53:59
         compiled from "/var/www/Sper.gs/templates/AppleLinks/404.tpl" */ ?>
<?php /*%%SmartyHeaderCode:140213150650f9a857c85bc7-64074042%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '667a235a28ce6bf7bd39efb1da09e4115e7580c4' => 
    array (
      0 => '/var/www/Sper.gs/templates/AppleLinks/404.tpl',
      1 => 1345423770,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '140213150650f9a857c85bc7-64074042',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9a857d31fb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9a857d31fb')) {function content_50f9a857d31fb($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Page Not Found</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href="/templates/AppleLinks/css/system7.css?18" />
    <link rel="stylesheet" type="text/css" href="templates/AppleLinks/css/osx.css?18" />

  <!--<script type="text/javascript" src="https://static.endoftheinter.net/base.js?27">~-->
    <script type="text/javascript" src="templates/default/js/base.js?27"></script>
</script>
</head>

<body class="regular">
	<div class="body">
				<div class="menubar-background"></div>
				<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

				<div style="position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
					<!--a reminder, for all that we fought against. -->
				</div>
				<div class="window-shadow">
					<div class="window-header">
						<span class="window-header-title"><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
</span>
						<div class="window-header-right"></div>
					</div>
					<h1>Page Not Found</h1>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<br />
						<br />
					</table>
			</div>
			<br />
			<br />
			<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		</div>
		<div class="scanline-overlay"/>
</body>
</html>

<?php }} ?>