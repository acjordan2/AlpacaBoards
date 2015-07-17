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

/**
* Parse HTML and remove malicous HTML tags and attriubutes as well
* as provide support for custom markup.
*
*/
class Parser
{

    private $_newline;

    private $pdo_conn;

    private $_site;

    private static $element_count;

    /**
    * Create new Parser Object and set allowed HTML tags
    *
    * @param PDO $db Database connection object
    */
    public function __construct($site = null)
    {
        libxml_use_internal_errors(true);
        // White list of allowed tags => attributes
        $this->allowed_tags = array("b" => null,
                                    "i" => null,
                                    "u" => null,
                                    "pre" => null,
                                    "html" => null,
                                    "body" => null,
                                    "#text" => null,
                                    "p" => null, // allowed for a work around
                                    "img"    => "src",
                                    "quote" => "msgid",
                                    "spoiler" => "caption");
        $this->doc = new DomDocument();
        $this->pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_site =  new Site();
    }

    /**
    * Parse an HTML snippet and remove non-white listed tags.
    * Turn custom markup tags (ie <quote>. <spoiler>) into 
    * valid HTML for the browser
    * 
    * @param string  $html             HTML code to be parsed
    * @param boolean $convert_newlines Replace instances of \n to <br/>
    *
    * @return DOMElement
    */
    public function parse($html, $convert_newlines = true)
    {
        $this->_newline = $convert_newlines;
        $p_count = 0;

        // Initizlize Variables
        $allowed_attributes = "";
        if ($html == null) {
            $html = " ";
        }

        $html = preg_replace_callback('/[\x{80}-\x{10FFFF}]/u', function($match) {
            list($utf8) = $match;
            $entity = mb_convert_encoding($utf8, 'HTML-ENTITIES', 'UTF-8');
            return $entity;
            }, $html);

        $this->doc->loadHTML($html);
        // Create a recurisve iterator
        // to get all nodes in a document
        // starting with the children
        $dit = new RecursiveIteratorIterator(
            new RecursiveDOMIterator($this->doc),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($dit as $node) {
            // If tag is not in whitelist,
            // create a textnode with data
            // and attributes from the tag
            if (!array_key_exists($node->nodeName, $this->allowed_tags)) {
                // Ignore CDATA fields. Not necessary
                // since all invalid tags will be
                // entity encoded
                if ($node->nodeName != "#cdata-section") {
                    $text = "<".$node->nodeName;
                    if ($node->hasAttributes()) {
                        $text .= " ";
                        foreach ($node->attributes as $attr) {
                            $text .= $attr->nodeName."=\"".$attr->nodeValue."\"";
                        }
                    }
                    if (!empty($node->nodeValue)) {
                        $text .= ">".$node->nodeValue."</".$node->nodeName.">";
                    } else {
                        $text .= " />";
                    }
                    $textNode = $this->doc->createTextNode($text);
                    $node->parentNode->replaceChild($textNode, $node);
                    print $node->value;
                }
            } else { // If tag is in whitelist, parse data
                if ($node->hasAttributes()) {
                    if (!is_null($this->allowed_tags[$node->nodeName])) {
                        // Get allowed attributes for tag
                        $allowed_attributes = explode(
                            "|",
                            $this->allowed_tags[$node->nodeName]
                        );
                    }

                    // Remove invalid attributes
                    // Keep ones that are whitelisted
                    for ($i=$node->attributes->length - 1; $i>=0; $i--) {
                        $attribute = $node->attributes->item($i);
                        if (!in_array($attribute->name, $allowed_attributes)) {
                            $node->removeAttributeNode($attribute);
                        }
                    }
                }
                // Workaround - if a text node comes before markup
                // the entire block will be converted to a textnode
                // instead of parsed. Adding the <p> tag to allowed tags
                // and remove margins to prevent line breaks fixes the problem.
                if ($node->nodeName == "p") {
                    //$node->setAttribute("style", "margin-bottom:0px;margin-top:0px");
                }
                // Processing for special tags
                // quote, spoiler, img
                switch ($node->nodeName) {
                    case "img":
                        $imgNode = $this->_createImageNode($node);
                        $node->parentNode->replaceChild($imgNode, $node);
                        break;
                    case "quote":
                        $quoteNode = $this->_createQuoteNode($node);
                        $node->parentNode->replaceChild($quoteNode, $node);
                        break;
                    case "spoiler":
                        $spoilerNode = $this->_createSpoilerNode($node);
                        $node->parentNode->replaceChild($spoilerNode, $node);
                        break;
                }
            }
            

        }
        $this->doc->removeChild($this->doc->firstChild);
        return $this->_cleanup($this->doc->saveHTML());
    }

    /**
    * Parse image tags to put them in a div container 
    * and add lazy loading fuctionality
    * 
    * @param DOMElement $node image node
    *
    * @return DOMElement
    */
    private function _createImageNode($node)
    {
        $img_div = $this->doc->createElement("div");
        $img_div->setAttribute("class", "imgs");

        $src = explode("/", $node->getAttribute("src"));
        if ($src[sizeof($src)-1] != "grey.gif") {
            $hash = $src[sizeof($src)-2];
            $size = $src[sizeof($src)-3];
            $filename_array = explode(".", $src[sizeof($src)-1]);
            $extension = array_pop($filename_array);
            $filename = implode($filename_array, ".");
            if ($node->parentNode->nodeName == "quote") {
                $size = 't';
                $sql = "SELECT thumb_width, thumb_height 
                    FROM UploadedImages WHERE sha1_sum = ?";
                $extension = "jpg";
            } else {
                $sql = "SELECT width, height FROM UploadedImages WHERE sha1_sum = ?";
            }
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array($hash));
            $results = $statement->fetch();

            if($statement->rowCount() === 0) {
                $text = "<".$node->nodeName;
                if ($node->hasAttributes()) {
                    $text .= " ";
                    foreach ($node->attributes as $attr) {
                        $text .= $attr->nodeName."=\"".$attr->nodeValue."\"";
                    }
                }
                if (!empty($node->nodeValue)) {
                    $text .= ">".$node->nodeValue."</".$node->nodeName.">";
                } else {
                    $text .= " />";
                }
                $img_div = $this->doc->createTextNode($text);

            } else {

                $img_a = $this->doc->createElement('a');
                $img_a->setAttribute("href", $this->_site->getBaseURL()."/imagemap.php?hash=".htmlentities($hash));

                $new_src = array(
                    $this->_site->getImagePath(),
                    $size,
                    $hash,
                    $filename.".".$extension
                );
                                

                $img = $this->doc->createElement("img");
                $img->setAttribute("width", $results[0]);
                $img->setAttribute("height", $results[1]);
                $img->setAttribute("data-original", implode("/", $new_src));
                $img->setAttribute("src", $this->_site->getBaseURL()."/templates/default/images/grey.gif");
                $img_a->appendChild($img);
                $img_div->appendChild($img_a);
            }
            return $img_div;
        }
    }

