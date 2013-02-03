<?php /* Smarty version Smarty-3.1.7, created on 2013-02-01 23:55:08
         compiled from "/var/www/Sper.gs/templates/default/linkme.tpl" */ ?>
<?php /*%%SmartyHeaderCode:196208565350f9a784dc07c9-28681055%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f51e0921138f77f796b71ec21668a4c85dc70119' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/linkme.tpl',
      1 => 1359784496,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '196208565350f9a784dc07c9-28681055',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9a78512ca7',
  'variables' => 
  array (
    'sitename' => 0,
    'link_data' => 0,
    'dateformat' => 0,
    'domain' => 0,
    'token' => 0,
    'user_id' => 0,
    'i' => 0,
    'message' => 0,
    'username' => 0,
    'karma' => 0,
    'link_id' => 0,
    'messages' => 0,
    'table' => 0,
    'p_signature' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9a78512ca7')) {function content_50f9a78512ca7($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/Sper.gs/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/Sper.gs/includes/smarty/plugins/modifier.replace.php';
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['link_data']->value['title'];?>
</title>
  <link rel="icon" href="https://static.endoftheinter.net/images/dealwithit.ico" type="image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href="https://static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href="/templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="templates/default/js/jquery.min.js" charset="utf-8"></script>
</head>

<body class="regular">
  <div class="body">
	<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>
    <h1><?php echo $_smarty_tpl->tpl_vars['link_data']->value['title'];?>
</h1><br />
    <br />
    <?php echo $_smarty_tpl->tpl_vars['link_data']->value['url'];?>
<br />
    <br />
    <b>Added by:</b> <a href=
    "/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['link_data']->value['username'];?>
</a><br />
    <b>Date:</b> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['link_data']->value['created'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
<br />
    <b>Code:</b> <a href="/linkme.php?l=SS<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
">SS<?php echo $_smarty_tpl->tpl_vars['link_data']->value['code'];?>
</a><br />
    <b>Hits:</b> <?php echo $_smarty_tpl->tpl_vars['link_data']->value['hits'];?>
<br />
    <b>Rating:</b> <?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['link_data']->value['rating']);?>
/10 (based on <?php echo $_smarty_tpl->tpl_vars['link_data']->value['NumberOfVotes'];?>
 votes)<br />
    <b>Rank:</b> <?php echo sprintf("%.0f",$_smarty_tpl->tpl_vars['link_data']->value['rank']);?>
<br />
    <b>Share:</b> <a href="/ss.php?l=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['code'];?>
"><?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/ss.php?l=SS<?php echo $_smarty_tpl->tpl_vars['link_data']->value['code'];?>
</a><br /><br />
    <b>Categories:</b> <?php echo $_smarty_tpl->tpl_vars['link_data']->value['categories'];?>
<br />
    <b>Options:</b> <a href=
    "./linkme.php?f=1&amp;l=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
&amp;token=<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
">Add to
    Favorites</a> | <a href="./linkreport.php?l=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
">Report
    Link</a>
    <?php if ($_smarty_tpl->tpl_vars['user_id']->value==$_smarty_tpl->tpl_vars['link_data']->value['user_id']){?>
    | <a href="/add.php?edit=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
">Edit
    link</a> 
    <?php }else{ ?>
    <br /><br /><b>Vote:</b> <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->value = 0;
  if ($_smarty_tpl->tpl_vars['i']->value<11){ for ($_foo=true;$_smarty_tpl->tpl_vars['i']->value<11; $_smarty_tpl->tpl_vars['i']->value++){
?><a href="./linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
&v=<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
&token=<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</a> <?php }} ?><br />
   	<br /><?php if (isset($_smarty_tpl->tpl_vars['message']->value)){?><b><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</b><?php }?><br />
    <?php }?>
    <br />
    <br />
    <b>Description:</b> <?php echo $_smarty_tpl->tpl_vars['link_data']->value['description'];?>

	<br />
	<br />
	<br />
	<br />
    <div class="userbar">
      <a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>:
      <span id="userbar_pms" style="display:none"><a href=
      "https://links.endoftheinter.net/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href=
      "/postmsg.php?link=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
">Post New Message</a> |
      <a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div>

    <div class="infobar">
      Page 1 of <span>1</span> <span style="display:none">| <a href=
      "/linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
&amp;page=2">Next
      Page</a></span> <span style="display:none">| <a href=
      "/linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
&amp;page=1">Last
      Page</a></span>
    </div>
<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(5, null, 0);?>
<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['messages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
    <div class="message-container" id="m<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
">
      <div class="message-top">
        <b>From:</b> <a href=
        "/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a> |
        <b>Posted:</b> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
 | <a href=
        "/message.php?id=<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
&amp;r=<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_no'];?>
&amp;link=1">
        Message Detail<?php if ($_smarty_tpl->tpl_vars['table']->value['revision_no']>1){?> (<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_id'];?>
 edits)<?php }elseif($_smarty_tpl->tpl_vars['table']->value['revision_no']==1){?> (<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_no'];?>
 edit)<?php }?></a> | <a href=
        "/postmsg.php?link=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
&amp;quote=<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
"
        onclick="return quickpost_quote('l,<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
,<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
@<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_no'];?>
');">Quote</a>
      </div>

      <table class="message-body">
        <tbody>
          <tr>
            <td msgid="l,<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
@<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_no'];?>
" class="message"><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['table']->value['message'],"<!--\$i-->",$_smarty_tpl->tpl_vars['i']->value++);?>
</td>

            <td class="userpic">
              <div class="userpic-holder">
                <a href=
                "/templates/default/images/LUEshi.jpg">
                <span class="img-loaded" style="width:150px;height:131px" id=
                "u0_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"><img src=
                "/templates/default/images/LUEshi.jpg"
                width="150" height="131" /></span></a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
<?php } ?>

    <div class="infobar">
      Page: 1
    </div>
    <br />
    <br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<a id="qptoggle" href="#">
	<span id="open">+</span>
	<span id="close" style="display:none">-</span>
</a>
<div id="pageexpander" style="height:280px;display:none;"></div>
<div id="quickpost" style="display:none;">
	<form method="POST" action="/postmsg.php" name="quickposts" id="quickposts">
			<input type="hidden" name="link" value="<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
" />
			<input type="hidden" name="h" value="76f03" />
			<b>Your Message:</b><br />
			<textarea id="qpmessage" name="message">

---
				<?php echo $_smarty_tpl->tpl_vars['p_signature']->value;?>

			</textarea><br />
			<input type="submit" value="Post Message" name="submit"/>
	</form>
</div> 
  <script type="text/javascript" src="templates/default/js/jquery.lazyload.min.js" charset="utf-8"></script>
  <script type="text/javascript" src="templates/default/js/jquery.base.js" charset="utf-8"></script>
</body>
</html>
<?php }} ?>