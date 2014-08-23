<?php
/*
 * Tag.class.php
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
 
class Tag
{
    /**
     * Database connection object
     * @var db_connection
     */
    private $_pdo_conn;

    /**
     * ID of the tag
     * @var integer
     */
    private $_tag_id;

    /**
     * Number of results to show on each page
     * @var integer
     */
    private $_results_per_page = 50;

    /**
     * Current user ID
     * @var integer
     */
    private $_user_id;

    /**
     * Create a new Tag object
     */
    public function __construct($user_id)
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_user_id = $user_id;
    }

    public function createTag($tag_name)
    {
        if (!preg_match('/[^a-z\-0-9\ ]/i', $tag_name)) {
            $data = array(
                "title" => $tag_name
            );
            $sql_checkTag = "SELECT title FROM TopicalTags
                WHERE title = :title";
            $statement_checkTag = $this->_pdo_conn->prepare($sql_checkTag);
            $statement_checkTag->execute($data);
            $row = $statement_checkTag->rowCount();
            $statement_checkTag->closeCursor();
            if ($row > 0) {
                return -1;
            } else {
                $sql_insertTag = "INSERT INTO TopicalTags (title, type, user_id, created)
                    VALUES (:title, 0, ".$this->_user_id.", ".time().")";
                $statement_insertTag = $this->_pdo_conn->prepare($sql_insertTag);
                return $statement_insertTag->execute($data);
            }
        }
    }

    /**
     * Get list of tags
     * @param  string $filter Search Critera 
     * @return array          Array of tag titles and tag IDs
     */
    public function getTags($filter = null)
    {
        $data = array();
        $sql = "SELECT tag_id as id, title FROM TopicalTags";
        if (!is_null($filter)) {
            $filter_sql = "";
            $type_flag = 0;
            $filter_flag = 0;
            $i = 0;
            preg_match_all("/\[.*?\]/", $filter, $matches);
            foreach ($matches[0] as $tag_filter) {
                $tag_filter = str_replace(array('[', ']'), "", $tag_filter);
                $filter_array = explode(":", $tag_filter);
                $search = $filter_array[0];
                switch ($search) {
                    case 'type':
                        $type_flag = 0;
                        $filter_params = explode("|", $filter_array[1]);
                        if ($filter_flag == 1) {
                            $filter_sql .= " AND";
                        } else {
                            $filter_flag = 1;
                        }
                        $filter_sql .= " (";
                        foreach ($filter_params as $param) {
                            if ($type_flag == 1) {
                                $filter_sql .= " OR ";
                            }
                            $filter_sql .= "type = :type".$i;
                            $data['type'.$i] = $param;
                            $i++;
                            $type_flag = 1;
                        }
                        $filter_sql .= ")";
                        break;
                    case 'title':
                        $title_flag = 0;
                        if ($filter_flag == 1) {
                            $filter_sql .= " AND";
                        } else {
                            $filter_flag = 1;
                        }
                        $filter_sql .= " (";
                        $filter_sql .= "title LIKE :title".$i;
                        $data['title'.$i] = "%".$filter_array[1]."%";
                        $i++;
                        $title_flag = 1;
                        $filter_sql .= ")";
                        break;

                    default:
                        # code...
                        break;
                }
            }
            if ($filter_flag == 1) {
                $sql .= " WHERE".$filter_sql;
            }
        }
        $sql .= " ORDER BY title ASC";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $results;
    }

    public function getTagInfoByTitle($tag_title)
    {
        $data_getTagInfo = array("title" => $tag_title);
        $sql_getTagInfo = "SELECT TopicalTags.tag_id, TopicalTags.title, 
            TopicalTags.description, TopicalTags.user_id, TopicalTags.created, Users.username
            FROM TopicalTags LEFT JOIN Users using(User_id) WHERE title = :title";
        $statement = $this->_pdo_conn->prepare($sql_getTagInfo);
        $statement->execute($data_getTagInfo);
        $results = $statement->fetch();
        $statement->closeCursor();
        return $results;
    }

    /**
     * Get content by tag
     * @param  string  $filter Tag filters in the format [tag1][tag2]
     * @param  integer $type   1 for topics, 2 for links
     * @return array           Content that meats the tagging filters
     */
    public function getContent($filter, $type)
    {
        preg_match_all("/\[.*?\]/", $filter, $matches);
        if (count($matches[0]) != 0) {
            $sql_getTagID = "SELECT TopicalTags.tag_id FROM TopicalTags WHERE (";

            $tag_id_data = array();
            for ($i=0; $i < count($matches[0]); $i++) {
                if ($i > 0) {
                    $sql_getTagID .= " OR ";
                }
                $title = str_replace(array("[", "]"), "", $matches[0][$i]);
                $tag_id_data['title'.$i] = str_replace("_", " ", $title);
                $sql_getTagID .= " title = :title".$i;
            }
            $sql_getTagID .= ")";

            $statement_getTagID = $this->_pdo_conn->prepare($sql_getTagID);
            $statement_getTagID->execute($tag_id_data);
            $tag_ids = $statement_getTagID->fetchAll();

            $sql_getNodes = "SELECT n.tag_id FROM TopicalTags as t
                LEFT JOIN  TopicalTagParentRelationship as r 
                    ON t.tag_id=r.parent_id
                LEFT JOIN TopicalTags as n 
                    ON r.child_id = n.tag_id
                WHERE t.tag_id = :tag_id";

            if (count($tag_ids) > 0) {
                $tag_array_tmp = array();
                foreach ($tag_ids as $tag) {
                    $tag_array_tmp[] = $tag['tag_id'];
                }

                for ($i=0; $i < count($tag_array_tmp); $i++) {
                    $data_getNodes = array(
                        "tag_id" => $tag_array_tmp[$i]
                    );
                    $statement = $this->_pdo_conn->prepare($sql_getNodes);
                    $statement->execute($data_getNodes);
                    $tmp_results = $statement->fetchAll();
                    for ($j=0; $j < count($tmp_results); $j++) {
                        $parent_id = $tmp_results[$j]['tag_id'];
                        $parent_array[] = $parent_id;
                        if (!in_array($parent_id, $tag_array_tmp) && $parent_id != "") {
                            $tag_array_tmp[] = $parent_id;
                        }
                    }
                }
                $data_getContent = array();
                if ($type == 1) {
                    // topics
                    $sql_getContent = "SELECT Topics.title, Topics.topic_id, Topics.user_id, 
                        Users.username, MAX(Messages.posted) AS posted 
                        FROM Topics 
                        LEFT JOIN Users 
                            USING(user_id) 
                        LEFT JOIN Messages 
                            USING(topic_id)
                        LEFT JOIN Tagged on Tagged.data_id = Topics.topic_id
                        WHERE (";
                    $data_getContent = array();
                    for ($i=0; $i < count($tag_array_tmp); $i++) {
                        if (!in_array($tag_array_tmp[$i], $data_getContent)) {
                            if ($i > 0) {
                                $sql_getContent .= " OR ";
                            }
                            $sql_getContent .= " Tagged.tag_id = :tag_id".$i;
                            $data_getContent["tag_id".$i] = $tag_array_tmp[$i];
                        }
                    }
                    $sql_getContent .= ") AND Tagged.type = 1 AND Messages.revision_no = 0 GROUP BY topic_id 
                        DESC LIMIT 50 OFFSET 0";

                    $statement_getContent = $this->_pdo_conn->prepare($sql_getContent);
                    $statement_getContent->execute($data_getContent);
                    $topic_data = $statement_getContent->fetchAll();
                    $statement_getContent->closeCursor();

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
                            TopicHistory.user_id = 1";
                        $statement_history = $this->_pdo_conn->query($sql_history);
                        $history_count = $statement_history->fetchAll();
                        $statement_history->closeCursor();

                        $topic_data[$i]['history'] = $history_count[0]['count'];
                        $topic_data[$i]['page'] = $history_count[0]['page'];
                        $topic_data[$i]['last_message'] = $history_count[0]['last_message'];
                        $topic_data[$i]['tags'] = $this->getObjectTags($topic_data[$i]['topic_id'], 1);
                        if (in_array_r("Anonymous", $topic_data[$i]['tags'])) {
                            $topic_data[$i]['username'] = "Human";
                            $topic_data[$i]['user_id'] = "-1";
                        }
                    }
                    return $topic_data;
                } elseif ($type == 2) {
                    // links
                    $sql_getContent = "SELECT Users.username, Links.link_id, Links.user_id, 
                        Links.title, Links.url, COUNT(LinkVotes.vote) AS NumberOfVotes, 
                        SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, 
                        SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating, 
                        Links.created 
                        FROM Users LEFT JOIN
                        Links USING(user_id) 
                        LEFT JOIN LinkVotes USING(link_id) 
                        LEFT JOIN Tagged ON Tagged.data_id = Links.link_id
                        WHERE (";
                    for ($i=0; $i < count($tag_array_tmp); $i++) {
                        if (!in_array($tag_array_tmp[$i], $data_getContent)) {
                            if ($i > 0) {
                                $sql_getContent .= " OR ";
                            }
                            $sql_getContent .= " Tagged.tag_id = :tag_id".$i;
                            $data_getContent["tag_id".$i] = $tag_array_tmp[$i];
                        }
                    }
                    $sql_getContent .= ") GROUP BY link_id";

                    $statement_getContent = $this->_pdo_conn->prepare($sql_getContent);
                    $statement_getContent->execute($data_getContent);
                    $link_data = $statement_getContent->fetchAll();

                    for ($i=0; $i < count($link_data); $i++) {
                        $link_data[$i]['title'] = htmlentities($link_data[$i]['title']);
                        $tags = $this->getObjectTags($link_data[$i]['link_id'], 2);
                        $link_data[$i]['tags'] = $tags;
                    }
                    return $link_data;
                }
            }
        }
    }

    /**
     * Get child tags of a provided tag ID
     * @param  integer $tag_id ID of tag
     * @return array           Array of child tag names and IDs
     */
    public function getChildren($tag_id)
    {
        $sql = "SELECT title, tag_id from TopicalTags
            LEFT JOIN TopicalTagParentRelationship ON TopicalTags.tag_id = TopicalTagParentRelationship.child_id
            WHERE TopicalTagParentRelationship.parent_id = :tag_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute(array('tag_id' => $tag_id));
        return $statement->fetchAll();
    }

    /**
     * Get parent tags of a provided tag ID
     * @param  integer $tag_id ID of tag
     * @return array           Array of parent tag names and IDs
     */
    public function getParents($tag_id)
    {
        $sql = "SELECT title, tag_id from TopicalTags
            LEFT JOIN TopicalTagParentRelationship ON TopicalTags.tag_id = TopicalTagParentRelationship.parent_id
            WHERE TopicalTagParentRelationship.child_id = :tag_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute(array('tag_id' => $tag_id));
        return $statement->fetchAll();
    }

    /**
     * Get tagging conflicts to avoid redundent tags (IE topics being tagged with both
     * the parent and child tag)
     * @param  array $tags  Array of tag IDs to check
     * @return array        Array of conflicting tag IDs 
     */
    public function getConflicts($tags)
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
            $statement = $this->_pdo_conn->prepare($sql);
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

    /**
     * Get tags for a given object
     * @param  integer $object_id   Object ID
     * @param  integer $object_type 1 for topic, 2 for link
     * @return array                Array of tag titles and IDs
     */
    public function getObjectTags($object_id, $object_type)
    {
        $data = array(
            "object_id" => $object_id
        );
        $sql = "SELECT TopicalTags.title, TopicalTags.tag_id FROM Tagged
            LEFT JOIN TopicalTags USING(tag_id) WHERE data_id = :object_id
            AND (Tagged.type = 0 OR Tagged.type = $object_type)";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $results = $statement->fetchAll();
        if (isset($results[0][0]) && is_null($results[0][0])) {
            return array();
        } else {
            foreach ($results as &$key) {
                $key['parents'] = $this->getParents($key['tag_id']);
            }
            return $results;
        }
    }
}
