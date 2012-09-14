<?php /* Smarty version Smarty-3.1.7, created on 2012-03-22 21:54:43
         compiled from "/home/kalphak/public_html/boards/templates/default/404.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11552450904f6be5f35a4360-52321680%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e9560613d17e5e2989e63c810f49ff7dd90332df' => 
    array (
      0 => '/home/kalphak/public_html/boards/templates/default/404.tpl',
      1 => 1332471282,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11552450904f6be5f35a4360-52321680',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f6be5f35f4cb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f6be5f35f4cb')) {function content_4f6be5f35f4cb($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Page Not Found</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
  <!--<script type="text/javascript" src="https://static.endoftheinter.net/base.js?27">~-->
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
    <br />
		<span style="color:red;">Page Not Found</span>
    <br />
    <br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
 
 <script type="text/javascript">
//<![CDATA[
  function get_cozdiv() {
  cozdiv = document.getElementById('cozpop');
  if (cozdiv) return cozdiv;

  cozdiv = document.createElement('img');
  cozdiv.setAttribute('id','cozpop');
  cozdiv.setAttribute( 'style', 'position:fixed;z-index:99999;top:30%;right:45%;margin:0;padding:0;border:#000 1px solid;background:#fff;width:10%;display:none;');
  cozdiv.setAttribute('src','http://static.endoftheinter.net/images/cosby.jpg');
  cozdiv.addEventListener('click', hide_cozpop, false);
  document.body.appendChild(cozdiv);
  return cozdiv;
  }
  function show_cozpop(e) {
  if ('m'== String.fromCharCode(e.charCode).toLowerCase()) get_cozdiv().style.display = 'inline';
  }

  function hide_cozpop(e) {
  get_cozdiv().style.display = 'none';
  }
  document.addEventListener('keypress', show_cozpop, false);
  //]]>
  </script>
</body>
</html>

<?php }} ?>