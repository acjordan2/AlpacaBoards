{include file="header.tpl"}
    <h1>Token Shop</h1>
    <h2>You have <b>{$credits}</b> credits tokens available to spend</h2>
    <table class="grid">
      <tr>
        <th>Cost</th>
        <th>Item</th>
        <th>Description</th>
      </tr>
	{foreach from=$items key=header item=table}
      <tr>
        <td>{$table.price}</td>
        <td><a href="shop.php?item={$table.item_id}">{$table.name}</a></td>
        <td>{$table.description}</td>
      </tr>
	{/foreach}
    </table><br />
    <br />
    {include file="footer.tpl"}
  </div>
</body>
</html>
