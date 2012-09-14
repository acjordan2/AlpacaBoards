<?php /* Smarty version Smarty-3.1.7, created on 2012-04-25 09:54:48
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/message.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2840429254f8e2b4ef06226-40172631%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7cfabba3066bf1d6c06a541bbdcbb875a57ffbe7' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/message.tpl',
      1 => 1335365667,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2840429254f8e2b4ef06226-40172631',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f8e2b4f0a3c9',
  'variables' => 
  array (
    'sitename' => 0,
    'link' => 0,
    'board_title' => 0,
    'board_id' => 0,
    'topic_id' => 0,
    'topic_title' => 0,
    'link_title' => 0,
    'message_id' => 0,
    'm_user_id' => 0,
    'm_username' => 0,
    'posted' => 0,
    'dateformat' => 0,
    'revision_no' => 0,
    'message' => 0,
    'user_id' => 0,
    'revision_history' => 0,
    'table' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f8e2b4f0a3c9')) {function content_4f8e2b4f0a3c9($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/kalphak/public_html/sper.gs/www/includes/smarty/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Message Detail</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type="image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href="//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href="/templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="/templates/default/js/base.js?27">
</script>
</head>

<body class="regular">
  <div class="body">
	<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Message Detail</h1><?php if ($_smarty_tpl->tpl_vars['link']->value!=true){?><b>Board:</b> <?php echo $_smarty_tpl->tpl_vars['board_title']->value;?>
<br />
    <b>Topic:</b> 
    <a href="/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['topic_title']->value;?>
</a>
	<?php }else{ ?><b>Link:</b> 
    <a href="/linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['link_title']->value;?>
</a>
    <?php }?>
    <div class="message-container" id="m<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
">
      <div class="message-top">
        <b>From:</b> <a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['m_user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['m_username']->value;?>
</a> |
        <b>Posted:</b> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['posted']->value,$_smarty_tpl->tpl_vars['dateformat']->value);?>

      </div>

      <table class="message-body">
        <tr>
          <td msgid="t,<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
@<?php echo $_smarty_tpl->tpl_vars['revision_no']->value;?>
" class="message"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</td>
          <td class="userpic">
            <div class="userpic-holder">
              <a href=
              "/templates/default/images/LUEshi.jpg">
              <span class="img-placeholder" style="width:148px;height:150px" id=
              "u0_1"></span><script type="text/javascript">
//<![CDATA[
              onDOMContentLoaded(function(){new ImageLoader($("u0_1"), "/templates/default/images/LUEshi.jpg", 148, 150)})
              //]]>
              </script></a>
            </div>
          </td>
        </tr>
      </table>
    </div><br />
    <?php if ($_smarty_tpl->tpl_vars['user_id']->value==$_smarty_tpl->tpl_vars['m_user_id']->value){?>
    <form method="get" action="/postmsg.php" style="display:inline;">
      <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
" /><input type="hidden" name=
      "<?php if ($_smarty_tpl->tpl_vars['link']->value==true){?>link<?php }else{ ?>topic<?php }?>" value="<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
" /><?php if ($_smarty_tpl->tpl_vars['link']->value!=true){?><input type="hidden" name="board" value=
      "<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
" /><?php }?><input type="submit" value="Edit this message" />
    </form>

    <form method="post" action="/message.php?id=<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
&amp;<?php if ($_smarty_tpl->tpl_vars['link']->value==true){?>link=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
<?php }else{ ?>topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
<?php }?>&amp;r=<?php echo $_smarty_tpl->tpl_vars['revision_no']->value;?>
"
    style="display:inline;">
      <input type="hidden" name="h" value="6912d" /><input type="hidden" name="action"
      value="1" /><!--<input type="submit" value="Delete this message" onclick=
      "return confirm(&quot;Are you sure you want to delete this message&quot;)" />-->
    </form><br />
    <br />
	<?php }?>
    <h3>Revisions</h3>
    
	<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['revision_history']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
?>
		#<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_no']+1;?>
 <?php if ($_smarty_tpl->tpl_vars['revision_no']->value==$_smarty_tpl->tpl_vars['table']->value['revision_no']){?><b><?php }else{ ?><a href="/message.php?id=<?php echo $_smarty_tpl->tpl_vars['message_id']->value;?>
&amp;<?php if ($_smarty_tpl->tpl_vars['link']->value==true){?>link=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;link=1<?php }else{ ?>topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
<?php }?>&amp;r=<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_no'];?>
"><?php }?>: 
		<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
<?php if ($_smarty_tpl->tpl_vars['revision_no']->value==$_smarty_tpl->tpl_vars['table']->value['revision_no']){?></b><?php }else{ ?></a><?php }?><br />
	<?php } ?>
    <br />
    <br />
    <br />
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>
</body>
</html>
<?php }} ?>