    /**
    * Turn quote tags (<quote>) into valid HTML for the browser
    * 
    * @param DOMElement $node quote node
    *
    * @return DOMElement
    */
    private function _createQuoteNode($node)
    {
        $results = null;
        if ($node->hasAttribute("msgid")) {
            $msgid = $node->getAttribute("msgid");
            // Verify the msgid attribute is in the right
            // format
            $msgid_pattern = "/(t|l),(\d+),(\d+)@(\d+)/";
            if (!preg_match($msgid_pattern, $msgid)) {
                $node->removeAttributeNode($node->attributes->item(0));
            } else {
                $tags = array();
                $pattern = "/[,@]/";
                $msgid_array = preg_split($pattern, $msgid);
                // Check if the quote is from a link or a topic
                if ($msgid_array[0] == "t") { // Topic
                    $sql_quote = "SELECT Users.username, Users.user_id, 
                        Messages.posted, Messages.deleted FROM Messages LEFT JOIN 
                        Users Using(user_id) WHERE Messages.topic_id=? 
                        AND Messages.message_id=? AND Messages.revision_no = 0";
                
                    $sql_checkAnon = "SELECT TopicalTags.title  FROM Tagged 
                        LEFT JOIN TopicalTags USING(tag_id)
                        WHERE Tagged.data_id = :topic_id
                        AND Tagged.type = 1 AND TopicalTags.title = 'Anonymous'";
                    
                    $statement_checkAnon = $this->pdo_conn->prepare($sql_checkAnon);
                    $statement_checkAnon->bindParam("topic_id", $msgid_array[1]);
                    $statement_checkAnon->execute();
                    $tags = $statement_checkAnon->fetch();
                } elseif ($msgid_array[0] == "l") { // Link
                    $sql_quote = "SELECT Users.username, Users.user_id, 
                        LinkMessages.posted FROM LinkMessages LEFT JOIN 
                        Users Using(user_id) WHERE LinkMessages.link_id=? 
                        AND LinkMessages.message_id=? AND 
                        LinkMessages.revision_no = 0";
                }

                // Get data about quote such as post date
                // and author
                $statement = $this->pdo_conn->prepare($sql_quote);
                $statement->execute(array($msgid_array[1], $msgid_array[2]));
                $results = $statement->fetch();
                if (count($tags) > 0 && $tags != null) {
                    $sql_getPosterCount = "SELECT DISTINCT(user_id)
                        FROM Messages WHERE topic_id = :topic
                        ORDER BY message_id";
                    $statement_getPosterCount = $this->pdo_conn->prepare($sql_getPosterCount);
                    $statement_getPosterCount->bindParam("topic", $msgid_array[1]);
                    $statement_getPosterCount->execute();
                    $results_anon = $statement_getPosterCount->fetchAll(PDO::FETCH_COLUMN, 0);
                    $human = array_search($results['user_id'], $results_anon)+1;
                    $results['username'] = "Human #".$human;
                    $results['user_id'] = $human * -1;
                }

                // Quote header showing post information
                $quote_header = $this->doc->createElement("div", "From: ");
                $quote_header->setAttribute("class", "message-header");

                // Add profile link to quote header
                $quote_author = $this->doc->createElement("a", $results['username']);
                $quote_author->setAttribute(
                    "href",
                    "./profile.php?user=".$results['user_id']
                );
            
                // Date of original quote
                $quote_time = $this->doc->createTextNode(
                    " | Posted: ".date(DATE_FORMAT, $results['posted'])
                );
                 
                // Append header child nodes to the parent node
                $quote_header->appendChild($quote_author);
                $quote_header->appendChild($quote_time);
            }
        }
        // Create parent div container for quote
        $quote_body = $this->doc->createElement("div");
        $quote_body->setAttribute("class", "quoted-message");

        // Append message header if one exists
        if (!is_null(@$quote_header)) {
            $quote_body->setAttribute("msgid", $msgid);
            $quote_body->appendChild($quote_header);
        }
        // Transfer child nodes from original parent
        // to the new parent

        if ($results['deleted'] == 2) {
            // If original message was deleted by a moderator
            // don't show quote
            $textNode = $this->doc->createTextNode($GLOBALS['locale_messages']['message']['deleted_moderator']);
            $quote_body->appendChild($textNode);
        } else {
            // Works, but leaves the the <quote> tags
            // Not sure why I can't enumerate the children
            $quote_body->appendChild($node->cloneNode(true));
        }
        return $quote_body;
    }

