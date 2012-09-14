<?php /* Smarty version Smarty-3.1.7, created on 2012-03-21 19:39:02
         compiled from "/home/kalphak/public_html/boards/templates/default/navigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10022502894f6a74a605fb31-39160812%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b50efa7931f4097d1c884237a65d4a9db4b27a3e' => 
    array (
      0 => '/home/kalphak/public_html/boards/templates/default/navigation.tpl',
      1 => 1332376421,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10022502894f6a74a605fb31-39160812',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'board_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f6a74a606edc',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f6a74a606edc')) {function content_4f6a74a606edc($_smarty_tpl) {?><div class="menubar">
	<a href="/main.php">Home</a> | 
	<a href="/archives.php">Archives</a> | 
	<a href="/addlink.php">Add a link</a> | 
	<a href="/linkme.php?l=random">Random link</a> | 
	<a href="/links.php?mode=topvoted">Top voted links</a> | 
	<a href="/links.php?mode=topvotedweek">Links of the week</a> |
	<a href="/links.php?mode=new">New links</a><br />
	<a href="//wiki.endoftheinter.net/index.php/Main_Page">Wiki</a> | 
	<a href="/links.php?mode=all">All links</a> |
	<a href="/links.php?mode=fav">Favorites</a> | 
	<a href="/links.php?mode=search">Search</a> | 
	<a href="/stats.php">Stats</a> | 
	<a href="/showtopics.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
">Boards</a> | 
	<a href="/userlist.php">User List</a> | 
	<a href="/logout.php">Logout</a> | 
	<a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
</div>
<?php }} ?>