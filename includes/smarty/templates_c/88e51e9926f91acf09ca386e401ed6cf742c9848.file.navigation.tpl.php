<?php /* Smarty version Smarty-3.1.7, created on 2012-04-17 20:48:57
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/navigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5387035884f8e1d89f2bdf0-16033006%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '88e51e9926f91acf09ca386e401ed6cf742c9848' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/navigation.tpl',
      1 => 1334713137,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5387035884f8e1d89f2bdf0-16033006',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'board_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f8e1d89f324c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f8e1d89f324c')) {function content_4f8e1d89f324c($_smarty_tpl) {?><div class="menubar">
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