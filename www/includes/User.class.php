<?php
/*
 * User.class.php
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

class User{
	
	private $pdo_conn;
	
	private $user_id;
	
	private $username;
	
	private $email;
	
	private $private_email;
	
	private $account_created;
	
	private $last_active;
	
	private $status;
	
	private $avatar;
	
	private $signature;
	
	private $quote;
	
	private $timezone;
	
	private $exist;
	
	public static $page_count;
	
	/**
	 * @param db_connection Database connection object, passed by reference
	*/
	function __construct(&$db_connection, $aUserID=null){
		##Pass DB connection by reference
		$this->pdo_conn = &$db_connection;
		if(!is_null($aUserID)){
			$this->user_id=$aUserID;
			$this->getUserByID();
		}
	}
	/**
	 * Authenticate the user. If session cookies do not exist, supplied
	 * username and password are checked. Session cookies crated upon 
	 * valid login.
	 * 
	 * @param $aUsername Username
	 * @param $aPassword Plaintext password
	 * @return TRUE if authentication is successful
	 */
	public function checkAuthentication($aUsername=null, $aPassword=null){
		# Check if session cookies exit.
		# If true, use cookies to check authentication
		# Else use supplied credentials
		if(isset($_COOKIE[AUTH_KEY1]) && isset($_COOKIE[AUTH_KEY2])){
			$statement = $this->pdo_conn->prepare("SELECT Users.user_id, Users.username, 
													Users.email, Users.private_email,
													Users.instant_messaging, Users.account_created,
													Users.last_active, Users.status, Users.avatar,
													Users.signature, Users.quote, Users.timezone
												 FROM Users
												 INNER JOIN Sessions
												 USING(user_id)
												 WHERE 
													Sessions.session_key1=:session_key1 
													AND Sessions.session_key2=:session_key2
													AND Sessions.useragent=:useragent");
			$session_data = array("session_key1" => $_COOKIE[AUTH_KEY1],
								  "session_key2" => $_COOKIE[AUTH_KEY2],
								  "useragent" => $_SERVER['HTTP_USER_AGENT']);
			$statement->execute($session_data);
			
			if($statement->rowCount() == 1){
				$statement->setFetchMode(PDO::FETCH_ASSOC);
				#Fetch all user data
				$this->setUserData($statement->fetch());
				$update_activity = $this->pdo_conn->prepare("UPDATE Users SET last_active=".time().
															" WHERE user_id=".$this->user_id);
				$update_activity->execute();
				$update_activity = $this->pdo_conn->prepare("UPDATE Sessions SET last_active=".time().
															" WHERE session_key1=:session_key1 AND session_key2=:session_key2");
				$update_activity->bindParam(":session_key1", $_COOKIE[AUTH_KEY1]);
				$update_activity->bindParam(":session_key2", $_COOKIE[AUTH_KEY2]);
				$update_activity->execute();
				return TRUE;
			}
			else{
				setcookie($name=AUTH_KEY1, $value="", $expire=-1, $path="/", 
								$path=DOMAIN, $secure=USE_SSL, $httponly=TRUE); 
				setcookie($name=AUTH_KEY2, $value="", $expire=-1, $path="/", 
								$path=DOMAIN, $secure=USE_SSL, $httponly=TRUE); 
			}
			
		}
		# Check supplied username and password
		elseif(!is_null($aUsername) && !is_null($aPassword)){
			$statement = $this->pdo_conn->prepare("SELECT user_id, username, 
													email, private_email, password, old_password,
													instant_messaging, account_created,
													last_active, status, avatar,
													signature, quote, timezone 
												  FROM Users 
												  WHERE username=:username");
			$statement->bindParam(":username", $aUsername);
			$statement->execute();
			
			if($statement->rowCount() == 1){
				$statement->setFetchMode(PDO::FETCH_ASSOC);
				$user_data = $statement->fetch();
				$old_pass_auth = FALSE;
				$new_pass_auth = FALSE;
				if($user_data['old_password'] != null){
					$old_salt = "m*Uc98/'14B#\|omIQ,G:IrnpVM:zo";
					$old_password  = md5($old_salt.$aPassword);
					if(strcmp($old_password, $user_data['old_password']) ==0){
						$old_pass_auth = TRUE;
					}
				}
				else{
					# Split stored password into salt and hash
					$salted_password = explode("\$", $user_data['password']);
					$salt = $salted_password[1];
					$password = $salted_password[2];
					if(strcmp($user_data['password'], $this->generatePasswordHash($aPassword, $salt)) == 0)
						$new_pass_auth = TRUE;
				}
				# Compare the stored hash with the generated hash
				if($new_pass_auth == TRUE || $old_pass_auth == TRUE){
					$this->setUserData($user_data);
					if($old_pass_auth == TRUE)
						$this->convertOldPassword($aPassword);
					# Generate session keys
					$session_key1 = hash("sha256", $this->user_id.$this->username.mt_rand());
					$session_key2 = hash("sha256", $this->username.$this->user_id.mt_rand());
					# Set session cookies
					setcookie($name=AUTH_KEY1, $value=$session_key1, $expire=0, $path="/", 
								$path=DOMAIN, $secure=USE_SSL, $httponly=TRUE); 
					setcookie($name=AUTH_KEY2, $value=$session_key2, $expire=0, $path="/", 
								$path=DOMAIN, $secure=USE_SSL, $httponly=TRUE);
					# Associate session cookies with IP address and user agent
					$session_data = array("user_id" => $this->user_id,
										  "session_key1" => $session_key1,
										  "session_key2" => $session_key2,
										  "created"	=> time(), 
										  "last_active" => time(),
										  "ip" => $_SERVER['REMOTE_ADDR'],
										  "useragent" => $_SERVER['HTTP_USER_AGENT']);
					# Insert sessoin data into the database
					$start_session = $this->pdo_conn->prepare("INSERT INTO Sessions
															(user_id, session_key1, session_key2, created, last_active, ip, useragent)
														   VALUES
															(:user_id, :session_key1, :session_key2, :created, :last_active, :ip, :useragent)");
					$start_session->execute($session_data);
					$update_activity = $this->pdo_conn->prepare("UPDATE Users SET last_active=".time().
																" WHERE user_id=".$this->user_id);
					$update_activity->execute();
					return TRUE;
				}
			}
			else 
				return FALSE;
			
		}
		else
			return FALSE;
	}
	
	/**
	 * Set global user variables from an associative array using the
	 * structure from the Users table
	 * 
	 * @param $user_data Array of user data extracted from the Users table
	 * @return void
	 */
	private function setUserData($user_data){
		$this->user_id = $user_data['user_id'];
		$this->username = $user_data['username'];
		$this->email = $user_data['email'];
		$this->private_email = $user_data['private_email'];
		$this->instant_messaging = $user_data['instant_messaging'];
		@$this->password = $user_data['password'];
		$this->last_active = $user_data['last_active'];
		$this->status = $user_data['status'];
		$this->avatar = $user_data['avatar'];
		$this->signature = $user_data['signature'];
		$this->quote = $user_data['quote'];
		$this->timezone = $user_data['timezone'];
		$this->account_created = $user_data['account_created'];
	}
	
	public function changePassword($aPassword1, $aPassword2, $reset=FALSE){
		$sql = "SELECT Users.password FROM Users WHERE Users.username='$this->username'";
		$statement = $this->pdo_conn->query($sql);
		if($statement->rowCount() == 1){
			$row = $statement->fetch();
			$salted_password = explode("\$", $row['password']);
			$salt = $salted_password[1];
			$password = $salted_password[2];
			if(strcmp($row['password'], $this->generatePasswordHash($aPassword1, $salt)) == 0 || $reset==TRUE){
				$newPassword = $this->generatePasswordHash($aPassword2);
				$sql2 = "UPDATE Users SET Users.password='$newPassword' WHERE Users.username='$this->username'";
				$this->pdo_conn->query($sql2);
				return TRUE;
			}
			else
				return FALSE;
		}
	}
	
	/**
	 * Generate a hash from a supplied password and salt. If no salt is
	 * provided, one is created.
	 * 
	 * @param $aPassoword Plaintext password to be hashed
	 * @param $aSalt pre-defined salt to be used in the hash calculation
	 * @return Concatenated salt and hash; Format $salt$hash
	 */
	private function generatePasswordHash($aPassword=null, $aSalt=null){
		$final_hash = "";
		if(!is_null($aPassword) && !is_null($aSalt)){
			$aSalt = base64_decode($aSalt);
			$hash = hash_hmac("sha256", $aSalt.$aPassword, SITE_KEY, TRUE);
			$i = HASH_INTERATIONS;
			while($i--)
				$hash = hash_hmac("sha256", $aSalt.$hash, SITE_KEY, TRUE);
			$hash = hash_hmac("sha256", $aSalt.$hash, SITE_KEY, FALSE);
			$final_hash = "\$".base64_encode($aSalt)."\$".$hash;
		}
		else{
			/**$salt = "";
			$salt_char_set = "ABCDEFGHIJKLMNOPQRSTVUWXYZ1234567890`~!@#%^&*()_-+=,./;'[]\<>?:\"{}|";
			for($i=0; $i<SALT_SIZE; $i++){
				$char = $salt_char_set[mt_rand(0, strlen($salt_char_set)-1)];
				$salt .= mt_rand(0,1) ? strtolower($char) : strtoupper($char);
			}**/
			$salt = override\random(SALT_SIZE);
			$hash = hash_hmac("sha256", $salt.$aPassword, SITE_KEY, TRUE);
			$i = HASH_INTERATIONS;
			while($i--)
				$hash = hash_hmac("sha256", $salt.$hash, SITE_KEY, TRUE);
			$hash = hash_hmac("sha256", $salt.$hash, SITE_KEY, FALSE);
			$final_hash =  "\$".base64_encode($salt)."\$".$hash;
		}
		return $final_hash;
	}
	
	private function convertOldPassword($aPassword){
		$new_password = $this->generatePasswordHash($aPassword);
		$statement = $this->pdo_conn->prepare("UPDATE Users SET old_password='', password=:password WHERE user_id=:user_id");
		$statement->execute(array("password" => $new_password, "user_id" => $this->user_id));
	}
	
	/**
	 * Verify that user supplied email address
	 * is valid
	 * 
	 * @param $aEmail Email address to validate
	 * @return TRUE if email is valid
	 */
	public function validateEmail($aEmail){
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $aEmail)){
			return FALSE;
		}
		# Split it into sections to make life easier
		$email_array = explode("@", $aEmail);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++){
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
				↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i])){
				return FALSE;
			}		
		}
		# Check if domain is IP. If not, 
		# it should be valid domain name
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])){
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return TRUE; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
					↪([A-Za-z0-9]+))$",$domain_array[$i])){
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	public function awardKarma(){
		$sql = "SELECT COUNT(Karma.created) as count, 
					   MAX(Karma.created) as created
				FROM Karma
				WHERE user_id=".$this->user_id;
		$statement = $this->pdo_conn->query($sql);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row['count'] < 14 && time() > $row['created'] + (60*60*24)){
			$sql = "INSERT INTO Karma (user_id, value, created)
						VALUES ($this->user_id, 1, ".time().")";
			$this->pdo_conn->query($sql);
		}
		elseif($row['count'] < 30 && time() > $row['created'] + (60*60*24*7)){
			$sql = "INSERT INTO Karma (user_id, value, created)
						VALUES ($this->user_id, 1, ".time().")";
			$this->pdo_conn->query($sql);
		}
		elseif($row['count'] < 60 && time() > $row['created'] + (60*60*24*14)){
			$sql = "INSERT INTO Karma (user_id, value, created)
						VALUES ($this->user_id, 1, ".time().")";
			$this->pdo_conn->query($sql);
		}
		elseif(time() > $row['created'] + (60*60*24*28)){
			$sql = "INSERT INTO Karma (user_id, value, created)
						VALUES ($this->user_id, 1, ".time().")";
			$this->pdo_conn->query($sql);
		}
	}
	
	public function getKarma(){
		return ($this->getGoodKarma() + $this->getContributionKarma()) - $this->getBadKarma();
	}
	
	public function getCredits(){
		$sql = "SELECT SUM(Karma.value) as value1, (SELECT SUM(ShopTransactions.value) FROM ShopTransactions WHERE user_id=$this->user_id) as value2
				FROM Karma 
				WHERE Karma.user_id=$this->user_id
				GROUP BY user_id";
		$statement = $this->pdo_conn->query($sql);
		$row = $statement->fetch();
		return intval($row['value1']-$row['value2']) + $this->getContributionKarma();
	}
	
	public function getGoodKarma(){
		return 0;
	}
	
	public function getBadKarma(){
		return 0;
	}
	
	public function getContributionKarma(){
		$sql = "SELECT SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank
				FROM LinkVotes
				LEFT JOIN Links USING (link_id)
				WHERE Links.user_id = $this->user_id";
		$statement = $this->pdo_conn->query($sql);
		$rank = $statement->fetch();
		$contributionKarma = (int)($rank[0]/30);
		return $contributionKarma;
	}
	
	private function getUserByID(){
		$sql = "SELECT Users.user_id, Users.username, 
					   Users.email, Users.private_email,
					   Users.instant_messaging, Users.account_created,
					   Users.last_active, Users.status, Users.avatar,
					   Users.signature, Users.quote, Users.timezone
				FROM Users
				WHERE Users.user_id=?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($this->user_id));
		if($statement->rowCount() == 1){
			$this->exist = TRUE;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$this->setUserData($statement->fetch());
		}else
			$this->exist = FALSE;
	}
	
	public function getInventory(){
		$sql = "SELECT Inventory.transaction_id, ShopItems.name, ShopItems.description FROM Inventory LEFT JOIN ShopTransactions USING(transaction_id) LEFT JOIN ShopItems USING(item_id) 
					WHERE Inventory.user_id=$this->user_id";
		$statement = $this->pdo_conn->query($sql);
		return $statement->fetchAll();
		
	}
	
	public function doesExist(){
		return $this->exist;
	}
	
	/**
	 * @return The user's ID
	 */
	public function getUserID(){
		return $this->user_id;
	}
	
	/**
	 * @return The user's username
	 */
	public function getUsername(){
		return $this->username;
	}
	
	/**
	 * @return The user's public email address
	 */
	public function getEmail(){
		return $this->email;
	}
	
	/**
	 * @return The user's private email address
	 */
	public function getPrivateEmail(){
		return $this->private_email;
	}
	
	/**
	 *@return The time the user's account was created in a Unix timestamp
	 */
	public function getAccountCreated(){
		return $this->account_created;
	}
	
	/**
	 * @return The time the user was last active in a Unix timestamp
	 */
	public function getLastActive(){
		return $this->last_active;
	}
	
	/**
	 * @return The current status of the users account
	 */
	public function getStatus(){
		return $this->status;
	}
	
	/**
	 * @return Location of the user's avatar
	 */
	public function getAvatar(){
		return $this->avatar();
	}
	
	/**
	 * @return the user's signature
	 */
	public function getSignature(){
		return $this->signature;
	}
	
	/**
	 * @return The user's quote
	 */
	public function getQuote(){
		return $this->quote;
	}
	
	/**
	 * @return The user's timezone
	 */
	public function getTimezone(){
		return $this->timezone;
	}
	
	/**
	 * Update the user's email address.
	 * @param $aEmail Public Email Address
	 */
	public function setEmail($aEmail){
		$statement = $this->pdo_conn->prepare("UPDATE Users SET email=? WHERE user_id=".$this->user_id);
		return $statement->execute(array($aEmail));
	}
	
	public function getNumberOfPosts(){
		$sql = "SELECT COUNT(DISTINCT(Messages.message_id)) AS count FROM Messages WHERE Messages.user_id=$this->user_id";
		$statement= $this->pdo_conn->query($sql);
		$row = $statement->fetch();
		return $row['count'];
	}
	
	public function getNumberOfTopics(){
		$sql = "SELECT COUNT(topic_id) AS count FROM Topics WHERE Topics.user_id=$this->user_id";
		$statement= $this->pdo_conn->query($sql);
		$row = $statement->fetch();
		return $row['count'];
	}
	
	public function getPostsInBestTopic(){
		//$sql = "SELECT MAX(COUNT(DISTINCT(Messages.message_id))) AS count FROM Messages LEFT JOIN Topics USING(user_id) WHERE Topics.user_id=$this->user_id";
		$sql = "SELECT Topics.topic_id, COUNT(DISTINCT(Messages.message_id)) FROM Topics LEFT JOIN Messages using(topic_id) WHERE Topics.user_id=$this->user_id";
		$sql = "SELECT Topics.topic_id, COUNT(DISTINCT(Messages.message_id)) as count FROM Topics LEFT JOIN Messages USING(topic_id) WHERE Topics.user_id=$this->user_id GROUP BY Topics.topic_id ORDER BY count DESC"; 
		$statement= $this->pdo_conn->query($sql);
		$row = $statement->fetchAll();
		return $row[0]['count'];
	}
	
	public function getNoReplyTopics(){
		$sql = "SELECT COUNT(Topics.topic_id) AS count FROM Topics LEFT JOIN Messages USING(topic_id) WHERE Topics.user_id=$this->user_id GROUP BY Topics.topic_id HAVING COUNT(DISTINCT(Messages.Message_id))<2 ";
		$statement= $this->pdo_conn->query($sql);
		$row = $statement->fetchAll();
		return sizeof($row);
	}
	
	public function getNumberOfLinks(){
		$sql = "SELECT COUNT(Links.link_id) AS count FROM Links WHERE user_id=$this->user_id";
		$statement = $this->pdo_conn->query($sql);
		$statement= $this->pdo_conn->query($sql);
		$row = $statement->fetchAll();
		return $row[0]['count'];
	}
	
	public function getNumberOfVotes(){
		$sql = "SELECT COUNT(LinkVotes.vote) as count FROM LinkVotes 
					LEFT JOIN Links USING(link_id) 
					WHERE Links.user_id = $this->user_id";
		$statement = $this->pdo_conn->query($sql);
		$statement= $this->pdo_conn->query($sql);
		$row = $statement->fetchAll();
		return $row[0]['count'];
	}
	
	public function getVoteAverage(){
		$sql = "SELECT SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) as average
					FROM LinkVotes LEFT JOIN Links USING(link_id) WHERE Links.user_id=$this->user_id 
					GROUP BY LinkVotes.link_id";
		$statement = $this->pdo_conn->query($sql);
		$statement= $this->pdo_conn->query($sql);
		$row = $statement->fetchAll();
		return $row[0]['average'];
	}
	
	public static function getUserList(&$db, $page=1, $query=NULL){
		$offset = 50 * ($page-1);
		if(!is_null($query)){
			if(strlen($query) == 1)
				$query = str_replace("%", "", $query);
			$query = "%".$query."%";
			$user_search = "WHERE Users.username LIKE ?";
		}
		$sql = "SELECT Users.username, Users.user_id, Users.account_created, Users.last_active, SUM(Karma.value) as value1, SUM(ShopTransactions.value) as value2
					FROM Users LEFT Join Karma using (user_id) LEFT JOIN ShopTransactions USING (user_id) $user_search GROUP BY Users.user_id ORDER BY User_id ASC LIMIT 50 OFFSET ?";
		$statement = $db->prepare($sql);
		if(!is_null($query))
			$statement->execute(array($query, $offset));
		else
			$statement->execute(array($offset));
		$sql2 = "SELECT COUNT(Users.user_id) as count FROM Users";
		$statement2 = $db->query($sql2);
		$row = $statement2->fetch();
		self::$page_count = intval($row['count']/50);
		if(self::$page_count % 50 != 0)
			self::$page_count++;
		return $statement->fetchAll();
	}
	/**
	 * Update the user's private email address.
	 * @param $aPrivateEmail Private Email Address
	 */
	public function setPrivateEmail($aPrivateEmail){
		
	}
	
	/**
	 * Update the user's account status.
	 * @param $aStatus -1 for banned, 0 for active, More than zero for number of days suspesion
	 */
	public function setStatus($aStatus){
		
	}
	
	/**
	 * Update the path of the user's avatar
	 * @param $aAvatar Path of the user's avatar
	 */
	public function setAvatar($aAvatar){
		
	}
	
	/**
	 * Update the user's signature
	 * @param Signature to be appended to every post
	 */
	public function setSignature($aSignature){
		
	}
	
	/**
	 * Update the user's quote
	 * @param $aQuote The user's quote to be displayed on their profile
	 */
	public function setQuote($aQuote){
		
	}
	
	/**
	 * Update the user's timezone
	 * @param $aTimezone The user's timezone
	 */
	public function setTimezone($aTimezone){
		
	}
	
	public function checkInvite($invite_code){
		$sql = "SELECT invited_by FROM InviteTree WHERE invite_code 
				COLLATE latin1_general_cs = ? and invited_user IS NULL and created > ".(time()-(60*60*72));
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($invite_code));
		$rows = $statement->rowCount();
		if($statement->rowCount() != 1){
			$url = "http://boards.endoftheinter.net/scripts/login.php?username=".urlencode($invite_code)."&ip=".$_SERVER['REMOTE_ADDR'];
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_REFERER, "");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			$output = curl_exec($curl);
			if($output == ("1:". $request['username']))
				return true;
			else return true;
		}else{
			return true;
		}
	}
	
	public function registerUser($request){
		if(!preg_match('/[^A-z0-9.\-_\ ]/', $request['username'])){
			$sql = "SELECT Users.user_id FROM Users where Users.username=?";
			$statement = $this->pdo_conn->prepare($sql);
			$statement->execute(array($request['username']));
			if($statement->rowCount() == 1)
				return -1;
			else{
				if(!$this->checkInvite($request['invite_code']))
					return -2;
				else{
					$sql2 = "INSERT INTO Users (username, private_email, password, account_created)
								VALUES (?, ?, ?, ".time().")";
					$statement2 = $this->pdo_conn->prepare($sql2);
					$data = array($request['username'], 
								  $request['email'], 
								  $this->generatePasswordHash($request['password']));
					$statement2->execute($data);
					$sql3 = "UPDATE InviteTree SET invited_user=".$this->pdo_conn->lastInsertId()." WHERE invite_code=?";
					$statement3 = $this->pdo_conn->prepare($sql3);
					$statement3->execute(array($request['invite_code']));
					if($statement2->rowCount())
						return 1;
				}
			}
		}
	}
	
	public function checkInventory($class_id=NULL){
		$sql = "SELECT DISTINCT ShopItems.name FROM ShopItems LEFT JOIN ShopTransactions USING(item_id) 
													 LEFT JOIN Inventory USING (transaction_id)
													 WHERE Inventory.user_id=$this->user_id
													 AND ShopItems.item_id=5";
		$statement = $this->pdo_conn->query($sql);
		return $statement->fetchAll();
	}
	
	public function getCommentHistory(){
		$sql = "SELECT DISTINCT Topics.topic_id, Boards.title as board_title, 
		Topics.title, Topics.board_id, MAX(Messages.posted) as u_last_posted 
			FROM Topics 
			LEFT JOIN Messages USING(topic_id)
			LEFT JOIN Boards USING(board_id) 
			WHERE Messages.user_id=$this->user_id AND Messages.revision_no=0
			GROUP BY Topics.topic_id ORDER BY Messages.posted DESC";
		$statement = $this->pdo_conn->query($sql);
		for($i=0; $topic_data_array = $statement->fetch(); $i++){
			
			$topic_data[$i]['board_title'] = $topic_data_array['board_title'];
			$topic_data[$i]['topic_id'] = $topic_data_array['topic_id'];
			$topic_data[$i]['u_last_posted'] = $topic_data_array['u_last_posted'];
			$topic_data[$i]['last_post'] = $topic_data_array['last_post'];
			$topic_data[$i]['title'] = override\htmlentities($topic_data_array['title']);
			$topic_data[$i]['username'] = $topic_data_array['username'];
			$topic_data[$i]['user_id'] = $topic_data_array['user_id'];
			
			$sql2 = "SELECT MAX(Messages.posted) as last_post FROM Messages
						WHERE Messages.topic_id=".$topic_data_array['topic_id'].
						" AND Messages.revision_no=0";
			$statement2 = $this->pdo_conn->query($sql2);
			$last_post = $statement2->fetchAll();
			
			$sql3 = "SELECT COUNT(Messages.message_id) as count FROM Messages
						WHERE Messages.topic_id=".$topic_data_array['topic_id'].
						" AND Messages.revision_no=0";
			$statement3 = $this->pdo_conn->query($sql3);
			$msg_count = $statement3->fetchAll();
			# Inefficient - Find if another way is possible
			/**
			$get_count = $this->pdo_conn->prepare("SELECT COUNT(DISTINCT topic_id, message_id) FROM Messages
													WHERE topic_id = ?");
			$get_count->execute(array($topic_data[$i]['topic_id']));
			$statement2->execute(array($topic_data[$i]['topic_id']));
			
			$msg_count = $get_count->fetchAll();
			$history_count = $statement2->fetchAll();
			**/
			$topic_data[$i]['last_post'] = $last_post[0][0];
			$topic_data[$i]['number_of_posts'] = $msg_count[0][0];
			$topic_data[$i]['history'] = $history_count[0]['count'];
			$topic_data[$i]['last_message'] = $history_count[0]['last_message'];

													
		}
		return $topic_data;
	}
}

?>
