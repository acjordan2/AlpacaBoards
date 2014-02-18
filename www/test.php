<?php
require "includes/init.php";
$db=null;
$string ='<div class="message-container" id="m3">
        <div class="message-top">
            <b>From:</b> <a href="./profile.php?user=1">adrek</a> | 
            <b>Posted:</b> 01/18/2014  6:46:46 PM | 
                            <a href="./showmessages.php?board=42&amp;topic=1&amp;u=1">Filter</a>
                        | <a href="./message.php?id=3&amp;topic=1&amp;r=0">Message Detail
                        </a> |
            <a href="./postmsg.php?board=42&amp;topic=1&amp;quote=3" 
                onclick="return quickpost_quote(\'t,1,3@0\');">Quote</a>
        </div>
        <table class="message-body">
            <tr>
                <td msgid="t,1,3@0" class="message">
                    <span class="spoiler_closed" id="s0_1"><span class="spoiler_on_close"><a class="caption" href="#"><b>&lt;&lt;test&gt; /&gt;</b></a><script type="text/javascript">$(document).ready(function(){llmlSpoiler($("#s0_1"));});</script></span><span class="spoiler_on_open"><a class="caption" href="#">&lt;&lt;test&gt;&gt;</a><spoiler caption="&lt;test&gt;">asdf</spoiler><a class="caption" href="#">&lt;/&lt;test&gt;&gt;</a></span></span>
<br />---
<br />              This is my sig
<br />      <br />
                </td>
                <td class="userpic">
                    <div class="userpic-holder">
                        <img src="./templates/default/images/grey.gif" data-original="usercontent/i/t/dedfe1a289988641bc20bef88e23756552cee2bd/index.jpg" width="124" height="150" />                   </div>
                </td>
            </tr>
        </table>
    </div>';
print json_encode($string);
sleep(50);
?>