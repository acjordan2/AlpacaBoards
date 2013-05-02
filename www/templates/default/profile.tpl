{include file="header.tpl"}
	<h1>User Information Page</h1>
	<div class="userbar">
		<a href="./profile.php?user={$user_id}">{$username} ({$karma})</a>: 
		<span id="userbar_pms" style="display:none">
			<a href="./inbox.php">Private Messages (<span id="userbar_pms_count">0</span>)</a> |
		</span>
		<a href="//wiki.endoftheinter.net/index.php/Help:Rules">Help</a>
	</div>
{literal}
	<script type="text/javascript">
		//<![CDATA[
		onDOMContentLoaded(function(){new PrivateMessageManager($("userbar_pms"), $("userbar_pms_count"), ["72057594037945962",0])})
		//]]>
	</script>
{/literal}
	<table class="grid">
		<tr>
			<th colspan="2">Current Information for {$p_username}</th>
		</tr>
		<tr>
			<td>User Name</td>
			<td>{$p_username} ({$p_karma})</td>
		</tr>
		<tr>
			<td>User ID</td>
			<td>{$p_user_id}</td>
		</tr>
{if $p_status != 0}
		<tr>
			<td>Status</td>
			<td>{if $p_status == -1}<b>Banned</b>{/if}</td>
		</tr>
{/if}
		<tr>
			<td>Total Karma</td>
			<td>{$p_karma}</td>
		</tr>
		<tr>
			<td><a href="./karmalist.php?user={$p_user_id}&amp;type=2">Good Karma</a></td>
			<td>{$good_karma}</td>
		</tr>
		<tr>
			<td><a href="./karmalist.php?user={$p_user_id}&amp;type=1">Bad Karma</a></td>
			<td>{$bad_karma}</td>
		</tr>
		<tr>
			<td><a href="./links.php?mode=user&amp;userid={$p_user_id}&amp;type=3">Contribution Karma</a></td>
			<td>{$contribution_karma}</td>
		</tr>
{if $user_id == $p_user_id}
		<tr>
			<td>Avaible Credits</td>
			<td>{$credit}</td>
		</tr>
{/if}
		<tr>
			<td>Account Created</td>
			<td>{$created|date_format:$dateformat}</td>
		</tr>
		<tr>
			<td>Last Active</td>
			<td>{$last_active|date_format:$dateformat}</td>
		</tr>
		<tr>
			<td>Signature</td>
			<td>{$signature}</td>
		</tr>
		<tr>
			<td>Quote</td>
			<td>{$quote}</td>
		</tr>
		<tr>
			<td>Email Address</td>
			<td>{$public_email}</td>
		</tr>
		<tr>
			<td>Instant Messaging</td>
			<td>{$instant_messaging}</td>
		</tr>
		<tr>
        	<td>Picture</td>
        	<td>
			<a target="_blank" imgsrc="./templates/default/images/LUEshi.jpg" href="./templates/default/images/LUEshi.jpg">
			<span class="img-placeholder" style="width:395px;height:400px" id="u0_1"></span>
			{literal}			
			<script type="text/javascript">
				//<![CDATA[
				onDOMContentLoaded(function(){new ImageLoader($("u0_1"), ".\/templates\/default\/images\/LUEshi.jpg", 395, 400)})
				//]]>
        		</script>
			{/literal}</a>
		</td>
		</tr>
		<tr>
			<th colspan="2">More Options</th>
		</tr>
{if $user_id == $p_user_id}
	<!--
		<tr>
			<td colspan="2"><a href="./editprofile.php">Edit My Profile</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./editdisplay.php">Edit My Site Display Options</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./editpass.php">Edit My Password</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./history.php">View My Posted Messages</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./links.php?mode=user&amp;userid={$p_user_id}">View Links I've Added</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./links.php?mode=comments">View My LUElink Comment History</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./mytokens.php?user=18026">View My Available Tokens</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./loser.php?userid=18026">View My Stats</a></td>
		</tr>
	-->
		<tr>
			<td colspan="2"><a href="./editpass.php">Edit My Password</a></td>
		</tr>      
		<tr>
			<td colspan="2"><a href="./history.php">View My Posted Messages</a></td>
	 	</tr>     
		<tr>
			<td colspan="2"><a href="./shop.php">Enter The Token Shop</a></td>
		</tr>      
		<tr>
			<td colspan="2"><a href="./inventory.php">View My Inventory</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./invite.php">Invite a User</a></td>
		</tr>
	<!--
		<tr>
			<td colspan="2"><a href="./showfavorites.php">View My Tagged Topics</a></td>
		</tr>
		<tr>
			<td colspan="2"><a href="./inbox.php">Check My Private Messages</a></td>
		</tr>
		<tr>
			<td colspan="2">View My Wiki Pages: 
				<a href="//wiki.endoftheinter.net/index.php/Adrek">Community Page</a> | 
				<a href="//wiki.endoftheinter.net/index.php/User:Adrek">User Page</a>
			</td>
		</tr>
		<tr>
			<td colspan="2"><a href="./imagemap.php">View My Image Map Entry</a></td>
		</tr>
	-->
{/if}
		<tr>
			<td colspan="2"><a href="./loser.php?user={$p_user_id}">View {if $user_id == $p_user_id}My{else}{$p_username}'s{/if} Stats</a></td>
		</tr>
{if $access_level > 0}
		<tr>
			<th colspan="2">{$access_title} Options</th>
		</tr>
		{if $mod_user_ban == 1}
		<tr>
			<td colspan="2">
				<a href="./profile.php?user={$p_user_id}&amp;mod_action=user_ban">Ban User</a>
			</d>
		</tr>
		{/if}
{/if}
	</table>
	
	<br />
	<br />
{include file="footer.tpl"}
</div>
</body>
</html>
