<?php /* Smarty version Smarty-3.1.7, created on 2013-01-18 14:00:39
         compiled from "/var/www/Sper.gs/templates/AppleLinks/showtopics.tpl" */ ?>
<?php /*%%SmartyHeaderCode:69261086150f9a856c12938-88010985%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '57e3cefec3618e0a54e99f9a9f27d2928e285958' => 
    array (
      0 => '/var/www/Sper.gs/templates/AppleLinks/showtopics.tpl',
      1 => 1358539199,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '69261086150f9a856c12938-88010985',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9a856de380',
  'variables' => 
  array (
    'sitename' => 0,
    'board_title' => 0,
    'board_id' => 0,
    'current_page' => 0,
    'page_count' => 0,
    'stickyList' => 0,
    'i' => 0,
    'domain' => 0,
    'table' => 0,
    'dateformat' => 0,
    'topicList' => 0,
    'num_readers' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9a856de380')) {function content_50f9a856de380($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/Sper.gs/includes/smarty/plugins/modifier.date_format.php';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
		<head>
			<title>End of the Internet - Home</title>
			<link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type="image/x-icon">
			<link rel="apple-touch-icon-precomposed" href=//static.endoftheinter.net/images/apple-touch-icon-ipad.png />
			<link rel="stylesheet" type="text/css" href="templates/AppleLinks/css/system7.css?1329195561" />
			<script type="text/javascript" src="https://static.endoftheinter.net/base.js?27"></script>
		</head>
		<body class="regular">
			<div class="body">
				<div class="menubar-background"></div>
				<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

				<div style="position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
					<!--a reminder, for all that we fought against. -->
				</div>
				<div class="window-shadow">
					<div class="window-header">
						<span class="window-header-title"><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
</span>
						<div class="window-header-right"></div>
					</div>
					<h1><?php echo $_smarty_tpl->tpl_vars['board_title']->value;?>
</h1>
						<div class="userbar">
						  <span id="userbar_pms" style="display:none">
							<a href="/inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
						  </span> 
						  <a href="/boardlist.php">Board List</a> | 
						  <a href="/postmsg.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
">Create New Topic</a> | 
						  <a href="/search.php?board=42">Search</a> | 
						  <a href="/showtopics.php?board=42&amp;sd&amp;h=e2292">Set Default</a> | 
						  <a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
						</div>
					    <div class="pager">
						  <?php if ($_smarty_tpl->tpl_vars['current_page']->value>1){?> <span><a href="/showtopics.php?board=42&amp;page=1">First Page</a> |</span><?php }?>
						  <?php if ($_smarty_tpl->tpl_vars['current_page']->value>2){?><span><a href="/showtopics.php?board=42&amp;page=<?php echo $_smarty_tpl->tpl_vars['current_page']->value-1;?>
">Prev Page</a> |</span><?php }?>
						  Page <?php echo $_smarty_tpl->tpl_vars['current_page']->value;?>
 of <span><?php echo $_smarty_tpl->tpl_vars['page_count']->value;?>
</span> 
						  <?php if ($_smarty_tpl->tpl_vars['current_page']->value<$_smarty_tpl->tpl_vars['page_count']->value-1){?><span>| <a href="/showtopics.php?board=42&amp;page=<?php echo $_smarty_tpl->tpl_vars['current_page']->value+1;?>
">Next Page</a></span> <?php }?>
						  <?php if ($_smarty_tpl->tpl_vars['current_page']->value<$_smarty_tpl->tpl_vars['page_count']->value){?><span>| <a href="/showtopics.php?board=42&amp;page=<?php echo $_smarty_tpl->tpl_vars['page_count']->value;?>
">Last Page</a></span><?php }?>
						</div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="75%" valign="top" style="padding: 2px 5px 0px 0px">
							<table class="grid">
								<tr>
									<th>Topic</th>
									<th>Created By</th>
									<th>Msgs</th>
									<th>Last Post</th>
								</tr>
								<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(0, null, 0);?>
								<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stickyList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
								 <tr class="zebra_<?php echo $_smarty_tpl->tpl_vars['i']->value%2;?>
">
									<td><a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
"><b><div class="sticky"><?php echo $_smarty_tpl->tpl_vars['table']->value['title'];?>
</div></b></a></td>
									<td><a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a></td>
									<td><?php echo $_smarty_tpl->tpl_vars['table']->value['number_of_posts'];?>
<?php if ($_smarty_tpl->tpl_vars['table']->value['history']>0){?> (<a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
#m<?php echo $_smarty_tpl->tpl_vars['table']->value['last_message'];?>
">+<?php echo $_smarty_tpl->tpl_vars['table']->value['history'];?>
</a>)<?php }?></td>
									<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
								  </tr><?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
								<?php } ?>
								<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topicList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
									 <tr class=" zebra_<?php echo $_smarty_tpl->tpl_vars['i']->value%2;?>
">
										<td><a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['title'];?>
</a></td>
										<td><a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a></td>
										<td><?php echo $_smarty_tpl->tpl_vars['table']->value['number_of_posts'];?>
<?php if ($_smarty_tpl->tpl_vars['table']->value['history']>0){?> (<a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
#m<?php echo $_smarty_tpl->tpl_vars['table']->value['last_message'];?>
">+<?php echo $_smarty_tpl->tpl_vars['table']->value['history'];?>
</a>)<?php }?></td>
										<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
									  </tr><?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
								<?php } ?>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div class="pager">Page: <?php $_smarty_tpl->tpl_vars["i"] = new Smarty_variable(1, null, 0);?><?php while ($_smarty_tpl->tpl_vars['i']->value<=$_smarty_tpl->tpl_vars['page_count']->value){?>
			  <?php if ($_smarty_tpl->tpl_vars['i']->value==$_smarty_tpl->tpl_vars['current_page']->value){?><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['i']->value<$_smarty_tpl->tpl_vars['page_count']->value){?>|<?php }?><?php }else{ ?><a href="/showtopics.php?board=42&amp;page=<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</a> <?php if ($_smarty_tpl->tpl_vars['i']->value<$_smarty_tpl->tpl_vars['page_count']->value){?>| <?php }?><?php }?><?php $_smarty_tpl->tpl_vars["i"] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?><?php }?>
			</div>

			<div class="pager">
			  There <?php if ($_smarty_tpl->tpl_vars['num_readers']->value<2){?>is<?php }else{ ?>are<?php }?> currently <?php echo $_smarty_tpl->tpl_vars['num_readers']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['num_readers']->value<2){?>person<?php }else{ ?>people<?php }?> reading this board.
			</div>
			<br />
			<br />
			<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		</div>
		<div class="scanline-overlay"/>
	</body>
</html>
<?php }} ?>