    /**
    * Turn spoiler tags (<spoiler>) into valid HTML for the browser
    * 
    * @param DOMElement $node spoiler node
    *
    * @return DOMElement
    */
    private function _createSpoilerNode($node)
    {
        $count = Parser::getElementCount();
        // Create container for all the spoiler tags
        // This is the default state for spoilers
        $closed_span = $this->doc->createElement("span");
        $closed_span->setAttribute("class", "spoiler_closed");
        $closed_span->setAttribute("id", "s0_".$count);

        // Set the spoiler title text
        if ($node->hasAttribute("caption")) {
            $caption = $node->getAttribute("caption");
        } else {
            $caption="spoiler";
        }

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
        $onOpen_span->appendChild($node->cloneNode(true));

        // Open Spoiler end tag
        $onOpen_a_endTag = $this->doc->createElement("a", "</".$caption.">");
        $onOpen_a_endTag->setAttribute("class", "caption");
        $onOpen_a_endTag->setAttribute("href", "#");
        $onOpen_span->appendChild($onOpen_a_endTag);

        $closed_span->appendChild($onClose_span);
        $closed_span->appendChild($onOpen_span);

        $script = $this->doc->createElement(
            "script",
            "$(document).ready(function(){llmlSpoiler($(\"#s0_".$count."\"));});"
        );
        $script->setAttribute("type", "text/javascript");
        $onClose_span->appendChild($script);

        return $closed_span;
    }

