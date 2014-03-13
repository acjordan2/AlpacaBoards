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

    private $_pdoconn;

    private $_sitename;

    private $_sitekey;

    private $_registration;

    private $_invites;


    public function __construct(&$db)
    {
        $this->_pdoconn = $db;
        $sql = "SELECT sitename, sitekey, registration, invites FROM SiteOptions";
        $statement = $this->_pdoconn->prepare($sql);
        $statement->execute();
        $results = $statement->fetch();

        $this->_sitename = $results['sitename'];
        $this->_sitekey = $results['sitekey'];
        $this->_registration = $results['registration'];
        $this->_invites = $results['invites'];
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
            $statement = $this->_pdoconn->prepare($sql);
            return $statement->execute($params);
        }
    }

    public function getSiteName()
    {
        return $this->_sitename;
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
}
