<?php /* Smarty version Smarty-3.1.7, created on 2012-07-24 01:09:15
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/history.tpl" */ ?>
<?php /*%%SmartyHeaderCode:39158291500e3c0b023050-54027840%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff81b815960c6e70164686c1138c0490727c0a5f' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/history.tpl',
      1 => 1343110092,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39158291500e3c0b023050-54027840',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
    'topicList' => 0,
    'table' => 0,
    'domain' => 0,
    'board_id' => 0,
    'dateformat' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_500e3c0b4102c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_500e3c0b4102c')) {function content_500e3c0b4102c($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/kalphak/public_html/sper.gs/www/includes/smarty/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>End of the Internet - Message History</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
    <script type="text/javascript" src="templates/default/js/base.js?27"></script>
</script>
</head>

<body class="regular">
  <div class="body">
    <?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Message History</h1>

    <div class="userbar">
      <a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href="/history.php?b">Sort By Topic's
      Last Post</a> | <a href="/history.php?archived">Archived Topics</a> | <a href="#"
      onclick=
      "$('search_bar').style.display = ($('search_bar').style.display == 'none') ? 'block' : 'none'; return false;">
      Search</a> | <a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div><script type="text/javascript">
//<![CDATA[
    onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>
    </script>

    <div class="userbar" id="search_bar" style="display: none;">
      Search:

      <form style="display: inline;" action="history.php" method="get">
        <input type="hidden" name="userid" value="18026" /> <input type="text" name="q"
        value="" size="25" /> &nbsp; <input type="submit" value="Submit" />
      </form>
    </div>

    <div class="infobar">
      Page 1 of <span>1</span> <span style="display:none">| <a href=
      "/history.php?page=2">Next Page</a></span> <span style="display:none">| <a href=
      "/history.php?page=1">Last Page</a></span>
    </div>

    <table class="grid">
      <tr>
        <th>Board</th>

        <th>Topic</th>

        <th>Msgs</th>

        <th>Your Last Post</th>

        <th>Last Post</th>
      </tr>
<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topicList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
	 <tr class="r0">
        
        <td>
			<?php echo $_smarty_tpl->tpl_vars['table']->value['board_title'];?>

        </td>
        <td>
			<a href=
        "//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['title'];?>
</a></td>

        <td><?php echo $_smarty_tpl->tpl_vars['table']->value['number_of_posts'];?>
<?php if ($_smarty_tpl->tpl_vars['table']->value['history']>0){?> (<a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
#m<?php echo $_smarty_tpl->tpl_vars['table']->value['last_message'];?>
">+<?php echo $_smarty_tpl->tpl_vars['table']->value['history'];?>
</a>)<?php }?></td>
		
		<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['u_last_posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
		
        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['last_post'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
      </tr>
<?php } ?>
    </table>

    <div class="infobar">
      Page: 1
    </div><br />
    <br />
    <small><b>Time Taken:</b> 0.0785s <b>sqlly stuff:</b> 5.61% <b>Server load:</b> 1.01
    =D &mdash; End of the Internet, LLC &copy; 2012</small>
  </div>
</body>
</html>
<?php }} ?>