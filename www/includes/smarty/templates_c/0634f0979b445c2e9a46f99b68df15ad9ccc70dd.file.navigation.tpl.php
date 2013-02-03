<?php /* Smarty version Smarty-3.1.7, created on 2013-01-18 13:53:58
         compiled from "/var/www/Sper.gs/templates/AppleLinks/navigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:182357711050f9a856de90f0-67305075%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0634f0979b445c2e9a46f99b68df15ad9ccc70dd' => 
    array (
      0 => '/var/www/Sper.gs/templates/AppleLinks/navigation.tpl',
      1 => 1345334316,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '182357711050f9a856de90f0-67305075',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'username' => 0,
    'karma' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9a856df50a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9a856df50a')) {function content_50f9a856df50a($_smarty_tpl) {?><div class="menubar">
	<a href="/main.php" class="menu-home"></a>
	<a href="/showtopics.php?board=42">Boards</a>
	<a href="/archives.php">Archives</a>
	<a href="/links.php">Links</a>
	<a href="/stats.php">Stats</a>
	<a href="/userlist.php">User List</a>
	<a href="/logout.php">Logout</a>
	<span id="userbar_pms" style="display:none">
		<a href="/inbox.php">Private Messages(<span id="userbar_pms_count">0</span>)</a>
	</span>
	<script type="text/javascript">
		onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
	</script>
	<a href="//endoftheinter.net/profile.php?user=18026" class="menu-user"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>
</div>
<?php }} ?>