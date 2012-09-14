<?php /* Smarty version Smarty-3.1.7, created on 2012-08-18 17:13:07
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1371474744f8e1f173b0240-46500170%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97d4d6b81cbcfc8429db7e26ed2c2357f4b3582e' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/login.tpl',
      1 => 1345327979,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1371474744f8e1f173b0240-46500170',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f8e1f173fef2',
  'variables' => 
  array (
    'message' => 0,
    'username' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f8e1f173fef2')) {function content_4f8e1f173fef2($_smarty_tpl) {?><!DOCTYPE HTML>
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