<?php
include "www/includes/Config.ini.php";

$db = new PDO(DATABASE_TYPE.":host=".DATABASE_HOST.";dbname=".DATABASE_NAME,
                                     DATABASE_USER, DATABASE_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);


$sql = "SELECT * FROM Links";
$results = $db->query($sql);

while($links = $results->fetch()){
	$data[$links['link_id']]['title'] = $links['title'];
	$data[$links['link_id']]['description'] = $links['description'];
}


$xmlwriter = new xmlWriter;
$xmlwriter->openMemory();
$xmlwriter->setIndent(true);
$xmlwriter->startDocument('1.0', 'UTF-8');
$xmlwriter->startElement("sphinx:docset");
$xmlwriter->startElement("sphinx:schema");

$xmlwriter->startElement("sphinx:field");
$xmlwriter->writeAttribute("name", "title");
$xmlwriter->endElement();

$xmlwriter->startElement("sphinx:field");
$xmlwriter->writeAttribute("name", "description");
$xmlwriter->endElement();

$xmlwriter->endElement();

foreach($data as $id => $links){
	$xmlwriter->startElement("sphinx:document");
	$xmlwriter->writeAttribute("id", $id);
	$xmlwriter->startElement("title");
	$xmlwriter->text($links['title']);
	$xmlwriter->endElement();
	
	$xmlwriter->startElement("description");
	$xmlwriter->text(utf8_encode($links['description']));
	$xmlwriter->endElement();
	
	$xmlwriter->endElement();
}
$xmlwriter->endElement();

print $xmlwriter->flush();
