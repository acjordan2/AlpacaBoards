<?php
/*
 * Topic.class.php
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

class Topic
{
    /**
     * Database connection object
     * @var db_connection
     */
    private $_pdo_conn;

    /**
     * Topic ID
     * @var integer
     */
    private $_topic_id;

    /**
     * Number of pages for provided topic
     * @var integer
     */
    private $_page_count;

    /**
     * Current user ID
     * @var integer
     */
    private $_user_id;

    /**
     * User ID of the topic creator
     * @var integer
     */
    private $_topic_creator;

    /**
     * Amount of messages to show on each topic page,
     * default is 50
     * @var integer
     */
    private $_messages_per_page = 50;

    /**
     * Parser for formating messages
     * @var parser
     */
    private $_parser;

    /**
     * Create a new Topic object
     * @param integer $topic_id Topic ID
     * @param integer $user_id  User ID of the current user
     */
    public function __construct(User $user, Parser $parser, $topic_id = null)
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_parser = $parser;
        $this->_user_id = $user->getUserId();
        if (is_numeric($topic_id)) {
            $this->loadTopic($topic_id);
        }
    }

    /**
     * Check that the provided topic ID exists
     * @param  integer   $topic_id Topic ID
     * @throws exception If the provided topic ID does not exist
     * @return void
     */
    public function loadTopic($topic_id)
    {
        $sql = "SELECT topic_id, title, user_id FROM Topics WHERE topic_id = :topic_id";
        $data = array(
            "topic_id" => $topic_id
        );
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        if ($statement->rowCount() == 1) {
            $results = $statement->fetch();
            $statement->closeCursor();
            $this->_topic_id = $results['topic_id'];
            $this->_topic_title = $results['title'];
            $this->_topic_creator = $results['user_id'];
        } else {
            throw new Exception('Topic does not exist');
        }
    }

    /**
     * Create a new topic
     * @param  string $title   Title of the topic
     * @param  array  $tags    Array of topical tag IDs
     * @param  string $message Opening message of the topic
     * @return integer         New topic ID
     */
    public function createTopic($title, $tags, $message)
    {
        $time = time();
        // New Topic
        $sql_topic = "INSERT INTO Topics (user_id, title, board_id, created)
            VALUES(:user_id, :title, 42, $time)";

        $statement_topic = $this->_pdo_conn->prepare($sql_topic);
        $data_topic = array("user_id" => $this->_user_id,
                      "title" => $title);
        $statement_topic->execute($data_topic);
        $topic_id = $this->_pdo_conn->lastInsertId();

        // First message of new topic
        $sql_message = "INSERT INTO Messages (user_id, topic_id, message, posted)
            VALUES(:user_id, :topic_id, :message, $time)";
        $statement_message = $this->_pdo_conn->prepare($sql_message);
        $data_message = array("user_id" => $this->_user_id,
                        "topic_id" => $topic_id,
                        "message" => $message);
        $statement_message->execute($data_message);
        $this->_parser->map($message, $this->_user_id, $topic_id);

        //Topical Tags
        $sql_tags = "INSERT INTO Tagged (data_id, tag_id, type)
            VALUES ($topic_id, :tag_id, 1)";
        $statement_tags = $this->_pdo_conn->prepare($sql_tags);
        foreach ($tags as $tag) {
            $data_tags = array("tag_id" => $tag);
            $statement_tags->execute($data_tags);
        }
        
        return $topic_id;
    }

    /**
     * Get topic list
     * @param  integer $page   Page number of topic list
     * @param  string  $filter Filter for topic lists
     * @return array           Array of topic list data
     */
    public function getTopics($page = 1, $filter = null)
    {
        $offset = 50*($page-1);

        $data = array(
            'offset' => $offset
        );

        $sql = "SELECT Topics.title, 
            Topics.topic_id, 
            Topics.user_id, 
            Users.username, 
            MAX(Messages.posted) AS posted 
            FROM Topics LEFT JOIN Users USING(user_id) 
            LEFT JOIN Messages USING(topic_id)
            WHERE Messages.revision_no = 0
            GROUP BY topic_id ORDER BY posted DESC LIMIT 50 OFFSET :offset";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $topic_data = $statement->fetchAll();
        $statement->closeCursor();

        for ($i=0; $i < count($topic_data); $i++) {
            $topic_data[$i]['title'] = htmlentities($topic_data[$i]['title']);

            // Get total message count
            $sql_getMsgCount = "SELECT COUNT(DISTINCT topic_id, message_id) FROM Messages
                WHERE topic_id = ".$topic_data[$i]['topic_id'];
            $statement_getMsgCount = $this->_pdo_conn->query($sql_getMsgCount);
            $msg_count = $statement_getMsgCount->fetchAll();
            $statement_getMsgCount->closeCursor();
            $topic_data[$i]['number_of_posts'] = $msg_count[0][0];

            // Get new messages since last read
            $sql_history = "SELECT COUNT(Messages.message_id) as count, 
                TopicHistory.message_id as last_message, 
                TopicHistory.page as page FROM Messages 
                LEFT JOIN TopicHistory Using(topic_id)
                WHERE Messages.topic_id=".$topic_data[$i]['topic_id']." AND 
                Messages.message_id > TopicHistory.message_id AND 
                TopicHistory.user_id = ".$this->_user_id;
            $statement_history = $this->_pdo_conn->query($sql_history);
            $history_count = $statement_history->fetchAll();
            $statement_history->closeCursor();

            $topic_data[$i]['history'] = $history_count[0]['count'];
            $topic_data[$i]['page'] = $history_count[0]['page'];
            $topic_data[$i]['last_message'] = $history_count[0]['last_message'];
            $tag = new Tag($this->_user_id);
            $topic_data[$i]['tags'] = $tag->getObjectTags($topic_data[$i]['topic_id'], 1);
            if (in_array_r("Anonymous", $topic_data[$i]['tags'])) {
                $topic_data[$i]['username'] = "Human";
                $topic_data[$i]['user_id'] = -1;
            }
        }
        return $topic_data;
    }


    /**
     * Get topic messages
     * @param  integer $page   Page number of topic
     * @param  string  $filter Filter for returned messages (eg user:1 will return messages from user with ID 1)
     * @return array           Messages
     */
    public function getMessages($page = 1, $filter = null, $anonymous = false)
    {
        $offset = $this->_messages_per_page * ($page - 1);
        $this->_page_count = $this->getPageCount();

        $data = array();
        $data['topic_id'] = $this->_topic_id;
        $data['offset'] = $offset;
        $sql = "SELECT Messages.message_id, 
            MAX(Messages.revision_no) as revision_id,
            Messages.user_id, Messages.deleted,
            Users.username, Users.avatar,
            Users.level, UploadedImages.sha1_sum,
            UploadedImages.thumb_width,
            UploadedImages.thumb_height,
            UploadLog.filename,
            Messages.message,
            MIN(Messages.posted) as posted
            FROM Messages
            LEFT JOIN Users
                ON Users.user_id = Messages.user_id
            LEFT JOIN UploadLog
                ON Users.avatar = UploadLog.uploadlog_id
            LEFT JOIN UploadedImages
                On UploadedImages.image_id = UploadLog.image_id
            WHERE
                Messages.topic_id = :topic_id AND Messages.type = 0";
        if (!is_null($filter)) {
            $filter_data = explode(":", $filter);
            switch ($filter_data[0]) {
                case 'user':
                    $sql .= " AND Messages.user_id = :user_id ";
                    $data['user_id'] = $filter_data[1];
                    break;
    
                case 'newMessages':
                    $replace = "LEFT JOIN TopicHistory ON 
                        TopicHistory.topic_id = Messages.topic_id WHERE";
                    $sql = str_replace("WHERE", $replace, $sql);
                    $sql .= " AND Messages.message_id > TopicHistory.message_id 
                        AND TopicHistory.user_id = :user_id ";
                    $data['user_id'] = $filter_data[1];
                    break;

                default:
                    # code...
                    break;
            }
        }
        $sql .= " GROUP BY Messages.message_id DESC
            ORDER BY posted ASC LIMIT ".$this->_messages_per_page." OFFSET :offset";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $results = $statement->fetchAll();
        $statement->closeCursor();
        
        if ($this->isTaggedWith("Anonymous") === true) {
            $sql_getUsers = "SELECT DISTINCT(user_id)
                FROM Messages WHERE topic_id = ".$this->_topic_id."
                ORDER BY message_id";
            $statement_getUsers = $this->_pdo_conn->query($sql_getUsers);
            $results_getUsers = $statement_getUsers->fetchAll(PDO::FETCH_COLUMN, 0);
        }


        foreach ($results as &$key) {
            if (!is_null($key['avatar'])) {
                $key['filename'] = urlencode($key['filename']).".jpg";
            }
            if ($key['level'] == 1) {
                $user = new User($key['user_id']);
                $key['title'] = $user->getAccessTitle();
                $key['title_color'] = $user->getTitleColor();
            }
            if ($key['deleted'] == 1) {
                $key['message'] = $GLOBALS['locale_messages']['message']['deleted'];
            } elseif ($key['deleted'] == 2) {
                $key['message'] = $GLOBALS['locale_messages']['message']['deleted_moderator'];
            } else {
                $key['message'] = $this->_parser->parse($key['message']);
                $key['message'] = autolink($key['message']);
            }
            if ($anonymous == true) {
                $human = array_search($key['user_id'], $results_getUsers)+1;
                $key['username'] = "Human #".$human;
                $key['user_id'] = $human * -1;
                $key['sha1_sum'] = null;
                $key['filename'] = null;
            }
        }
        if (count($results) > 0) {
            $last_message = end($results);
            $this->updateHistory($last_message['message_id'], $page);
        }
        return $results;
    }

    /**
     * Get total number of pages for the topic
     * @return integer Page count
     */
    public function getPageCount()
    {
        $data = array(
            "topic_id" => $this->_topic_id
        );
        $sql = "SELECT COUNT(DISTINCT(message_id)) as count FROM Messages
            WHERE Messages.topic_id = :topic_id AND Messages.type = 0";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $results = $statement->fetch();
        $statement->closeCursor();
        $page_count = intval($results['count']/$this->_messages_per_page);
        if ($results['count'] % $this->_messages_per_page != 0) {
            $page_count++;
        }
        return $page_count;
    }

    /**
     * Update viewing history of topic to keep track of the last message read by the user
     * @param  integer $message_id Last read message ID
     * @param  integer $page       Last page the user was on
     * @return void
     */
    public function updateHistory($message_id, $page)
    {
        $sql = "INSERT INTO TopicHistory (topic_id, user_id, message_id, date, page)
                VALUES (:topic_id, $this->_user_id, $message_id, ".time().", :page)
                ON DUPLICATE KEY UPDATE 
                message_id = IF(message_id < $message_id, $message_id, message_id), 
                date=".time().", page= IF(page < :page2, :page3, page)";
        $data = array(
            "topic_id" => $this->_topic_id,
            "page" => $page,
            "page2" => $page,
            "page3" => $page
        );
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
    }

    /**
     * Edit or post a new message in a topic
     * @param  string  $message     Message to be posted
     * @param  integer $message_id  Message ID of message to be edited, null if its a new message
     * @return boolean              False if message to be edited does not exist
     */
    public function postMessage($message, $message_id = null)
    {
        if ($this->isTaggedWith('Archived') === false) {
            // Message ID was not provided, post a new message
            if (is_null($message_id)) {
                $sql = "INSERT INTO Messages ( user_id, topic_id, message, posted)
                    VALUES (:user_id, :topic_id, :message, ".time().")";
                $data = array(
                    "user_id" => $this->_user_id,
                    "topic_id" => $this->_topic_id,
                    "message" => $message
                );
                $statement = $this->_pdo_conn->prepare($sql);
                if ($statement->execute($data)) {
                    $this->_parser->map($message, $this->_user_id, $this->_topic_id);
                    $statement->closeCursor();
                    return true;
                } else {
                    return false;
                }
            } else {
                // Message ID was provided, edit exiting message
                $sql = "SELECT MAX(revision_no) as revision_no FROM Messages
                    WHERE Messages.message_id = :message_id AND Messages.user_id = :user_id";
                $data = array(
                    "message_id" => $message_id,
                    "user_id" => $this->_user_id
                );
                $statement = $this->_pdo_conn->prepare($sql);
                $statement->execute($data);
                $row = $statement->fetch();
                $statement->closeCursor();
                if ($statement->rowCount() == 1) {
                    // Provided message ID exists
                    $revision_number = $row[0] + 1;
                    $sql2 = "INSERT INTO Messages (message_id, user_id, topic_id, message, 
                        revision_no, posted) 
                        VALUES(:message_id, :user_id, :topic_id, :message, 
                    $revision_number, ".time().")";
                    $data2 = array(
                        "message_id" => $message_id,
                        "user_id" => $this->_user_id,
                        "topic_id" => $this->_topic_id,
                        "message" => $message
                    );
                    $statement2 = $this->_pdo_conn->prepare($sql2);
                    return $statement2->execute($data2);
                } else {
                    // Provided message ID does not exist
                    return false;
                }
            }
        } else {
            // Topic is archived or locked
            return false;
        }
    }

    /**
     * Check if a topic is tagged with Anonymous
    */
    public function isAnonymous()
    {
        $sql = "SELECT TopicalTags.title  FROM Tagged 
            LEFT JOIN TopicalTags USING(tag_id)
            WHERE Tagged.data_id = :topic_id
            AND Tagged.type = 1 AND TopicalTags.title = 'Anonymous'";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("topic_id", $this->_topic_id);
        $statement->execute();
        $results = $statement->fetch();
        if ($results != null) {
            return true;
        } else {
            return false;
        }
    }

    public function isTaggedWith($tag)
    {
        $sql = "SELECT TopicalTags.title  FROM Tagged 
            LEFT JOIN TopicalTags USING(tag_id)
            WHERE Tagged.data_id = :topic_id
            AND Tagged.type = 1 AND TopicalTags.title = :tag";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("topic_id", $this->_topic_id);
        $statement->bindParam("tag", $tag);
        $statement->execute();
        $results = $statement->fetch();
        if ($results != null) {
            return true;
        } else {
            return false;
        }
           
    }

    /**
     * Poll database for new message, keep connection open for 60 seconds
     * or until a new message is posted, which ever comes first
     * @return array Message
     */
    public function pollMessage()
    {
        $message = null;
        for ($i=0; count($message) == 0 && $i < 60; $i++) {
            $message = $this->getMessages(1, "newMessages:".$this->_user_id, $this->isAnonymous());
            if (count($message) == 0) {
                // No new messages found, sleep for 1 second and check again
                sleep(1);
            }
        }
        return $message;
    }

    /**
     * Pin topic on the main board for 24 hours
     * @return void
     */
    public function pinTopic()
    {
        $sql = "INSERT INTO StickiedTopics (topic_id, user_id, created)
            VALUES(:topic_id, :user_id, ".time().")";
        $data = array(
            "topic_id" => $this->_topic_id,
            "user_id" => $this->_user_id
        );
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $statement->closeCursor();
    }

    /**
     * Get number of readers in the last 5 minutes
     * for the current topic
     * @return integer Number of readers
     */
    public function getReaders()
    {
        $sql = "SELECT COUNT(topic_id) FROM TopicHistory
            WHERE topic_id = :topic_id AND date >= ".(time() - 60*5);
        $data = array(
            "topic_id" => $this->_topic_id
        );
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $row = $statement->fetch();
        $statement->closeCursor();
        return $row[0];
    }

    /**
     * Get topic ID
     * @return integer Current topic ID
     */
    public function getTopicID()
    {
        return $this->_topic_id;
    }

    /**
     * Get the title of the current topic
     * @return string Title of topic
     */
    public function getTopicTitle()
    {
        return $this->_topic_title;
    }

    
    /**
     * Get the user ID of the topic creator
     * @return integer User ID
     */
    public function getTopicCreator()
    {
        return $this->_topic_creator;
    }
}
