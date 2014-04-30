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
    private $_pdo_conn;

    private $_topic_id;

    private $_page_count;

    private $_user_id;

    private $_messages_per_page = 50;

    private $_parser;

    public function __construct($topic_id, $user_id)
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_loadTopic($topic_id);
        $this->_parser = new Parser();
        $this->_user_id = $user_id;
    }

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
        if ($page_count % $this->_messages_per_page != 0) {
            $page_count++;
        }
        return $page_count;
    }

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
        return $statement->execute($data);
    }

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

    public function pollMessage()
    {
        $message = false;
        for ($i=0; $i < !$message && $i < 60; $i++) {
            $message = $this->getMessages(1, "newMessages:".$this->_user_id);
        }
        return $message;
    }

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

    public function getReaders()
    {
        $sql = "SELECT COUNT(topic_id) FROM TopicHistory
                    WHERE topic_id = :topic_id AND date >= ".(time() - 60*15);
        $data = array(
            "topic_id" => $this->_topic_id
        );
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $row = $statement->fetch();
        $statement->closeCursor();
        return $row[0];
    }

    public function getTopicTitle()
    {
        return $this->_topic_title;
    }
}
