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
												ORDER BY posted ASC LIMIT 49 OFFSET ?");
		$statement->execute(array($this->topic_id, $offset));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$getMessageCount = $this->pdo_conn->prepare("SELECT DISTINCT(message_id) FROM Messages WHERE Messages.topic_id = ?;");
		$getMessageCount->execute(array($this->topic_id));
		$topic_count = $getMessageCount->rowCount();
		$this->page_count = intval($topic_count/50);
		if($topic_count % 49 != 0)
			$this->page_count += 1;
		for($i=0; $message_data_array=$statement->fetch(); $i++){
			$message_data[$i]['message_id'] = $message_data_array['message_id'];
			$message_data[$i]['user_id'] = $message_data_array['user_id'];
			$message_data[$i]['username'] = $message_data_array['username'];
			$message_data[$i]['message'] = $this->formatComments(override\makeURL(override\closeTags(
												str_replace("\n", "<br/>", 
													override\htmlentities($message_data_array['message'], $allowed_tags)))));
			$message_data[$i]['posted'] = $message_data_array['posted'];
			$message_data[$i]['revision_id'] = $message_data_array['revision_id'];
		}
		
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

}
?>
