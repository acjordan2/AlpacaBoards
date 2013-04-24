{include file="header.tpl"}
	<h1>Token Shop</h1>

	<h2>You have <b>{$credits}</b> credits available to spend</h2><br />
	<div class="message">
		<b>Item:</b> {$item.name}<br />
		<b>Price:</b> {$item.price}<br />
		<b>Description:</b> {$item.description}
		<br />
		<br />
	{if $credits < $item.price}If only you could afford it...{else}
		<form method="POST">
			<input type="hidden" name="token" value="{$csrf_token}" />
			<input type="hidden" name="item" value="{$item.item_id}" />
			<input type="submit" name="submit" Value="Purchase" />
		</form>
	{/if}
	</div>
	<br />
	<br />
	{include file="footer.tpl"}
</div>
</body>
</html>
