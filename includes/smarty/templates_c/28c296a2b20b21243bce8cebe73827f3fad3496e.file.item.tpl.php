<?php /* Smarty version Smarty-3.1.7, created on 2012-04-15 15:36:25
         compiled from "/home/kalphak/public_html/boards/templates/default/item.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5921283694f8b3149a488a5-75035426%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '28c296a2b20b21243bce8cebe73827f3fad3496e' => 
    array (
      0 => '/home/kalphak/public_html/boards/templates/default/item.tpl',
      1 => 1334521929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5921283694f8b3149a488a5-75035426',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'karma' => 0,
    'item' => 0,
    'csrf_token' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f8b3149ab96c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f8b3149ab96c')) {function content_4f8b3149ab96c($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
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
   <script type="text/javascript" src="/templates/default/js/base.js?27"></script>
</script>
</head>

<body class="regular">
  <div class="body">
	<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Token Shop</h1>

    <h2>You have <b><?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
</b> karma tokens available to spend</h2><br />

    <div class="message">
      <b>Item:</b> <?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
<br />
      <b>Price:</b> <?php echo $_smarty_tpl->tpl_vars['item']->value['price'];?>
<br />
      <b>Description:</b> <?php echo $_smarty_tpl->tpl_vars['item']->value['description'];?>
<br />
      <br />
      <?php if ($_smarty_tpl->tpl_vars['karma']->value<$_smarty_tpl->tpl_vars['item']->value['price']){?>If only you could afford it...<?php }else{ ?><form method="POST">
		<input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['csrf_token']->value;?>
" />
		<input type="hidden" name="item" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_id'];?>
" />
		<input type="submit" name="submit" Value="Purchase" />
		</form><?php }?>
    </div><br />
    <br />
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>