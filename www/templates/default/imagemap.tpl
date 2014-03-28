{include file="header.tpl"}
            <h1>Related Images for <a href="#">image.png</a></h1>
            <center>(<a href="/imagemap.php?md5=cd73844a9667f34f82628faf81b0cc0b">go to topic view</a>)</center>
            <br/>
            <div class="infobar">Page 1 of <span>1</span><!--<span> | <a>Next Page</a></span><span> | <a>Last Page</a></span>--></div>
            <div class="image_grid">
            {foreach from=$images key=header item=img}
                <div class="grid_block">
                    <a href="./usercontent/i/t/{$img.sha1_sum}/image.jpg"><img src="./usercontent/i/t/{$img.sha1_sum}/image.jpg" /></a><br/>
                    <div class="block_desc">
                        <a style="float: left;" href="./img.php?hash=f818de60196ad15c888b7f2140a77744/like.png">image.jpg</a>
                        <div style="float: right;"><a href="./imagemap.php?hash={$img.sha1_sum}">topics</a> | <a href="./imagemap.php?hash=f818de60196ad15c888b7f2140a77744">related</a></div>
                        <br/><br/><a href="./showmessages.php?topic={$img.topic_id}">{$img.title|escape:"html"}</a> <span style="float: right;">(<a href="./imagemap.php?topic={$img.topic_id}">images</a>)</span>
                    </div>
                </div>
            {/foreach}
            </div>
            <div style="clear: left;"><br/></div>
            </table>
            <div class="infobar">Page: 1</div>
            <br /><br />{include file="footer.tpl"}
        </div>
    </body>
</html>