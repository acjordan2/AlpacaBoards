{include file="header.tpl"}
	<h1>Stats</h1>
	<br />
	<br />
	<b>Total Users:</b> {$user_num}<br />
	<b>Total Links:</b> {$links_num}<br />
    <b>Total Topics:</b> {$topic_num}<br />
	<b>Total Messages:</b> {$message_num}<br />
    <b>Total Images Uploaded:</b> {$image_num}<br />
    <b>Total Unique Images Uploaded:</b> {$image_unique_num}
	<br />
	<br />
{include file="footer.tpl"}
	</div>
{literal}
<script type="text/javascript">
//<![CDATA[
	function get_cozdiv() {
		cozdiv = document.getElementById('cozpop');
		if (cozdiv) return cozdiv;
		cozdiv = document.createElement('img');
		cozdiv.setAttribute('id','cozpop');
		cozdiv.setAttribute( 'style', 'position:fixed;z-index:99999;top:30%;right:45%;margin:0;padding:0;border:#000 1px solid;background:#fff;width:10%;display:none;');
		cozdiv.setAttribute('src','http://static.endoftheinter.net/images/cosby.jpg');
		cozdiv.addEventListener('click', hide_cozpop, false);
		document.body.appendChild(cozdiv);
		return cozdiv;
	}
	function show_cozpop(e) {
		if ('m'== String.fromCharCode(e.charCode).toLowerCase()) get_cozdiv().style.display = 'inline';
	}

	function hide_cozpop(e) {
		get_cozdiv().style.display = 'none';
	}
	document.addEventListener('keypress', show_cozpop, false);
//]]>
 </script>
</body>
</html>
{/literal}
