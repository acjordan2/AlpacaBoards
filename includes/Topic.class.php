<?php
/*
 * Board.class.php
 * 
 * Copyright (c) 2012 Andrew Jordan
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

class Topic{

	private $pdo_conn;
	
	private $topic_id;
	
	private $topic_title;
	
	private $board_id;
	
	private $board_title;
	
	private $user_id;
	
	private $page_count;
	
	private $exists;
	
	function __construct(&$db_connection, $aTopic_id, $aUser_id){
		$this->pdo_conn = &$db_connection;
		$this->topic_id = $aTopic_id;
		$this->user_id = $aUser_id;
		$statement = $this->pdo_conn->prepare("SELECT Topics.title as topic_title,
													Boards.title as board_title,
													Boards.board_id
												FROM Topics
												JOIN Boards
													USING(board_id)
												WHERE
													Topics.topic_id = ?");
		$statement->execute(array($this->topic_id));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		if($statement->rowCount()){
			$this->exists = TRUE;
			$row = $statement->fetch();
			$this->topic_title = $row['topic_title'];
			$this->board_title = $row['board_title'];
			$this->board_id = $row['board_id'];
		}
	}
	
	public function getMessages($page=1){
		global $allowed_tags;
		$offset = 50*($page-1);
		$statement = $this->pdo_conn->prepare("SELECT Messages.message_id, 
													MAX(Messages.revision_no) as revision_id,
													Messages.user_id,
													Users.username,
													Messages.message,
													MIN(Messages.posted) as posted
												FROM Messages
												LEFT JOIN Users
													USING(user_id)
												WHERE
													Messages.topic_id = ?
												GROUP BY Messages.message_id DESC 
												ORDER BY posted ASC LIMIT 50 OFFSET ?");
		$statement->execute(array($this->topic_id, $offset));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$getMessageCount = $this->pdo_conn->prepare("SELECT DISTINCT(message_id) FROM Messages WHERE Messages.topic_id = ?;");
		$getMessageCount->execute(array($this->topic_id));
		$topic_count = $getMessageCount->rowCount();
		$this->page_count = intval($topic_count/50);
		if($topic_count % 50 != 0)
			$this->page_count += 1;
		for($i=0; $message_data_array=$statement->fetch(); $i++){
			/**
			if(preg_match_all("/<quote msgid=\"t,(\d+),(\d+)@(\d+)\">/", $message_data_array['message'], $info)){
				$sql2 = "SELECT Users.username, Messages.posted FROM Messages LEFT JOIN Users USING(user_id) 
					WHERE Messages.message_id = ? AND Messages.revision_no = 0";
				$statement2 = $this->pdo_conn->prepare($sql2);
				for($k=0; $k<sizeof($info[0]); $k++){
					$statement2->execute(array($info[2][$k]));
					$row = $statement2->fetchAll();
					$pattern[$k] = "/<quote msgid=\"t,(\d+),(\d+)@(\d+)\">/";
					$test[$k] = "<quote msgid=\"t,$1,$2@$3\" from=\"".@$row[0]['username']."\" posted=\"".@$row[0]['posted']."\">";
					$message_data_array['message'] = preg_replace($pattern[$k], $test[$k], $message_data_array['message'], 1);
				}
				//$message_data_array['message'] = preg_replace($pattern, $test, $message_data_array['message'], 1);
			}**/
			
			
			$message_data[$i]['message_id'] = $message_data_array['message_id'];
			$message_data[$i]['user_id'] = $message_data_array['user_id'];
			$message_data[$i]['username'] = $message_data_array['username'];
			$message_data[$i]['message'] = override\makeURL(override\embedVideo(override\closeTags(
												str_replace("\n", "<br/>\n", 
													override\htmlentities($message_data_array['message'], $allowed_tags)))));
			libxml_use_internal_errors(true);
			$doc = new DomDocument();
			$doc->loadHTML($message_data[$i]['message']);
			$sql3 = "SELECT Users.user_id, Users.username, Messages.posted
										FROM Messages LEFT JOIN Users Using(user_id)
										WHERE Messages.topic_id = ? AND Messages.message_id=? 
										AND Messages.revision_no = 0";
			$statement3 = $this->pdo_conn->prepare($sql3);
			foreach($doc->getElementsByTagName('quote') as $quote){
				$msgid = $quote->getAttribute('msgid');
				$msgid_array = explode(",", $msgid);
				$statement3->execute(array($msgid_array[1], $msgid_array[2]));
				$row = $statement3->fetch();
				
				$divnode = $doc->createElement("div", $quote->nodeValue);
				$divnode->setAttribute("class", "quoted-message");
				$divnode->setAttribute("msgid", $msgid);
				
				$quote->parentNode->replaceChild($divnode, $quote);
				//$doc->replaceChild($divnode, $quote);
				//$quote->setAttribute(new DOMAttr('class', "quoted-message"));
				
				$message_top = $doc->createElement("div");
				$message_top->setAttribute("class", "message-top");
				$message_top->appendChild($doc->createTextNode('text'));
				
				$doc->insertBefore($message_top, $quote->firstElement);
				
				$rawHTML = $doc->saveHTML();
				
				$content = preg_replace(array("/^\<\!DOCTYPE.*?<html><body>/si",
                                  "!</body></html>$!si"),
                            "",
                            $rawHTML);
                //print $content;
                $message_data[$i]['message'] = $content;
				
			}
			libxml_use_internal_errors(false);
			
			//$message_data[$i]['message'] = str_replace("</quote>", "</div>", $message_data[$i]['message']);
			$message_data[$i]['posted'] = $message_data_array['posted'];
			$message_data[$i]['revision_id'] = $message_data_array['revision_id'];
		}
		$this->updateHistory($message_data[sizeof($message_data)-1]['message_id'], $page);
		return $message_data;
	}
	
	public function postMessage($aMessage, $aMessage_id=null){
		
		if(is_null($aMessage_id)){
			$statement = $this->pdo_conn->prepare("INSERT INTO Messages ( user_id,
															  topic_id, 
															  message, 
															  posted)
										VALUES (:user_id,
												:topic_id,
												:message,
												".time().")");
			$data = array("user_id" =>  $this->user_id,
						  "topic_id" => $this->topic_id,
						  "message"  => $aMessage);
			if($statement->execute($data))
				return TRUE;
			else
				return FALSE;
		}else{
			$statement = $this->pdo_conn->prepare("SELECT MAX(revision_no) as revision_no FROM 
														Messages
													WHERE
														Messages.message_id = ?
													AND
														Messages.user_id = ?");
			$statement->execute(array($aMessage_id, $this->user_id));
			$row = $statement->fetch();
			//print_r($row);
			if($statement->rowCount() == 1){
				$revision_no = $row[0] + 1;
				$statement2 = $this->pdo_conn->prepare("INSERT INTO Messages ( message_id,
																		user_id,
																		topic_id,
																		message,
																		revision_no,
																		posted)
														VALUES( :message_id,
																:user_id,
															   :topic_id,
															   :message,
															   $revision_no,
															   ".time().")");
				$data = array( "message_id" => $aMessage_id,
							"user_id" =>  $this->user_id,
						  "topic_id" => $this->topic_id,
						  "message"  => $aMessage);
				if($statement2->execute($data))
					return TRUE;
				else
					return FALSE;
			}else
				return FALSE;
		}							
	}
	
	public function updateHistory($last_message, $page){
		if(!is_null($last_message)){
		$sql = "INSERT INTO TopicHistory (topic_id, user_id, message_id, date, page)
			VALUES (:topic_id, $this->user_id, $last_message, ".time().", :page)
			ON DUPLICATE KEY UPDATE message_id= IF(message_id < $last_message, $last_message, message_id), date=".time().", page= IF(page < :page2, :page3, page)";
		$statement = $this->pdo_conn->prepare($sql);
		#PDO limitimation: cant call the same named place holder more than once per query
		$statement->execute(array('topic_id' => $this->topic_id, 'page'=>$page, 'page2'=>$page, 'page3'=>$page));
		return TRUE;
	}	
	}
	
	public function formatComments($string){	
		$string = preg_replace("/\<quote /", "<div class=\"quoted-message\" ", $string);
		/*
		$attr = explode("\"", $string);
		$params = $attr[3];
		$message_id = explode(",", $attr[3]);
		$message_id = explode("@", $message_id[2]);
		$message_id = $message_id[0];
		$statement = $this->pdo_conn->prepare("SELECT Messages.user_id,
													  Users.username,
													  Messages.posted
												FROM Messages
												LEFT JOIN Users
												USING(user_id)
												WHERE Messages.message_id = ?
												AND revision_no = 0");
		$statement->execute(array($message_id));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		$string = preg_replace("/<div class=\"quoted-message\" msgid=\"t,1,13@0\"\\>/", "<div class=\"quoted-message\" msgid=\"t,1,13@0\"\>/> From: ".$row['username']." | Posted: ".$row['posted'], $string);
		*/
		$string = preg_replace("/\<\/quote>/", "</div>", $string);
		return $string;
	}
	
	public function doesExist(){
		return $this->exists;
	}
	
	public function getPageCount(){
		return $this->page_count;
	}
	
	public function getTopicTitle(){
		return $this->topic_title;
	}
	
	public function getBoardTitle(){
		return $this->board_title;
	}
	
	public function getBoardID(){
		return $this->board_id;
	}
	
	public function getReaders(){
		$sql = "SELECT COUNT(topic_id) FROM TopicHistory
					WHERE topic_id=? AND date >= ".(time() - 60*15);
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->topic_id));
		$row = $statement->fetch();
		return $row[0];
	}
	
	public function pinTopic(){
		$sql = "INSERT INTO StickiedTopics (topic_id, user_id, created)
				VALUES(?, ?, ".time().")";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->topic_id, $this->user_id));
	}

}
?>
