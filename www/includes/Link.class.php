<?php
/*
 * profile.php
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
 
class Link{
	
	private $pdo_conn;
	
	private $link_id;
	
	private $user_id;
	
	function __construct(&$db, $aUserID=null, $aLinkID=null){
		$this->pdo_conn = &$db;
		if($aUserID != null){
			$this->user_id = $aUserID;
		}
		if(!is_null($aLinkID))
			$this->link_id = $aLinkID;
	}
	
	public function getLinkList($orderby=1){
		$order = "";
		$where = "";
		switch($orderby){
			case 1:
				$order="rank DESC, rating DESC";
				break;
			case 2:
				$order="created DESC";
				break;
			case 3:
				$order="rank DESC, rating DESC";
				$where="WHERE Links.created > '".(time()-(60*60*24*7))."'";
				break;
			case 4:
				$order="created ASC";
		}
		$sql = "SELECT Users.username, Links.link_id, Links.user_id, Links.title, Links.url, COUNT(LinkVotes.vote) AS NumberOfVotes, 
				SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating, 
				Links.created 
				FROM Users LEFT JOIN
				Links USING(user_id) 
				LEFT JOIN LinkVotes USING(link_id) 
				$where 
				GROUP BY link_id ORDER BY $order";
		$statement = $this->pdo_conn->query($sql);
		$link_data = array();
		for($i=0; $link_data_array=$statement->fetch(); $i++){
			if($link_data_array['link_id'] != null){
				$link_data[$i]['link_id'] = $link_data_array['link_id'];
				$link_data[$i]['user_id'] = $link_data_array['user_id'];
				$link_data[$i]['title'] = override\htmlentities($link_data_array['title']);
				$link_data[$i]['created'] = $link_data_array['created'];
				$link_data[$i]['NumberOfVotes'] = $link_data_array['NumberOfVotes'];
				$link_data[$i]['rank'] = $link_data_array['rank'];
				$link_data[$i]['rating'] = $link_data_array['rating'];
				$link_data[$i]['username'] = $link_data_array['username'];
			}
		}
		return $link_data;
	}
	
	public function getLink(){
		global $allowed_tags;
		$sql = "SELECT Users.username, Links.user_id, Links.title, Links.url, Links.description, Links.created,
		COUNT(LinkVotes.vote) AS NumberOfVotes, SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating  
		FROM Links LEFT JOIN LinkVotes using(link_id) LEFT JOIN Users ON Links.user_id=Users.user_id WHERE link_id = ?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id));
		if($statement->rowCount() == 1){
			$sql2 = "SELECT LinkCategories.name FROM LinksCategorized 
						JOIN LinkCategories ON (LinksCategorized.category_id = LinkCategories.category_id)  WHERE LinksCategorized.link_id = ?";
			$statement2 = $this->pdo_conn->prepare($sql2);
			$statement2->execute(array($this->link_id));
			
			$parser = new Parser($this->pdo_conn);
			
			$this->updateHistory();
			$row = $statement->fetch();
			$row['url2'] = override\htmlentities($row['url']);
			$row['url'] = override\makeURL(htmlentities($row['url']));
			$row['title'] = htmlentities($row['title']);
			$row['raw_description'] = $row['description'];
			$parser->loadHTML($GLOBALS['pre_html_purifier']->purify($row['description']));
			$parser->parse();
			$row['description'] = $GLOBALS['post_html_purifier']->purify($parser->getHTML());
			$message_script = str_replace("<safescript type=\"text/javascript\">", "<script type=\"text/javascript\">", override\makeURL(str_replace("\n", "<br/>\n",$row['description'])));
			$message_script = str_replace("</safescript>", "</script>", $message_script);
			$row['description'] = str_replace("</script>&lt;/span&gt;", "</script>", $message_script);
			
			$row['code'] = dechex($this->link_id);
			$row['link_id'] = $this->link_id;
			$row['hits'] = $this->getHits();
			$row['categories'] = "";
			while($cats = $statement2->fetch()){
				$row['categories'] .=  $cats['name'].", ";
			}
			$row['categories'] = substr($row['categories'], 0, (strlen($row['categories'])-2));
			return $row;
		}
		else
			return FALSE;
	}
	
	private function updateHistory(){
		if(!is_null($this->user_id)){
		$sql = "INSERT INTO LinkHistory (link_id, user_id, date)
						VALUES (?, $this->user_id, ".time().")
						ON DUPLICATE KEY UPDATE date=".time();
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id));
		return TRUE;
		}else
			return FALSE;
	}
	
	public function getMessages($page=1){
		global $allowed_tags;
		$offset = 50*($page-1);
		$statement = $this->pdo_conn->prepare("SELECT LinkMessages.message_id, 
													MAX(LinkMessages.revision_no) as revision_no,
													LinkMessages.user_id,
													Users.username,
													LinkMessages.message,
													MIN(LinkMessages.posted) as posted
												FROM LinkMessages
												LEFT JOIN Users
													USING(user_id)
												WHERE
													LinkMessages.link_id = ?
												GROUP BY LinkMessages.message_id DESC 
												ORDER BY posted ASC LIMIT 50 OFFSET ?");
		$statement->execute(array($this->link_id, $offset));
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$getMessageCount = $this->pdo_conn->prepare("SELECT DISTINCT(message_id) FROM LinkMessages WHERE LinkMessages.link_id = ?;");
		$getMessageCount->execute(array($this->link_id));
		$topic_count = $getMessageCount->rowCount();
		$this->page_count = intval($topic_count/50);
		if($topic_count % 50 != 0)
			$this->page_count += 1;
		$message_data = array();
		$parser = new Parser($this->pdo_conn);
		for($i=0; $message_data_array=$statement->fetch(); $i++){
			$tmp_user = new User($this->pdo_conn, $message_data_array['user_id']);
			$message_data[$i]['message_id'] = $message_data_array['message_id'];
			$message_data[$i]['user_id'] = $message_data_array['user_id'];
			$message_data[$i]['username'] = $message_data_array['username'];
			$message_data[$i]['avatar'] = $tmp_user->getAvatar();
			$parser->loadHTML($GLOBALS['pre_html_purifier']->purify($message_data_array['message']));
			$parser->parse();
			$message_content = $GLOBALS['post_html_purifier']->purify($parser->getHTML());
			
			//$message_script = str_replace("<safescript type=\"text/javascript\">", "<script type=\"text/javascript\">", override\makeURL(str_replace("\n", "<br/>\n",$message_content)));
			//$message_script = str_replace("</safescript>", "</script>", $message_script);
			$message_data[$i]['message'] = Parser::cleanUp($message_content);

			//str_replace("</script>&lt;/span&gt;", "</script>", $message_script);
			
			//$message_data[$i]['message'] = $this->formatComments(override\makeURL(override\closeTags(
											//	str_replace("\n", "<br/>\n", 
													//override\htmlentities($message_data_array['message'], $allowed_tags)))));
			$message_data[$i]['posted'] = $message_data_array['posted'];
			$message_data[$i]['revision_no'] = $message_data_array['revision_no'];
		}
		
		return $message_data;
	}
	
	private function getHits(){
		$sql = "SELECT COUNT(LinkHistory.link_id) as count FROM LinkHistory
					WHERE LinkHistory.link_id = ?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id));
		$row = $statement->fetch();
		return $row['count'];
	}
	
	public function postMessage($aMessage, $aMessage_id=null){
		
		if(is_null($aMessage_id)){
			$statement = $this->pdo_conn->prepare("INSERT INTO LinkMessages ( user_id,
															  link_id, 
															  message, 
															  posted)
										VALUES (:user_id,
												:link_id,
												:message,
												".time().")");
			$data = array("user_id" =>  $this->user_id,
						  "link_id" => $this->link_id,
						  "message"  => $aMessage);
			if($statement->execute($data))
				return TRUE;
			else
				return FALSE;
		}else{
			$statement = $this->pdo_conn->prepare("SELECT MAX(revision_no) as revision_no FROM 
														LinkMessages
													WHERE
														LinkMessages.message_id = ?
													AND
														LinkMessages.user_id = ?");
			$statement->execute(array($aMessage_id, $this->user_id));
			$row = $statement->fetch();
			//print_r($row);
			if($statement->rowCount() == 1){
				$revision_no = $row[0] + 1;
				$statement2 = $this->pdo_conn->prepare("INSERT INTO LinkMessages ( message_id,
																		user_id,
																		link_id,
																		message,
																		revision_no,
																		posted)
														VALUES( :message_id,
																:user_id,
															   :link_id,
															   :message,
															   $revision_no,
															   ".time().")");
				$data = array( "message_id" => $aMessage_id,
							"user_id" =>  $this->user_id,
						  "link_id" => $this->link_id,
						  "message"  => $aMessage);
				if($statement2->execute($data))
					return TRUE;
				else
					return FALSE;
			}else
				return FALSE;
		}							
	}
	
	public function vote($vote){
		$statement = $this->pdo_conn->prepare("INSERT INTO LinkVotes (link_id, vote, user_id, created)
							VALUES(?, ?, $this->user_id, ".time().")
							ON DUPLICATE KEY UPDATE vote=?");
		$statement->execute(array($this->link_id, $vote, $vote));
		if($statement->rowCount() == 1)
			return TRUE;
	}
	
	public static function getCategories(&$db){
		$sql = "SELECT LinkCategories.name, LinkCategories.category_id FROM LinkCategories";
		$statement = $db->prepare($sql);
		$statement->execute();
		return $statement->fetchAll();
	}
	
	public function addLink($request){
		$allowed_categories = self::getCategories($this->pdo_conn);
		if(is_null(@$request['lurl']))
			$request['lurl'] = "";
		$k = 0;
		for($i=0; $i<sizeof($allowed_categories); $i++){
			if(isset($request[$allowed_categories[$i][0]])){
				if($request[$allowed_categories[$i][0]] == 1){
					 $cats[$k] = $allowed_categories[$i][1];
					 $k++;
					 print($k);
				}			
			}
		}
		$sql = "INSERT INTO Links (user_id, title, url, description, created)
			VALUES($this->user_id, ?, ?, ?, ".time().")";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($request['title'], $request['lurl'], $request['description']));
		$this->link_id = $this->pdo_conn->lastInsertId();
		for($i=0; $i<sizeof($cats); $i++){
			$sql2 = "INSERT INTO LinksCategorized (link_id, category_id)
						VALUES($this->link_id, ".$cats[$i].")";
			$statement2 = $this->pdo_conn->query($sql2);
		}
		return true;
	}
	
	public function updateLink($request){
		$allowed_categories = self::getCategories($this->pdo_conn);
		if(is_null(@$request['lurl']))
			$request['lurl'] = "";
		$k = 0;
		for($i=0; $i<sizeof($allowed_categories); $i++){
			if(isset($request[$allowed_categories[$i][0]])){
				if($request[$allowed_categories[$i][0]] == 1){
					 $cats[$k] = $allowed_categories[$i][1];
					 $k++;
					 print($k);
				}			
			}
		}
		$sql = "UPDATE Links SET Links.title=?, Links.url=?, Links.description=?
			 WHERE Links.user_id=".$this->user_id." AND Links.link_id=?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($request['title'], $request['lurl'], $request['description'], $this->link_id));
		/**
		$this->link_id = $this->pdo_conn->lastInsertId();
		for($i=0; $i<sizeof($cats); $i++){
			$sql2 = "INSERT INTO LinksCategorized (link_id, category_id)
						VALUES($this->link_id, ".$cats[$i].")";
			$statement2 = $this->pdo_conn->query($sql2);
		}
		**/
		return true;
	}
	
	public function doesExist(){
		$sql = "SELECT Links.link_id FROM Links WHERE Links.link_id = ?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id));
		if($statement->rowCount() == 1)
			return TRUE;
		else
			return FALSE;
		
	}
	
	public function getLinkID(){
		return $this->link_id;
	}

	public function checkURLExist($link){
		if(isset($this->link_id))
			$sql_append = "AND link_id != ?";
		$sql = "SELECT Links.url FROM Links WHERE url=? $sql_appened";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($link, $this->link_id));
		if($statement->rowCount() == 1)
			return TRUE;
		else
			return FALSE;
	}
	
	public function isFavorite(){
		$sql = "SELECT link_id FROM LinkFavorites WHERE user_id=$this->user_id AND link_id=?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id));
		return $statement->rowCount();
	}
	
	public function addToFavorites($add){
		$add = intval($add);
		if($add === 1){
			$sql = "INSERT INTO LinkFavorites (link_id, user_id, created)
					VALUES (? , $this->user_id, ".time().")
					ON DUPLICATE KEY UPDATE user_id = user_id";
		}elseif($add === 0){
			$sql = "DELETE FROM LinkFavorites WHERE LinkFavorites.user_id=$this->user_id AND LinkFavorites.link_id=?";
		}
		$statement = $this->pdo_conn->prepare($sql);
		if($statement->execute(array($this->link_id)))
			return TRUE;
		else
			return FALSE;
	}
	
	public function getFavorites(){
		$sql = "SELECT Users.username, Links.link_id, Links.user_id, Links.title, Links.url, COUNT(LinkVotes.vote) AS NumberOfVotes, 
				SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating, 
				Links.created 
				FROM Users LEFT JOIN
				Links USING(user_id) 
				LEFT JOIN LinkVotes USING(link_id)
				LEFT JOIN LinkFavorites USING(link_id) 
				WHERE LinkFavorites.user_id = $this->user_id
				GROUP BY link_id";
		$statement = $this->pdo_conn->query($sql);
		$link_data = array();
		for($i=0; $link_data_array=$statement->fetch(); $i++){
			if($link_data_array['link_id'] != null){
				$link_data[$i]['link_id'] = $link_data_array['link_id'];
				$link_data[$i]['user_id'] = $link_data_array['user_id'];
				$link_data[$i]['title'] = override\htmlentities($link_data_array['title']);
				$link_data[$i]['created'] = $link_data_array['created'];
				$link_data[$i]['NumberOfVotes'] = $link_data_array['NumberOfVotes'];
				$link_data[$i]['rank'] = $link_data_array['rank'];
				$link_data[$i]['rating'] = $link_data_array['rating'];
				$link_data[$i]['username'] = $link_data_array['username'];
			}
		}
		return $link_data;			   
	}
	
	public function getLinkListByID($aLink_id){
		$sql = "SELECT Users.username, Links.link_id, Links.user_id, Links.title, Links.url, COUNT(LinkVotes.vote) AS NumberOfVotes, 
				SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating, 
				Links.created 
				FROM Users LEFT JOIN
				Links USING(user_id) 
				LEFT JOIN LinkVotes USING(link_id) 
				WHERE Links.link_id = ?
				GROUP BY link_id";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($aLink_id));
		return $statement->fetch();
	}
	
	public function reportLink($request){
		$sql = "INSERT INTO LinksReported (user_id, link_id, reason, created)
			VALUES(".$this->user_id.", ?, ?, ".time().")";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id, $request));
		return 1;
	}

	public function getLinkUserID(){
		$sql = "SELECT Links.user_id from Links where Links.link_id=?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id));
		return $statement->fetch();
	}

	public function getVotes(){
		$sql = "SELECT COUNT(LinkVotes.vote) AS NumberOfVotes, SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, format(SUM(LinkVotes.vote)/COUNT(LinkVotes.vote),2) AS rating  
		FROM LinkVotes WHERE link_id = ?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->link_id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}
}
?>
