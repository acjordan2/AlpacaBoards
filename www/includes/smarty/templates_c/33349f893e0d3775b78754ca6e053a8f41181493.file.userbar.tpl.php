<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 12:05:26
         compiled from "/var/www/Sper.gs/www/templates/default/userbar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:125607029517586139a35d0-26796515%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33349f893e0d3775b78754ca6e053a8f41181493' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/userbar.tpl',
      1 => 1366656859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '125607029517586139a35d0-26796515',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_517586139b197',
  'variables' => 
  array (
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
    'board_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_517586139b197')) {function content_517586139b197($_smarty_tpl) {?>	<div class="userbar">
		<a href="./profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>: 
		<span id="userbar_pms" style="display:none">
			<a href="./inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
		</span> 
		<a href="./boardlist.php">Board List</a>| 
		<a href="./postmsg.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
">Create New Topic</a> | 
		<a href="./search.php?board=42">Search</a> | 
		<a href="./showtopics.php?board=42&amp;sd&amp;h=e2292">Set Default</a> | 
		<a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
 	</div>
<?php }} ?>