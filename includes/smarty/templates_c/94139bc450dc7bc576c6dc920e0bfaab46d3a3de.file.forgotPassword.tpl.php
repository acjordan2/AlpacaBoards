<?php /* Smarty version Smarty-3.1.7, created on 2012-08-18 16:47:30
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/forgotPassword.tpl" */ ?>
<?php /*%%SmartyHeaderCode:873017189502e8997ccea59-51742174%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '94139bc450dc7bc576c6dc920e0bfaab46d3a3de' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/forgotPassword.tpl',
      1 => 1345326446,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '873017189502e8997ccea59-51742174',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_502e8997ea2c1',
  'variables' => 
  array (
    'message' => 0,
    'change' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_502e8997ea2c1')) {function content_502e8997ea2c1($_smarty_tpl) {?><!DOCTYPE HTML>
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