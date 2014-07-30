<?php
/*
 * CSRFGuard.class.php
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
 
class CSRFGuard
{
    
    /**
    * CSRF Token Value 
    * @var string
    */
    private $_csrf_token;

    /**
    * Site key used for HMAC to geneterate the CSRF string
    * @var string
    */
    private $_site_key;

    /**
     * Array used to change various parameters for the CSRF token generation
     * @var array
     */
    private $_options = array();

    /**
     * Salt used to give each page a unique CSRF token
     * @var string
     */
    private $_page_salt = null;

    /**
     * Create a new CSRF token
     * @param string  site_key            The secret key used to generate token
     * @param boolean $cookie_secure_flag Set CSRF cookie with the 'Secure' flag (set to true if your site uses SSL)
     * @param string  $cookie_name        Name of the CSRF cookie
     * @param string  $domain             Domain to scope the cookie to
     * @return void
     */
    public function __construct($site_key = null, $options = null)
    {
        
        $this->_site_key = $site_key;
        $default_options = array(
            "namespace" => "csrf",
            "session_salt" => null,
            "global_timespan" => 60 * 60 * 24,
            "token_timespan" => null,
            "reusable" => true,
            "domain" => null,
            "path" => null,
            "ssl" => false
        );

        foreach ($default_options as $key => $value) {
            $this->_options[$key] = isset($options[$key]) ? $options[$key] : $value;
        }

        // Get CSRF token from cookie, if it does not exist
        // create a new token
        if (isset($_COOKIE[$this->_options['namespace']])) {
            $this->_csrf_token = $_COOKIE[$this->_options['namespace']];
        } else {
            $this->resetToken();
        }
    }

    /**
     * Set an salt to give each page a unique CSRF token
     * without creating a new cookie
     * @param mixed $salt String or array to be used as the salt
     * @return void
     */
    public function setPageSalt($salt)
    {
        if (is_array($salt)) {
            $salt = implode($salt);
        }
        $this->_page_salt = $salt;
    }
    
    /**
     * Get CSRF token
     * @param  string $salt Salt value for the token, allows for per page tokens
     * (Should be the same salt used in validateToken())
     * @return string       The value of the CSRF token
     */
    public function getToken()
    {
        $raw = $this->websafeDecode($this->_csrf_token);
        $token = explode("|", $raw);

        // Calculate and verify HMAC of cookie data to ensure cookie
        // has not been modified
        $hmac_cookie = hash_hmac("sha1", $token[0], $this->_site_key, true);
        if ($hmac_cookie == $token[1]) {
            // Generate CSRF token based on random data in cookie and the salt
            // and HMAC'd with the sites private key
            $hash = hash_hmac(
                "sha256",
                $raw.$this->_options['session_salt'].$this->_page_salt,
                $this->_site_key,
                true
            );
            $encoded_hash = $this->websafeEncode($hash);
        } else {
            $this->resetToken();
            $encoded_hash = $this->getToken();
        }
        return $encoded_hash;
    }

    /**
     * Generate a new CSRF token, if one currently exists, it will be regenerated
     * @return void
     */
    public function resetToken()
    {
        // Generate random data for CSRF token
        $r = mcrypt_create_iv(26, MCRYPT_DEV_URANDOM);
        // HMAC data to ensure integrity
        $hmac = hash_hmac("sha1", $r, $this->_site_key, true);
        // Append HMAC to random data, encode in websafe base64
        // and store in a cookie
        $this->_csrf_token = $this->websafeEncode($r."|".$hmac);
        setcookie(
            $name = $this->_options['namespace'],
            $value = $this->_csrf_token,
            $expire = $this->_options['global_timespan'] + time(),
            $path = $this->_options['path'],
            $domain = $this->_options['domain'],
            $secure = $this->_options['ssl'],
            $httponly = true
        );
    }
    
    /**
     * Verify the token provided in the request is valid
     * @param string $request The CSRF token from the request
     * @param string $salt    Salt used to create a per page CSRF token (Should be the same salt used in getToken())
     * @return boolean        True if the token is valid
     */
    public function validateToken($request)
    {
        $raw_request = $this->websafeDecode($request);
        $raw_cookie = $this->websafeDecode($this->_csrf_token);

        $hmac_cookie = hash_hmac(
            "sha256",
            $raw_cookie.$this->_options['session_salt'].$this->_page_salt,
            $this->_site_key,
            true
        );
        if ($hmac_cookie == $raw_request) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Encode data using websafe base64
     * @param  string $text Text to encode
     * @return string       Encoded text
     */
    public static function websafeEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Decode data from websafe base64
     * @param  string $text Websafe base64 encoded string to decode
     * @return string       Decoded text
     */
    public static function websafeDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
