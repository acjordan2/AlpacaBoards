<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 18:29:45
         compiled from "/var/www/Sper.gs/www/templates/default/navigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1054695424510dafb18bc3c7-11454367%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a64423a72fea87f8ecd51aedb2e04d56f7f6dd4e' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/navigation.tpl',
      1 => 1366680582,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1054695424510dafb18bc3c7-11454367',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_510dafb18cc51',
  'variables' => 
  array (
    'board_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_510dafb18cc51')) {function content_510dafb18cc51($_smarty_tpl) {?><div class="menubar">
			<a href="./main.php">Home</a> | 
			<a href="./archives.php">Archives</a> | 
			<a href="./addlink.php">Add a link</a> | 
			<a href="./linkme.php?l=random">Random link</a> | 
			<a href="./links.php?mode=topvoted">Top voted links</a> | 
			<a href="./links.php?mode=topvotedweek">Links of the week</a> |
			<a href="./links.php?mode=new">New links</a><br />
			<a href="//wiki.endoftheinter.net/index.php/Main_Page">Wiki</a> | 
			<a href="./links.php?mode=all">All links</a> |
			<a href="./links.php?mode=fav">Favorites</a> | 
			<a href="./links.php?mode=search">Search</a> | 
			<a href="./stats.php">Stats</a> | 
			<a href="./showtopics.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
">Boards</a> | 
			<a href="./userlist.php">User List</a> | 
			<a href="./logout.php">Logout</a> | 
			<a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
		</div>
<?php }} ?>