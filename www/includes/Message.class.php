<?php

Class Message{

	protected $pdo_conn;
	
	protected $message_id;
	
	protected $message;
	
	protected $user_id;
	
	protected $topic_id;
	
	protected $revision_id;
	
	protected $exists;
	
	protected $link_id;
	
	protected $link_title;

    protected $message_deleted;

	protected $user_avatar;
	
	function __construct(&$db_connection, $aMessage_id, $aUser_id=NULL, $is_link=FALSE){
		$this->pdo_conn = &$db_connection;
		$this->message_id = $aMessage_id;
		$this->is_link = $is_link;
		if($is_link == TRUE){
			$sql = "SELECT LinkMessages.link_id,
						 LinkMessages.revision_no as revision_no,
						 LinkMessages.user_id,
						 LinkMessages.message
					FROM
						LinkMessages
					WHERE LinkMessages.message_id = ?";
			if(!is_null($aUser_id))
				$sql .= " AND LinkMessages.user_id=$aUser_id";
			$sql .= " ORDER BY LinkMessages.revision_no DESC LIMIT 1";			
		}else{
			$sql = "SELECT Messages.topic_id,
						 Messages.revision_no as revision_no,
						 Messages.user_id,
						 Messages.message,
                         Messages.deleted
					FROM
						Messages
					WHERE Messages.message_id = ?";
			if(!is_null($aUser_id))
				$sql .= " AND Messages.user_id=$aUser_id";
			$sql .= " ORDER BY Messages.revision_no DESC LIMIT 1";
		}
		
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->message_id));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		if($statement->rowCount())
			$this->exists = TRUE;
		$row = $statement->fetch();
		$this->setMessageData($row);
	}
	
	protected function setMessageData($data){
		if($this->is_link)
			$this->link_id = $data['link_id'];

		else
			$this->topic_id = $data['topic_id'];
		$this->user_id = $data['user_id'];
		$this->revision_id = $data['revision_no'];
        $this->message_deleted = $data['deleted'];
        if($this->message_deleted == 1) {
            $this->message = $GLOBALS['locale_messages']['message']['deleted'];
        } elseif ($this->message_deleted == 2) {
            $this->message = $GLOBALS['locale_messages']['message']['deleted_moderator'];
        } else {
		  $this->message = $data['message'];
        }
	}
		
	public function getRevisions(){
		
		if($this->is_link){
		$sql = "SELECT LinkMessages.revision_no, 
					   LinkMessages.posted
			    FROM LinkMessages
			    WHERE LinkMessages.message_id = $this->message_id
			    ORDER BY revision_no DESC";
		}else{
			$sql = "SELECT Messages.revision_no, 
						   Messages.posted
					FROM Messages
					WHERE Messages.message_id = $this->message_id
					ORDER BY revision_no DESC";
		}
		$statement = $this->pdo_conn->query($sql);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		return $statement->fetchAll();
	}
	
	public function getLinkID(){
		return $this->link_id;
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

    public function isDeleted(){
        return $this->message_deleted;
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

    protected $message_deleted;

    protected $message;
	
	protected $is_link;
	
	function __construct(&$db_connection, $aMessage_id, $aRevision_no, $is_link){
		$this->pdo_conn = &$db_connection;
		$this->message_id = $aMessage_id;
		$this->revision_no = $aRevision_no;
		$this->is_link = $is_link;
		if($is_link){
			$sql = "SELECT LinkMessages.link_id,
						 LinkMessages.revision_no as revision_no,
						 LinkMessages.user_id,
						 LinkMessages.message,
						 Users.username,
						 Links.title as link_title
					FROM 
						Users
					LEFT JOIN LinkMessages USING(user_id)
					LEFT JOIN Links USING(link_id)
					WHERE LinkMessages.message_id = ? AND LinkMessages.revision_no = ?
					ORDER BY LinkMessages.revision_no DESC LIMIT 1";
			$sql2 = "SELECT LinkMessages.posted FROM LinkMessages WHERE LinkMessages.message_id = ? AND LinkMessages.revision_no=0";
		}
		else{
			$sql = "SELECT Messages.topic_id,
						 Messages.revision_no as revision_no,
						 Messages.user_id,
						 Messages.message,
                         Messages.deleted,
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
					ORDER BY Messages.revision_no DESC LIMIT 1";
			$sql2 = "SELECT Messages.posted FROM Messages WHERE Messages.message_id = ? AND revision_no=0";
		}
		$statement = $this->pdo_conn->prepare($sql);
												
		$statement2 = $this->pdo_conn->prepare($sql2);
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
		
		if($this->is_link){
			$this->link_id = $data['link_id'];
			$this->link_title = $data['link_title'];
		}else{
			$this->topic_id = $data['topic_id'];
			$this->topic_title = $data['topic_title'];
			$this->board_title = $data['board_title'];
			$this->board_id = $data['board_id'];
		}
		
		$this->user_id = $data['user_id'];
		$this->revision_id = $data['revision_no'];
        $this->message_deleted = $data['deleted'];
        if ($data['deleted'] == 1){
            $this->message = $GLOBALS['locale_messages']['message']['deleted'];
        } elseif ($data['deleted'] == 2) {
            $this->message = $GLOBALS['locale_messages']['message']['deleted_moderator'];
        } else {
          $this->revision_id = 0;
		  $this->message = $data['message'];
        }
		$this->posted = $data['posted'];
		$this->username = $data['username'];
		/*$this->user_avatar = array("height" => $data['thumb_height'],
								   "width" => $data['thumb_width'],
								   "avatar" => $data['sha1_sum']."/".urlencode(substr($data['filename']);
		*/
		$tmp_user = new User($this->pdo_conn, $this->user_id);
		$this->user_avatar = $tmp_user->getAvatar();
	}

    public function deleteMessage($action)
    {
        if ($action != 1 && $action != 2) {
            return false;
        } else {
            $sql = "UPDATE Messages SET deleted = ? WHERE message_id = ".$this->message_id;
            $statement = $this->pdo_conn->prepare($sql);
            $this->message = "[This message has been deleted]";
            return $statement->execute(array($action));
        }
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
	
	public function getLinkTitle(){
		return $this->link_title;
	}

	public function getAvatar(){
		return $this->user_avatar;
	}
	
    public function getMessage(){
        return $this->message;
    }

    public function isDeleted(){
        return $this->message_deleted;
    }
    
}
?>
