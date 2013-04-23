{include file="header.tpl"}
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
		</table>
	{/if}
	<br />
	<br />
	{include file="footer.tpl"}
	</div>
</body>
</html>
