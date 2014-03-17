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

class Topic{

    private $pdo_conn;
    
    private $topic_id;
    
    private $topic_title;
    
    private $board_id;
    
    private $board_title;
    
    private $user_id;
    
    private $page_count;
    
    private $exists;
    
    function __construct(&$db_connection, $aTopic_id, $aUser_id){
        $this->pdo_conn = &$db_connection;
        $this->topic_id = $aTopic_id;
        $this->user_id = $aUser_id;
        $statement = $this->pdo_conn->prepare("SELECT Topics.title as topic_title,
                                                    Boards.title as board_title,
                                                    Boards.board_id
                                                FROM Topics
                                                JOIN Boards
                                                    USING(board_id)
                                                WHERE
                                                    Topics.topic_id = ?");
        $statement->execute(array($this->topic_id));
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        if($statement->rowCount()){
            $this->exists = TRUE;
            $row = $statement->fetch();
            $this->topic_title = $row['topic_title'];
            $this->board_title = $row['board_title'];
            $this->board_id = $row['board_id'];
        }
    }
    
    public function getMessages($page=1, $p_user_id=NULL){
        global $allowed_tags;
        $offset = 50*($page-1);
        if(!is_null($p_user_id) and is_numeric($p_user_id))
            $filter_by_user = " AND Messages.user_id = ? ";
        $sql = "SELECT Messages.message_id, 
                MAX(Messages.revision_no) as revision_id,
                Messages.user_id,
                Messages.deleted,
                Users.username,
                Users.avatar,
                Users.level,
                UploadedImages.sha1_sum,
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
                Messages.topic_id = ?".
                @$filter_by_user."
            GROUP BY Messages.message_id DESC 
            ORDER BY posted ASC LIMIT 50 OFFSET ?";
        $statement = $this->pdo_conn->prepare($sql);
        if(!is_null($p_user_id) and is_numeric($p_user_id))
            $statement->execute(array($this->topic_id, $p_user_id, $offset));
        else
            $statement->execute(array($this->topic_id, $offset));
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $getMessageCount = $this->pdo_conn->prepare("SELECT DISTINCT(message_id) FROM Messages WHERE Messages.topic_id = ?;");
        $getMessageCount->execute(array($this->topic_id));
        $topic_count = $getMessageCount->rowCount();
        $this->page_count = intval($topic_count/50);
        if($topic_count % 50 != 0)
            $this->page_count += 1;
        for($i=0; $message_data_array=$statement->fetch(); $i++){
            if(!is_null($message_data_array['avatar'])){
                $avatar_extension = end(explode(".", $message_data_array['filename']));
                $message_data[$i]['avatar'] = $message_data_array['sha1_sum']."/".urlencode(substr($message_data_array['filename'],0,-1*(strlen($avatar_extension))))."jpg";
                $message_data[$i]['avatar_width'] = $message_data_array['thumb_width'];
                $message_data[$i]['avatar_height'] = $message_data_array['thumb_height'];
            }
            else {
                $message_data[$i]['avatar'] = NULL;
            }
            $message_data[$i]['message_id'] = $message_data_array['message_id'];
            $message_data[$i]['user_id'] = $message_data_array['user_id'];
            $message_data[$i]['username'] = $message_data_array['username'];
            $message_data[$i]['level'] = $message_data_array['level'];
            $message_data[$i]['deleted'] = $message_data_array['deleted'];
            
            if ($message_data_array['deleted'] == 1) {
                $message_content = $GLOBALS['locale_messages']['message']['deleted'];
            } elseif ($message_data_array['deleted'] == 2) {
                $message_content = $GLOBALS['locale_messages']['message']['deleted_moderator'];
            } else {
                $message_content = $message_data_array['message'];
                $parser = new Parser($this->pdo_conn);
                $message_content = $parser->parse($message_content);
                $message_content = autolink($message_content);
            }
            $message_data[$i]['message'] = $message_content;

            
            $message_data[$i]['posted'] = $message_data_array['posted'];
            $message_data[$i]['revision_id'] = $message_data_array['revision_id'];
        }
        $statement->closeCursor();
        $this->updateHistory($message_data[sizeof($message_data)-1]['message_id'], $page);
        return $message_data;
    }
    
    public function postMessage($aMessage, $aMessage_id=null){
        
        if(is_null($aMessage_id)){
            $statement = $this->pdo_conn->prepare("INSERT INTO Messages ( user_id,
                                                              topic_id, 
                                                              message, 
                                                              posted)
                                        VALUES (:user_id,
                                                :topic_id,
                                                :message,
                                                ".time().")");
            $data = array("user_id" =>  $this->user_id,
                          "topic_id" => $this->topic_id,
                          "message"  => $aMessage);
            if($statement->execute($data))
                return TRUE;
            else
                return FALSE;
        }else{
            $statement = $this->pdo_conn->prepare("SELECT MAX(revision_no) as revision_no FROM 
                                                        Messages
                                                    WHERE
                                                        Messages.message_id = ?
                                                    AND
                                                        Messages.user_id = ?");
            $statement->execute(array($aMessage_id, $this->user_id));
            $row = $statement->fetch();
            //print_r($row);
            if($statement->rowCount() == 1){
                $revision_no = $row[0] + 1;
                $statement2 = $this->pdo_conn->prepare("INSERT INTO Messages ( message_id,
                                                                        user_id,
                                                                        topic_id,
                                                                        message,
                                                                        revision_no,
                                                                        posted)
                                                        VALUES( :message_id,
                                                                :user_id,
                                                               :topic_id,
                                                               :message,
                                                               $revision_no,
                                                               ".time().")");
                $data = array( "message_id" => $aMessage_id,
                            "user_id" =>  $this->user_id,
                          "topic_id" => $this->topic_id,
                          "message"  => $aMessage);
                if($statement2->execute($data))
                    return TRUE;
                else
                    return FALSE;
            }else
                return FALSE;
        }                            
    }
    
    public function updateHistory($last_message, $page){
        if(!is_null($last_message)){
            $sql = "INSERT INTO TopicHistory (topic_id, user_id, message_id, date, page)
                VALUES (:topic_id, $this->user_id, $last_message, ".time().", :page)
                ON DUPLICATE KEY UPDATE message_id= IF(message_id < $last_message, $last_message, message_id), date=".time().", page= IF(page < :page2, :page3, page)";
            $statement = $this->pdo_conn->prepare($sql);
            #PDO limitimation: cant call the same named place holder more than once per query
            $statement->execute(array('topic_id' => $this->topic_id, 'page'=>$page, 'page2'=>$page, 'page3'=>$page));
            $statement->closeCursor();
            return TRUE;
       }    
    }

    public function pollMessage(){
        $message_data = null;
        $count = 0;
        $sql = "SELECT Messages.topic_id, Messages.message_id, Messages.revision_no as revision_id,
            Messages.user_id, Users.username, Users.avatar, UploadedImages.sha1_sum,
            UploadedImages.thumb_width, UploadedImages.thumb_height, UploadLog.filename,
            Messages.message, Messages.posted as posted
            FROM Messages 
            LEFT JOIN Users ON Users.user_id = Messages.user_id
            LEFT JOIN UploadLog ON Users.avatar = UploadLog.uploadlog_id
            LEFT JOIN UploadedImages On UploadedImages.image_id = UploadLog.image_id
            LEFT JOIN TopicHistory ON TopicHistory.topic_id = Messages.topic_id
            WHERE Messages.topic_id = ? AND Messages.message_id > TopicHistory.message_id
            AND TopicHistory.user_id = ?";
        $statement = $this->pdo_conn->prepare($sql);

        while(is_null($message_data) && ($count < 20)) { 
            $statement->execute(array($this->topic_id, $this->user_id));
            if($statement->rowCount() > 0) {
               for($i=0; $message_data_array=$statement->fetch(); $i++){
                    if(!is_null($message_data_array['avatar'])){
                        $avatar_extension = end(explode(".", $message_data_array['filename']));
                        $message_data[$i]['avatar'] = $message_data_array['sha1_sum']."/".urlencode(substr($message_data_array['filename'],0,-1*(strlen($avatar_extension))))."jpg";
                        $message_data[$i]['avatar_width'] = $message_data_array['thumb_width'];
                        $message_data[$i]['avatar_height'] = $message_data_array['thumb_height'];
                    }
                    else {
                        $message_data[$i]['avatar'] = NULL;
                    }
                    $message_data[$i]['message_id'] = $message_data_array['message_id'];
                    $message_data[$i]['user_id'] = $message_data_array['user_id'];
                    $message_data[$i]['username'] = $message_data_array['username'];
                    
                    $message_content = $message_data_array['message'];
                    $parser = new Parser($this->pdo_conn);
                    $message_content = $parser->parse($message_content);
                    $message_content = autolink($message_content);
                    $message_data[$i]['message'] = $message_content;
                    $message_data[$i]['topic_id'] = $message_data_array['topic_id'];
                    $message_data[$i]['posted'] = $message_data_array['posted'];
                    $message_data[$i]['revision_id'] = $message_data_array['revision_id'];
                    $this->updateHistory($message_data[$i]['message_id'], 0);
                }
            } else {
                    sleep(1);
            }
            $count++;
        }
        return $message_data;
    }
    
    public function doesExist(){
        return $this->exists;
    }
    
    public function getPageCount(){
        return $this->page_count;
    }
    
    public function getTopicTitle(){
        return $this->topic_title;
    }
    
    public function getBoardTitle(){
        return $this->board_title;
    }
    
    public function getBoardID(){
        return $this->board_id;
    }
    
    public function getReaders(){
        $sql = "SELECT COUNT(topic_id) FROM TopicHistory
                    WHERE topic_id=? AND date >= ".(time() - 60*15);
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($this->topic_id));
        $row = $statement->fetch();
        return $row[0];
    }
    
    public function pinTopic(){
        $sql = "INSERT INTO StickiedTopics (topic_id, user_id, created)
                VALUES(?, ?, ".time().")";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($this->topic_id, $this->user_id));
    }

}
?>
