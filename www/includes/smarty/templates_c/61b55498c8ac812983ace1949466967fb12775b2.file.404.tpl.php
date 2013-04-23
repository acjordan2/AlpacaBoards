<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 19:27:48
         compiled from "/var/www/Sper.gs/www/templates/default/404.tpl" */ ?>
<?php /*%%SmartyHeaderCode:979900242510db2f88a2137-16765452%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '61b55498c8ac812983ace1949466967fb12775b2' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/404.tpl',
      1 => 1366684061,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '979900242510db2f88a2137-16765452',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_510db2f88dcf8',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_510db2f88dcf8')) {function content_510db2f88dcf8($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

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