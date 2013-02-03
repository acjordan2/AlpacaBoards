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
   <script type="text/javascript" src="/templates/default/js/base.js?27"></script>
</script>
</head>

<body class="regular">
  <div class="body">
	{include file="navigation.tpl"}

    <div style=
    "position:fixed;z-index:999;background:red;width:1px;height:1px;bottom:45px!important;bottom:10000px;right:24px">
    <!--a reminder, for all that we fought against. -->
    </div>

    <h1>Token Shop</h1>

    <h2>You have <b>{$karma}</b> karma tokens available to spend</h2><br />

    <div class="message">
      <b>Item:</b> {$item.name}<br />
      <b>Price:</b> {$item.price}<br />
      <b>Description:</b> {$item.description}<br />
      <br />
      {if $karma < $item.price}If only you could afford it...{else}<form method="POST">
		<input type="hidden" name="token" value="{$csrf_token}" />
		<input type="hidden" name="item" value="{$item.item_id}" />
		<input type="submit" name="submit" Value="Purchase" />
		</form>{/if}
    </div><br />
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
