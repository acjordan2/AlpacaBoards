<?php /* Smarty version Smarty-3.1.7, created on 2012-06-05 19:51:24
         compiled from "/home/kalphak/public_html/sper.gs/www/templates/default/linkme.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6120810134f9758ed8ad4f2-28844895%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '240dae9774257ffa03b6a8e59fbd131bf25760f7' => 
    array (
      0 => '/home/kalphak/public_html/sper.gs/www/templates/default/linkme.tpl',
      1 => 1338943882,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6120810134f9758ed8ad4f2-28844895',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f9758edaf7e7',
  'variables' => 
  array (
    'sitename' => 0,
    'link_data' => 0,
    'dateformat' => 0,
    'domain' => 0,
    'user_id' => 0,
    'i' => 0,
    'token' => 0,
    'message' => 0,
    'username' => 0,
    'karma' => 0,
    'link_id' => 0,
    'messages' => 0,
    'table' => 0,
    'topic_id' => 0,
    'p_signature' => 0,
    'signature' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f9758edaf7e7')) {function content_4f9758edaf7e7($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/kalphak/public_html/sper.gs/www/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_replace')) include '/home/kalphak/public_html/sper.gs/www/includes/smarty/plugins/modifier.replace.php';
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['link_data']->value['title'];?>
</title>
  <link rel="icon" href="https://static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "https://static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
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
    <?php if ($_smarty_tpl->tpl_vars['user_id']->value==$_smarty_tpl->tpl_vars['link_data']->value['user_id']){?>
    <!--
    <b>Options:</b> <a href=
    "https://links.endoftheinter.net/linkme.php?h=53178&amp;f=1&amp;l=332364">Add to
    favorites</a> | <a href="/add.php?edit=$link_data.link_id">Edit
    link</a> | <a href="/linkreport.php?l=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
">Report
    Link</a>--><br />
    <?php }else{ ?>
    <b>Vote:</b> <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->value = 0;
  if ($_smarty_tpl->tpl_vars['i']->value<11){ for ($_foo=true;$_smarty_tpl->tpl_vars['i']->value<11; $_smarty_tpl->tpl_vars['i']->value++){
?><a href="/linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['link_data']->value['link_id'];?>
&v=<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
&token=<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</a> <?php }} ?><br />
   	<br /><b><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</b><br />
    <?php }?>
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
        onclick="return QuickPost.publish('quote', this);">Quote</a>
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


        <form method="post" action="/postmsg.php" class="quickpost" id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
" name="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
">
      <input type="hidden" name="topic" value="<?php echo $_smarty_tpl->tpl_vars['topic_id']->value;?>
" /><input type="hidden" name="h"
      value="76f03" /><a href="#" class="quickpost-nub" id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+2;?>
" name=
      "u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+2;?>
"><span class="open">+</span><span class="close">-</span></a>

      <div class="quickpost-canvas">
        <div id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+6;?>
"></div>

        <div class="quickpost-body">
          <b>Your Message</b><br />
          <textarea id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+7;?>
" name="message">

<?php echo $_smarty_tpl->tpl_vars['p_signature']->value;?>

</textarea>
<script type="text/javascript">

//<![CDATA[
          $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+7;?>
").value = "\n---\n"+unescape("<?php echo $_smarty_tpl->tpl_vars['signature']->value;?>
");
          //]]>
          </script><br />
          <!--<input type="submit" value="Preview Message" id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+3;?>
" name="preview" />-->
          <input type="submit" value="Post Message" id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+4;?>
" name="submit" />
          <!--<input type="button" value="Upload Image" id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+5;?>
" />-->
        </div>
      </div><a href="#" class="quickpost-grip" id="u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+4;?>
" name=
      "u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+4;?>
">&nbsp;</a><script type="text/javascript">
//<![CDATA[
      onDOMContentLoaded(function(){new QuickPost(7758474, $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+1;?>
"), unescape("<?php echo $_smarty_tpl->tpl_vars['signature']->value;?>
"), $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+2;?>
"), $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+3;?>
"), $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+4;?>
"), $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+5;?>
"), $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+6;?>
"), $("u0_<?php echo $_smarty_tpl->tpl_vars['i']->value+7;?>
"))})
      //]]>
      
      </script>
    </form>
  </div>

  <div style="display: none;" id="hiddenlpsubmitdiv"></div>
</body>
</html>
<?php }} ?>