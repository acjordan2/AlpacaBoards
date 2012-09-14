<?php /* Smarty version Smarty-3.1.7, created on 2012-04-15 15:44:01
         compiled from "/home/kalphak/public_html/boards/templates/default/inventory.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6910564124f8b3311b56da0-40032987%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '20cab4da26a39a4abb2b5a33926973697df3c3f0' => 
    array (
      0 => '/home/kalphak/public_html/boards/templates/default/inventory.tpl',
      1 => 1334521923,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6910564124f8b3311b56da0-40032987',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'inventory' => 0,
    'table' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f8b3311bc2f1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f8b3311bc2f1')) {function content_4f8b3311bc2f1($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Token Shop</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
   <script type="text/javascript" src="templates/default/js/base.js?27"></script>
</script>
</head>

<body class="regular">
  <div class="body">
	<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

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
    </table><?php }?><br />
    <br />
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>