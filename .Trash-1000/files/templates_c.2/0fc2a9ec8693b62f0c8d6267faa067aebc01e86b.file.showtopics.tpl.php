<?php /* Smarty version Smarty-3.1.7, created on 2012-03-05 17:31:38
         compiled from "/home3/discovg7/public_html/dev2/templates/default/showtopics.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15487506404f41c6e74ab610-69970289%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0fc2a9ec8693b62f0c8d6267faa067aebc01e86b' => 
    array (
      0 => '/home3/discovg7/public_html/dev2/templates/default/showtopics.tpl',
      1 => 1330993890,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15487506404f41c6e74ab610-69970289',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f41c6e750f0d',
  'variables' => 
  array (
    'board_title' => 0,
    'username' => 0,
    'stickyList' => 0,
    'domain' => 0,
    'board_id' => 0,
    'table' => 0,
    'dateformat' => 0,
    'topicList' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f41c6e750f0d')) {function content_4f41c6e750f0d($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home3/discovg7/public_html/dev2/includes/smarty/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="generator" content=
  "HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />

  <title>End of the Internet - <?php echo $_smarty_tpl->tpl_vars['board_title']->value;?>
</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
  <!--<script type="text/javascript" src="https://static.endoftheinter.net/base.js?27">~-->
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

    <h1><?php echo $_smarty_tpl->tpl_vars['board_title']->value;?>
</h1>

    <div class="userbar">
      <a href="//endoftheinter.net/profile.php?user=18026"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (0)</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href="/boardlist.php">Board List</a>
      | <a href="/postmsg.php?board=42">Create New Topic</a> | <a href=
      "/search.php?board=42">Search</a> | <a href=
      "/showtopics.php?board=42&amp;sd&amp;h=e2292">Set Default</a> | <a href=
      "//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div>

<script type="text/javascript">
//<![CDATA[
    onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>
    </script>


    <div class="infobar">
      Page 1 of <span>214</span> <span>| <a href=
      "/showtopics.php?board=42&amp;page=2">Next Page</a></span> <span>| <a href=
      "/showtopics.php?board=42&amp;page=214">Last Page</a></span>
    </div>

    <table class="grid">
      <tr>
        <th>Topic</th>

        <th>Created By</th>

        <th>Msgs</th>

        <th>Last Post</th>
      </tr>
<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['stickyList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
	 <tr>
        <td>
			<a href=
        "//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
"><b><?php echo $_smarty_tpl->tpl_vars['table']->value['title'];?>
</b></a></td>
        <td><a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a></td>

        <td><?php echo $_smarty_tpl->tpl_vars['table']->value['number_of_posts'];?>
</td>

        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
      </tr>
<?php } ?>

<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topicList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
	 <tr>
        <td>
			<a href=
        "//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['table']->value['topic_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['title'];?>
</a></td>
        <td><a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a></td>

        <td><?php echo $_smarty_tpl->tpl_vars['table']->value['number_of_posts'];?>
</td>

        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>
      </tr>
<?php } ?>
    </table>

    <div class="infobar">
      Page: 1
    </div>

    <div class="infobar">
      There is currently 1 person reading this board.
    </div><br />
    <br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
 
 <script type="text/javascript">
//<![CDATA[
  function get_cozdiv() {
  cozdiv = document.getElementById('cozpop');
  if (cozdiv) return cozdiv;

  cozdiv = document.createElement('img');
  cozdiv.setAttribute('id','cozpop');
  cozdiv.setAttribute( 'style', 'position:fixed;z-index:99999;top:30%;right:45%;margin:0;padding:0;border:#000 1px solid;background:#fff;width:10%;display:none;');
  cozdiv.setAttribute('src','http://static.endoftheinter.net/images/cosby.jpg');
  cozdiv.addEventListener('click', hide_cozpop, false);
  document.body.appendChild(cozdiv);
  return cozdiv;
  }
  function show_cozpop(e) {
  if ('m'== String.fromCharCode(e.charCode).toLowerCase()) get_cozdiv().style.display = 'inline';
  }

  function hide_cozpop(e) {
  get_cozdiv().style.display = 'none';
  }
  document.addEventListener('keypress', show_cozpop, false);
  //]]>
  </script>
</body>
</html>

<?php }} ?>