<?php /* Smarty version Smarty-3.1.7, created on 2013-01-18 13:46:29
         compiled from "/var/www/Sper.gs/templates/default/navigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:304725250f9a695eaa9d6-82158744%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c764fc541b46c4224a9eba2e1a13a79011c9c72' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/navigation.tpl',
      1 => 1348181004,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '304725250f9a695eaa9d6-82158744',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'board_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9a695ec0e1',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9a695ec0e1')) {function content_50f9a695ec0e1($_smarty_tpl) {?><div class="menubar">
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