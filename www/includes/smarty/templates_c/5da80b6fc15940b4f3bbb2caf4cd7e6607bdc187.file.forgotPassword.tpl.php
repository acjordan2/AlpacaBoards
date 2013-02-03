<?php /* Smarty version Smarty-3.1.7, created on 2013-01-19 17:48:34
         compiled from "/var/www/Sper.gs/templates/default/forgotPassword.tpl" */ ?>
<?php /*%%SmartyHeaderCode:116422584950fb30d294a232-54640039%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5da80b6fc15940b4f3bbb2caf4cd7e6607bdc187' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/forgotPassword.tpl',
      1 => 1348181004,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '116422584950fb30d294a232-54640039',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'message' => 0,
    'change' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50fb30d29b8e2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50fb30d29b8e2')) {function content_50fb30d29b8e2($_smarty_tpl) {?><!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">	
	<head>
		<title>Сброс пароля</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

	</head>
	<body onload="document.getElementsByTagName('input')[0].focus();">
		<form action="" method="POST" autocomplete="off">
		<?php if (isset($_smarty_tpl->tpl_vars['message']->value)){?><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
<?php }?><br />
		<br /> 
		 <fieldset style="width:250px;">
			<br />
			<?php if ($_smarty_tpl->tpl_vars['change']->value==true){?>
			Пароль:	
			<input type="password" name="new" value="" style="width:100%" />
			Подтвердите пароль:
			<input type="password" name="new2" value="" style="width:100%" />
			<?php }else{ ?>
			Логин:<br />
			<input type="text" name="username" value="" style="width:100%;">
			<?php }?>
			<br /><br />
			<input type="submit" value="передавать">
		  </fieldset>
		</form>
		<br />
		<a href="/register.php">Создать Логин</a>
	</body>
</html>
<?php }} ?>