<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$sitename} - Add Link</title>
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
{include file="navigation.tpl"}

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

<h1>Add teh link!</h1>
<em>{$error_msg}</em>
<br />
<form action="/addlink.php" method="POST">
<input type="hidden" name="token" value="{$token}">
{if isset($error)}
<span style="color: #ff0000;"><b>Error:</b> {$error}</span><br /><br />
{/if}
<b>Link Title</b><br />
<input type="text" name="title" value="{$title}" maxlength="80" size="60"><br />
<br />
<b>Link URL</b><br />
<input type="text" id="lurl" name="lurl" value="{$title}" maxlength="200" size="60">
<input type="checkbox" id="nourl" name="nourl" onchange="document.getElementById('lurl').disabled=!(document.getElementById('lurl').disabled); document.getElementById('lurl').readonly=!(document.getElementById('lurl').readonly)"> <small>(No URL Required)</small><br />
<br />
<b>Link Categories</b><br />
{$i=1}
<table>
	<tr>
{foreach from=$categories key=header item=table}
<td><input type="checkbox" name="{$table.name}" value="1" />{$table.name}</td>{if $i % 4 == 0}</tr><tr>{/if}
{$i= $i + 1}
{/foreach}
</tr>
</table>
<br />
<br />
<b>Link Description</b><br />
Enter a link description. Make it good!<br />
<textarea cols="100" rows="20" name="description" id="description">{$description}
</textarea>
<br />
<br />
<input type="submit" name="addlink" value="Add Link">
</form>
</form>
    {include file="footer.tpl"}
  </div>
</body>
</html>
