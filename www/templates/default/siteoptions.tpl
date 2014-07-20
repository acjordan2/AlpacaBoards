{include file="header.tpl"}
<h1>Edit Site Options</h1>
{if isset($message)}<h2><em>{$message}</em></h2>{/if}
<br />
<form action="siteoptions.php" method="post">
    <table class="grid">
        <tr>
            <th colspan="2">Change Site Settings</th>
        </tr>
        <tr>
            <td>Site Name:</td>
            <td><input type="text" maxlength="100" name="sitename" value="{$sitename}"/></td>
        </tr>
        <tr>
            <td>Domain:</td>
            <td><input type="text" maxlength="256" name="domain" value="{$domain}"/>{if isset($needs_save)}<em>Needs save</em>{/if}</td>
        </tr>
        <tr>
            <td>Registration</td>
            <td>
                <select name="registration">
                    <option value="0" {if $registration_status == 0}selected{/if}>Disabled</option>
                    <option value="1" {if $registration_status == 1}selected{/if}>Invite Only</option>
                    <option value="2" {if $registration_status == 2}selected{/if}>Open</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Invites</td>
            <td>
                <select name="invites">
                    <option value="0" {if $invite_status == 0}selected{/if}>Disabled</option>
                    <option value="1" {if $invite_status == 1}selected{/if}>Can be bought</option>
                    <option value="2" {if $invite_status == 2}selected{/if}>Open</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="go" value="Make Changes"></td>
        </tr>
    </table>
    <input type="hidden" name="token" value="{$token}" />
</form>
<br />
<br />
{include file="footer.tpl"}
