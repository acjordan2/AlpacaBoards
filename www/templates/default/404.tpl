{include file="header.tpl"}
	<br />
	<span style="color:red;">Page Not Found</span>
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
{/literal}
</body>
</html>

