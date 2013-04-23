<?php /* Smarty version Smarty-3.1.7, created on 2013-04-22 18:19:36
         compiled from "/var/www/Sper.gs/www/templates/default/postmsg.tpl" */ ?>
<?php /*%%SmartyHeaderCode:160072272951758a9ab7b166-16950817%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '601a2a5426d7d8c9b4051371b9bd8f477ae1cdba' => 
    array (
      0 => '/var/www/Sper.gs/www/templates/default/postmsg.tpl',
      1 => 1366679972,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '160072272951758a9ab7b166-16950817',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_51758a9ac3897',
  'variables' => 
  array (
    'preview_message' => 0,
    'token' => 0,
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
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51758a9ac3897')) {function content_51758a9ac3897($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<h1>Post Message</h1>
<?php if ($_smarty_tpl->tpl_vars['preview_message']->value==true){?>
	<form action="postmsg.php" method="post">
		<input type="hidden" name="message" value="$p_message" />
    		<input type="hidden" name="h" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
" />
    		<input type="hidden" name="topic" value="<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
" />
		<?php if (isset($_smarty_tpl->tpl_vars['message_id']->value)){?>
			<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
" />
		<?php }?>
		<div class="message">
			<?php echo $_smarty_tpl->tpl_vars['p_message']->value;?>

		</div>
    		<input type="submit" name="submit" value="Post This Message" />
	</form>
<?php }?>
	<form action="postmsg.php" method="post">
		<span style="color: #ff0000">
			<b>The rules:</b> Don't be an ass hat. I will ban
		</span>
		<br />
      		<br />
		<?php if (isset($_smarty_tpl->tpl_vars['new_topic']->value)){?>
			To create a new topic, enter a title for the topic below and create the first message.<br />
			<input type="text" name="title" value="" maxlength="80" size="60" /><br />
			(Between 5 and 80 characters in length)<br /><br />
      		<?php }elseif($_smarty_tpl->tpl_vars['is_link']->value==true){?>
			<b>Current Link:</b> 
			<a href="linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['link_title']->value;?>
</a><br />
			(Click to open a new window with the current messages)<br />
			<br />
	     		<input type="hidden" name="link" value="<?php echo $_smarty_tpl->tpl_vars['link_id']->value;?>
" />
		<?php }else{ ?>
			<b>Current Topic:</b> 
      			<a href="showmessages.php?topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['topic_title']->value;?>
</a><br />
      			(Click to open a new window with the current messages)<br />
      			<br />
     	 		<input type="hidden" name="topic" value="<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
" />
		<?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['message_id']->value)){?>
			<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
" />
		<?php }?>
		<input type="hidden" name="board" value="<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
" /> 
		<b>Your Message</b><br />
		Enter your message text below.<br />
		<textarea cols="100" rows="20" name="message" id="message">
<?php if (isset($_smarty_tpl->tpl_vars['e_message']->value)){?><?php echo $_smarty_tpl->tpl_vars['e_message']->value;?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['quote']->value)){?><quote msgid="t,<?php echo $_smarty_tpl->tpl_vars['quote_topic']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['quote_id']->value;?>
@<?php echo $_smarty_tpl->tpl_vars['quote_revision']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['quote_message']->value;?>
</quote><?php echo $_smarty_tpl->tpl_vars['signature']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['signature']->value;?>
<?php }?></textarea><br />
      <br />

      <div>
        <input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
" /> 
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