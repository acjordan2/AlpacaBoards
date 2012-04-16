<?php /* Smarty version Smarty-3.1.7, created on 2012-02-20 20:10:51
         compiled from "/home3/discovg7/public_html/dev2/templates/default/navigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4841006084f4309a711d976-51050683%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bec88ba2418122bc1652c6331837ce5d0272d5ac' => 
    array (
      0 => '/home3/discovg7/public_html/dev2/templates/default/navigation.tpl',
      1 => 1329793839,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4841006084f4309a711d976-51050683',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f4309a711fdd',
  'variables' => 
  array (
    'board_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f4309a711fdd')) {function content_4f4309a711fdd($_smarty_tpl) {?><div class="menubar">
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