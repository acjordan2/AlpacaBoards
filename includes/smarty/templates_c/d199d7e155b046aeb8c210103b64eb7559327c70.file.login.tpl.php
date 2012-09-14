<?php /* Smarty version Smarty-3.1.7, created on 2012-03-21 19:38:59
         compiled from "/home/kalphak/public_html/boards/templates/default/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9876408244f6a74a4004365-74055891%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd199d7e155b046aeb8c210103b64eb7559327c70' => 
    array (
      0 => '/home/kalphak/public_html/boards/templates/default/login.tpl',
      1 => 1332376421,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9876408244f6a74a4004365-74055891',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'username' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f6a74a404b0d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f6a74a404b0d')) {function content_4f6a74a404b0d($_smarty_tpl) {?><!DOCTYPE HTML>
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