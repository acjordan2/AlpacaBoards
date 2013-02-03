<?php /* Smarty version Smarty-3.1.7, created on 2013-02-01 20:55:32
         compiled from "/var/www/Sper.gs/templates/default/addlink.tpl" */ ?>
<?php /*%%SmartyHeaderCode:578428162510c8024815861-40097521%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd6eef255b046b94a1a147fc81231270a4b92d846' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/addlink.tpl',
      1 => 1348181004,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '578428162510c8024815861-40097521',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'error_msg' => 0,
    'token' => 0,
    'error' => 0,
    'title' => 0,
    'categories' => 0,
    'table' => 0,
    'i' => 0,
    'description' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_510c802496b0e',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_510c802496b0e')) {function content_510c802496b0e($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Add Link</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="templates/default/js/base.js?27">
</script>
</head>

<body class="regular">
  <div class="body">
<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

<h1>Add teh link!</h1>
<em><?php echo $_smarty_tpl->tpl_vars['error_msg']->value;?>
</em>
<br />
<form action="/addlink.php" method="POST">
<input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
">
<?php if (isset($_smarty_tpl->tpl_vars['error']->value)){?>
<span style="color: #ff0000;"><b>Error:</b> <?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</span><br /><br />
<?php }?>
<b>Link Title</b><br />
<input type="text" name="title" value="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
" maxlength="80" size="60"><br />
<br />
<b>Link URL</b><br />
<input type="text" id="lurl" name="lurl" value="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
" maxlength="200" size="60">
<input type="checkbox" id="nourl" name="nourl" onchange="document.getElementById('lurl').disabled=!(document.getElementById('lurl').disabled); document.getElementById('lurl').readonly=!(document.getElementById('lurl').readonly)"> <small>(No URL Required)</small><br />
<br />
<b>Link Categories</b><br />
<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, 0);?>
<table>
	<tr>
<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
<td><input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['table']->value['name'];?>
" value="1" /><?php echo $_smarty_tpl->tpl_vars['table']->value['name'];?>
</td><?php if ($_smarty_tpl->tpl_vars['i']->value%4==0){?></tr><tr><?php }?>
<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
<?php } ?>
</tr>
</table>
<br />
<br />
<b>Link Description</b><br />
Enter a link description. Make it good!<br />
<textarea cols="100" rows="20" name="description" id="description"><?php echo $_smarty_tpl->tpl_vars['description']->value;?>

</textarea>
<br />
<br />
<input type="submit" name="addlink" value="Add Link">
</form>
</form>
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>