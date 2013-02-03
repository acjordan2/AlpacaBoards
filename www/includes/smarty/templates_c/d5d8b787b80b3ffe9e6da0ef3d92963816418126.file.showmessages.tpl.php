<?php /* Smarty version Smarty-3.1.7, created on 2013-02-01 22:10:54
         compiled from "/var/www/Sper.gs/templates/default/showmessages.tpl" */ ?>
<?php /*%%SmartyHeaderCode:57489629450f9a71e52da87-17350815%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5d8b787b80b3ffe9e6da0ef3d92963816418126' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/showmessages.tpl',
      1 => 1359778244,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '57489629450f9a71e52da87-17350815',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9a71e78011',
  'variables' => 
  array (
    'sitename' => 0,
    'topic_title' => 0,
    'board_title' => 0,
    'status_message' => 0,
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
    'board_id' => 0,
    'topic_id' => 0,
    'action' => 0,
    'token' => 0,
    'current_page' => 0,
    'page_count' => 0,
    'messages' => 0,
    'table' => 0,
    'dateformat' => 0,
    'filter' => 0,
    'i' => 0,
    'k' => 0,
    'num_readers' => 0,
    'p_signature' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9a71e78011')) {function content_50f9a71e78011($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/Sper.gs/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/Sper.gs/includes/smarty/plugins/modifier.replace.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['topic_title']->value;?>
</title>
  <link rel="icon" href="//static.endoftheinter.net/images/dealwithit.ico" type="image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href="//static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href="templates/default/css/nblue.css?18" />
  <script type="text/javascript" src="templates/default/js/jquery.min.js" charset="utf-8"></script>
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

    <h2><?php echo $_smarty_tpl->tpl_vars['topic_title']->value;?>
</h2>
	<?php if (isset($_smarty_tpl->tpl_vars['status_message']->value)&&$_smarty_tpl->tpl_vars['status_message']->value!=null){?><br /><h3 style="text-align:center"><em><?php echo $_smarty_tpl->tpl_vars['status_message']->value;?>
</em></h3><br /><?php }?>
    <div class="userbar">
      <a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>: <span id=
      "userbar_pms" style="display:none"><a href="/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href="boardlist.php">Board List</a> |
      <a href="/showtopics.php?board=42">Topic List</a> | <a href=
      "/postmsg.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
">Post New Message</a><!-- | <a href=
      "//boards.endoftheinter.net/showmessages.php?board=42&amp;topic=7758474&amp;h=76f03"
      onclick="return !tagTopic(this, 7758474, true)">Tag</a> | <a href=
      "//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>-->
      <?php if ($_smarty_tpl->tpl_vars['action']->value!=null){?> | <a href="/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&sticky=1&token=<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
" onclick="confirm('Are you sure you want to pin this topic?');"><?php echo $_smarty_tpl->tpl_vars['action']->value[0]['name'];?>
</a><?php }?>
    </div><script type="text/javascript">
    
//<![CDATA[
    //onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
    //]]>
    
    </script>

    <div class="infobar" id="u0_2">
      <?php if ($_smarty_tpl->tpl_vars['current_page']->value>1){?> <span><a href="/showmessages.php?board=42&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;page=1">First Page</a> |</span><?php }?>
	  <?php if ($_smarty_tpl->tpl_vars['current_page']->value>2){?><span><a href="/showmessages.php?board=42&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;page=<?php echo $_smarty_tpl->tpl_vars['current_page']->value-1;?>
">Prev Page</a> |</span><?php }?>
      Page <?php echo $_smarty_tpl->tpl_vars['current_page']->value;?>
 of <span><?php echo $_smarty_tpl->tpl_vars['page_count']->value;?>
</span> 
      <?php if ($_smarty_tpl->tpl_vars['current_page']->value<$_smarty_tpl->tpl_vars['page_count']->value-1){?><span>| <a href="/showmessages.php?board=42&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;page=<?php echo $_smarty_tpl->tpl_vars['current_page']->value+1;?>
">Next Page</a></span> <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['current_page']->value<$_smarty_tpl->tpl_vars['page_count']->value){?><span>| <a href="/showmessages.php?board=42&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;page=<?php echo $_smarty_tpl->tpl_vars['page_count']->value;?>
">Last Page</a></span><?php }?>
    </div>

    <div id="u0_1">
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
          <b>From:</b> <a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
">
          <?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a> | <b>Posted:</b> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['posted'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
 | <?php if (isset($_smarty_tpl->tpl_vars['filter']->value)){?><a href=
          "/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
">
          Unfilter<?php }else{ ?><a href=
          "/showmessages.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;u=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
">
          Filter</a><?php }?> | <a href=
          "/message.php?id=<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;r=<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_id'];?>
">Message Detail<?php if ($_smarty_tpl->tpl_vars['table']->value['revision_id']>1){?> (<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_id'];?>
 edits)<?php }elseif($_smarty_tpl->tpl_vars['table']->value['revision_id']==1){?> (<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_id'];?>
 edit)<?php }?></a> |
          <a href="/postmsg.php?board=<?php echo $_smarty_tpl->tpl_vars['board_id']->value;?>
&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;quote=<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
" onclick=
          "return quickpost_quote('t,<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
@<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_id'];?>
');">Quote</a>
        </div>

        <table class="message-body">
          <tr>
            <td msgid="t,<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['table']->value['message_id'];?>
@<?php echo $_smarty_tpl->tpl_vars['table']->value['revision_id'];?>
" class="message"><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['table']->value['message'],"<!--\$i-->",$_smarty_tpl->tpl_vars['i']->value++);?>
</td>

            <td class="userpic">
              <div class="userpic-holder">
               
               <!-- <span class="img-placeholder" style="width:150px;height:131px" id=
                "u0_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"></span> -->
                <img src="/templates/default/images/grey.gif" data-original="https://i.sper.gs/i/t/7805f50352da7b2b878b645408ed669f/lueshi.jpg" width="150" height="156" />
                <script type="text/javascript">
					
				/*
				//<![CDATA[
                onDOMContentLoaded(function(){new ImageLoader($("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"), "/templates/default/images/LUEshi.jpg", 150, 131)})
                //]]>*/
                
                </script></a><?php if ($_smarty_tpl->tpl_vars['table']->value['user_id']==1016){?><br /><em><b>Glorious Super Admin Master Race</b></em><?php }?>
              </div>
            </td>
          </tr>
        </table>
      </div>
 <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
