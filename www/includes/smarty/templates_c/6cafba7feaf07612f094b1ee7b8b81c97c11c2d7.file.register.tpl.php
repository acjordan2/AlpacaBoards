<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 20:55:05
         compiled from "/var/www/Sper.gs/www/templates/default/register.tpl" */ ?>
<?php /*%%SmartyHeaderCode:227703800517583385e3a44-13585050%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6cafba7feaf07612f094b1ee7b8b81c97c11c2d7' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/register.tpl',
      1 => 1366689301,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '227703800517583385e3a44-13585050',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5175833862cca',
  'variables' => 
  array (
    'sitename' => 0,
    'message' => 0,
    'token' => 0,
    'invite' => 0,
    'invite_code' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5175833862cca')) {function content_5175833862cca($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Register</title>
	</head>
	<body>
		<h1>Register</h1>
		<br />
		<?php if (isset($_smarty_tpl->tpl_vars['message']->value)){?><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
<?php }?>
		<br />
		<br />
		<form action="" method="POST" autocomplete="OFF">
		  <fieldset style="width:250px;">
			<legend><small>Enter your details:</small></legend>
			<br />
			<input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
" />
			<?php if (isset($_smarty_tpl->tpl_vars['invite']->value)){?><input type="hidden" name="invite_code" value="<?php echo $_smarty_tpl->tpl_vars['invite_code']->value;?>
" /><?php }?>
			Desired Username:<br />
			<input type="text" name="username" style="width:100%;" />
			<br /><br />
			Email:<br />
			<input type="text" name="email" style="width:100%;" />
			<br /><br />
			Password:<br />
			<input type="password" name="password" style="width:100%;" />
			<br /><br />
			Password (Again):<br />
			<input type="password" name="password2" style="width:100%;" />
			<br /><br />
			<?php if (!isset($_smarty_tpl->tpl_vars['invite']->value)){?>Invite Code:<br />
			<input type="text" name="invite_code" style="width:100%;" />
			<br /><br /><?php }?>
			<input type="submit" value="Register">
		  </fieldset>
		</form>
		<br />
	</body>
</html>
<?php }} ?>