<?php /* Smarty version Smarty-3.1.7, created on 2013-01-18 12:32:37
         compiled from "/var/www/Sper.gs/templates/default/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:199713425850f995450a3890-77663153%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9e84a9f5cf250f0cee1eae467773de5e438b3bd0' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/login.tpl',
      1 => 1348181004,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '199713425850f995450a3890-77663153',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'message' => 0,
    'username' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9954517c7f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9954517c7f')) {function content_50f9954517c7f($_smarty_tpl) {?><!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">	
	<head>
		<title>Логин</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		<?php echo $_smarty_tpl->tpl_vars['message']->value;?>
<br />
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
		<a href="/register.php">Создать Логин</a> | <a href="/passwordReset.php">Забыли пароль</a>
	</body>
</html>
<?php }} ?>