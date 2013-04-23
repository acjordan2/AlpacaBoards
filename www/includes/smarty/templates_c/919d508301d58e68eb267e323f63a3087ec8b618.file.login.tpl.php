<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 11:36:36
         compiled from "/var/www/Sper.gs/www/templates/default/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:55326859451757fe231af23-64516619%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '919d508301d58e68eb267e323f63a3087ec8b618' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/login.tpl',
      1 => 1366655785,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '55326859451757fe231af23-64516619',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51757fe255374',
  'variables' => 
  array (
    'message' => 0,
    'username' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51757fe255374')) {function content_51757fe255374($_smarty_tpl) {?><!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">	
	<head>
		<title>Логин</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		<?php if ($_smarty_tpl->tpl_vars['message']->value!=null){?><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
<?php }?><br />
		<br />
		<form action="" method="POST" autocomplete="off">
		  <fieldset style="width:250px;">
			<br />
			Логин:<br />
			<input type="text" name="username" value="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
" style="width:100%;">
			<br /><br />
			Пароль:<br />
			<input type="password" name="password" style="width:100%;">
			<br /><br />
			<input type="submit" value="Логин">
		  </fieldset>
		</form>
		<br />
		<a href="./register.php">Создать Логин</a> | <a href="./passwordReset.php">Забыли пароль</a>
	</body>
</html>
<?php }} ?>