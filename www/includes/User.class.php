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

if (!function_exists("password_hash")) {
    include "Password.lib.php";
}

class User {
    
    /* PDO Database Connection */
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
    
    private $level = array();
    
    private $permissions;

    private $hash_algorithm = PASSWORD_DEFAULT;

    private $hash_options = array();
    
    public static $page_count;
    
    /**
     * Create a new user
     * 
     * @param db_connection Database connection object, passed by reference
     * @param int $aUserID  Create a user by specifiying a user ID
     * 
     * @return void
    */
    public function __construct(&$db_connection, $aUserID = null)
    {
        // Pass DB connection by reference
        $this->pdo_conn = &$db_connection;
        if (!is_null($aUserID)) {
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
     * 
     * @return boolean True if authentication is successful
     */
    public function checkAuthentication($aUsername = null, $aPassword = null)
    {
        // Check if session cookies exit.
        // If true, use cookies to check authentication
        // Else use supplied credentials
        if (isset($_COOKIE[AUTH_KEY1]) && isset($_COOKIE[AUTH_KEY2])) {
            $sql = "SELECT Users.user_id, Users.username, 
                Users.email, Users.private_email,
                Users.instant_messaging, Users.account_created,
                Users.last_active, Users.status, Users.avatar, UploadedImages.sha1_sum,
                UploadedImages.height, UploadedImages.width, UploadedImages.thumb_width,
                UploadedImages.thumb_height, UploadLog.filename, Users.signature, Users.quote, 
                Users.timezone, Users.level
                 FROM Users
                 LEFT JOIN UploadLog
                 ON Users.avatar=UploadLog.uploadlog_id
                 LEFT JOIN UploadedImages
                 ON UploadLog.image_id = UploadedImages.image_id
                 INNER JOIN Sessions
                 on Users.user_id=Sessions.user_id
                 WHERE 
                    Sessions.session_key1=:session_key1 
                    AND Sessions.session_key2=:session_key2
                    AND Sessions.useragent=:useragent";
            $statement = $this->pdo_conn->prepare($sql);

            $session_data = array("session_key1" => $_COOKIE[AUTH_KEY1],
                                  "session_key2" => $_COOKIE[AUTH_KEY2],
                                  "useragent" => $_SERVER['HTTP_USER_AGENT']);
            $statement->execute($session_data);
            
            if ($statement->rowCount() == 1) {
                $statement->setFetchMode(PDO::FETCH_ASSOC);
                // Fetch all user data
                $this->setUserData($statement->fetch());
                $sql_updateActivity = "UPDATE Users SET last_active=".time()." WHERE user_id=".$this->user_id;
                $update_activity = $this->pdo_conn->prepare($sql_updateActivity);
                $update_activity->execute();
                $sql_updateActivity2 = "UPDATE Sessions SET last_active=".time().
                    " WHERE session_key1=:session_key1 AND session_key2=:session_key2";
                $update_activity = $this->pdo_conn->prepare($sql_updateActivity2);
                $update_activity->bindParam(":session_key1", $_COOKIE[AUTH_KEY1]);
                $update_activity->bindParam(":session_key2", $_COOKIE[AUTH_KEY2]);
                $update_activity->execute();
                return true;
            } else {
                // Cookies are invalid, remove them
                setcookie(
                    $name = AUTH_KEY1,
                    $value = "",
                    $expire = -1,
                    $path = "/",
                    $domain = DOMAIN,
                    $secure = USE_SSL,
                    $httponly = true
                );
                setcookie(
                    $name = AUTH_KEY2,
                    $value = "",
                    $expire = -1,
                    $path = "/",
                    $domain = DOMAIN,
                    $secure = USE_SSL,
                    $httponly = true
                );
            }
        } elseif (!is_null($aUsername) && !is_null($aPassword)) {
            // Check supplied username and password
            $sql = "SELECT user_id, username, 
                email, private_email, password, old_password,
                instant_messaging, account_created,
                last_active, status, avatar,
                signature, quote, timezone, level 
              FROM Users 
              WHERE username=:username";
            $statement = $this->pdo_conn->prepare($sql);
            $statement->bindParam(":username", $aUsername);
            $statement->execute();
            
            if ($statement->rowCount() == 1) {
                $statement->setFetchMode(PDO::FETCH_ASSOC);
                $user_data = $statement->fetch();
                $new_pass_auth = false;
                
                // Split stored password into salt and hash
                $salted_password = explode("\$", $user_data['password']);
                $salt = $salted_password[1];
                $password = $salted_password[2];
                if (strcmp($user_data['password'], $this->generatePasswordHash($aPassword, $salt)) === 0) {
                    $new_pass_auth = true;
                    $new_hash = password_hash($aPassword, $this->hash_algorithm, $this->hash_options);
                    $this->setUserData($user_data);
                    $this->updatePassword($new_hash);
                } else {
                    $new_pass_auth = password_verify($aPassword, $user_data['password']);
                    if ($new_pass_auth) {
                        if (password_needs_rehash($user_data['password'], $this->hash_algorithm, $this->hash_options)) {
                            $hash = password_hash($aPassword, $this->hash_algorithm, $this->hash_options);
                            $this->setUserData($user_data);
                            $this->updatePassword($hash);
                        }
                    }
                }

                // Compare the stored hash with the generated hash
                if ($new_pass_auth == true) {
                    $this->setUserData($user_data);
                    // Generate session keys
                    $session_key1 = base64_encode(
                        mcrypt_create_iv(48, MCRYPT_DEV_URANDOM)
                    );
                    $session_key2 = base64_encode(
                        mcrypt_create_iv(48, MCRYPT_DEV_URANDOM)
                    );
                    // Set session cookies
                    setcookie(
                        $name = AUTH_KEY1,
                        $value = $session_key1,
                        $expire = 0,
                        $path = "/",
                        $domain = DOMAIN,
                        $secure = USE_SSL,
                        $httponly = true
                    );
                    setcookie(
                        $name = AUTH_KEY2,
                        $value = $session_key2,
                        $expire = 0,
                        $path = "/",
                        $path = DOMAIN,
                        $secure = USE_SSL,
                        $httponly = true
                    );
                    // Associate session cookies with IP address and user agent
                    $headers = apache_request_headers();
                    $x_forward = @$headers["X-Forwarded-For"];
                    
                    $session_data = array("user_id" => $this->user_id,
                                          "session_key1" => $session_key1,
                                          "session_key2" => $session_key2,
                                          "created"    => time(),
                                          "last_active" => time(),
                                          "ip" => $_SERVER['REMOTE_ADDR'],
                                          "x_forwarded_for" => $x_forward,
                                          "useragent" => $_SERVER['HTTP_USER_AGENT']);
                    // Insert sessoin data into the database
                    $sql_startSession = "INSERT INTO Sessions
                        (user_id, session_key1, session_key2, created, last_active, ip, x_forwarded_for, useragent)
                        VALUES (:user_id, :session_key1, :session_key2, :created, :last_active, :ip, :x_forwarded_for, :useragent)";
                    $start_session = $this->pdo_conn->prepare($sql_startSession);
                    $start_session->execute($session_data);

                    $sql_updateActivity = "UPDATE Users SET last_active=".time()." WHERE user_id=".$this->user_id;
                    $update_activity = $this->pdo_conn->prepare($sql_updateActivity);
                    $update_activity->execute();
                    return true;
                } else {
                    // Prevent timing attacks
                    password_hash("password", PASSWORD_DEFAULT);
                }
            } else {
                return false;
            }
            
        } else {
            return false;
        }
    }
    
    /**
     * Set global user variables from an associative array using the
     * structure from the Users table
     * 
     * @param array $user_data Array of user data extracted from the Users table
     * 
     * @return void
     */
    private function setUserData($user_data)
    {
        @$filename_array = explode(".", $user_data['filename']);
        $this->user_id = $user_data['user_id'];
        $this->username = $user_data['username'];
        $this->email = $user_data['email'];
        $this->private_email = $user_data['private_email'];
        $this->instant_messaging = $user_data['instant_messaging'];
        @$this->password = $user_data['password'];
        $this->last_active = $user_data['last_active'];
        $this->status = $user_data['status'];
        $this->avatar = @array('sha1_sum' => $user_data['sha1_sum'],
                               'filename' => $filename_array[0],
                               'extension' => $filename_array[1],
                               'width' =>    $user_data['width'],
                               'height' => $user_data['height'],
                               'thumb_width' => $user_data['thumb_width'],
                               'thumb_height' => $user_data['thumb_height']);
        $this->signature = $user_data['signature'];
        $this->quote = $user_data['quote'];
        $this->timezone = $user_data['timezone'];
        $this->account_created = $user_data['account_created'];
        $this->level[0] = $user_data['level'];
        if ($this->level != 0) {
                $sql = "SELECT * FROM StaffPermissions WHERE position_id = ".$this->level[0];
                $statement = $this->pdo_conn->query($sql);
                $this->permissions = $statement->fetch();
                $sql2 = "SELECT StaffPositions.title FROM StaffPositions WHERE position_id=".$this->level[0];
                $statement2 = $this->pdo_conn->query($sql2);
                $results = $statement2->fetch();
                $this->level[1] = $results[0];
        }
    }
    
    /**
     * Change the user's password
     * 
     * @param  string  $oldPassword The user's current password
     * @param  string  $newPassword The user's new password
     * @param  boolean $reset       True if the user forgot their password and needs to reset it
     * 
     * @return boolean              True if password was changed successfully
     */
    public function changePassword($oldPassword, $newPassword, $reset = false)
    {
        $sql = "SELECT Users.password FROM Users WHERE Users.username='$this->username'";
        $statement = $this->pdo_conn->query($sql);
        if ($statement->rowCount() == 1) {
            $row = $statement->fetch();
            if (password_verify($oldPassword, $row['password']) || $reset == true) {
                $newPassword_hash = password_hash($newPassword, $this->hash_algorithm, $this->hash_options);
                $sql2 = "UPDATE Users SET Users.password='$newPassword_hash' WHERE Users.username='$this->username'";
                $this->pdo_conn->query($sql2);
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * @deprecated Generate a hash from a supplied password and salt. If no salt is
     * provided, one is created.
     *
     * @param string $aPassoword Plaintext password to be hashed
     * @param string $aSalt pre-defined salt to be used to compute hash
     * 
     * @return string Concatenated salt and hash; Format $salt$hash
     */
    private function generatePasswordHash($aPassword = null, $aSalt = null)
    {
        $final_hash = "";
        // If password and salt exist, create a matching hash
        if (!is_null($aPassword) && !is_null($aSalt)) {
            $aSalt = base64_decode($aSalt);
            $hash = hash_hmac("sha256", $aSalt.$aPassword, SITE_KEY, true);
            $i = HASH_INTERATIONS;
            while ($i--) {
                $hash = hash_hmac("sha256", $aSalt.$hash, SITE_KEY, true);
            }
            $hash = hash_hmac("sha256", $aSalt.$hash, SITE_KEY, false);
            $final_hash = "\$".base64_encode($aSalt)."\$".$hash;
        } else {
            // Salt does not exist, which means this is a new password
            // and we need to generate a new salt
            $salt = mcrypt_create_iv(SALT_SIZE, MCRYPT_DEV_URANDOM);
            $hash = hash_hmac("sha256", $salt.$aPassword, SITE_KEY, true);
            $i = HASH_INTERATIONS;
            while ($i--) {
                $hash = hash_hmac("sha256", $salt.$hash, SITE_KEY, true);
            }
            $hash = hash_hmac("sha256", $salt.$hash, SITE_KEY, false);
            $final_hash =  "\$".base64_encode($salt)."\$".$hash;
        }
        return $final_hash;
    }
    
    /**
     * Update the user's password in the database.
     * Used mainly for when a password needs to be rehashed
     *
     * @param  string $aPassword New password hash for the user
     */
    private function updatePassword($aPassword)
    {
        $new_password = $aPassword;
        $sql = "UPDATE Users SET old_password='', password=:password WHERE user_id=:user_id";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array("password" => $new_password, "user_id" => $this->user_id));
    }
    
    /**
     * Verify that user supplied email address
     * is valid
     * 
     * @param string $aEmail Email address to validate
     * 
     * @return boolean True if email is valid
     */
    public function validateEmail($aEmail)
    {
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $aEmail)) {
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $aEmail);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!ereg(
                "^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
                ↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
                $local_array[$i]
            )) {
                return false;
            }
        }
        // Check if domain is IP. If not,
        // it should be valid domain name
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return true; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!ereg(
                    "^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
                    ↪([A-Za-z0-9]+))$",
                    $domain_array[$i]
                )) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Award a user karma in timed intervals on login
     * Once a day for 14 days, then once a week for 16 
     * weeks, then once every 2 weeks for 30 weeks, then
     * once a month. 
     * 
     * 
     * @return void
     */
    public function awardKarma()
    {
        $sql = "SELECT COUNT(Karma.created) as count, 
                       MAX(Karma.created) as created
                FROM Karma
                WHERE user_id=".$this->user_id;
        $statement = $this->pdo_conn->query($sql);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $row = $statement->fetch();
        if ($row['count'] < 14 && time() > $row['created'] + (60*60*24)) {
            $sql = "INSERT INTO Karma (user_id, value, created)
                        VALUES ($this->user_id, 1, ".time().")";
            $this->pdo_conn->query($sql);
        } elseif ($row['count'] < 30 && time() > $row['created'] + (60*60*24*7)) {
            $sql = "INSERT INTO Karma (user_id, value, created)
                        VALUES ($this->user_id, 1, ".time().")";
            $this->pdo_conn->query($sql);
        } elseif ($row['count'] < 60 && time() > $row['created'] + (60*60*24*14)) {
            $sql = "INSERT INTO Karma (user_id, value, created)
                        VALUES ($this->user_id, 1, ".time().")";
            $this->pdo_conn->query($sql);
        } elseif (time() > $row['created'] + (60*60*24*28)) {
            $sql = "INSERT INTO Karma (user_id, value, created)
                        VALUES ($this->user_id, 1, ".time().")";
            $this->pdo_conn->query($sql);
        }
    }
    
    /**
     * Get total number of karma
     *
     * @return int karma
     */
    public function getKarma()
    {
        return ($this->getGoodKarma() + $this->getContributionKarma()) - $this->getBadKarma();
    }
    
    /**
     * Get number of credits
     * 
     * @return int Total amount of credits
     */
    public function getCredits()
    {
         $sql = "SELECT SUM(Karma.value) as value1, (SELECT SUM(ShopTransactions.value)
            FROM ShopTransactions WHERE user_id=$this->user_id) as value2
            FROM Karma WHERE Karma.user_id=$this->user_id GROUP BY user_id";
        $statement = $this->pdo_conn->query($sql);
        $row = $statement->fetch();
        return intval($row['value1']-$row['value2']) + $this->getContributionKarma();
    }
    
    /**
     * Get a user's good karma
     * 
     * @return int Amount of good karma
     */
    public function getGoodKarma()
    {
        return 0;
    }
    
    /**
     * Get a user's bad karma
     * 
     * @return int Amount of bad karma
     */
    public function getBadKarma()
    {
        return 0;
    }
    
    /**
     * Get a user's contribution karma accumulated from adding links
     * 
     * @param  int $user_id user id of the user
     * 
     * @return int Amount of contribution karma
     */
    public function getContributionKarma($user_id = null)
    {
        if ($user_id == null) {
            $user_id = $this->user_id;
        }
        $sql = "SELECT SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank
                FROM LinkVotes
                LEFT JOIN Links USING (link_id)
                WHERE Links.user_id = $user_id";
        $statement = $this->pdo_conn->query($sql);
        $rank = $statement->fetch();
        $contributionKarma = (int)(pow(abs($rank[0])/15, (1/3)));
        if ($rank[0] < 0) {
            $contributionKarma = $contributionKarma * -1;
        }
        return $contributionKarma;
    }
    
    /**
     * Get user information by a supplied user ID
     * 
     * @return void
     */
    private function getUserByID()
    {
        $sql = "SELECT Users.user_id, Users.username, 
                       Users.email, Users.private_email,
                       Users.instant_messaging, Users.account_created,
                       Users.last_active, Users.status, Users.avatar,
                       Users.signature, Users.quote, Users.timezone, Users.level, UploadedImages.sha1_sum,
                       UploadedImages.height, UploadedImages.width, UploadedImages.thumb_width,
                       UploadedImages.thumb_height, UploadLog.filename, Users.signature, Users.quote,
                       Users.timezone, Users.level
                FROM Users
                LEFT JOIN UploadLog
                     ON Users.avatar=UploadLog.uploadlog_id
                     LEFT JOIN UploadedImages
                     ON UploadLog.image_id = UploadedImages.image_id
                WHERE Users.user_id=?";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($this->user_id));
        if ($statement->rowCount() == 1) {
            $this->exist = true;
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $this->setUserData($statement->fetch());
        } else {
            $this->exist = false;
        }
    }
    
    /**
     * Get purchased items and unused items from the user's inventory
     *
     * @param  int $item_id The ID of the item to filter by, if none is provided all items are returned
     * 
     * @return array Array of inventory items
     */
    public function getInventory($item_id = null)
    {
        $sql = "SELECT Inventory.transaction_id, ShopTransactions.item_id, ShopItems.name, ShopItems.description 
            FROM Inventory LEFT JOIN ShopTransactions USING(transaction_id) 
            LEFT JOIN ShopItems USING(item_id) WHERE Inventory.user_id=$this->user_id";
        if ((int) $item_id != 0) {
            $sql .= " AND ShopTransactions.item_id = ? ORDER BY ShopTransactions.date ASC";
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array($item_id));
        } else {
            $statement = $this->pdo_conn->query($sql);
        }
        return $statement->fetchAll();
    }
    
    /**
     * Check if the user exists
     * 
     * @return boolean True if the user exists
     */
    public function doesExist()
    {
        return $this->exist;
    }
    
    /**
     * Get the user's user ID
     * 
     * @return int The user's ID
     */
    public function getUserID()
    {
        return $this->user_id;
    }
    
    /**
     * Get the user's username
     * 
     * @return string The user's username
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Get the user's public email address
     * 
     * @return string The user's public email address
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Get the user's private email address
     * 
     * @return string The user's private email address
     */
    public function getPrivateEmail()
    {
        return $this->private_email;
    }
    
   /**
     * The date and time the account was created
     * 
     *@return int The time the user's account was created in a Unix timestamp
     */
    public function getAccountCreated()
    {
        return $this->account_created;
    }
    
    /**
     * The time the user was last active 
     * 
     * @return int The time the user was last active in a Unix timestamp
     */
    public function getLastActive()
    {
        return $this->last_active;
    }
    
    /**
     * Get the user's account status
     * 
     * @return int The current status of the users account
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Get the user's avatar
     * 
     * @return array Avatar path information
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    
    /**
     * Get instant messaging handle
     * 
     * @return string Instant messaging handle
     */
    public function getInstantMessaging()
    {
        return $this->instant_messaging;
    }
    
    /**
     * Get the user's signature
     * 
     * @return the user's signature
     */
    public function getSignature()
    {
        return $this->signature;
    }
    
    /**
     * Get the user's quote
     * 
     * @return string The user's quote
     */
    public function getQuote()
    {
        return $this->quote;
    }
    
    /**
     * Get the user's timezone
     * 
     * @return string The user's timezone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
    
    /**
    * Check if user has supplied permsion
    *
    * @param string $search The permission to search for
    *
    * @return int|null 1 if user has permission, 0 if the user doesnt, null on error
    */
    public function checkPermissions($search)
    {
        return @$this->permissions[$search];
    }
    
    /**
    * Get the type of user account
    *
    * @return int ID of staff position type
    */
    public function getAccessLevel()
    {
        return $this->level[0];
    }
    
    /**
    * Get the user title of a privileged account
    * 
    * @return int Title
    */
    public function getAccessTitle()
    {
        return $this->level[1];
    }
    
    /**
     * Update the user's email address.
     *
     * @param string $aEmail Public Email Address
     * 
     * @return boolean True If email was updated sucessfully
     */
    public function setEmail($aEmail)
    {
        $sql = "UPDATE Users SET email=? WHERE user_id=".$this->user_id;
        $statement = $this->pdo_conn->prepare($sql);
        return $statement->execute(array($aEmail));
    }
    
    /**
    * Get total number of posts from a given user
    *
    * @return int Number of posts
    */
    public function getNumberOfPosts()
    {
        $sql = "SELECT COUNT(DISTINCT(Messages.message_id)) AS count 
            FROM Messages WHERE Messages.user_id=$this->user_id";
        $statement= $this->pdo_conn->query($sql);
        $row = $statement->fetch();
        return $row['count'];
    }
    
    /**
    * Get total number created by a given user
    *
    * @return int Number of topics
    */
    public function getNumberOfTopics()
    {
        $sql = "SELECT COUNT(topic_id) AS count FROM Topics 
            WHERE Topics.user_id=$this->user_id";
        $statement= $this->pdo_conn->query($sql);
        $row = $statement->fetch();
        return $row['count'];
    }
    
    /**
    * Get highest number of posts in topic created by a given user
    *
    * @return int Number of posts in the topic
    */
    public function getPostsInBestTopic()
    {
        $sql = "SELECT Topics.topic_id, COUNT(DISTINCT(Messages.message_id)) 
            as count FROM Topics LEFT JOIN Messages USING(topic_id) WHERE 
            Topics.user_id=$this->user_id GROUP BY Topics.topic_id 
            ORDER BY count DESC";
        $statement= $this->pdo_conn->query($sql);
        $row = $statement->fetchAll();
        if (!empty($row)) {
            return $row[0]['count'];
        } else {
            return 0;
        }
    }
    
    /**
    * Get total number of topics made by a given user that have no replies
    *
    * @return int Number of topics
    */
    public function getNoReplyTopics()
    {
        $sql = "SELECT COUNT(Topics.topic_id) AS count FROM Topics 
            LEFT JOIN Messages USING(topic_id) WHERE 
            Topics.user_id=$this->user_id GROUP BY Topics.topic_id 
            HAVING COUNT(DISTINCT(Messages.Message_id))<2 ";
        $statement= $this->pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return sizeof($row);
    }
    
    /**
    * Get total number of links added from a given user
    * 
    * @return int Number of links
    */
    public function getNumberOfLinks()
    {
        $sql = "SELECT COUNT(Links.link_id) AS count 
            FROM Links WHERE user_id=$this->user_id";
        $statement = $this->pdo_conn->query($sql);
        $statement= $this->pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return $row[0]['count'];
    }
    
    /**
    * Get total number of votes accumulated for all the links added by a given user
    * 
    * @return int Number of votes
    */
    public function getNumberOfVotes()
    {
        $sql = "SELECT COUNT(LinkVotes.vote) as count FROM LinkVotes 
                    LEFT JOIN Links USING(link_id) 
                    WHERE Links.user_id = $this->user_id";
        $statement = $this->pdo_conn->query($sql);
        $statement= $this->pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return $row[0]['count'];
    }
    
    /**
    * Get the average rating for all added links 
    * 
    * @return float Vote average 
    */
    public function getVoteAverage()
    {
        $sql = "SELECT SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) as average
            FROM LinkVotes LEFT JOIN Links USING(link_id) 
            WHERE Links.user_id=$this->user_id GROUP BY LinkVotes.link_id";
        $statement = $this->pdo_conn->query($sql);
        $statement= $this->pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return @$row[0]['average'];
    }
    
    /**
    * Get a list of currently registered users, sign-up date, 
    * last active data, and token count in groups of 50 records
    *
    * @param int    $page  The page number of the user list.
    * @param string $query Username to search for
    * 
    * @return array List of users
    */
    public function getUserList($page = 1, $query = null)
    {
        // Get users 50 at a time
        $offset = 50 * ($page-1);
        $user_search  = "";

        // Search for a speficied username
        if (!is_null($query)) {
            if (strlen($query) == 1) {
                $query = str_replace("%", "", $query);
            }
            $query = "%".$query."%";
            $user_search = "WHERE Users.username LIKE ?";
        }
        $sql = "SELECT Users.username, Users.user_id, Users.account_created, 
            Users.last_active FROM Users LEFT Join Karma using (user_id) 
            LEFT JOIN ShopTransactions USING (user_id) $user_search GROUP 
            BY Users.user_id ORDER BY User_id ASC LIMIT 50 OFFSET ?";
        $statement = $this->pdo_conn->prepare($sql);
        if (!is_null($query)) {
            $statement->execute(array($query, $offset));
        } else {
            $statement->execute(array($offset));
        }
        $sql2 = "SELECT COUNT(Users.user_id) as count FROM Users";
        $statement2 = $this->pdo_conn->query($sql2);
        $row = $statement2->fetch();
        self::$page_count = intval($row['count']/50);
        if (self::$page_count % 50 != 0) {
            self::$page_count++;
        }
        $userList = $statement->fetchAll();
        foreach ($userList as $key => $value) {
            $userList[$key]['karma']
                = $this->getContributionKarma($userList[$key]['user_id']);
        }
        return $userList;
    }

    /**
     * Update the user's private email address.
     *
     * @param string $aPrivateEmail Private Email Address
     * 
     * @return void;
     */
    public function setPrivateEmail($aPrivateEmail)
    {
        //TODO
    }
    
    /**
    * Update the instant messaging feild on the user's profile
    * 
    * @param string $aInstantMessaging Instant messaging handle
    *
    * @return void
    */
    public function setInstantMessaging($aInstantMessaging)
    {
        $sql = "UPDATE Users set instant_messaging = ? 
            WHERE user_id = ".$this->user_id;
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($aInstantMessaging));
        $this->instant_messaging = $aInstantMessaging;
    }
    
    /**
     * Update the user's account status.
     *
     * @param int    $aStatus     -1 for banned, 0 for active
     * @param int    $aUser_id    User ID of the account to change status
     * @param string $action      Short description for action taken 
     * @param string $description Reason for performing action
     *
     * @return boolean True if status change is sucessful
     */
    public function setStatus($aStatus, $aUser_id, $action, $description)
    {
        if ($this->level > 0) {
            $is_authorized = false;
            if ($aStatus == -1) {
                $is_authorized = $this->checkPermissions("user_ban");
            } elseif ($aStatus > 0) {
                $is_authorized = $this->checkPermissions("user_suspend");
            } elseif ($aStatus = 0) {
                //unban or unsuspend code
            }
        
            $sql = "UPDATE Users SET status = ? WHERE user_id = ?";
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array($aStatus, $aUser_id));
            
            $sql2 = "INSERT INTO DisciplineHistory 
                (user_id, mod_id, action_taken, description, date)
                VALUES (?, $this->user_id, ?, ?, ".time().")";
            $statement2 = $this->pdo_conn->prepare($sql2);
            $statement2->execute(array($aUser_id, $action, $description));
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Update the path of the user's avatar
     *
     * @param int $image_id ID of the uploaded image to set the avatar too
     *
     * @return void
     */
    public function setAvatar($image_id)
    {
        
        $sql_getAvatarID = "SELECT UploadLog.filename, UploadedImages.width, 
            UploadedImages.height, UploadedImages.sha1_sum FROM UploadLog
            LEFT JOIN UploadedImages USING(image_id) 
            WHERE UploadLog.uploadlog_id = $image_id";
        $statement_getAvatarID = $this->pdo_conn->query($sql_getAvatarID);
        $result = $statement_getAvatarID->fetch();
        $sql = "UPDATE Users set Users.avatar = $image_id 
            WHERE user_id=".$this->user_id;
        $statement = $this->pdo_conn->query($sql);
        $this->avatar = array(
            $result['sha1_sum'],
            $result['filename'],
            $result['width'],
            $result['height']
        );
    }
    
    /**
     * Update the user's signature
     *
     * @param string $aSignature to be appended to every post
     *
     * @return void
     */
    public function setSignature($aSignature)
    {
        $sql = "UPDATE Users SET signature = ? WHERE user_id = ".$this->user_id;
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($aSignature));
        $this->signature = $aSignature;
    }
    
    /**
     * Update the user's quote
     *
     * @param string $aQuote The user's quote to be displayed on their profile
     *
     * @return void
     */
    public function setQuote($aQuote)
    {
        $sql = "UPDATE Users SET quote = ? WHERE user_id = ".$this->user_id;
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($aQuote));
        $this->quote = $aQuote;
    }
    
    /**
     * Update the user's timezone
     *
     * @param int $aTimezone The user's 
     *
     * @return void
     */
    public function setTimezone($aTimezone)
    {
        
    }
    

    /**
    * Get reason why user was banned or suspended
    *
    * @return string Displine description
    */
    public function getDisciplineReason()
    {
        $sql = "SELECT description FROM DisciplineHistory 
            WHERE user_id = $this->user_id ORDER BY date DESC LIMIT 1";
        $statement = $this->pdo_conn->query($sql);
        $results = $statement->fetch();
        return $results['description'];
    }
    
    /**
    * Checks the supplied invite code
    *
    * @param string $invite_code Invite code supplied by the user
    * 
    * @return boolean True if invite code is valid
    */
    public function checkInvite($invite_code)
    {
        // Check if invite code exists in the database
        $sql = "SELECT invited_by FROM InviteTree WHERE invite_code 
                COLLATE latin1_general_cs = ? and invited_user IS 
                NULL and created > ".(time()-(60*60*72));

        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($invite_code));
        $rows = $statement->rowCount();

        // If the invite code does not exist
        // check ETI
        if ($statement->rowCount() != 1) {
            $url = "http://boards.endoftheinter.net/scripts/login.php?username="
                .urlencode($invite_code)."&ip=".$_SERVER['REMOTE_ADDR'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_REFERER, "");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $output = curl_exec($curl);
            if ($output == ("1:". $invite_code)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    
    /**
    * Create a new user using the supplied information
    *
    * @param array $request POST request containing email, 
    * username, password, and invite code
    *
    * @return int
    */
    public function registerUser($request, $inviteStatus = 0)
    {
        // Check username against whitelist of allowed characters
        // Alpha numerica characters, underscores, dashes, and spaces
        // no special characters
        // Ensure the user has a valid invite code
        if (!$this->checkInvite(@$request['invite_code']) && $inviteStatus == 1) {
            return -2;
        } else {
            if (!preg_match('/[^A-z0-9.\-_\ ]/', $request['username'])) {
                // Check if a username exists
                $sql = "SELECT Users.user_id FROM Users where Users.username=?";
                $statement = $this->pdo_conn->prepare($sql);
                $statement->execute(array($request['username']));
                if ($statement->rowCount() == 1) {
                    return -1;
                } else {
                    // Everything is valid, add user
                    $sql2 = "INSERT INTO Users (username, private_email, 
                        password, account_created) VALUES (?, ?, ?, ".time().")";

                    $statement2 = $this->pdo_conn->prepare($sql2);
                    $data = array($request['username'],
                        $request['email'],
                        password_hash(
                            $request['password'],
                            $this->hash_algorithm,
                            $this->hash_options
                        )
                    );
                    $statement2->execute($data);

                    // Add new user to the invite tree of the inviter
                    $sql3 = "UPDATE InviteTree SET invited_user="
                        .$this->pdo_conn->lastInsertId()." WHERE invite_code=?";
                    $statement3 = $this->pdo_conn->prepare($sql3);
                    $statement3->execute(array($request['invite_code']));
                    if ($statement2->rowCount()) {
                        return 1;
                    }
                }
            }
        }
    }
    
    /**
    * Check the inventory for an item
    * 
    * @param int $class_id
    *
    * @return List of requested items
    * @todo Change to use for all items
    */
    public function checkInventory($class_id = null)
    {
        $sql = "SELECT DISTINCT ShopItems.name FROM ShopItems 
            LEFT JOIN ShopTransactions USING(item_id) 
            LEFT JOIN Inventory USING (transaction_id) 
            WHERE Inventory.user_id=$this->user_id
            AND ShopItems.item_id=5";

        $statement = $this->pdo_conn->query($sql);
        return $statement->fetchAll();
    }
    
    /**
    * Get a list of files uploaded by the user
    *
    * @return array List of files uploaded by the user
    */
    public function getUploads()
    {
        $sql = "SELECT UploadLog.filename, UploadedImages.sha1_sum, 
            MaxCreated FROM (SELECT UploadLog.filename, UploadLog.user_id, 
            UploadLog.image_id, UploadedImages.sha1_sum, MAX(UploadLog.created) 
            as MaxCreated FROM UploadLog LEFT JOIN UploadedImages USING(image_id) 
            GROUP BY UploadLog.user_id, UploadLog.image_id) UploadLog LEFT JOIN 
            UploadedImages ON UploadLog.image_id = UploadedImages.image_id
            WHERE UploadLog.user_id=".$this->user_id." GROUP BY UploadLog.user_id, 
            UploadLog.image_id ORDER BY MaxCreated DESC";

        $statement = $this->pdo_conn->query($sql);
        $results = $statement->fetchAll();
        return $results;
    }
    
    /**
    * Get a list of topics that have been posted in
    * by the current user.
    * 
    * @return array Topic data 
    */
    public function getCommentHistory()
    {
        $sql = "SELECT DISTINCT Topics.topic_id, Boards.title as board_title, 
        Topics.title, Topics.board_id, MAX(Messages.posted) as u_last_posted 
            FROM Topics 
            LEFT JOIN Messages USING(topic_id)
            LEFT JOIN Boards USING(board_id) 
            WHERE Messages.user_id=$this->user_id AND Messages.revision_no=0
            GROUP BY Topics.topic_id ORDER BY Messages.posted DESC";
        $statement = $this->pdo_conn->query($sql);
        $topic_data = array();
        for ($i=0; $topic_data_array = $statement->fetch(); $i++) {
            $topic_data[$i]['board_title'] = $topic_data_array['board_title'];
            $topic_data[$i]['topic_id'] = $topic_data_array['topic_id'];
            $topic_data[$i]['u_last_posted'] = $topic_data_array['u_last_posted'];
            $topic_data[$i]['title'] = htmlentities($topic_data_array['title']);
            
            $sql2 = "SELECT MAX(Messages.posted) as last_post FROM Messages
                        WHERE Messages.topic_id=".$topic_data_array['topic_id'].
                        " AND Messages.revision_no=0";
            $statement2 = $this->pdo_conn->query($sql2);
            $last_post = $statement2->fetchAll();
            
            // Number of unread posts in a prevously visited topic
            $sql3 = "SELECT COUNT(Messages.message_id) as count FROM Messages
                        WHERE Messages.topic_id=".$topic_data_array['topic_id'].
                        " AND Messages.revision_no=0";
            $statement3 = $this->pdo_conn->query($sql3);
            $msg_count = $statement3->fetchAll();

            $topic_data[$i]['last_post'] = $last_post[0][0];
            $topic_data[$i]['number_of_posts'] = $msg_count[0][0];
        }
        return $topic_data;
    }

    /**
     * Invite a new user to the site by sending an email with an invite code. 
     *
     * @param  string $email Email address to send invite
     * @param  int $transaction_id Transaction ID for the purchased invite, only tracked if invites are not open
     *
     * @todo  Return unused invites to the inventory after they have been expired
     * 
     * @return  boolean True if invite was sent successfully. 
     */

    public function inviteUser($email, $transaction_id = null)
    {
        if ($this->validateEmail($email)) {
            $invite_code = CSRFGuard::websafeEncode(
                mcrypt_create_iv(33, MCRYPT_DEV_URANDOM)
            );

            $sql = "INSERT INTO InviteTree (invite_code, email, invited_by, transaction_id, created)
                    VALUES ('$invite_code', ?, ".$this->getUserID().", ?, ".time().")";
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array($_POST['email'], $transaction_id));

            if ($transaction_id != null) {
                $sql_removeFromInventory = "DELETE FROM Inventory WHERE transaction_id = $transaction_id";
                $statement = $this->pdo_conn->query($sql_removeFromInventory);
            }

            $mail = new PHPMailer();
            $email = $_POST['email'];
            $mail->From = "no-reply@".DOMAIN;
            $mail->FromName = "Do Not Reply";
            $mail->AddAddress($email);

            $mail->WordWrap = 50;
            $mail->IsHTML(true);

            $mail->Subject = "You have been invited to ".SITENAME;
            $mail->Body    = "The user ".$this->getUsername()." has invited you to
                join ".SITENAME." and has specified this address (".$email.") as your 
                email. If you do not know this person, please disregard.<br /><br />
                <br />To confirm your invite, click on the folowing link:<br /><br />
                ".BASEURL."/register.php?code=$invite_code<br /><br />
                After you register, you will be able to use your account. Please take 
                note that if you do not use this invite in the next 3 days, 
                it will expire.";
            $mail->AltBody = "The user ".$this->getUsername()." has invited you to
                join ".SITENAME." and has specified this address (".$email.") as your 
                email. If you do not know this person, please disregard.\n\n
                To confirm your invite, click on the folowing link:\n\n
                ".BASEURL."/register.php?code=$invite_code\n\n
                After you register, you will be able to use your account. Please take 
                note that if you do not use this invite in the next 3 days, 
                it will expire.";

            return $mail->Send();
        } else {
            return false;
        }

    }

    /**
    * Destroys the user's current session by remove the session
    * keys from the database and deleting all relevent cookies
    *
    * @return void
    */
    public function logout()
    {
        if (isset($_COOKIE[AUTH_KEY1]) && isset($_COOKIE[AUTH_KEY2])) {
            // Remove session keys from the database
            // invalidating the session server side
            $sql = "UPDATE Sessions SET session_key1=NULL, session_key2=NULL 
                WHERE session_key1=? AND session_key2=?";
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array($_COOKIE[AUTH_KEY1], $_COOKIE[AUTH_KEY2]));

            // Delete session keys from the browser
            // invalidating the session client side
            setcookie(
                $name = AUTH_KEY1,
                $value = '',
                $expire = 1,
                $path = "/",
                $domain = DOMAIN,
                $secure = USE_SSL,
                $httponly = true
            );
            setcookie(
                $name = AUTH_KEY2,
                $value = '',
                $expire = 1,
                $path = "/",
                $domain = DOMAIN,
                $secure = USE_SSL,
                $httponly = true
            );
        }
        // Remove PHP session cookie
        if (isset($_COOKIE[session_name()])) {
            $params = session_get_cookie_params();
            @session_destroy();
            setcookie(
                $name = session_name(),
                $value = '',
                $expire = 1,
                $path = "/",
                $domain = DOMAIN,
                $secure = USE_SSL,
                $httponly = true
            );
        }
        // Remove anti-csrf cookie
        if (isset($_COOKIE['csrf'])) {
            setcookie(
                $name = "csrf",
                $value = '',
                $expire = 1,
                $path = "/",
                $domain = DOMAIN,
                $secure = USE_SSL,
                $httponly = true
            );
        }
    }
}
