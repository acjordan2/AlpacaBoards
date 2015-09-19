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

if (!defined("RATE_LIMIT"))
    define("RATE_LIMIT", 30);
 
class Link
{

    private $_pdo_conn;

    private $_parser;

    private $_tag;

    private $_link_id;

    private $_user_id;

    private $_user;

    private $_title;

    private $_url;

    private $_description;

    private $_short_code;

    private $_hit_count;

    private $_link_user_id;

    private $_link_username;

    private $_number_of_votes;

    private $_rank;

    private $_rating;

    private $_created;

    private $_tags;

    private $_messages_per_page = 50;

    private $_results_per_page = 50;

    public function __construct(User $user, Parser $parser = null, Tag $tag = null, $link_id = null)
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $this->_parser = $parser;
        $this->_tag = $tag;
        $this->_user_id = $user->getUserId();
        $this->_user = $user;
        if (is_numeric($link_id)) {
            $this->_loadLink($link_id);
        }
    }

    private function _loadLink($link_id)
    {
        $sql = "SELECT Users.username, Links.link_id, Links.user_id, Links.title, Links.url,
            Links.description, Links.created, COUNT(LinkVotes.vote) as NumberOfVotes,
            SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) as rank,
            SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating
            FROM Links
            LEFT JOIN LinkVotes USING(link_id) 
            LEFT JOIN Users ON Links.user_id = Users.user_id
            WHERE link_id = :link_id GROUP BY link_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("link_id", $link_id);
        $statement->execute();

        if ($statement->rowCount() == 1) {
            $results = $statement->fetch();
            $this->_link_id = $results['link_id'];
            $this->_title = $results['title'];
            $this->_url = $results['url'];
            $this->_description = $results['description'];
            $this->_short_code = "LL".dechex($this->_link_id);
            $this->_link_user_id = $results['user_id'];
            $this->_link_username = $results['username'];
            $this->_number_of_votes = $results['NumberOfVotes'];
            $this->_rank = $results['rank'];
            $this->_rating = $results['rating'];
            $this->_created = $results['created'];
        } else {
            throw new Exception('Link does not exist');
        }
    }

    private function _updateHistory()
    {
        $sql = "INSERT INTO LinkHistory (link_id, user_id, date)
            VALUES (".$this->_link_id.", ".$this->_user_id.", ".time().") 
            ON DUPLICATE KEY UPDATE date=".time();
        $statement = $this->_pdo_conn->query($sql);
        $statement->execute();
        $statement->closeCursor();
    }

    public function getLinks($page = 1, $filter = null)
    {
        $offset = $this->_results_per_page * ($page - 1);
        $data = array(
            "offset" => $offset
        );

        $sql = "SELECT Users.username, Links.link_id, Links.user_id, Links.title, 
            COUNT(LinkVotes.vote) AS NumberOfVotes, 
            SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, 
            SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating, 
            Links.created 
            FROM Links LEFT JOIN Users USING(user_id) 
            LEFT JOIN LinkVotes USING(link_id) 
            GROUP BY link_id ORDER BY link_id DESC LIMIT ".$this->_results_per_page.
            " OFFSET :offset";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $results = $statement->fetchAll();

        foreach ($results as &$key) {
            $key['title'] = htmlentities($key['title']);
            $key['tags'] = $this->_tag->getObjectTags($key['link_id'], 2);
        }

        return $results;
    }

    public function getMessages($page = 1, $filter = null) 
    {
        $offset = $this->_messages_per_page * (intval($page) - 1);
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
                Messages.link_id = :link_id";

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
        $statement->bindParam("link_id", $this->_link_id);
        $statement->bindParam("offset", $offset);
        $statement->execute();

        $results = $statement->fetchAll();

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
            $this->_updateHistory($last_message['message_id'], $page);
        } else {
            $this->_updateHistory();
        }
        return $results;
    }

    public function createLink($title, $url, $description, $tags)
    {
        $time = time();
        $last_link = $this->_user->getLastLink();

        if ($last_link['created'] > $time - RATE_LIMIT) {
            $time = ($last_link['created'] + RATE_LIMIT) - time();
            return $time * - 1;
        } else {
            $sql_link = "INSERT INTO Links (user_id, title, url, description, created)
                VALUES (".$this->_user_id.", :title, :url, :description, $time)";
            $statement_link = $this->_pdo_conn->prepare($sql_link);
            $data_link = array(
                "title" => $title,
                "url" => $url,
                "description" => $description
            );
            $statement_link->execute($data_link);
            $link_id = $this->_pdo_conn->lastInsertId();

            $sql_tags = "INSERT INTO Tagged (data_id, tag_id, type)
                VALUES ($link_id, :tag_id, 2)";
            $statement_tags = $this->_pdo_conn->prepare($sql_tags);
            foreach ($tags as $tag) {
                $statement_tags->bindParam("tag_id", $tag);
                $statement_link->execute();
            }

            return $link_id;
        }
    }

    public function updateLink($title, $url, $description, $tags)
    {
        // Update link data
        $sql = "UPDATE Links SET Links.title = :title, Links.url = :url, 
            Links.description = :description
            WHERE Links.user_id = ".$this->_user_id." AND Links.link_id = :link_id";
        $statement = $this->_pdo_conn->prepare($sql);
        
        $data = array(
            "title" => $title,
            "url" => $url,
            "description" => $description,
            "link_id" => $this->_link_id
        );

        $statement->execute($data);
        $this->_tag->editTags($this->_link_id, 2, $tags);
    }

    public function reportLink($reason)
    {
        $sql = "INSERT INTO LinksReported (user_id, link_id, reason, created)
            VALUES(".$this->_user_id.", :link_id, :reason, ".time().")";
        $statement = $this->_pdo_conn->prepare($sql);
        $data = array(
            "link_id" => $this->_link_id,
            "reason" => $reason
        );
        return $statement->execute($data);
    }

    public function isFavorite()
    {
        $sql = "SELECT link_id FROM LinkFavorites 
            WHERE user_id = ".$this->_user_id." AND link_id = :link_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("link_id", $this->_link_id);
        $statement->execute();
        if ($statement->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function addToFavorites()
    {
        // Add link to favorites
        $sql = "INSERT INTO LinkFavorites (link_id, user_id, created)
                VALUES (:link_id , ".$this->_user_id.", ".time().")
                ON DUPLICATE KEY UPDATE user_id = user_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("link_id", $this->_link_id);
        $statement->execute();
    }

    public function removeFromFavorites()
    {
        $sql = "DELETE FROM LinkFavorites WHERE LinkFavorites.user_id = ".$this->_user_id.
            " AND LinkFavorites.link_id = :link_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("link_id", $this->_link_id);
        $statement->execute();
    }

	public function setVote($vote) {
		$vote = (int) $vote;
		$sql = "INSERT INTO LinkVotes (link_id, vote, user_id, created)
			VALUES(:link_id, :vote, ".$this->_user_id.", ".time().")
			ON DUPLICATE KEY UPDATE vote = :vote2";
		$statement = $this->_pdo_conn->prepare($sql);
		$data = array(
			"link_id" => $this->_link_id,
			"vote" => $vote,
			"vote2" => $vote
		);
		$statement->execute($data);
	}

	public function getVotes()
	{
		$sql = "SELECT COUNT(LinkVotes.vote) AS NumberOfVotes,
			SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank,
			FORMAT(SUM(LinkVotes.vote)/COUNT(LinkVotes.vote),2) AS rating
		FROM LinkVotes WHERE link_id = :link_id";
		
		$statement = $this->_pdo_conn->prepare($sql);
		$statement->bindParam("link_id", $this->_link_id);
		$statement->execute();

		return $statement->fetch(PDO::FETCH_ASSOC);
	}

    public function getFavorites()
    {
        $sql = "SELECT Users.username, Links.link_id, Links.user_id, Links.title, Links.url, 
            COUNT(LinkVotes.vote) AS NumberOfVotes, 
            SUM(LinkVotes.vote) - (5 * COUNT(LinkVotes.vote)) AS rank, 
            SUM(LinkVotes.vote)/COUNT(LinkVotes.vote) AS rating, 
            Links.created 
            FROM Users LEFT JOIN
            Links USING(user_id) 
            LEFT JOIN LinkVotes USING(link_id)
            LEFT JOIN LinkFavorites USING(link_id) 
            WHERE LinkFavorites.user_id = ".$this->_user_id.
            " GROUP BY link_id";
        $statement = $this->_pdo_conn->query($sql);
        $results = $statement->fetchAll();

        foreach ($results as &$key) {
            $key['title'] = htmlentities($key['title']);
            $key['tags'] = $this->_tag->getObjectTags($key['link_id'], 2);
        }

        return $results;
    }

    public function getPageCount()
    {
        $data = array(
            "link_id" => $this->_link_id
        );
        $sql = "SELECT COUNT(DISTINCT(message_id)) as count FROM Messages
            WHERE Messages.link_id = :link_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute($data);
        $results = $statement->fetch();
        $statement->closeCursor();
        $page_count = intval($results['count']/$this->_messages_per_page);
        if ($results['count'] % $this->_messages_per_page != 0 || $results['count'] == 0) {
            $page_count++;
        }
        return $page_count;
    }

    public function getHitCount()
    {
        $sql = "SELECT COUNT(LinkHistory.link_id) as count FROM LinkHistory
            WHERE LinkHistory.link_id = :link_id";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->bindParam("link_id", $this->_link_id);
        $statement->execute();
        $row = $statement->fetch();
        return $row['count'];
    }

	public function postMessage($message) 
	{	
        if (is_null($message_id)) {
            $sql = "INSERT INTO Messages ( user_id, link_id, message, posted)
                VALUES (:user_id, :link_id, :message, ".time().")";
            $data = array(
                "user_id" => $this->_user_id,
                "link_id" => $this->_link_id,
                "message" => $message
            );
            $statement = $this->_pdo_conn->prepare($sql);
            if ($statement->execute($data)) {
                $statement->closeCursor();
                return true;
            } else {
                return false;
            }
		}
	}

    public function getLinkId()
    {
        return $this->_link_id;
    }

    public function getLinkUserId()
    {
        return $this->_link_user_id;
    }

    public function getNumberOfVotes()
    {
        return $this->_number_of_votes;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    public function getRank()
    {
        return $this->_rank;
    }

    public function getRating()
    {
        return $this->_rating;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getLinkUsername()
    {
        return $this->_link_username;
    }

    public function getShortCode()
    {
        return $this->_short_code;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getDescription()
    {
        return $this->_description;
    }

}
