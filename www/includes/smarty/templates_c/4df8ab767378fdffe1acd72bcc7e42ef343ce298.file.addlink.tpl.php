<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 20:01:15
         compiled from "/var/www/Sper.gs/www/templates/default/addlink.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13240651355175ee50a0add2-44390474%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4df8ab767378fdffe1acd72bcc7e42ef343ce298' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/addlink.tpl',
      1 => 1366685942,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13240651355175ee50a0add2-44390474',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5175ee50a9e05',
  'variables' => 
  array (
    'link_edit' => 0,
    'link_id' => 0,
    'token' => 0,
    'error' => 0,
    'title' => 0,
    'lurl' => 0,
    'categories' => 0,
    'table' => 0,
    'i' => 0,
    'description' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5175ee50a9e05')) {function content_5175ee50a9e05($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<h1>Add teh link!</h1>
	<br />
	<form action="./addlink.php<?php if (isset($_smarty_tpl->tpl_vars['link_edit']->value)){?>?edit=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
<?php }?>" method="POST">
		<input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
">
		<?php if (isset($_smarty_tpl->tpl_vars['error']->value)){?>
			<span style="color: #ff0000;">
				<b>Error:</b> <?php echo $_smarty_tpl->tpl_vars['error']->value;?>

			</span>
			<br />
			<br />
		<?php }?>
		<b>Link Title</b><br />
		<input type="text" name="title" value="<?php if (isset($_smarty_tpl->tpl_vars['title']->value)){?><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
<?php }?>" maxlength="80" size="60">
		<br />
		<br />
		<b>Link URL</b><br />
		<input type="text" id="lurl" name="lurl" value="<?php if (isset($_smarty_tpl->tpl_vars['lurl']->value)){?><?php echo $_smarty_tpl->tpl_vars['lurl']->value;?>
<?php }?>" maxlength="200" size="60">
		<input type="checkbox" id="nourl" name="nourl" onchange="document.getElementById('lurl').disabled=!(document.getElementById('lurl').disabled); document.getElementById('lurl').readonly=!(document.getElementById('lurl').readonly)">
		<small>(No URL Required)</small>
		<br />
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
			<td>
				<input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['table']->value['name'];?>
" value="1" /><?php echo $_smarty_tpl->tpl_vars['table']->value['name'];?>
</td>
		<?php if ($_smarty_tpl->tpl_vars['i']->value%4==0){?>
			</tr>
			<tr>
		<?php }?>
<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
<?php } ?>
			</tr>
		</table>
		<br />
		<br />
		<b>Link Description</b><br />
		Enter a link description. Make it good!<br />
		<textarea cols="100" rows="20" name="description" id="description">
			<?php if (isset($_smarty_tpl->tpl_vars['description']->value)){?><?php echo $_smarty_tpl->tpl_vars['description']->value;?>
<?php }?>
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