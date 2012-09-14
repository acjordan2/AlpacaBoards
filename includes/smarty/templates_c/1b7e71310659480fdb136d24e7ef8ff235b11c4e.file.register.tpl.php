<?php /* Smarty version Smarty-3.1.7, created on 2012-07-23 23:22:47
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/register.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5289721974f9770547aab06-54586664%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1b7e71310659480fdb136d24e7ef8ff235b11c4e' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/register.tpl',
      1 => 1343103403,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5289721974f9770547aab06-54586664',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f9770547edf2',
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
<?php if ($_valid && !is_callable('content_4f9770547edf2')) {function content_4f9770547edf2($_smarty_tpl) {?><!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Register</title>
	</head>
	<body>
		<h1>Register</h1>
		<br />
		<?php echo $_smarty_tpl->tpl_vars['message']->value;?>

		<br />
		<br />
		<form action="" method="POST" autocomplete="OFF">
		  <fieldset style="width:250px;">
			<legend><small>Enter your details:</small></legend>
			<br />
			<input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
" />
			<?php if ($_smarty_tpl->tpl_vars['invite']->value==1){?><input type="hidden" name="invite_code" value="<?php echo $_smarty_tpl->tpl_vars['invite_code']->value;?>
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
			<?php if ($_smarty_tpl->tpl_vars['invite']->value!=1){?>Invite Code:<br />
			<input type="text" name="invite_code" style="width:100%;" />
			<br /><br /><?php }?>
			<input type="submit" value="Register">
		  </fieldset>
		</form>
		<br />
	</body>
</html>
<?php }} ?>