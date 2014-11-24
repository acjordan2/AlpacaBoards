{include file="header.tpl"}
<h1>Edit Profile</h1><br />
<form action="editprofile.php" method="post" enctype="multipart/form-data">
	<table class="grid">
		<tr>
			<th colspan="2">Change User Settings for {$username}</th>
		</tr>
		<tr>
			<td>Email Address</td>
			<td><input type="text" maxlength="100" name="email" value="{$public_email}"></td>
		</tr>
		<tr>
			<td>Private Email</td>
			<td><input type="text" maxlength="100" name="pemail" value="{$private_email}"></td>
		</tr>
		<tr>
			<td>Instant Messaging</td>
			<td><input type="text" maxlength="100" name="im" value="{$instant_messaging}"></td>
		</tr>
		<tr>
			<td>Signature</td>
			<td><textarea rows="2" cols="60" name="sig">{$signature}</textarea></td>
		</tr>
		<tr>
			<td>Quote</td>
			<td><textarea rows="4" cols="60" name="quote">{$quote}</textarea></td>
		</tr>
		<tr>
			<td>Picture</td>
			<td>
				{if isset($avatar)}<img src="./templates/default/images/grey.gif" data-original="{$base_image_url}/n/{$avatar}" width="{$avatar_width}" height="{$avatar_height}" />{/if}
				<br/>
				<input name="picture" type="file" /><!-- | <input type="submit" name="delete_picture" value="Delete Picture">-->
			</td>
		<tr>
			<td colspan="2"><input type="submit" name="go" value="Make Changes"></td>
		</tr>
	</table>
	<input type="hidden" name="token" value="{$token}" />
</form>
<br />
<br />
{include file="footer.tpl"}
