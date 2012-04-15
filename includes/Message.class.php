<?php

Class Message{

	protected $pdo_conn;
	
	protected $message_id;
	
	protected $message;
	
	protected $user_id;
	
	protected $topic_id;
	
	protected $revision_id;
	
	protected $exists;
	
	function __construct(&$db_connection, $aMessage_id, $aUser_id=NULL){
		$this->pdo_conn = &$db_connection;
		$this->message_id = $aMessage_id;
		$sql = "SELECT Messages.topic_id,
					 Messages.revision_no as revision_no,
					 Messages.user_id,
					 Messages.message
				FROM
					Messages
				WHERE Messages.message_id = ?";
		if(!is_null($aUser_id))
			$sql .= " AND Messages.user_id=$aUser_id";
		$sql .= " ORDER BY Messages.revision_no DESC LIMIT 1";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->message_id));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		if($statement->rowCount())
			$this->exists = TRUE;
		$row = $statement->fetch();
		$this->setMessageData($row);
	}
	
	protected function setMessageData($data){
		$this->topic_id = $data['topic_id'];
		$this->user_id = $data['user_id'];
		$this->revision_id = $data['revision_no'];
		$this->message = $data['message'];
	}
	
	public function formatComments($string){
		$string = preg_replace("/\<quote /", "<div class=\"quoted-message\" ", $string);
		/*$attr = explode("\"", $string);
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
	
	public function getRevisions(){
		$sql = "SELECT Messages.revision_no, 
					   Messages.posted
			    FROM Messages
			    WHERE Messages.message_id = $this->message_id
			    ORDER BY revision_no DESC";
		$statement = $this->pdo_conn->query($sql);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		return $statement->fetchAll();
	}
	
	public function doesExist(){
		return $this->exists;
	}
	
	public function getMessage(){
		return $this->message;
	}
	
	public function getUserID(){
		return $this->user_id;
	}
	
	public function getTopicID(){
		return $this->topic_id;
	}
	
	public function getRevisionID(){
		return $this->revision_id;
	}
		
}

#Hack because PHP is lame and does not allow
#function overloading
class MessageRevision Extends Message{
	
	private $revision_no;
	
	private $topic_title;
	
	private $board_title;
	
	private $board_id;
	
	private $username;
	
	private $posted;
	
	function __construct(&$db_connection, $aMessage_id, $aRevision_no){
		$this->pdo_conn = &$db_connection;
		$this->message_id = $aMessage_id;
		$this->revision_no = $aRevision_no;														
		$statement = $this->pdo_conn->prepare("SELECT Messages.topic_id,
													 Messages.revision_no as revision_no,
													 Messages.user_id,
													 Messages.message,
													 Users.username,
													 Topics.title as topic_title,
													 Boards.title as board_title,
													 Boards.board_id
												FROM
													Users
												LEFT JOIN Messages USING(user_id)
												LEFT JOIN Topics USING(topic_id)
												LEFT JOIN Boards USING(board_id)
												WHERE Messages.message_id = ? AND Messages.revision_no = ?
												ORDER BY Messages.revision_no DESC LIMIT 1");
												
		$statement2 = $this->pdo_conn->prepare("SELECT Messages.posted FROM Messages WHERE Messages.message_id = ? AND revision_no=0");
		$statement->execute(array($this->message_id, $this->revision_no));
		$statement2->execute(array($this->message_id));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$statement2->setFetchMode(PDO::FETCH_ASSOC);
		
		if($statement->rowCount())
			$this->exists = TRUE;
			$row = $statement->fetch();
			$row2 = $statement2->fetch();
			$row = array_merge($row, $row2);
			$this->setMessageData($row);
	}
	
	protected function setMessageData($data){
		$this->topic_id = $data['topic_id'];
		$this->topic_title = $data['topic_title'];
		$this->board_title = $data['board_title'];
		$this->user_id = $data['user_id'];
		$this->revision_id = $data['revision_no'];
		$this->message = $data['message'];
		$this->posted = $data['posted'];
		$this->board_id = $data['board_id'];
		$this->username = $data['username'];
	}
	
	public function getUsername(){
		return $this->username;
	}
	
	public function getBoardTitle(){
		return $this->board_title;
	}
	
	public function getTopicTitle(){
		return $this->topic_title;
	}
	
	public function getPosted(){
		return $this->posted;
	}
	
	public function getBoardID(){
		return $this->board_id;
	}
}
?>
