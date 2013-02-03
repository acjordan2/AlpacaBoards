<?php /* Smarty version Smarty-3.1.7, created on 2013-01-18 13:49:54
         compiled from "/var/www/Sper.gs/templates/default/links.tpl" */ ?>
<?php /*%%SmartyHeaderCode:147241136650f9a76286e939-69448421%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1ff3f8d3db26be2d1de7a7e1bec88ed57d942bd4' => 
    array (
      0 => '/var/www/Sper.gs/templates/default/links.tpl',
      1 => 1348181004,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '147241136650f9a76286e939-69448421',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'user_id' => 0,
    'username' => 0,
    'karma' => 0,
    'links' => 0,
    'table' => 0,
    'dateformat' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_50f9a7629796d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50f9a7629796d')) {function content_50f9a7629796d($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/Sper.gs/includes/smarty/plugins/modifier.date_format.php';
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />

  <title><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 - Links</title>
  <link rel="icon" href="https://static.endoftheinter.net/images/dealwithit.ico" type=
  "image/x-icon" />
  <link rel="apple-touch-icon-precomposed" href=
  "https://static.endoftheinter.net/images/apple-touch-icon-ipad.png" />
  <link rel="stylesheet" type="text/css" href=
  "/templates/default/css/nblue.css?18" />
    <script type="text/javascript" src="templates/default/js/base.js?27"></script>
</head>
<body class="regular">
  <div class="body">
	<?php echo $_smarty_tpl->getSubTemplate ("navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Links</h1>

    <div class="userbar">
      <a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['karma']->value;?>
)</a>:
      <span id="userbar_pms" style="display:none"><a href=
      "https://links.endoftheinter.net/inbox.php">Private Messages (<span id=
      "userbar_pms_count">0</span>)</a> |</span> <a href=
      "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3#"
      onclick="return toggle_spoiler(document.getElementById('links_cat_filt'))">Edit
      category filters</a> | <a href=
      "https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
    </div>


    <div class="infobar">
      Page 1 of <span>1</span> <span style="display:none">| <a href=
      "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=2">
      Next Page</a></span> <span style="display:none">| <a href=
      "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;page=1">
      Last Page</a></span>
    </div>

    <table class="grid">
      <tbody>
        <tr>
          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=2&amp;sortd=1">
          Title</a></th>
          
          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">
          Added By:</a></th>

          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=1&amp;sortd=1">
          Date</a></th>

          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=4&amp;sortd=2">
          Rating</a></th>

          <th><a href=
          "https://links.endoftheinter.net/links.php?mode=user&amp;userid=18026&amp;type=3&amp;category=0&amp;exclude=0&amp;sort=6&amp;sortd=2">
          Rank</a></th>
        </tr>

<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['links']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
 $_smarty_tpl->tpl_vars['header']->value = $_smarty_tpl->tpl_vars['table']->key;
?>
        <tr class="r0">
          <td><a href="/linkme.php?l=<?php echo $_smarty_tpl->tpl_vars['table']->value['link_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['title'];?>
</a></td>
			
		  <td><a href="/profile.php?user=<?php echo $_smarty_tpl->tpl_vars['table']->value['user_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value['username'];?>
</a></td>
          <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['table']->value['created'],$_smarty_tpl->tpl_vars['dateformat']->value);?>
</td>

          <td><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['table']->value['rating']);?>
/10 (based on <?php echo $_smarty_tpl->tpl_vars['table']->value['NumberOfVotes'];?>
 votes)</td>

          <td><?php echo sprintf("%.0f",$_smarty_tpl->tpl_vars['table']->value['rank']);?>
</td>

        </tr>
<?php } ?>
      </tbody>
    </table>

    <div class="infobar">
      Page: 1
    </div><br />
    <br />
    <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  </div>

  <div style="display: none;" id="hiddenlpsubmitdiv"></div><script type=
  "text/javascript">
//<![CDATA[
  try{for(var lastpass_iter=0; lastpass_iter < document.forms.length; lastpass_iter++){ var lastpass_f = document.forms[lastpass_iter]; if(typeof(lastpass_f.lpsubmitorig2)=="undefined"){ lastpass_f.lpsubmitorig2 = lastpass_f.submit; lastpass_f.submit = function(){ var form=this; var customEvent = document.createEvent("Event"); customEvent.initEvent("lpCustomEvent", true, true); var d = document.getElementById("hiddenlpsubmitdiv"); for(var i = 0; i < document.forms.length; i++){ if(document.forms[i]==form){ d.innerText=i; } } d.dispatchEvent(customEvent); form.lpsubmitorig2(); } } }}catch(e){}
  //]]>
  </script>
</body>
</html>
<?php }} ?>