    /**
    * Count the number of custom elements made by the parser. 
    * Currently only used for spoilers
    * 
    * @return int
    */
    public static function getElementCount()
    {
        Parser::$element_count++;
        return Parser::$element_count;
    }

    /**
    * Clean up HTML code and remove artifacts left by parser
    *
    * @param string $html Final HTML snippet after being parsed
    *
    * @return string
    */
    private function _cleanup($html)
    {
        if ($this->_newline) {
            $html = str_replace("\n", "<br />", $html);
        }

        $html = str_replace("<html>", "", $html);
        $html = str_replace("</html>", "", $html);
        $html = str_replace("<body>", "", $html);
        $html = str_replace("</body>", "", $html);
        
        // Remove enclosing <p> put there by DomDocument
        // Fix for spoiler not hiding images when there
        // body starts with a text node
        if (substr($html, 0, 3) == "<p>") {
            $html = substr($html, 3, strlen($html) - 13);
        }

        return $html;
    }

    /**
     * Map images and quotes when messages are posted in order to keep track
     * of when and where images are used and popular quotes. 
     * 
     * @param  string $message The message being posted
     * @param  int $user_id    The user id of the user submitting the message
     * 
     * @return boolean         True if suscessful
     */
    public function map($message, $user_id, $topic_id)
    {
        $this->doc->loadHTML($message);
        $imgs = $this->doc->getElementsByTagName('img');

        foreach ($imgs as $img) {
            if (!$this->_recursiveCheckParent($img, "quote")) {
                $src = explode("/", $img->getAttribute("src"));
                $hash = $src[sizeof($src)-2];
                
                $sql_getImageId = "SELECT image_id FROM UploadedImages WHERE sha1_sum = ?";
                $statement_getImageId = $this->pdo_conn->prepare($sql_getImageId);
                $statement_getImageId->execute(array($hash));
                $image_id = $statement_getImageId->fetch();
                
                $sql_imageMap = "INSERT INTO ImageMap (user_id, image_id, topic_id)
                    VALUES ($user_id, ".$image_id[0].", ?)";
                $statement_imageMap = $this->pdo_conn->prepare($sql_imageMap);
                $statement_imageMap->execute(array($topic_id));
            }
        }
    }

    /**
     * Recursively check if any parent node of an element matches
     * 
     * @param  DOMElement $node   Child node
     * @param  string $parentName Name of the paerent node to match
     * 
     * @return boolean            True if there is a match of any parent node
     */
    private function _recursiveCheckParent($node, $parentName)
    {
        $pnode = $node;
        while (is_object($pnode->parentNode)) {
            $pnode = $pnode->parentNode;
            if ($pnode->nodeName == $parentName) {
                return true;
                break;
            }
        }
        return false;
    }
}
