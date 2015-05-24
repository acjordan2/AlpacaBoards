{include file="header.tpl"}
	<h1>Change Topic Tags</h1>
    <h2><a href="{$base_url}/showmessages.php?topic={$topic}">
            {$topic_title}
        </a>
    </h2>
	<form action="" method="post">
		<span style="color: #ff0000">
            {if isset($message)}{$message}<br />{/if}
		</span>
		<br />
        <input type='text' id="tags" name="tags" style="width: 100%;" value="{if isset($tags)}{$tags}{/if}"/>
        <div>
            <input type="hidden" name="token" value="{$token}" /> 
            <input type="submit" name="submit" id="save" value="Save" /> 
        </div>
    </form>
    <br />
    <br />
    {include file="footer.tpl"}
    </div>
</body>
</html>
