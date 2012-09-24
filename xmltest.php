<?php

libxml_use_internal_errors(true);
$doc = new DomDocument();
$doc->loadHTML("hello world<quote from=\"asdf\">asdf</quote></quote><quote msgid=\"1234,567@2\">This is a quote<quote msgid=\"asdf,2345@3\">This is an inline quote</quote></quote>");

foreach($doc->getElementsByTagName('quote') as $quote){
    $linkthumb = $quote->getAttribute('msgid');
    echo $linkthumb;
}
libxml_use_internal_errors(false);

?>
