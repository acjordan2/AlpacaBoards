<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 21:23:43
         compiled from "/var/www/Sper.gs/www/templates/default/inventory.tpl" */ ?>
<?php /*%%SmartyHeaderCode:47774231651760c146052f3-63450714%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b9b282d4e890cdc9ad71236b5005f996f41a64d5' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/inventory.tpl',
      1 => 1366691020,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '47774231651760c146052f3-63450714',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51760c1465a6a',
  'variables' => 
  array (
    'inventory' => 0,
    'table' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51760c1465a6a')) {function content_51760c1465a6a($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<h1>Inventory</h1>
	<h2>Purchased Items</h2>
	<?php if ($_smarty_tpl->tpl_vars['inventory']->value==null){?><h3 style="color:red">You have not bought anything yet</h3><?php }else{ ?>
		<table class="grid">
			<tr>
				<th>Item</th>
				<th>Description</th>
			</tr>
		<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['inventory']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
			<tr>
				<td><?php echo $_smarty_tpl->tpl_vars['table']->value['name'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['table']->value['description'];?>
</td>
			</tr>
		<?php } ?>
		</table>
	<?php }?>
	<br />
	<br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	</div>
</body>
</html>
<?php }} ?>