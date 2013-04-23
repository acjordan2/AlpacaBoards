<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 19:55:17
         compiled from "/var/www/Sper.gs/www/templates/default/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1056442937510db2f4e00f41-79257865%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fca995cd9029417e4191067a1ffa74943ec0ee85' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/search.tpl',
      1 => 1366685709,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1056442937510db2f4e00f41-79257865',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_510db2f4e5401',
  'variables' => 
  array (
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_510db2f4e5401')) {function content_510db2f4e5401($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<h1>Search Links</h1>
	<div class="userbar">
		<a href="./profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>:
			<span id="userbar_pms" style="display:none">
				<a href="https://links.endoftheinter.net/inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
			</span>
		<a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>


	<div class="infobar">
	Page 1 of <span>1</span> 
	<span style="display:none">| 
		<a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=2">Next Page</a>
	</span>
	<span style="display:none">| 
		<a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=1">
		Last Page</a>
	</span>
	</div>
	<br />
	<form action="./links.php" method="get">
		<input id="mode" type="hidden" value="search" name="mode"/>
		<input id="q" style="font-size:24px" type="text" name="q" />
		<input style="font-size:24px" type="submit" value="Submit" />
	</form>
	Tips
	<ul>
		<li>This will search both the <b>title</b> and <b>description</b> of a link, no posts will be searched</li>
		<li>For now, only links with <b>all</b> your search terms will be shown</li>
	</ul>
	More search options to come later. 
	<br />
	<br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

 	</div>
</body>
</html>
<?php }} ?>