<?php } ?>
    <div class="infobar" id="u0_3">Page: <?php $_smarty_tpl->tpl_vars["k"] = new Smarty_variable(1, null, 0);?><?php while ($_smarty_tpl->tpl_vars['k']->value<=$_smarty_tpl->tpl_vars['page_count']->value){?>
      <?php if ($_smarty_tpl->tpl_vars['k']->value==$_smarty_tpl->tpl_vars['current_page']->value){?><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['k']->value<$_smarty_tpl->tpl_vars['page_count']->value){?>|<?php }?><?php }else{ ?><a href="/showmessages.php?board=42&amp;topic=<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
&amp;page=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
</a> <?php if ($_smarty_tpl->tpl_vars['k']->value<$_smarty_tpl->tpl_vars['page_count']->value){?>| <?php }?><?php }?><?php $_smarty_tpl->tpl_vars["k"] = new Smarty_variable($_smarty_tpl->tpl_vars['k']->value+1, null, 0);?><?php }?>
    </div>

    <div class="infobar" id="u0_4">
      <!--There are currently 2 people reading this topic.-->
      There <?php if ($_smarty_tpl->tpl_vars['num_readers']->value<2){?>is<?php }else{ ?>are<?php }?> currently <?php echo $_smarty_tpl->tpl_vars['num_readers']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['num_readers']->value<2){?>person<?php }else{ ?>people<?php }?> reading this topic
    </div><!--<script type="text/javascript">
    
//<![CDATA[
    //onDOMContentLoaded(function(){new TopicManager(7758474, 1, 471, $("u0_1"), [new uiPagerBrowser($("u0_2"), "\/\/boards.endoftheinter.net\/showmessages.php?board=42&topic=7758474", 471, 1), new uiPagerEnum($("u0_3"), "\/\/boards.endoftheinter.net\/showmessages.php?board=42&topic=7758474", 471, 1)], $("u0_4"), ["144115188083614346",471], 0)})
    //]]>

    
    </script>--><br />
    <br />
	<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<a id="qptoggle" href="#">
	<span id="open">+</span>
	<span id="close" style="display:none">-</span>
</a>
<div id="pageexpander" style="height:280px;display:none;"></div>
<div id="quickpost" style="display:none;">
	<form method="POST" action="/postmsg.php" name="quickposts" id="quickposts">
			<input type="hidden" name="topic" value="<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
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