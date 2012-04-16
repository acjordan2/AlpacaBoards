<?php /* Smarty version Smarty-3.1.7, created on 2012-02-17 23:02:29
         compiled from "/home3/discovg7/public_html/dev2/templates/default/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2827871544f3f39027d66b2-89569475%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3c42d5048d79c36dc41fda9b987b56e69b4377aa' => 
    array (
      0 => '/home3/discovg7/public_html/dev2/templates/default/login.tpl',
      1 => 1329544875,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2827871544f3f39027d66b2-89569475',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f3f390280fba',
  'variables' => 
  array (
    'username' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f3f390280fba')) {function content_4f3f390280fba($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
	<head>
		<title>Sper.gs</title>
	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		<h1>Login</h1>
		<br />
		<br />
		<form action="" method="POST" autocomplete="off">
		  <fieldset style="width:250px;">
			<legend><small>Enter your details:</small></legend>
			<br />
			Username:<br />
			<input type="text" name="username" value="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
" style="width:100%;">
			<br /><br />
			Password:<br />
			<input type="password" name="password" style="width:100%;">
			<br /><br />
			<input type="submit" value="Login">
		  </fieldset>
		</form>
		<br />
		<a href="/register.php">Register</a>
	</body>
</html>
<?php }} ?>