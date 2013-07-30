<?php
/*
 * Parser.class.php
 * 
 * Copyright (c) 2013 Andrew Jordan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the 
 * "Software"), to deal in the Software without restriction, including 
 * without limitation the rights to use, copy, modify, merge, publish, 
 * distribute, sublicense, and/or sell copies of the Software, and to 
 * permit persons to whom the Software is furnished to do so, subject to 
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be 
 * included in all copies or substantial portions of the Software. 
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
class Parser{
	
	private $pdo_conn;
	
	private $raw_html;
	
	private $final_html;
	
	private $config;
	
	private $doc;
	
	private $purifier;
	
	static $element_count;
	
	function __construct($db){
		libxml_use_internal_errors(true);
		$this->doc = new DomDocument();
		$this->pdo_conn = $db;

	}
	
	public function loadHTML($aHtml, $r=false){
		global $allowed_tags;
		if($r)
			$this->raw_html = "$aHtml";
		else
			$this->raw_html = "<div>$aHtml</div>";
		//$this->raw_html = $aHtml;
		$this->doc->loadHTML($this->raw_html);
	}
	
	public function parse(){
		$element_img = $this->doc->getElementsByTagName("img");
		foreach($element_img as $img){
			$img_div = $this->doc->createElement("safediv");
			$img_div->setAttribute("class", "imgs");
			$src = $img->getAttribute("src");
			$src_array = explode("/", $src);
			if($src_array[sizeof($src_array)-1] != "grey.gif"){
				$hash = $src_array[3];
				if($img->parentNode->getAttribute('msgid') != NULL){
					$src_array[2] = 't';
					$filename = explode(".", $src_array[sizeof($src_array)-1]);
					$src_array[sizeof($src_array)-1] = $filename[0].".jpg";
				}
				if($src_array[2] === 'n')
					$sql = "SELECT width, height FROM UploadedImages WHERE sha1_sum = ?";
				else
					$sql = "SELECT thumb_width, thumb_height FROM UploadedImages WHERE sha1_sum = ?";
				$statement = $this->pdo_conn->prepare($sql);
				$statement->execute(array($hash));
				$results = $statement->fetch();
				$img_tmp = $this->doc->createElement("img");
				$img_tmp->setAttribute("width", $results[0]);
				$img_tmp->setAttribute("height", $results[1]);
				$img_tmp->setAttribute("style", "display: inline;");
				$img_tmp->setAttribute("data-original", implode("/", $src_array));
				$img_tmp->setAttribute("src", "./templates/default/images/grey.gif");
				$img_div->appendChild($img_tmp);
				$img->parentNode->replaceChild($img_div, $img);
			}
		}
		$element_quote = $this->doc->getElementsByTagName('quote');
		foreach($element_quote as $quote){
			$msgid = $quote->getAttribute('msgid');
			$parent_msgid = $quote->parentNode->parentNode->getAttribute('msgid');
			if($msgid != NULL){
				$msgid_array = explode(",", $msgid);
				$message_id = explode("@", $msgid_array[2]);
				if($msgid_array[0] == "t")
					$sql_getHeader = "SELECT Users.username, Users.user_id, Messages.posted FROM Messages LEFT JOIN Users Using(user_id) WHERE Messages.message_id=? AND Messages.topic_id=? AND Messages.revision_no = 0";
				elseif($msgid_array[0] == "l")
					$sql_getHeader = "SELECT Users.username, Users.user_id, LinkMessages.posted FROM LinkMessages LEFT JOIN Users Using(user_id) WHERE LinkMessages.message_id=? AND LinkMessages.link_id=? AND LinkMessages.revision_no = 0";

				$statement = $this->pdo_conn->prepare($sql_getHeader);
				$statement->execute(array($message_id[0], $msgid_array[1]));
				$results = $statement->fetch();
				$message_header = "From: <a href=\"./profile.php?id=".$results['user_id']."\">".$results['username']."</a> | Posted: ".date(DATE_FORMAT, $results['posted']);
				$quote_headers = $this->doc->CreateElement("div", $message_header);
				$quote_headers->setAttribute("class", "message-header");
				if($parent_msgid != null)
					$quote->nodeValue = "[<a href=\"./message.php?id=".urlencode($message_id[0])."&amp;topic=".urlencode($msgid_array[1])."&amp;r=".urlencode($message_id[1])."\">Quoted text omitted</a>]";
				$quote_body = $this->prependElement($quote, $quote_headers->ownerDocument->saveHTML($quote_headers));
			}else{
				$quote_body = $quote;
			}
			$divnode = $this->doc->createElement("div", $this->get_inner_html($quote_body));
			$divnode->setAttribute("class", "quoted-message");
			if($msgid != NULL) $divnode->setAttribute("msgid", $msgid);
			$quote->parentNode->replaceChild($divnode, $quote);
			$this->raw_html = html_entity_decode($this->doc->saveHTML());
			$this->loadHTML($this->raw_html, true);
			$this->parse();
		}
		$element_spoiler = $this->doc->getElementsByTagName("spoiler");
		foreach($element_spoiler as $spoiler){
			$count = Parser::getElementCount();
			$spannode = $this->doc->createElement('span');
			$spannode->setAttribute("class", "spoiler_closed");
			$spannode->setAttribute("id", "s0_".$count);
			$caption = $spoiler->getAttribute('caption');
			if($caption == NULL)
				$caption = "spoiler";
			
			$spoiler_on_close_node = $this->doc->createElement('span');
			$spoiler_on_close_node->setAttribute("class", "spoiler_on_close");
			$spoiler_on_close_bold = $this->doc->createElement("b", "&lt;$caption /&gt;");
			$spoiler_on_close_tag = $this->doc->createElement("a");
			$spoiler_on_close_tag->setAttribute("class", "caption");
			$spoiler_on_close_tag->setAttribute("href", "#");
			$spoiler_on_close_tag->appendChild($spoiler_on_close_bold);
			$spoiler_on_close_node->appendChild($spoiler_on_close_tag);
			
			$spoiler_on_open_node = $this->doc->createElement('span');
			$spoiler_on_open_node->setAttribute("class", "spoiler_on_open");
			$spoiler_on_open_start_tag = $this->doc->createElement("a", "&lt;$caption&gt;");
			$spoiler_on_open_start_tag->setAttribute("class", "caption");
			$spoiler_on_open_start_tag->setAttribute("href", "#");
			
			$spoiler_on_open_node->appendChild($spoiler_on_open_start_tag);
			$spoiler_body = $this->buildTree($spoiler, $spoiler_on_open_node);
			//$spoiler_body = $this->doc->createTextNode($this->get_inner_html($spoiler));

			$spoiler_on_open_end_tag = $this->doc->createElement("a", "&lt;/$caption&gt;");
			$spoiler_on_open_end_tag->setAttribute("class", "caption");
			$spoiler_on_open_end_tag->setAttribute("href", "#");
			$spoiler_on_open_node->appendChild($spoiler_on_open_end_tag);
			//$spoiler_on_open_node->appendChild($spoiler_body);
			//$spoiler_on_open_node->appendChild($spoiler_on_open_end_tag);
			
			$spannode->appendChild($spoiler_on_close_node);
			$spannode->appendChild($spoiler_on_open_node);			
			
			$script = $this->doc->createElement("safescript", "$(document).ready(function(){llmlSpoiler($(\"#s0_".$count."\"));});");
			$script->setAttribute("type", "text/javascript");
			$spannode->appendChild($script);
			
			$spoiler->parentNode->replaceChild($spannode, $spoiler);
			//$this->parse();
		}		
		$this->final_html = $this->raw_html;
		
	}
	
	public function prependElement($parent, $child){
		$new_element = $this->doc->createElement($parent->tagName, $child.$this->get_inner_html($parent));
		if($parent->hasAttributes()){
			foreach($parent->attributes as $attr){
				$new_element->setAttribute($attr->nodeName, $attr->nodeValue);
			}
		}
		return $new_element;
	}
	
	public function get_inner_html($node){
		$innerHTML = '';
		$children = $node->childNodes;
		foreach($children as $child){
			$innerHTML .= htmlentities($child->ownerDocument->saveXML($child));
		}
		return html_entity_decode($innerHTML);
	}
	
	public function buildTree($node, $parent){
		$children = $node->childNodes;
		foreach($children as $child){
			$parent->appendChild($child);
		}
		return $parent;
	}
	
	public function getHTML(){
		# remove <!DOCTYPE 
		$this->doc->removeChild($this->doc->firstChild);            
		# remove <html><body></body></html> 
		//$this->doc->replaceChild($this->doc->firstChild->firstChild->firstChild, $this->doc->firstChild);
		$this->final_html = $this->doc->saveHTML();
		return $this->final_html;
	}
	
	public static function getElementCount(){
		Parser::$element_count++;
		return Parser::$element_count;
	}
	
	public static function cleanUp($html){
		// Replaces <safescript> tag with <script> since html purify does not allow <script> tags @TODO: Test extensively for XSS
		// Removes extra </span> created by the parser @TODO: figure out why this happens
		$message = str_replace("<safescript type=\"text/javascript\">", "<script type=\"text/javascript\">", $html);
		$message = str_replace("</safescript>", "</script>", $message);
		$message = str_replace("</script>&lt;/span&gt;", "</script>", $message);
		$message = str_replace("<safediv class=\"imgs\">", "<div class=\"imgs\">", $message);
		$message = str_replace("</safediv>", "</div>", $message);
		return $message;
	}
}
?>
