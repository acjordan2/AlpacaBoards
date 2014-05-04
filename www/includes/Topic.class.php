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
    public function __construct($topic_id, $user_id)
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_loadTopic($topic_id);
        $this->_parser = new Parser();
        $this->_user_id = $user_id;
    }

    /**
     * Check that the provided topic ID exists
     * @param  integer   $topic_id Topic ID
     * @throws exception If the provided topic ID does not exist
     * @return void
     */
    private function _loadTopic($topic_id)
    {
        $sql = "SELECT topic_id, title FROM Topics WHERE topic_id = :topic_id";
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
        } else {
            throw new Exception('Topic does not exist');
        }
    }

    /**
     * Get topic messages
     * @param  integer $page   Page number of topic
     * @param  string  $filter Filter for returned messages (eg user:1 will return messages from user with ID 1)
     * @return array           Messages
     */
    public function getMessages($page = 1, $filter = null)
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
                Messages.topic_id = :topic_id";
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
            ORDER BY posted ASC LIMIT 50 OFFSET :offset";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $results = $statement->fetchAll();
        $statement->closeCursor();
        
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
            WHERE Messages.topic_id = :topic_id";
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
            $message = $this->getMessages(1, "newMessages:".$this->_user_id);
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
}
