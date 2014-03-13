<?php
/*
 * Board.class.php
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


class Board
{
    
    private $pdo_conn;
    
    private $board_id;
    
    private $category_id;
    
    private $title;
    
    private $description;
    
    private $user_id;
    
    private $page_rows;
    
    private $page_count;
    
    public function __construct(&$aDatabaseConnection, $aBoardID, $aUser_id)
    {
        $this->board_id = $aBoardID;
        $this->pdo_conn = &$aDatabaseConnection;
        $sql = "SELECT title FROM Boards where board_id = ?";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($this->board_id));
        $row = $statement->fetch();
        $this->title = $row['title'];
        $this->user_id = $aUser_id;
        $this->page_rows = 50;
    }
    
    public function getTopics($page = 1)
    {
        global $allowed_tags;
        $offset = 50*($page-1);
        $statement = $this->pdo_conn->prepare("SELECT Topics.title, 
                                                       Topics.topic_id, 
                                                       Topics.user_id, 
                                                       Users.username, 
                                                       MAX(Messages.posted) AS posted 
                                                FROM Topics 
                                                LEFT JOIN Users 
                                                    USING(user_id) 
                                                LEFT JOIN Messages 
                                                    USING(topic_id)
                                                LEFT JOIN
                                                    StickiedTopics ON StickiedTopics.topic_id = Topics.topic_id 
                                                WHERE Topics.board_id = ? AND (StickiedTopics.topic_id IS NULL OR (StickiedTopics.created  < ".(time()-(60*60*24))." AND StickiedTopics.mod != 1))
                                                 AND Messages.revision_no = 0
                                                GROUP BY topic_id ORDER BY posted DESC LIMIT 50 OFFSET ?;");
         //$this->pdo_conn->query("SHOW STATUS LIKE '%qcache%'");
        //do_conn->query("SHOW STATUS LIKE '%qcache%'");
        $statement->execute(array($this->board_id, $offset));
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $getTopicCount = $this->pdo_conn->query("SELECT topic_id FROM Topics;");
        $topic_count = $getTopicCount->rowCount();
        $this->page_count = intval($topic_count/50);
        if($topic_count % 50 != 0)
            $this->page_count += 1;
        
        $sql2 = "SELECT COUNT(Messages.message_id) as count, TopicHistory.message_id as last_message, TopicHistory.page as page FROM Messages 
                    LEFT JOIN TopicHistory Using(topic_id)
                    WHERE Messages.topic_id=? AND Messages.message_id > TopicHistory.message_id AND TopicHistory.user_id=$this->user_id";
        $statement2 = $this->pdo_conn->prepare($sql2);
        $topic_data = array();
        for($i=0; $topic_data_array = $statement->fetch(); $i++){
            $topic_data[$i]['topic_id'] = $topic_data_array['topic_id'];
            $topic_data[$i]['posted'] = $topic_data_array['posted'];
            $topic_data[$i]['title'] = htmlentities($topic_data_array['title']);
            $topic_data[$i]['username'] = $topic_data_array['username'];
            $topic_data[$i]['user_id'] = $topic_data_array['user_id'];
            # Inefficient - Find if another way is possible
            $get_count = $this->pdo_conn->prepare("SELECT COUNT(DISTINCT topic_id, message_id) FROM Messages
                                                    WHERE topic_id = ?");
            $get_count->execute(array($topic_data[$i]['topic_id']));
            $statement2->execute(array($topic_data[$i]['topic_id']));
            
            $msg_count = $get_count->fetchAll();
            $history_count = $statement2->fetchAll();
            $topic_data[$i]['number_of_posts'] = $msg_count[0][0];
            $topic_data[$i]['history'] = $history_count[0]['count'];
            $topic_data[$i]['page'] = $history_count[0]['page'];
            $topic_data[$i]['last_message'] = $history_count[0]['last_message'];

                                                    
        }
        return $topic_data;
    }
    
    public function getStickiedTopics(){
         /*
         $statement = $this->pdo_conn->prepare("SELECT Messages.topic_id,
                                                    Messages.posted,
                                                    Topics.title,
                                                    Topics.user_id,
                                                    Users.username
                                                FROM Messages
                                                LEFT JOIN 
                                                    Topics USING(topic_id)
                                                LEFT JOIN
                                                    Users on Users.user_id = Topics.user_id
                                                WHERE Messages.message_id IN 
                                                    (SELECT MAX(message_id) 
                                                        FROM Messages 
                                                        GROUP BY topic_id) 
                                                AND Topics.topic_id in 
                                                    (SELECT topic_id FROM StickiedTopics)
                                                AND Messages.posted IN 
                                                    (SELECT MIN(posted) 
                                                    FROM Messages
                                                    GROUP BY message_id)
                                                AND Topics.board_id=?");
                                                */
        $statement = $this->pdo_conn->prepare("SELECT Topics.title, 
                                                       Topics.topic_id, 
                                                       Topics.user_id, 
                                                       Users.username, 
                                                       MAX(Messages.posted) AS posted 
                                                FROM Topics 
                                                LEFT JOIN Users 
                                                    USING(user_id) 
                                                LEFT JOIN Messages 
                                                    USING(topic_id)
                                                RIGHT JOIN
                                                    StickiedTopics ON StickiedTopics.topic_id = Topics.topic_id 
                                                        AND StickiedTopics.topic_id IS NOT NULL
                                                WHERE Topics.board_id = ? AND 
                                                (StickiedTopics.created  > ".(time()-(60*60*24))." OR StickiedTopics.mod=1)
                                                GROUP BY topic_id ORDER BY posted DESC;");    
                                    
        $statement->execute(array($this->board_id));
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        
                
        $sql2 = "SELECT COUNT(Messages.message_id) as count, TopicHistory.message_id as last_message, TopicHistory.page FROM Messages 
            LEFT JOIN TopicHistory Using(topic_id)
            WHERE Messages.topic_id=? AND Messages.message_id > TopicHistory.message_id AND TopicHistory.user_id=$this->user_id";
        $statement2 = $this->pdo_conn->prepare($sql2);
        $topic_data = array();
        for($i=0; $topic_data_array = $statement->fetch(); $i++){
            $topic_data[$i]['topic_id'] = $topic_data_array['topic_id'];
            $topic_data[$i]['posted'] = $topic_data_array['posted'];
            $topic_data[$i]['title'] = $topic_data_array['title'];
            $topic_data[$i]['username'] = $topic_data_array['username'];
            $topic_data[$i]['user_id'] = $topic_data_array['user_id'];
            # Inefficient - Find if another way is possible
            $get_count = $this->pdo_conn->prepare("SELECT COUNT(DISTINCT topic_id, message_id) FROM Messages
                                                    WHERE topic_id = ?");            
            
            $get_count->execute(array($topic_data[$i]['topic_id']));
            $statement2->execute(array($topic_data[$i]['topic_id']));
            
            $msg_count = $get_count->fetchAll();
            $history_count = $statement2->fetchAll();

            $topic_data[$i]['number_of_posts'] = $msg_count[0][0];
            $topic_data[$i]['history'] = $history_count[0]['count'];
            $topic_data[$i]['page'] = $history_count[0]['page'];
            $topic_data[$i]['last_message'] = $history_count[0]['last_message'];
                                                                
        }
        return $topic_data;

    }
    
    public function createTopic($title, $message){
        global $allowed_tags;
        $time = time();
        $statement = $this->pdo_conn->prepare("INSERT INTO Topics (user_id, board_id, title, created)
                                        VALUES(:user_id, :board_id, :title, $time)");
        
        $data = array("user_id" => $this->user_id,
                      "board_id" => $this->board_id,
                      "title" => $title);
        if($statement->execute($data)){
            $statement2 = $this->pdo_conn->prepare("INSERT INTO Messages (user_id, topic_id, message, posted)
                                                        VALUES(:user_id, :topic_id, :message, $time);");
            $topic_id = $this->pdo_conn->lastInsertId();
            $data2 = array("user_id" => $this->user_id,
                            "topic_id" => $topic_id,
                            "message" => $message);
            if($statement2->execute($data2))
                return $topic_id;
            else
                return FALSE;
        }
        else
            return FALSE;
        
    }
        
    public function getTitle(){
        return $this->title;
    }
    
    public function getPageCount(){
        return $this->page_count;
    }
    
    public static function getCategoriesList(){
        
    }
    
    public static function getBoardsByCategory($aCategoryID){
        
    }
    
    public static function getBoardsList(){
        
    }
    
    public function getReaders(){
        $sql = "SELECT COUNT(user_id) FROM Users WHERE last_active > ".(time() - 60*15);
        $statement = $this->pdo_conn->query($sql);
        $row = $statement->fetch();
        return $row[0];
    }
    
}

?>
