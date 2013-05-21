{include file="header.tpl"}
	<h1>{$link_data.title}</h1><br />
	<br />
	{$link_data.url}
	<br />
	<br />
	<b>Added by:</b> <a href="./profile.php?user={$link_data.user_id}">{$link_data.username}</a><br />
	<b>Date:</b> {$link_data.created|date_format:$dateformat}<br />
	<b>Code:</b> <a href="./linkme.php?l=SS{$link_data.link_id}">SS{$link_data.code}</a><br />
 	<b>Hits:</b> {$link_data.hits}<br />
	<b>Rating:</b> {$link_data.rating|string_format:"%.2f"}/10 (based on {$link_data.NumberOfVotes} votes)<br />
	<b>Rank:</b> {$link_data.rank|string_format:"%.0f"}<br />
	<b>Share:</b> <a href="./ss.php?l={$link_data.code}">{$domain}/ss.php?l=SS{$link_data.code}</a><br /><br />
	<b>Categories:</b> {$link_data.categories}<br />
	<b>Options:</b> 
	<a href="./linkme.php?f=1&amp;l={$link_data.link_id}&amp;token={$token}">Add to Favorites</a> | 
	<a href="./linkreport.php?l={$link_data.link_id}">Report Link</a>
{if $user_id == $link_data.user_id}
	| <a href="/addlink.php?edit={$link_data.link_id}">Edit link</a> 
{else}
	<br />
	<br />
	<b>Vote:</b> {for $i=0; $i<11; $i++}<a href="./linkme.php?l={$link_data.link_id}&v={$i}&token={$token}">{$i}</a> {/for}<br />
   	<br />{if isset($message)}<b>{$message}</b>{/if}<br />
{/if}
	<br />
	<br />
	<b>Description:</b> {$link_data.description}
	<br />
	<br />
	<br />
	<br />
	<div class="userbar">
		<a href="./profile.php?user={$user_id}">{$username} ({$karma})</a>:
      		<span id="userbar_pms" style="display:none">
		<a href="./inbox.php">
			Private Messages (<span id="userbar_pms_count">0</span>)
		</a> 
		|</span> 
		<a href="./postmsg.php?link={$link_id}">Post New Message</a> |
		<a href="https://wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>
	<div class="infobar">
		Page 1 of <span>1</span> 
		<span style="display:none">| 
			<a href="./linkme.php?l={$link_id}&amp;page=2">Next Page</a>
		</span> <span style="display:none">| 
		<a href="./linkme.php?l={$link_id}&amp;page=1">Last Page</a></span>
 	</div>
{$i = 5}
{foreach from=$messages key=header item=table}
	<div class="message-container" id="m{$table.message_id}">
		<div class="message-top">
		<b>From:</b> <a href="./profile.php?user={$table.user_id}">{$table.username}</a> |
        	<b>Posted:</b> {$table.posted|date_format:$dateformat} | 
		<a href="./message.php?id={$table.message_id}&amp;topic={$link_id}&amp;r={$table.revision_no}&amp;link=1">
			Message Detail{if $table.revision_no > 1} ({$table.revision_id} edits){elseif $table.revision_no == 1} ({$table.revision_no} edit){/if}
		</a> | 
		<a href="./postmsg.php?link={$link_id}&amp;quote={$table.message_id}" onclick="return quickpost_quote('l,{$link_data.link_id},{$table.message_id}@{$table.revision_no}');">Quote</a>
	</div>
	<table class="message-body">
		<tbody>
			<tr>
				<td msgid="l,{$link_id},{$table.message_id}@{$table.revision_no}" class="message">{$table.message|replace:"<!--\$i-->":$i++}</td>
				<td class="userpic">
					<div class="userpic-holder">
						<a href="./templates/default/images/LUEshi.jpg">
							<span class="img-loaded" style="width:150px;height:131px" id="u0_{$i}">
								<img src="./templates/default/images/LUEshi.jpg" width="150" height="131" />
							</span>
						</a>
					</div>
				</td>
			</tr>
        	</tbody>
	</table>
</div>
{$i = $i+1}
{/foreach}

    <div class="infobar">
      Page: 1
    </div>
    <br />
    <br />
	{include file="footer.tpl"}
<a id="qptoggle" href="#">
	<span id="open">+</span>
	<span id="close" style="display:none">-</span>
</a>
<div id="pageexpander" style="height:280px;display:none;"></div>
<div id="quickpost" style="display:none;">
	<form method="POST" action="./postmsg.php" name="quickposts" id="quickposts">
			<input type="hidden" name="link" value="{$link_data.link_id}" />
			<input type="hidden" name="token" value="{$token}" />
			<b>Your Message:</b><br />
			<textarea id="qpmessage" name="message">

---
				{$p_signature}
			</textarea><br />
			<input type="submit" value="Post Message" name="submit"/>
	</form>
</div> 
  <script type="text/javascript" src="templates/default/js/jquery.lazyload.min.js" charset="utf-8"></script>
  <script type="text/javascript" src="templates/default/js/jquery.base.js" charset="utf-8"></script>
</body>
</html>
