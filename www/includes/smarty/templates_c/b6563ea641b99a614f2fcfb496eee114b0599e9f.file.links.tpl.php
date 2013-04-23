<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 19:58:16
         compiled from "/var/www/Sper.gs/www/templates/default/links.tpl" */ ?>
<?php /*%%SmartyHeaderCode:331648684510dafb40ea230-79507944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6563ea641b99a614f2fcfb496eee114b0599e9f' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/links.tpl',
      1 => 1366685741,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '331648684510dafb40ea230-79507944',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_510dafb42d1a8',
  'variables' => 
  array (
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
    'links' => 0,
    'table' => 0,
    'dateformat' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_510dafb42d1a8')) {function content_510dafb42d1a8($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/Sper.gs/www/includes/smarty/plugins/modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<h1>Links</h1>

	<div class="userbar">
		<a href="./profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>:
		<span id="userbar_pms" style="display:none">
			<a href="./inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a>
		|</span> 
		<a href="./links.php?mode=user&amp;userid=18026&amp;type=3#"onclick="return toggle_spoiler(document.getElementById('links_cat_filt'))">Edit category filters</a> | 
		<a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>


	<div class="infobar">
		Page 1 of <span>1</span> <span style="display:none">| 
		<a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=2">Next Page</a></span>
		<span style="display:none">
			| <a href="https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=1">Last Page</a>
		</span>
	</div>

	<table class="grid">
		<tbody>
			<tr>
				<th>
					<a href="./links.php?mode=user&amp;userid=&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=2&amp;sortd=1">Title</a>
				</th>
				<th>
					<a href="./links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">Added By:</a>
				</th>
				<th>
					<a href="./links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">Date</a>
				</th>
				<th>
					<a href="./links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=4&amp;sortd=2">Rating</a>
				</th>
				<th>
					<a href="./links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=6&amp;sortd=2">Rank</a>
				</th>
        		</tr>
<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['links']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
			<tr class="r0">
				<td>
					<a href="./linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['table']->value['link_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['title'];?>
</a>
				</td>	
				<td>
					<a href="./profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a>
				</td>
			  	<td>
					<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['created'],$_smarty_tpl->tpl_vars['dateformat']->value);?>

				</td>
				<td>
					<?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['table']->value['rating']);?>
/10 (based on <?php echo $_smarty_tpl->tpl_vars['table']->value['NumberOfVotes'];?>
 votes)
				</td>
				<td>
					<?php echo sprintf("%.0f",$_smarty_tpl->tpl_vars['table']->value['rank']);?>

				</td>
			</tr>
<?php } ?>
		</tbody>
	</table>
	<div class="infobar">
		Page: 1
	</div>
	<br />
	<br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</div>
</body>
</html>
<?php }} ?>