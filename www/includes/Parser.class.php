<?php
/*
 * Parser.class.php
 * 
 * Copyright (c) 2014 Andrew Jordan
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

require_once "RecursiveDOMIterator.class.php";

class Parser
{

	private $new_line;

	static $element_count;

	function __construct($db){
		libxml_use_internal_errors(true);
		// White list of allowed tags => attributes
		$this->allowed_tags = array("b" => NULL, 
									"i" => NULL, 
									"u" => NULL, 
									"pre" => NULL,
									"html" => NULL, 
									"body" => NULL, 
									"#text" => NULL,
									"img"	=> "src",
									"quote" => "msgid",
									"spoiler" => "caption");
		$this->doc = new DomDocument();
		$this->pdo_conn = $db;
	}

	public function parse($html, $convert_newlines = true){
		$this->new_line = $convert_newlines;

		// Initizlize Variables
		$allowed_attributes = "";
		if($html == null){
			$html = " ";
		}
		$this->doc->loadHTML($html);
		// Create a recurisve iterator 
		// to get all nodes in a document
		// starting with the children
		$dit = new RecursiveIteratorIterator(
			new RecursiveDOMIterator($this->doc),
			RecursiveIteratorIterator::CHILD_FIRST);

		foreach($dit as $node){
			// If tag is not in whitelist,
			// create a textnode with data 
			// and attributes from the tag
			if(!array_key_exists($node->nodeName, $this->allowed_tags)){
				// Ignore CDATA fields. Not necessary
				// since all invalid tags will be 
				// entity encoded
				if($node->nodeName != "#cdata-section"){
					$text = "<".$node->nodeName;
					if($node->hasAttributes()){
						$text .= " ";
						foreach($node->attributes as $attr){
							$text .= $attr->nodeName."=\"".$attr->nodeValue."\"";
						}
					}	
					if(!empty($node->nodeValue)){
						$text .= ">".$node->nodeValue."</".$node->nodeName.">";
					}else
						$text .= " />";
					$textNode = $this->doc->createTextNode($text);
					$node->parentNode->replaceChild($textNode,$node);
				}
			}
			// If tag is in whitelist,
			// parse data
			else{
				if($node->hasAttributes()){
					if(!is_null($this->allowed_tags[$node->nodeName])){
						// Get allowed attributes for tag
						$allowed_attributes = explode("|", $this->allowed_tags[$node->nodeName]);
					}	
					// Remove invalid attributes
					// Keep ones that are whitelisted
					for($i=$node->attributes->length - 1; $i>=0; $i--){
						$attribute = $node->attributes->item($i);
						if(!in_array($attribute->name, $allowed_attributes))
							$node->removeAttributeNode($attribute);
					}
				}
				// Processing for special tags
				// quote, spoiler, img
				switch($node->nodeName){
					case "img":
						$imgNode = $this->createImageNode($node);
						$node->parentNode->replaceChild($imgNode, $node);
						break;
					case "quote":
						$quoteNode = $this->createQuoteNode($node);
						$node->parentNode->replaceChild($quoteNode, $node);
						break;
					case "spoiler":
						$spoilerNode = $this->createSpoilerNode($node);
						$node->parentNode->replaceChild($spoilerNode, $node);
						break;
				}
			}
			

		}
		$this->doc->removeChild($this->doc->firstChild);            
		return $this->cleanup($this->doc->saveHTML()); 
	} 

	private function createImageNode($node){
		$img_div = $this->doc->createElement("div");
		$img_div->setAttribute("class", "imgs");
		$src = explode("/", $node->getAttribute("src"));
		if($src[sizeof($src)-1] != "grey.gif") {
			$hash = $src[sizeof($src)-2];
			if($node->parentNode->nodeName == "quote"){
				$src[sizeof($src)-3] = 't';
				$filename = explode(".", $src[sizeof($src)-1]);
				$src[sizeof($src)-1] = $filename[0].".jpg";
				$sql = "SELECT thumb_width, thumb_height FROM UploadedImages WHERE sha1_sum = ?";
			}
			else
				$sql = "SELECT width, height FROM UploadedImages WHERE sha1_sum = ?";
			$statement = $this->pdo_conn->prepare($sql);
			$statement->execute(array($hash));
			$results = $statement->fetch();

			$img = $this->doc->createElement("img");
			$img->setAttribute("width", $results[0]);
			$img->setAttribute("height", $results[1]);
			$img->setAttribute("data-original", implode("/", $src));
			$img->setAttribute("src", "./templates/default/images/grey.gif");
			$img_div->appendChild($img);
			return $img_div;
		}
	}

	private function createQuoteNode($node){
		
		if($node->hasAttribute("msgid")) {
			$msgid = $node->getAttribute("msgid");
			// Verify the msgid attribute is in the right
			// format
			$msgid_pattern = "/(t|l),(\d+),(\d+)@(\d+)/";
			if(!preg_match($msgid_pattern, $msgid))
				$node->removeAttributeNode($node->attributes->item(0));
			else{
				$pattern = "/[,@]/";
				$msgid_array = preg_split($pattern, $msgid);
				// Check if the quote is from a link or a topic
				if ($msgid_array[0] == "t") { // Topic
					$sql_quote = "SELECT Users.username, Users.user_id, 
						Messages.posted FROM Messages LEFT JOIN 
						Users Using(user_id) WHERE Messages.topic_id=? 
						AND Messages.message_id=? AND Messages.revision_no = 0";
                } else { // Link
					$sql_quote = "SELECT Users.username, Users.user_id, LinkMessages.posted 
					FROM LinkMessages LEFT JOIN Users Using(user_id) 
					WHERE LinkMessages.message_id=? AND LinkMessages.link_id=? 
					AND LinkMessages.revision_no = 0";
                }

				// Get data about quote such as post date
				// and author
				$statement = $this->pdo_conn->prepare($sql_quote);
				$statement->execute(array($msgid_array[1], $msgid_array[2]));
				$results = $statement->fetch();

				// Quote header showing post information
				$quote_header = $this->doc->createElement("div", "From: ");
				$quote_header->setAttribute("class", "message-header");

				// Add profile link to quote header
				$quote_author = $this->doc->createElement("a", $results['username']);
				$quote_author->setAttribute("href", "./profile.php?id=".$results['user_id']);
			
				// Date of original quote
				$quote_time = $this->doc->createTextNode(" | Posted: ".date(DATE_FORMAT, $results['posted']));
				 
				// Append header child nodes to the parent node
				$quote_header->appendChild($quote_author);
				$quote_header->appendChild($quote_time);
			}
		}
		// Create parent div container for quote
		$quote_body = $this->doc->createElement("div");
		$quote_body->setAttribute("class", "quoted-message");

		// Append message header if one exists
		if(!is_null(@$quote_header)){
			$quote_body->setAttribute("msgid", $msgid);
			$quote_body->appendChild($quote_header);
		}
		// Transfer child nodes from original parent
		// to the new parent

		// Works, but leaves the the <quote> tags
		// Not sure why I can't enumerate the children
		$quote_body->appendChild($node->cloneNode(true));
		return $quote_body;
	}

	private function createSpoilerNode($node) {

		$count = Parser::getElementCount();
		// Create container for all the spoiler tags
		// This is the default state for spoilers
		$closed_span = $this->doc->createElement("span");
		$closed_span->setAttribute("class", "spoiler_closed");
		$closed_span->setAttribute("id", "s0_".$count);

		// Set the spoiler title text
		if($node->hasAttribute("caption"))
			$caption = $node->getAttribute("caption");
		else
			$caption="spoiler";

		// Closed Spoiler
		$onClose_span = $this->doc->createElement("span");
		$onClose_span->setAttribute("class", "spoiler_on_close");
		$onClose_bold = $this->doc->createElement("b", "<".$caption." />");
		$onClose_a = $this->doc->createElement("a");
		$onClose_a->setAttribute("class", "caption");
		$onClose_a->setAttribute("href", "#");
		$onClose_a->appendChild($onClose_bold);
		$onClose_span->appendChild($onClose_a);

		// Open Spoiler start tag
		$onOpen_span = $this->doc->createElement("span");
		$onOpen_span->setAttribute("class", "spoiler_on_open");
		$onOpen_a_startTag = $this->doc->createElement("a", "<".$caption.">");
		$onOpen_a_startTag->setAttribute("class", "caption");
		$onOpen_a_startTag->setAttribute("href", "#");
		$onOpen_span->appendChild($onOpen_a_startTag);

		// Spoiler body
		$onOpen_span->appendChild($node->cloneNode(True));

		// Open Spoiler end tag
		$onOpen_a_endTag = $this->doc->createElement("a", "</".$caption.">");
		$onOpen_a_endTag->setAttribute("class", "caption");
		$onOpen_a_endTag->setAttribute("href", "#");
		$onOpen_span->appendChild($onOpen_a_endTag);

		$closed_span->appendChild($onClose_span);
		$closed_span->appendChild($onOpen_span);

		$script = $this->doc->createElement("script", "$(document).ready(function(){llmlSpoiler($(\"#s0_".$count."\"));});");
		$script->setAttribute("type", "text/javascript");
		$onClose_span->appendChild($script);

		return $closed_span;
	}

	public static function getElementCount(){
		Parser::$element_count++;
		return Parser::$element_count;
	}

	private function cleanup($html){
		if($this->new_line)
			$html = str_replace("\n", "<br />", $html);
		$html = str_replace("&lt;p&gt;", "", $html);
		$html = str_replace("&lt;/p&gt;", "", $html);
		$html = str_replace("<html>", "", $html);
		$html = str_replace("</html>", "", $html);
		$html = str_replace("<body>", "", $html);
		$html = str_replace("</body>", "", $html);

		return $html;
	}
}