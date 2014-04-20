<?php
/*
 * Link.class.php
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
 
class Link
{
    
    private $pdo_conn;
    
    private $link_id;
    
    private $user_id;

    private $page_count;

    /**
     * Create a new link
     * 
     * @param db_connection Database connection object, passed by reference
     * @param int $aUserID User ID for the current logged in user
     * @param int $aLinkID 
     *
     * @return void
    */
    
    public function __construct (&$db, $aUserID = null, $aLinkID = null)
    {
        $this->pdo_conn = &$db;
        if ($aUserID != null) {
            $this->user_id = $aUserID;
        }
        if (!is_null($aLinkID)) {
            $this->link_id = $aLinkID;
        }
    }
    
    /**
    * Get list of all added links
    * 
    * @param int $orderby List sorting.
    * 
    * @return array List of added links
    */
    public function getLinkList ($orderby = 1)
    {
        $order = "";
        $where = "";
        switch($orderby){
            // Sort by link rank, top rated first
            case 1:
                $order="rank DESC, rating DESC";
                break;
            // Sort by date created, newest first
            case 2:
                $order="created DESC";
                break;
            // Get links added within the last 7 days.
            // Sort by rank.
            case 3:
                $order="rank DESC, rating DESC";
                $where="WHERE Links.created > '".(time()-(60*60*24*7))."'";
                break;
            // Oldest links first
            case 4:
                $order="created ASC";
        }
        $sql = "SELECT Users.username, Links.link_id, Links.user_id, Links.title, 
                    Links.url, COUNT(LinkVotes.vote) AS NumberOfVotes, 
                    SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, 
                    SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating, 
                    Links.created FROM Users LEFT JOIN Links USING(user_id) 
                    LEFT JOIN LinkVotes USING(link_id) $where GROUP BY link_id 
                    ORDER BY $order";
        $statement = $this->pdo_conn->query($sql);
        $link_data = array();
        // Add all fetched links and entity encode link title
        for ($i=0; $link_data_array=$statement->fetch(); $i++) {
            if ($link_data_array['link_id'] != null) {
                $link_data[$i]['link_id'] = $link_data_array['link_id'];
                $link_data[$i]['user_id'] = $link_data_array['user_id'];
                $link_data[$i]['title'] = htmlentities($link_data_array['title']);
                $link_data[$i]['created'] = $link_data_array['created'];
                $link_data[$i]['NumberOfVotes'] = $link_data_array['NumberOfVotes'];
                $link_data[$i]['rank'] = $link_data_array['rank'];
                $link_data[$i]['rating'] = $link_data_array['rating'];
                $link_data[$i]['username'] = $link_data_array['username'];
            }
        }
        return $link_data;
    }
    
    /**
     * Get link data
     *
     * @return  array Link data
     */

    public function getLink()
    {
        // Get link data
        $sql = "SELECT 
                Users.username, 
                Links.user_id,
                Links.title,
                Links.url,
                Links.description,
                Links.created,
                COUNT(LinkVotes.vote) AS NumberOfVotes, 
                SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, 
                SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating 
            FROM Links 
            LEFT JOIN LinkVotes using(link_id)
            LEFT JOIN Users ON Links.user_id=Users.user_id 
            WHERE link_id = :link_id";

        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array("link_id" => $this->link_id));

        if ($statement->rowCount() == 1) {
            //$sql2 = "SELECT LinkCategories.name FROM LinksCategorized
            //JOIN LinkCategories ON (LinksCategorized.category_id = LinkCategories.category_id)
            //WHERE LinksCategorized.link_id = ?";
            
            // Get link tags
            $sql2 = "SELECT TopicalTags.title FROM Tagged 
                LEFT JOIN TopicalTags USING(tag_id) 
                WHERE Tagged.data_id = :link_id";
            $statement2 = $this->pdo_conn->prepare($sql2);
            $statement2->execute(array("link_id" => $this->link_id));
            
            // Get link message count for calculating pages
            $sql3 = "SELECT DISTINCT(message_id) FROM LinkMessages WHERE LinkMessages.link_id = ?";
            $statement3 = $this->pdo_conn->prepare($sql3);
            $message_count = $statement3->rowCount();
            $this->page_count = intval($message_count/50);
            if ($message_count % 50 != 0) {
                $this->page_count += 1;
            }

            // Parse link markup
            $parser = new Parser($this->pdo_conn);
            
            $this->updateHistory();
            $row = $statement->fetch();
            $row['url2'] = htmlentities($row['url']);
            $row['url'] = autolink(htmlentities($row['url']));
            $row['title'] = htmlentities($row['title']);
            $row['raw_description'] = $row['description'];
            $row['description'] = $parser->parse($row['description']);
            
            $row['code'] = dechex($this->link_id);
            $row['link_id'] = $this->link_id;
            $row['hits'] = $this->getHits();
            $row['categories'] = "";

            // Dispaly Categories (replaced by tags)
            while ($cats = $statement2->fetch()) {
                $row['categories'] .=  $cats['title'].", ";
            }
            $row['categories'] = substr($row['categories'], 0, (strlen($row['categories'])-2));
            return $row;
        } else {
            // Link does not exist
            return false;
        }
    }
    
    /**
     * Update viewing history for the link. Used for keeping an accurate
     * hit counter as well as last viewed comment
     *
     * @return boolean True if history was successfully updated
     */

    private function updateHistory()
    {
        if (!is_null($this->user_id)) {
            $sql = "INSERT INTO LinkHistory (link_id, user_id, date)
                VALUES (:link_id, $this->user_id, ".time().") ON DUPLICATE KEY UPDATE date=".time();
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array("link_id" => $this->link_id));
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get link message
     * 
     * @param  integer $page Page number, each page has 50 messages
     * 
     * @return array         Message data
     */
    public function getMessages($page = 1)
    {
        // Set the page offset
        $offset = 50*($page-1);

        // Get the next set of messages in increments of 50
        $sql = "SELECT LinkMessages.message_id, 
                MAX(LinkMessages.revision_no) as revision_no,
                LinkMessages.user_id, 
                Users.username,
                LinkMessages.message,
                MIN(LinkMessages.posted) as posted
                FROM LinkMessages
                LEFT JOIN Users
                    USING(user_id)
                WHERE
                    LinkMessages.link_id = :link_id
                GROUP BY LinkMessages.message_id DESC 
                ORDER BY posted ASC LIMIT 50 OFFSET :offset";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array("link_id" => $this->link_id, "offset" => $offset));
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        // Get the link message count
        $sql_getMessageCount = "SELECT DISTINCT(message_id) FROM LinkMessages WHERE LinkMessages.link_id = :link_id";
        $getMessageCount = $this->pdo_conn->prepare($sql_getMessageCount);
        $getMessageCount->execute(array("link_id" => $this->link_id));
        $topic_count = $getMessageCount->rowCount();
        $this->page_count = intval($topic_count/50);
        if ($topic_count % 50 != 0) {
            $this->page_count += 1;
        }
        $message_data = array();

        // Parse link data for links
        $parser = new Parser($this->pdo_conn);
        for ($i=0; $message_data_array=$statement->fetch(); $i++) {
            $tmp_user = new User($this->pdo_conn, $message_data_array['user_id']);
            $message_data[$i]['message_id'] = $message_data_array['message_id'];
            $message_data[$i]['user_id'] = $message_data_array['user_id'];
            $message_data[$i]['username'] = $message_data_array['username'];
            $message_data[$i]['avatar'] = $tmp_user->getAvatar();
            $message_data[$i]['message'] = $parser->parse($message_data_array['message']);
            
            $message_data[$i]['posted'] = $message_data_array['posted'];
            $message_data[$i]['revision_no'] = $message_data_array['revision_no'];
        }
        
        return $message_data;
    }
    
    /**
     * Get unique page hits for link
     * 
     * @return int Number of page hits
     */
    private function getHits()
    {
        $sql = "SELECT COUNT(LinkHistory.link_id) as count FROM LinkHistory
                    WHERE LinkHistory.link_id = ?";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($this->link_id));
        $row = $statement->fetch();
        return $row['count'];
    }
    
    /**
     * Add message to link
     * 
     * @param  string $aMessage    Message to be posted
     * @param  int $aMessage_id    Message ID of exising message, null if message is new
     * 
     * @return boolean             True if message was successfully posted
     */
    public function postMessage($aMessage, $aMessage_id = null)
    {
        if (is_null($aMessage_id)) {
            // Message is new
            $sql = "INSERT INTO LinkMessages (user_id,link_id, message, posted)
                VALUES (:user_id, :link_id, :message, ".time().")";
            $statement = $this->pdo_conn->prepare($sql);
            $data = array(
                "user_id" =>  $this->user_id,
                "link_id" => $this->link_id,
                "message"  => $aMessage
            );
            if ($statement->execute($data)) {
                return true;
            } else {
                return false;
            }
        } else {
            // Message exists and needs to be edited
            $sql = "SELECT MAX(revision_no) as revision_no FROM 
                LinkMessages WHERE LinkMessages.message_id = :message_id AND LinkMessages.user_id = :user_id";
            $statement = $this->pdo_conn->prepare($sql);
            $data = array(
                "message_id" => $aMessage_id,
                "user_id" => $this->user_id
            );
            $statement->execute($data);
            $row = $statement->fetch();
            if ($statement->rowCount() == 1) {
                $revision_no = $row[0] + 1;
                $sql2 = "INSERT INTO LinkMessages (message_id, user_id, link_id, message, revision_no, posted)
                    VALUES( :message_id, :user_id, :link_id, :message, $revision_no, ".time().")";
                $statement2 = $this->pdo_conn->prepare($sql2);
                $data2 = array(
                    "message_id" => $aMessage_id,
                    "user_id" =>  $this->user_id,
                    "link_id" => $this->link_id,
                    "message"  => $aMessage
                );
                return $statement2->execute($data2);
            } else {
                return false;
            }
        }
    }
    
    /**
     * Vote on a link using a scale of 1 to 10
     * 
     * @param  int $vote Vote value number 1-10
     * 
     * @return boolean   True if vote was added
     */
    public function vote($vote)
    {
        $sql = "INSERT INTO LinkVotes (link_id, vote, user_id, created)
            VALUES(:link_id, :vote, $this->user_id, ".time().") ON DUPLICATE KEY UPDATE vote=:vote2";
        $statement = $this->pdo_conn->prepare();
        $data = array(
            "link_id" => $this->link_id,
            "vote" => $vote,
            "vote2" => $vote
        );
        return $statement->execute($data);
    }
    
    /**
     * @deprecated Get link catetories; replaced by tags
     * 
     * @param  db_connection $db Database connection
     * 
     * @return array             Array of link categories
     */
    public static function getCategories(&$db)
    {
        $sql = "SELECT LinkCategories.name, LinkCategories.category_id FROM LinkCategories";
        $statement = $db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }
    
    /**
     * Add a link
     * 
     * @param array $request Full request of POST params
     *
     * @todo Provide individual parameters for each varible. 
     *
     * @return boolean True if link was succesfuly added. 
     */
    public function addLink($request){
        $tags = explode(",", $request['tags']);
        if (!empty($tags)){
            $tag_list = $this->validateTags($tags);
            $tag_relationship = $this->checkParentTag($tag_list);
            if(!empty($tag_relationship)){
                return false;
            } else {
                $sql = "INSERT INTO Links (user_id, title, url, description, created)
                        VALUES($this->user_id, ?, ?, ?, ".time().")";
                $statement = $this->pdo_conn->prepare($sql);
                $statement->execute(array($request['title'], $request['lurl'], $request['description']));
                $this->link_id = $this->pdo_conn->lastInsertId();
                $sql_tags = "INSERT INTO Tagged (data_id, tag_id, type) VALUES
                    (".$this->link_id.", :tag_id, 2)";
                foreach ($tag_list as $tag) {
                    $statement_tags = $this->pdo_conn->prepare($sql_tags);
                    $statement_tags->execute(array("tag_id" => $tag));
                }
                return true;
            }
        }
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
                }            
            }
        }
        $sql = "UPDATE Links SET Links.title=?, Links.url=?, Links.description=?
             WHERE Links.user_id=".$this->user_id." AND Links.link_id=?";
        $statement = $this->pdo_conn->prepare($sql);
        $statement->execute(array($request['title'], $request['lurl'], $request['description'], $this->link_id));
        /*
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
        $sql_append = "";
        if(isset($this->link_id))
            $sql_append = "AND link_id != ?";
        $sql = "SELECT Links.url FROM Links WHERE url=? $sql_append";
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
                $link_data[$i]['title'] = htmlentities($link_data_array['title']);
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

    public function getPageCount(){
        return $this->page_count;
    }

    public function getTags($q = null)
    {
        $sql = "SELECT tag_id as id, title FROM TopicalTags where (type = 0 OR type = 2)";
        if (!is_null($q)) {
            $sql.= " AND title LIKE ?";
            $q = "%".$q."%";
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array($q));
        } else {
            $statement = $this->pdo_conn->query($sql);
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkParentTag($tags)
    {
        $tag_array_tmp = $tags;
        $parent_array = array();

        $sql = "SELECT n.tag_id FROM TopicalTags as t
            LEFT JOIN  TopicalTagParentRelationship as r 
                ON t.tag_id=r.child_id
            LEFT JOIN TopicalTags as n 
                ON r.parent_id = n.tag_id
            WHERE t.tag_id = :tag_id";

        for ($i = 0; $i < count($tag_array_tmp); $i++) {
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array("tag_id" => $tag_array_tmp[$i]));
            $tmp_results = $statement->fetchAll();
            for ($j = 0; $j<count($tmp_results); $j++) {
                $parent_id = $tmp_results[$j]['tag_id'];
                $parent_array[] = $parent_id;
                if (!in_array($parent_id, $tag_array_tmp)) {
                    $tag_array_tmp[] = $parent_id;
                }
            }
        }
        return array_intersect($parent_array, $tags);
    }

    public function validateTags($tags)
    {
        $tag_list = array();
        $sql = "SELECT tag_id FROM TopicalTags
            WHERE tag_id = :tag_id AND (type = 2 OR type = 0)";
        foreach ($tags as $tag) {
            $statement = $this->pdo_conn->prepare($sql);
            $statement->execute(array("tag_id" => $tag));
            $results = $statement->fetchAll();
            if(!empty($results)) {
                $tag_list[] = $results[0]['tag_id'];
            }
        }
        return $tag_list;
    }
}
