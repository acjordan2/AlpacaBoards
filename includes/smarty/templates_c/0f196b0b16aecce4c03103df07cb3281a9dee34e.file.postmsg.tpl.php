<?php /* Smarty version Smarty-3.1.7, created on 2012-09-20 18:20:50
         compiled from "/var/www/Sper.gs/templates/default/postmsg.tpl" */ ?>
<?php /*%%SmartyHeaderCode:143772967505ba4d204fb84-58081858%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0f196b0b16aecce4c03103df07cb3281a9dee34e' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/postmsg.tpl',
      1 => 1348181004,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '143772967505ba4d204fb84-58081858',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'preview_message' => 0,
    'topic_id' => 0,
    'message_id' => 0,
    'p_message' => 0,
    'new_topic' => 0,
    'is_link' => 0,
    'link_id' => 0,
    'link_title' => 0,
    'board_id' => 0,
    'topic_title' => 0,
    'e_message' => 0,
    'quote' => 0,
    'quote_topic' => 0,
    'quote_id' => 0,
    'quote_revision' => 0,
    'quote_message' => 0,
    'signature' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_505ba4d210858',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_505ba4d210858')) {function content_505ba4d210858($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Post Message</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="templates/default/js/base.js?27">
</script>
</head>

<body class="regular">
  <div class="body">
<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Post Message</h1>
<?php if ($_smarty_tpl->tpl_vars['preview_message']->value==true){?>
	<form action="postmsg.php" method="post">
    <input type="hidden" name="message" value="
---
Save The Internet - http://act2.freepress.net/letter/two_million/
&lt;b&gt;FlashGot For Chrome - LL50a03&lt;/b&gt;" />
    <input type="hidden" name="h" value="37944" />
    <input type="hidden" name="topic" value="<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
" />
    <?php if ($_smarty_tpl->tpl_vars['message_id']->value!=null){?><input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
" /><?php }?>
    <div class="message">
		<?php echo $_smarty_tpl->tpl_vars['p_message']->value;?>

	</div>
    <input type="submit" name="submit" value="Post This Message" />
  </form>
<?php }?>
    <form action="postmsg.php" method="post">
      <span style="color: #ff0000">
		  <b>The rules:</b> Don't be an ass hat. I will ban
	  </span><br />
      <br />
      <?php if ($_smarty_tpl->tpl_vars['new_topic']->value==true){?>
      To create a new topic, enter a title for the topic below and create the first message.<br />
		<input type="text" name="title" value="" maxlength="80" size="60" /><br />
    (Between 5 and 80 characters in length)<br /><br />
      <?php }elseif($_smarty_tpl->tpl_vars['is_link']->value==true){?>
      <b>Current Link:</b> 
      <a href="linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
" 
				target="_blank"><?php echo $_smarty_tpl->tpl_vars['link_title']->value;?>
</a><br />
      (Click to open a new window with the current messages)<br />
      <br />
      <input type="hidden" name="link" value="<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
" />
      <?php }else{ ?>
      <b>Current Topic:</b> 
      <a href="showmessages.php?topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
" 
				target="_blank"><?php echo $_smarty_tpl->tpl_vars['topic_title']->value;?>
</a><br />
      (Click to open a new window with the current messages)<br />
      <br />
      <input type="hidden" name="topic" value="<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
" />
      <?php }?>
     <?php if ($_smarty_tpl->tpl_vars['message_id']->value!=null){?><input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
" /><?php }?>
      <input type="hidden" name="board" value="<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
" /> 
      <b>Your Message</b><br />
      Enter your message text below.<br />
      <textarea cols="100" rows="20" name="message" id="message">
<?php if ($_smarty_tpl->tpl_vars['e_message']->value!=null){?><?php echo $_smarty_tpl->tpl_vars['e_message']->value;?>
<?php }elseif($_smarty_tpl->tpl_vars['quote']->value==true){?><quote msgid="t,<?php echo $_smarty_tpl->tpl_vars['quote_topic']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['quote_id']->value;?>
@<?php echo $_smarty_tpl->tpl_vars['quote_revision']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['quote_message']->value;?>
</quote><?php echo $_smarty_tpl->tpl_vars['signature']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['signature']->value;?>
<?php }?></textarea><br />
      <br />

      <div>
        <input type="hidden" name="h" value="937eb" /> 
        <input type="submit" name="preview" value="Preview Message" /> 
        <input type="submit" name="submit" value="Post Message" /> 
        <!--<input type="button" value="Upload Image" onclick=
			"new upload_form($('message'), this.parentNode, 7764709); 
			this.style.display = 'none'" />-->
        </div>
    </form><br />
    <br />
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>