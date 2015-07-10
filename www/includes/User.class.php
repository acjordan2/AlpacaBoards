<?php
/*
 * User.class.php
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

if (!function_exists("password_hash")) {
    include "Password.lib.php";
}

class User
{
    /**
     * Database connection object
     * @var db_connection
     */
    private $_pdo_conn;

    /**
     * Site object
     * @var site
     */
    private $_site;

    /**
     * Hashing algorithm used for storing passwords
     * @var integer
     */
    private $_hash_algo = PASSWORD_DEFAULT;

    /**
     * Hashing options for password storage
     * @var array
     */
    private $_hash_options = array();

    /**
     * Current user ID
     * @var integer
     */
    private $_user_id;

    /**
     * User's username
     * @var string
     */
    private $_username;

    /**
     * User's public email address
     * @var string
     */
    private $_email;

    /**
     * User's private email address 
     * @var string
     */
    private $_private_email;

    /**
     * Instant messaging username
     * @var string
     */
    private $_instant_message;

    /**
     * Last active time for the current account in UTC unix timestamp
     * @var integer
     */
    private $_last_active;

    /**
     * User's status
     * @var integer
     */
    private $_status;

    /**
     * User's signature that is appended to all posts
     * @var string
     */
    private $_signature;

    /**
     * Quote to be displayed on the user's profile
     * @var string
     */
    private $_quote;

    /**
     * Timezone settings
     * @var integer
     */
    private $_timezone;

    /**
     * UTC unix timestamp from when the account was created. 
     * @var integer
     */
    private $_created;

    /**
     * Permissions Level
     * @var integer
     */
    private $_level;

    /**
     * Avatar of the user
     * @var array
     */
    private $_avatar;

    /**
     * User account permssions
     * @var array
     */
    private $_permissions;

    public function __construct($site, $user_id = null)
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_site = $site;
        if (!is_null($user_id)) {
            $this->_loadUserById($user_id);
        }
    }

    /**
     * Get user data by supplied ID
     * @param  integer $user_id User ID
     * @throws exception        If user does not exist
     * @return void
     */
    private function _loadUserById($user_id)
    {
        $sql_loadUserById = "SELECT Users.user_id, Users.username, 
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
        WHERE Users.user_id=:user_id";

        $statement_loadUserById = $this->_pdo_conn->prepare($sql_loadUserById);
        $statement_loadUserById->bindParam(":user_id", $user_id);
        $statement_loadUserById->execute();

        if ($statement_loadUserById->rowCount() == 1) {
            $results = $statement_loadUserById->fetch();
            $this->_setUserData($results);
        } else {
            throw new Exception('User does not exist');
        }

    }

    /**
     * Authenticate a user using session cookies
     * @return boolean True if the user is authenticated
     */
    public function authenticateWithCookie()
    {
        $sql_authenticate = " SELECT Users.user_id, Users.username, 
            Users.email, Users.private_email, Users.instant_messaging, Users.account_created,
            Users.last_active, Users.status, Users.avatar, UploadedImages.sha1_sum,
            UploadedImages.height, UploadedImages.width, UploadedImages.thumb_width,
            UploadedImages.thumb_height, UploadLog.filename, Users.signature, Users.quote, 
            Users.timezone, Users.level 
            FROM Users 
            LEFT JOIN UploadLog ON Users.avatar=UploadLog.uploadlog_id
            LEFT JOIN UploadedImages ON UploadLog.image_id = UploadedImages.image_id
            INNER JOIN Sessions on Users.user_id=Sessions.user_id
            WHERE Sessions.session_key1=:session_key1 AND Sessions.session_key2=:session_key2
            AND Sessions.useragent=:useragent";

        $statement_authenticate = $this->_pdo_conn->prepare($sql_authenticate);
        $data_authenticate = array(
            "session_key1" => $_COOKIE[AUTH_KEY1],
            "session_key2" => $_COOKIE[AUTH_KEY2],
            "useragent" => $_SERVER['HTTP_USER_AGENT']
        );

        $statement_authenticate->execute($data_authenticate);
        if ($statement_authenticate->rowCount() == 1) {
            // Cookies are valid, set user data
            $user_data = $statement_authenticate->fetch();
            $this->_setUserData($user_data);
            $this->_updateActivity();
            return true;
        } else {
            // Cookies are invalid, remove them
            setcookie(
                $name = AUTH_KEY1,
                $value = "",
                $expire = -1,
                $path = "/",
                $domain = $this->_site->getDomain(),
                $secure = USE_SSL,
                $httponly = true
            );
            setcookie(
                $name = AUTH_KEY2,
                $value = "",
                $expire = -1,
                $path = "/",
                $domain = $this->_site->getDomain(),
                $secure = USE_SSL,
                $httponly = true
            );
            return false;
        }
    }

    /**
     * Authenicate a user with a username and password
     * @param  string $username Username
     * @param  string $password Password
     * @return boolean          True if valid credentials are provided
     */
    public function authenticateWithCredentials($username, $password)
    {
        // Get password hash stored in the database associated with provided usernam
        $sql_authenticate = "SELECT user_id, password FROM Users WHERE username = :username";
        $statement_authenticate = $this->_pdo_conn->prepare($sql_authenticate);
        $statement_authenticate->bindParam(":username", $username);
        $statement_authenticate->execute();

        if ($statement_authenticate->rowCount() == 1) {
            // Username exists, check hashes
            $user_info = $statement_authenticate->fetch();
            if (password_verify($password, $user_info['password'])) {
                // Password hashes match
                if (password_needs_rehash($user_info['password'], $this->_hash_algo, $this->_hash_options)) {
                    // Rehash password using updated settings
                    $this->_username = $username;
                    $this->updatePassword($password);
                }

                $this->_user_id = $user_info['user_id'];
                // Create session cookies
                $session_key1 = base64_encode(mcrypt_create_iv(48, MCRYPT_DEV_URANDOM));
                $session_key2 = base64_encode(mcrypt_create_iv(48, MCRYPT_DEV_URANDOM));

                $session_key1 = strtr($session_key1, '+/=', '-_,');
                $session_key2 = strtr($session_key2, '+/=', '-_,');

                setcookie(
                    $name = AUTH_KEY1,
                    $value = $session_key1,
                    $expire = 0,
                    $path = "/",
                    $domain = $this->_site->getDomain(),
                    $secure = USE_SSL,
                    $httponly = true
                );
                setcookie(
                    $name = AUTH_KEY2,
                    $value = $session_key2,
                    $expire = 0,
                    $path = "/",
                    $domain = $this->_site->getDomain(),
                    $secure = USE_SSL,
                    $httponly = true
                );
                
                // Associate session with IP address and user agent
                $sql_session = "INSERT INTO Sessions (user_id, session_key1, session_key2, created,
                    last_active, ip, x_forwarded_for, useragent) VALUES (".$user_info['user_id'].", 
                    '$session_key1', '$session_key2', ".time().", ".time().", :ip, :x_forwarded_for, 
                    :useragent)";
                $statement_session = $this->_pdo_conn->prepare($sql_session);
                $session_data = array(
                    "ip" => $_SERVER['REMOTE_ADDR'],
                    "x_forwarded_for" => @$_SERVER['HTTP_X_FORWARDED_FOR'],
                    "useragent" => $_SERVER['HTTP_USER_AGENT']
                );
                $statement_session->execute($session_data);
                $this->_updateActivity($session_key1, $session_key2);
                return true;
            } else {
                // Password does not match
                return false;
            }
        } else {
            // Username does not  match, create arbitrary hash to prevent timing attacks
            password_hash("password", $this->_hash_algo, $this->_hash_options);
            return false;
        }

    }

    /**
     * Set the global user data from an using the structure from the Users database table
     * @param array $user_data Array of user data
     * @return void
     */
    private function _setUserData($user_data)
    {
        $this->_user_id = $user_data['user_id'];
        $this->_username = $user_data['username'];
        $this->_email = $user_data['email'];
        $this->_private_email = $user_data['private_email'];
        $this->_instant_message = $user_data['instant_messaging'];
        $this->_last_active = $user_data['last_active'];
        $this->_status = $user_data['status'];
        $this->_signature = $user_data['signature'];
        $this->_quote = $user_data['quote'];
        $this->_timezone = $user_data['timezone'];
        $this->_created = $user_data['account_created'];
        $this->_level[0] = $user_data['level'];

        $this->_avatar = array(
            'sha1_sum' => $user_data['sha1_sum'],
            'filename' => $user_data['filename'],
            'extension' => end(explode(".", $user_data['filename'])),
            'width' =>    $user_data['width'],
            'height' => $user_data['height'],
            'thumb_width' => $user_data['thumb_width'],
            'thumb_height' => $user_data['thumb_height']
        );

        // Get user permissions
        if ($this->_level[0] != 0) {
            $sql_permissions = "SELECT * FROM StaffPermissions WHERE position_id = ".$this->_level[0];
            $statement_permissions = $this->_pdo_conn->query($sql_permissions);
            $this->_permissions = $statement_permissions->fetch();
        }

    }

    /**
     * Update the user's and the session's last active time 
     * @return void
     */
    private function _updateActivity($session_key1 = null, $session_key2 = null)
    {
        if (is_null($session_key1)) {
            $session_key1 = $_COOKIE[AUTH_KEY1];
        }
        if (is_null($session_key2)) {
            $session_key2 = $_COOKIE[AUTH_KEY2];
        }

        // Update user activtiy
        $sql_updateUserActivity = "UPDATE Users SET last_active = ".time().
            " WHERE user_id = ".$this->_user_id;
        $statement_updateUserActivity  = $this->_pdo_conn->query($sql_updateUserActivity);

        // Update user session activity
        $sql_updateSessionActivity = "UPDATE Sessions SET last_active=".time().
            " WHERE session_key1 = :session_key1 AND session_key2 = :session_key2";
        $statement_updateSessionActivity = $this->_pdo_conn->prepare($sql_updateSessionActivity);
        $data_session = array(
            "session_key1" => $session_key1,
            "session_key2" => $session_key2
        );
        $statement_updateSessionActivity->execute($data_session);
    }

    /**
     * Update the users password, if a new password is not provided, the old one is 
     * rehashed with the current hashing options
     * @param  string  $oldPassword User's current password
     * @param  string  $newPassword User's new password, null if just rehashing old password
     * @param  boolean $reset       True if the user forgot there password and used the password rest
     * @return boolean              True if supplied old password matches the database record
     */
    public function updatePassword($oldPassword, $newPassword = null, $reset = false)
    {
        if ($newPassword == null) {
            // If a new password is not set, the old one is rehashed using the
            // current hashing options
            $newPassword = $oldPassword;
        }

        $sql_checkPassword = "SELECT password FROM Users WHERE username = :username";
        $statement_checkPassword = $this->_pdo_conn->prepare($sql_checkPassword);
        $statement_checkPassword->bindParam("username", $this->_username);
        $statement_checkPassword->execute();
        $row = $statement_checkPassword->fetch();

        if (password_verify($oldPassword, $row['password']) || $reset == true) {
            // Old password matches
            $hash = password_hash($newPassword, $this->_hash_algo, $this->_hash_options);
            $sql_updatePassword = "UPDATE Users SET password = '$hash'
                WHERE username = :username";
            $statement_updatePassword = $this->_pdo_conn->prepare($sql_updatePassword);
            $statement_updatePassword->bindParam("username", $this->_username);
            $statement_updatePassword->execute();
            return true;
        } else {
            // Old password does not match
            return false;
        }
    }

    /**
     * Destroy a user's session by removing it remove the database
     * and removing the cookies
     * @return void
     */
    public function logout()
    {
        if (isset($_COOKIE[AUTH_KEY1]) && isset($_COOKIE[AUTH_KEY2])) {
            // Remove session keys from the database
            $sql_logout = "UPDATE Sessions SET session_key1=NULL, session_key2=NULL 
                WHERE session_key1=:session_key1 AND session_key2=:session_key2";
            $statement_logout = $this->_pdo_conn->prepare($sql_logout);
            $data_session = array(
                "session_key1" => $_COOKIE[AUTH_KEY1],
                "session_key2" => $_COOKIE[AUTH_KEY2]
            );
            $statement_logout->execute($data_session);

            // Delete session cookies
            setcookie(
                $name = AUTH_KEY1,
                $value = '',
                $expire = 1,
                $path = "/",
                $domain = $this->_site->getDomain(),
                $secure = USE_SSL,
                $httponly = true
            );
            setcookie(
                $name = AUTH_KEY2,
                $value = '',
                $expire = 1,
                $path = "/",
                $domain = $this->_site->getDomain(),
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
                $domain = $this->_site->getDomain(),
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
                $domain = $this->_site->getDomain(),
                $secure = USE_SSL,
                $httponly = true
            );
        }
    }

    /**
     * Create a new user
     * @param  string  $username       Username
     * @param  string  $password       Password
     * @param  string  $email          Private email address
     * @param  integer $registerStatus Site registration status
     * @return integer                 -1 if user account exists, 1 if user was registered
     */
    public function createUser($username, $password, $email, $registerStatus = 0)
    {
        if (!preg_match('/[^A-z0-9.\-_\ ]/', $username)) {
            $sql = "SELECT Users.user_id FROM Users WHERE Users.username = :username";
            $statement = $this->_pdo_conn->prepare($sql);
            $statement->bindParam(":username", $username);
            $statement->execute();
            if ($statement->rowCount() === 1) {
                return -1;
            } else {
                $hash = password_hash($password, $this->_hash_algo, $this->_hash_options);
                $sql_create = "INSERT INTO Users (username, private_email, password, account_created)
                    VALUES (:username, :email, '".$hash."' ,".time().")";
                $statement_create = $this->_pdo_conn->prepare($sql_create);
                $data_create = array(
                    "username" => $username,
                    "email" => $email
                );
                $statement_create->execute($data_create);
                return 1;
            }
        }
    }

    /**
     * Check if a user has a supplied permission
     * @param  string $search Permission title
     * @return boolean        True if the user has the permission        
     */
    public function checkPermissions($search)
    {
        if (isset($this->_permissions[$search])) {
            return ($this->_permissions[$search] == 1 ? true : false);
        } else {
            return false;
        }
    }

    /**
     * Verify an email address is valid
     * @param  string $email Email address to validate
     * @return boolean       True if email is valid
     */
    public function validateEmail($email)
    {
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
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
     * Update the user's email address.
     * @param  string $email Public Email Address
     * @return void
     */
    public function setEmail($email)
    {
        $sql = "UPDATE Users SET email=:email WHERE user_id=".$this->_user_id;
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam(":email", $email);
        $statement->execute();
        $this->__email = $email;
    }

    /**
     * Set the user's private email address
     * @param string $email Private email addresss
     * @return void
     */
    public function setPrivateEmail($email)
    {
        $sql = "UPDATE Users SET private_email=:email WHERE user_id=".$this->_user_id;
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam(":email", $email);
        $statement->execute();
    }

    /**
    * Update the instant messaging feild on the user's profile
    * @param string $im Instant messaging handle
    * @return void
    */
    public function setInstantMessaging($im)
    {
        $sql = "UPDATE Users set instant_messaging = :im  WHERE user_id = ".$this->_user_id;
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam(":im", $im);
        $statement->execute();
        $this->_instant_messaging = $im;
    }

    /**
     * Update the user's account status
     * @param integer $status      -1 for banned, 0 for active
     * @param integer $user_id     Account user ID to change status
     * @param string  $action      Short description for action taken
     * @param string  $description Reason for action taken
     * @return void
     */
    public function setStatus($status, $user_id, $action, $description)
    {
        $sql = "UPDATE Users SET status = :status WHERE user_id = :user_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $data = array(
            "status" => $status,
            "user_id" => $user_id
        );
        $statement->execute($data);
        
        $sql2 = "INSERT INTO DisciplineHistory 
            (user_id, mod_id, action_taken, description, date)
            VALUES (:user_id, $this->_user_id, :action, :description, ".time().")";
        $statement2 = $this->_pdo_conn->prepare($sql2);
        $data2 = array(
            "user_id" => $user_id,
            "action" => $action,
            "description" => $description
        );
        $statement2->execute($data2);
    }

    /**
     * Set the user's avatar
     * @param  integer $image_id ID of an uploaded image
     * @return void
     */
    public function setAvatar($image_id)
    {
        $sql_getAvatarID = "SELECT UploadLog.filename, UploadedImages.width, 
            UploadedImages.height, UploadedImages.sha1_sum FROM UploadLog
            LEFT JOIN UploadedImages USING(image_id) 
            WHERE UploadLog.uploadlog_id = $image_id";
        $statement_getAvatarID = $this->_pdo_conn->query($sql_getAvatarID);
        $result = $statement_getAvatarID->fetch();
        $sql = "UPDATE Users set Users.avatar = $image_id 
            WHERE user_id=".$this->_user_id;
        $statement = $this->_pdo_conn->query($sql);
        $this->avatar = array(
           "sha1_sum" => $result['sha1_sum'],
           "filename" => $result['filename'],
           "width" => $result['width'],
           "height" => $result['height']
        );
    }

    /**
     * Update the user's signature
     * @param  string $signature User's signature that is appended to every post
     * @return void
     */
    public function setSignature($signature)
    {
        $sql = "UPDATE Users SET signature = :signature WHERE user_id = ".$this->_user_id;
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam(":signature", $signature);
        $statement->execute();
        $this->_signature = $signature;
    }

    /**
     * Update the user's quote to be displayed on their profile
     * @param  string $quote Quote
     * @return void
     */
    public function setQuote($quote)
    {
        $sql = "UPDATE Users SET quote = :quote WHERE user_id = ".$this->_user_id;
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam(":quote", $quote);
        $statement->execute();
        $this->_quote = $quote;
    }

    /**
     * Update timezone settings
     * @param  integer $timezone Timezone
     * @return void
     */
    public function setTimezone($timezone)
    {
        $sql = "UPDATE Users SET timezone = :timezone WHERE user_id = ".$this->_user_id;
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam(":timezone", $timezone);
        $statement->execute();
    }

    /**
    * Get total number of messages posted
    * @return integer Number of posts
    */
    public function getNumberOfPosts()
    {
        $sql = "SELECT COUNT(DISTINCT(Messages.message_id)) AS count 
            FROM Messages WHERE Messages.user_id=$this->_user_id";
        $statement= $this->_pdo_conn->query($sql);
        $row = $statement->fetch();
        return $row['count'];
    }

    /**
    * Get the last message posted by the user
    * helpful for rate limiting
    */
    public function getLastPost() {
        $sql = "SELECT Messages.message_id, Messages.posted FROM
            Messages WHERE Messages.user_id = :user_id AND revision_no = 0
            ORDER by Messages.message_id DESC  LIMIT 1";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("user_id", $this->_user_id);
        $statement->execute();
        return $statement->fetch();
    }

    /**
    * Get total number of topics created by a given user
    * @return integer Number of topics
    */
    public function getNumberOfTopics()
    {
        $sql = "SELECT COUNT(topic_id) AS count FROM Topics 
            WHERE Topics.user_id=$this->_user_id";
        $statement= $this->_pdo_conn->query($sql);
        $row = $statement->fetch();
        return $row['count'];
    }
    
    /**
    * Get highest number of posts in topic created by the user
    * @return integer Number of posts in the topic
    */
    public function getPostsInBestTopic()
    {
        $sql = "SELECT Topics.topic_id, COUNT(DISTINCT(Messages.message_id)) 
            as count FROM Topics LEFT JOIN Messages USING(topic_id) WHERE 
            Topics.user_id=$this->_user_id GROUP BY Topics.topic_id 
            ORDER BY count DESC";
        $statement= $this->_pdo_conn->query($sql);
        $row = $statement->fetchAll();
        if (!empty($row)) {
            return $row[0]['count'];
        } else {
            return 0;
        }
    }
    
    /**
    * Get total number of topics made by a given user that have no replies
    * @return integer Number of topics
    */
    public function getNoReplyTopics()
    {
        $sql = "SELECT COUNT(Topics.topic_id) AS count FROM Topics 
            LEFT JOIN Messages USING(topic_id) WHERE 
            Topics.user_id=$this->_user_id GROUP BY Topics.topic_id 
            HAVING COUNT(DISTINCT(Messages.Message_id))<2 ";
        $statement= $this->_pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return sizeof($row);
    }
    
    /**
    * Get total number of links added from a given user
    * @return integer Number of links
    */
    public function getNumberOfLinks()
    {
        $sql = "SELECT COUNT(Links.link_id) AS count 
            FROM Links WHERE user_id=$this->_user_id";
        $statement = $this->_pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return $row[0]['count'];
    }
    
    /**
    * Get total number of votes accumulated for all the links added by a given user
    * @return integer Number of votes
    */
    public function getNumberOfVotes()
    {
        $sql = "SELECT COUNT(LinkVotes.vote) as count FROM LinkVotes 
                    LEFT JOIN Links USING(link_id) 
                    WHERE Links.user_id = $this->_user_id";
        $statement = $this->_pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return $row[0]['count'];
    }
    
    /**
    * Get the average rating for all added links 
    * @return float Vote average 
    */
    public function getVoteAverage()
    {
        $sql = "SELECT SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) as average
            FROM LinkVotes LEFT JOIN Links USING(link_id) 
            WHERE Links.user_id=$this->_user_id GROUP BY LinkVotes.link_id";
        $statement = $this->_pdo_conn->query($sql);
        $row = $statement->fetchAll();
        return @$row[0]['average'];
    }
   
    /**
     * Award credit to a user once every 24 hours of activity
     * @return void
     */
    public function awardCredit()
    {
        $sql = "SELECT COUNT(Karma.created) as count, MAX(Karma.created) as created
        FROM Karma WHERE user_id=".$this->_user_id;
        $statement = $this->_pdo_conn->query($sql);
        $row = $statement->fetch();
        if (time() > $row['created'] + (60*60*24)) {
            $sql = "INSERT INTO Karma (user_id, value, created)
                VALUES ($this->_user_id, 1, ".time().")";
            $this->_pdo_conn->query($sql);
        }
    }

    /**
     * Get total amount of availble credits for a user
     * @return integer Number of availbile credits
     */
    public function getCredits()
    {
        $sql = "SELECT SUM(Karma.value) as value1, (SELECT SUM(ShopTransactions.value)
            FROM ShopTransactions WHERE user_id=$this->_user_id) as value2
            FROM Karma WHERE Karma.user_id=$this->_user_id GROUP BY user_id";
        $statement = $this->_pdo_conn->query($sql);
        $row = $statement->fetch();
        return intval($row['value1']-$row['value2']) + $this->getContributionKarma();
    }

    /**
     * Get a user's good karma
     * @return integer Amount of good karma
     * @todo Add ability to give/get good karma
     */
    public function getGoodKarma()
    {
        return 0;
    }
    
    /**
     * Get a user's bad karma
     * @return integer Amount of bad karma
     * @todo Add ability to give/get bad karma
     */
    public function getBadKarma()
    {
        return 0;
    }

    /**
     * Get total number of karma
     * @return integer Total amount of karma for a given user
     */
    public function getKarma()
    {
        return ($this->getGoodKarma() + $this->getContributionKarma()) - $this->getBadKarma();
    }

    /**
     * Calculate the amount of contribution karma a user accumulated
     * from adding links
     * @param  integer $user_id User ID
     * @return integer          Amount of contribution karma
     */
    public function getContributionKarma($user_id = null)
    {
        if ($user_id == null) {
            $user_id = $this->_user_id;
        }
        $sql_getContributionKarma = "SELECT SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank
            FROM LinkVotes LEFT JOIN Links USING (link_id) WHERE Links.user_id = :user_id";
        $statement_getContributionKarma = $this->_pdo_conn->prepare($sql_getContributionKarma);
        $statement_getContributionKarma->bindParam(":user_id", $user_id);
        $statement_getContributionKarma->execute();
        $rank = $statement_getContributionKarma->fetch();
        $contributionKarma = (int)(pow(abs($rank[0])/15, (1/3)));
        if ($rank[0] < 0) {
            $contributionKarma = $contributionKarma * -1;
        }
        return $contributionKarma;
    }

    /**
     * Get items that have been purchased but not yet used. 
     * @param  integer $item_id Item ID to filter by
     * @return array            Array of inventory items
     */
    public function getInventory($item_id = null)
    {
        $sql_getInventory = "SELECT Inventory.transaction_id, ShopTransactions.item_id, 
            ShopItems.name, ShopItems.description 
            FROM Inventory LEFT JOIN ShopTransactions USING(transaction_id) 
            LEFT JOIN ShopItems USING(item_id) WHERE Inventory.user_id=$this->_user_id";
        if ((int) $item_id != 0) {
            $sql_getInventory .= " AND ShopTransactions.item_id = :item_id ORDER BY ShopTransactions.date ASC";
            $statement_getInventory = $this->_pdo_conn->prepare($sql_getInventory);
            $statement_getInventory->bindParam(":item_id", $item_id);
            $statement_getInventory->execute();
        } else {
            $statement_getInventory = $this->_pdo_conn->query($sql_getInventory);
        }
        return $statement_getInventory->fetchAll();
    }

    /**
     * Get reason for displanary action against a user
     * @return string Description of action
     */
    public function getDisciplineReason()
    {
        $sql = "SELECT description FROM DisciplineHistory 
            WHERE user_id = $this->_user_id ORDER BY date DESC LIMIT 1";
        $statement = $this->_pdo_conn->query($sql);
        $results = $statement->fetch();
        return $results['description'];
    }

    /**
     * Get a list of files uploaded by the user
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
            WHERE UploadLog.user_id=".$this->_user_id." GROUP BY UploadLog.user_id, 
            UploadLog.image_id ORDER BY MaxCreated DESC";

        $statement = $this->_pdo_conn->query($sql);
        $results = $statement->fetchAll();
        return $results;
    }

    /**
     * Get a list of topics posted in by the user
     * @return Array List of topics
     * @todo Add unread messages count
     */
    public function getCommentHistory()
    {
        $sql = "SELECT DISTINCT Topics.topic_id, Topics.topic_id as t, '???' as board_title,
            Topics.title, MAX(Messages.posted) as u_last_posted,
            (SELECT MAX(Messages.posted) FROM Messages WHERE Messages.topic_id = t) as last_post,
            (SELECT COUNT(Messages.message_id) as count FROM Messages WHERE Messages.topic_id = t 
                AND Messages.revision_no = 0) as number_of_posts
            FROM Topics
            LEFT JOIN Messages USING(topic_id)
            WHERE Messages.user_id = ".$this->_user_id." AND Messages.revision_no=0
            GROUP BY Topics.topic_id ORDER BY Messages.posted DESC";
        $statement = $this->_pdo_conn->query($sql);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        foreach ($results as &$key) {
            $key['title'] = htmlentities($key['title']);

        }
        return $results;
    }

    /**
     * Get user ID
     * @return integer User ID
     */
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     * Get username
     * @return string Username
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * Get public email address
     * @return string Public email address
     */
    public function getEmail()
    {
        return $this->_email;
    }
    
    /**
     * Get private email address
     * @return string Private email address
     */
    public function getPrivateEmail()
    {
        return $this->_private_email;
    }
    
   /**
     * The date and time the account was created
     * @return integer Unix timestamp
     */
    public function getAccountCreated()
    {
        return $this->_created;
    }
    
    /**
     * The time the user was last active 
     * @return integer Unix timestamp
     */
    public function getLastActive()
    {
        return $this->_last_active;
    }
    
    /**
     * Get the user's account status
     * @return integer Account status
     */
    public function getStatus()
    {
        return $this->_status;
    }
    
    /**
     * Get the user's avatar
     * @return array Avatar information
     */
    public function getAvatar()
    {
        return $this->_avatar;
    }
    
    /**
     * Get instant messaging handle
     * @return string Instant messaging handle
     */
    public function getInstantMessaging()
    {
        return $this->_instant_message;
    }
    
    /**
     * Get the user's signature
     * @return string The user's signature
     */
    public function getSignature()
    {
        $signature = $this->_signature;
        if (strlen($this->_signature) > 0) {
            $signature = "\n---\n".$signature;
        }
        return $signature;

    }
    
    /**
     * Get the user's quote
     * @return string The user's quote
     */
    public function getQuote()
    {
        return $this->_quote;
    }
    
    /**
     * Get the user's timezone
     * @return string The user's timezone
     */
    public function getTimezone()
    {
        return $this->_timezone;
    }

    /**
    * Get the type of user account
    * @return integer ID of staff position type
    */
    public function getAccessLevel()
    {
        return $this->_level[0];
    }
    
    /**
    * Get the user title of a privileged account
    * @return string Title
    */
    public function getAccessTitle()
    {
        return $this->_permissions['title'];
    }

    /**
     * Get the color of the user's title
     * @return string Color of the title
     */
    public function getTitleColor()
    {
        return $this->_permissions['title_color'];
    }
}
