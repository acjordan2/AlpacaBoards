<?php
function getTags($tagname, $data){
		libxml_use_internal_errors(true);
		$doc = new DomDocument();
		$doc->loadHTML($data);
		$content = '';
		
		if($doc->getElementsByTagName("quote")->length == 0){
			print "asdf";
			return $content;
		}else{
			print "asdf";
			foreach($doc->getElementsByTagName($tagname) as $quote){
				$msgid = $quote->getAttribute('msgid');
				$msgid_array = explode(",", $msgid);
				$msgid = $quote->getAttribute('msgid');
				$msgid_array = explode(",", $msgid);

				$innerHTML= ''; 
				$children = $quote->childNodes; 
				foreach ($children as $child) { 
					$innerHTML .= $child->ownerDocument->saveXML($child);
				}	
				$quote_headers = $doc->createElement("div", "");
				$quote_headers->setAttribute("class", "message-header");
				$quote->appendChild($quote_headers);
		
				$divnode = $doc->createElement("div", $innerHTML);
				$divnode->setAttribute("class", "quoted-message");
				$divnode->setAttribute("msgid", $msgid);
				$quote->parentNode->replaceChild($divnode, $quote);	
														
				$rawHTML = $doc->saveHTML();
				//$rawHTML = $doc->saveXML();
				$content = preg_replace(array("/^\<\!DOCTYPE.*?<html><body>/si",
								  "!</body></html>$!si"), "", $rawHTML);
				print html_entity_decode($content);
				getTags("quotes", html_entity_decode($content));
			}
		}		
}
$html = '<quote msgid="t,1599,56997@0">
<quote msgid="t,1599,56996@0"><quote msgid="t,1599,56995@0">
<quote msgid="t,1599,56994@0">
<quote msgid="t,1599,56988@0">
<quote msgid="t,1599,56985@0"><script>alert(2)</script><spoiler>helloworld</spoiler>
</quote>
</quote>
</quote>
</quote>
</quote>
</quote>';

print htmlentities(getTags("quote", $html));

?>
