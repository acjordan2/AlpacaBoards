<?php
/*
 * Message.class.php
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

Class Message {
    
    private $_pdo_conn;

    private $_site;

    private $_message_id;

    private $_message;

    private $_topic_id;

    private $_revision_no;

    private $_user_id;

    private $_username;

    private $_title;

    private $_state;

    public function __construct($site, $message_id, $revision_no = 0)
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_site = $site;
        $this->_loadMessage($message_id, $revision_no);
    }

    private function _loadMessage($message_id, $revision_no)
    {
        $sql = "SELECT Messages.topic_id, Messages.message_id, Messages.revision_no, Messages.user_id, 
            Messages.message, Messages.deleted, Users.username, Topics.title, 
            (SELECT Messages.posted FROM Messages WHERE Messages.message_id = :message_id_post
                AND Messages.revision_no = 0) as posted
        FROM Users
        LEFT JOIN Messages USING(user_id)
        LEFT JOIN Topics USING(topic_id)
        WHERE Messages.message_id = :message_id AND Messages.revision_no = :revision_no
        ORDER BY Messages.revision_no DESC LIMIT 1";
        
        $data_loadMessage = array(
            "message_id_post" => $message_id,
            "message_id" => $message_id,
            "revision_no" => $revision_no
        );

        $statement_loadMessage = $this->_pdo_conn->prepare($sql);
        $statement_loadMessage->execute($data_loadMessage);

        if ($statement_loadMessage->rowCount() == 1) {
            $results = $statement_loadMessage->fetch();
            $statement_loadMessage->closeCursor();

            $this->_message_id = $results['message_id'];
            $this->_user_id = $results['user_id'];
            $this->_username = $results['username'];
            $this->_title = $results['title'];
            $this->_state = $results['deleted'];
            $this->_posted = $results['posted'];
            $this->_topic_id = $results['topic_id'];
            $this->_revision_no = $results['revision_no'];

            
            if ($this->_state == 0) {
                $this->_message = $results['message'];
            } elseif ($this->_state == 1) {
                $this->_message = $this->_site->getMessage("message_deleted");
            } elseif ($results['deleted'] == 2) {
                $this->_message = $this->_site->getMessage("message_deleted_moderator");
            }
            
        } else {
            throw new Exception('Message does not exist');
        }
    }

    public function delete($action, $moderator_id = null, $reason = null)
    {
        $sql_delete = "UPDATE Messages SET deleted = :deleted WHERE message_id = ".$this->_message_id;
        $statement_delete = $this->_pdo_conn->prepare($sql_delete);
        $statement_delete->bindParam("deleted", $action);
        $statement_delete->execute();
        $statement_delete->closeCursor();

        if ($action == 2) {
            $sql_modDelete = "INSERT INTO DisciplineHistory 
                (user_id, mod_id, message_id, action_taken, description, date)
                VALUES (".$this->_user_id.", $moderator_id, ".$this->_message_id.", 
                'Message Deleted', :description, ".time().")";
            $statement_modDelete = $this->_pdo_conn->perpare($sql_modDelete);
            $statement_modDelete->bindParam("description", $reason);
            $statement_modDelete->closeCursor();
            $this->_message = $this->_site->getMessage("message_deleted_moderator");
        } else {
            $this->_message = $this->_site->getMessage("message_deleted");
        }
    }

    public function getRevisions()
    {
        $table_name = "Messages";

        $sql = "SELECT revision_no, posted
            FROM $table_name WHERE message_id = ".$this->_message_id.
            " ORDER BY revision_no DESC";
        $statement = $this->_pdo_conn->query($sql);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $results;
    }

    public function getMessage($quote = false)
    {
        if ($quote == true) {
            $message = explode("\n---", $this->_message);

            if (count($message > 1)) {
                array_pop($message);
                $message = trim(implode("---", $message));
            } else {
                $message = $this->_message;
            }

            $quote = "<quote msgid=t,".$this->_topic_id.","
                .$this->_message_id."@".$this->_revision_no.">";
            $quote .= $message;
            $quote .= "</quote>";
            $message = $quote;
        } else {
            $message = $this->_message;
        }
        return $message;
    }

    public function getState()
    {
        return $this->_state;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function getUserId()
    {
        return $this->_user_id;
    }

    public function getPosted()
    {
        return $this->_posted;
    }

    public function getRevisionId()
    {
        return $this->_revision_no;
    }
}
