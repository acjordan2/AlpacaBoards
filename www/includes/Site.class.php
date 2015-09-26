<?php
/*
 * Site.class.php
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

class Site {

    private $_pdo_conn;

    private $_sitename;

    private $_sitekey;

    private $_registration;

    private $_invites;

    private $_domain;

    private static $_root_path;

    private $_base_url;

    private $_use_short_handler = true;

    public function __construct()
    {
        $this->_pdo_conn = ConnectionFactory::getInstance()->getConnection();
        $sql = "SELECT sitename, sitekey, registration, invites, domain FROM SiteOptions";
        $statement = $this->_pdo_conn->prepare($sql);
        $statement->execute();
        $results = $statement->fetch();

        $this->_sitename = $results['sitename'];
        $this->_sitekey = $results['sitekey'];
        $this->_registration = $results['registration'];
        $this->_invites = $results['invites'];
        $this->_domain = $results['domain'];
        $this->setBaseUrl();
    }

    /**
     * Update the site wide settings
     * 
     * @param  string $sitename     The name of the site displayed in page titles
     * @param  int    $registration Registration Settings: Disable (0), Invite Only(1), Open(2)
     * @param  int    $invites      Invite Settings: Disabled (0), Can be purchased (1), Open(2)
     * 
     * @return boolean              True if the site was successfully updated
     */
    public function updateSiteOptions($sitename, $registration, $invites)
    {
        if (($registration < 0 || $registration > 3) || ($invites < 0 || $invites > 3)) {
            return false;
        } else {
            $params = array(
                "sitename" => $sitename,
                "registration" => $registration,
                "invites" => $invites
            );
            $sql = "UPDATE SiteOptions set sitename = :sitename, registration = :registration, 
                invites = :invites";
            $statement = $this->_pdo_conn->prepare($sql);
            return $statement->execute($params);
        }
    }

    public function getUserList($user, $page = 1, $query = null)
    {
        // Get users 50 at a time
        if (!is_int($page)) {
            $page = 1;
        }
        $offset = 50 * ($page-1);
        $user_search  = "";

        $data = array();
        $data['offset'] = $offset;

        // Search for a speficied username
        if (!is_null($query)) {
            if (strlen($query) == 1) {
                $query = str_replace("%", "", $query);
            }
            $query = "%".$query."%";
            $user_search = "WHERE Users.username LIKE :query";
        }
        $sql = "SELECT Users.username, Users.user_id, Users.account_created, 
            Users.last_active FROM Users LEFT Join Karma using (user_id) 
            LEFT JOIN ShopTransactions USING (user_id) $user_search GROUP 
            BY Users.user_id ORDER BY User_id ASC LIMIT 50 OFFSET :offset";
        $statement = $this->_pdo_conn->prepare($sql);
        if (!is_null($query)) {
            $data['query'] = $query;
            $statement->execute($data);
        } else {
            $statement->execute($data);
        }
        $sql2 = "SELECT COUNT(Users.user_id) as count FROM Users";
        $statement2 = $this->_pdo_conn->query($sql2);
        $row = $statement2->fetch();
        $page_count = intval($row['count']/50);
        if ($page_count % 50 != 0) {
            $page_count++;
        }
        $userList = $statement->fetchAll();
        foreach ($userList as $key => $value) {
           $userList[$key]['karma']
                = $user->getContributionKarma($userList[$key]['user_id']);
        }
        array_unshift($userList, array("page_count" => $page_count));
        return $userList;
    }

    public function setDomain($domain)
    {
        if ($this->verifyDomain($domain)) {
            $sql = "UPDATE SiteOptions SET domain = :domain";
            $statement = $this->_pdo_conn->prepare($sql);
            $statement->bindParam(":domain", $domain);
            $statement->execute();
            return true;
        } else {
            return false;
        }
    }

    public function verifyDomain($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }

    public static function setRootPath()
    {
        $root_path = "";
        $root_path_array = explode("/", dirname(__FILE__));
        for ($i=0; $i<sizeof($root_path_array)-1; $i++) {
            $root_path .= $root_path_array[$i]."/";
        }
        $root_path = substr($root_path, 0, strlen($root_path)-1);
        self::$_root_path = $root_path;
    }

    public function setBaseUrl()
    {
        $time = explode(' ', microtime());
        $start = $time[1] + $time[0];

        $tempPath1 = explode('/', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
        $tempPath2 = explode('/', substr(self::getRootPath(), 0, -1));
        $tempPath3 = explode('/', str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])));

        for ($i = count($tempPath2); $i < count($tempPath1); $i++) {
            array_pop($tempPath3);
        }

        if ($this->_use_short_handler == true) {
            $protocol = "//";
        } else {
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == "https") {
                $_SERVER['HTTPS'] = "on";
            }


            if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                $protocol = "https://";
            } else {
                $protocol = "http://";
            }
        }

        if (verify_domain($_SERVER['HTTP_HOST'])) {
            $urladdr = $protocol.urlencode(strip_tags($_SERVER['HTTP_HOST'].implode('/', $tempPath3)));
            if (!($urladdr{strlen($urladdr) - 1}== '/')) {
                $urladdr .= "/";
            }
        } else {
            $urladdr = $tempPath3;
        }
        $base_url = str_replace("%2F", "/", $urladdr);
        // Remove trailing slash
        $base_url = rtrim($base_url, "/");
        $this->_base_url = $base_url;
    }

    public function getReaders()
    {
        $sql = "SELECT COUNT(user_id) FROM Users WHERE
            last_active > ".(time() - (60*15));
        $statement = $this->_pdo_conn->query($sql);
        $row = $statement->fetch();
        return $row[0];
    }

    public function getMessage($id)
    {
        return "Message deleted";
    }

    public static function getRootPath()
    {
        if (!isset(self::$_root_path)) {
            self::setRootPath();    
        } 
        return self::$_root_path;
    }

    public function getBaseUrl()
    {
        return $this->_base_url;
    }

    public function getSiteName()
    {
        return $this->_sitename;
    }

    public function getDomain()
    {
        return $this->_domain;
    }

    public function getImageDomain()
    {
        return $this->_domain;
    }

    public function getImagePath()
    {
        return $this->getBaseURL()."/usercontent/i";
    }

    public function getSiteKey()
    {
        return $this->_sitekey;
    }

    public function getRegistrationStatus()
    {
        return $this->_registration;
    }

    public function getInviteStatus()
    {
        return $this->_invites;
    }

    public static function getConstant($key) {
        $constants = array(
            "TEMPLATE_DIR" => self::getRootPath()."/templates",
            "TEMPLATE_CACHE" => self::getRootPath()."/includes/smarty/cache",
            "TEMPLATE_CONFIG" => self::getRootPath()."/includes/smarty/configs",
            "TEMPLATE_COMPILE" => self::getRootPath()."/includes/smarty/templates_c"
        );

        return $constants[$key];    
    }
}
