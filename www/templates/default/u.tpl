<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head>
<title>{$sitename} - {$page_title}</title></head><body>
<form enctype="multipart/form-data" action="./u.php" method="post">
  <input name="file" type="file" /> <input type="submit" value="Upload File" /><input type="hidden" name="token" value="{$token}" />
</form><br />
{if isset($images)}{foreach from=$images key=header item=table}<div class="img"><img src="{$base_image_url}/t/{$table.sha1_sum}/{$table.filename}.jpg" /></div><input type="text" value="<img src=&quot;{$base_image_url}/n/{$table.sha1_sum}/{$table.filename}.{$table.extension}&quot; />" onfocus="this.select()" onMouseUp="return false" />{/foreach}{/if}
</body></html>
