<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{$sitename} - Token Shop</title>
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
	{include file="navigation.tpl"}

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Inventory</h1>
	<h2>Purchased Items</h2>
	{if $inventory == NULL}<h3 style="color:red">You have not bought anything yet</h3>{else}
    <table class="grid">
      <tr>
        <th>Item</th>

        <th>Description</th>
      </tr>
	{foreach from=$inventory key=header item=table}
      <tr>
        <td>{$table.name}</td>

        <td>{$table.description}</td>
      </tr>
	{/foreach}
    </table>{/if}<br />
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
