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
    * Name of the CSRF cookie
    * @var string
    */
    private $_cookie_name;

    /**
    * Flag to set the 'Secure' cookie option
    * @var boolean
    */
    private $_cookie_secure;

    /**
    * Domain scope to set the cookie
    * @var string
    */
    private $_cookie_domain;

    /**
    * Amount of time before the CSRF cookie is valid for
    * @var integer
    */
    private $_cookie_expires = 86400; // 24 hours

    /**
     * Create a new CSRF token
     * @param string  site_key            The secret key used to generate token
     * @param boolean $cookie_secure_flag Set CSRF cookie with the 'Secure' flag (set to true if your site uses SSL)
     * @param string  $cookie_name        Name of the CSRF cookie
     * @param string  $domain             Domain to scope the cookie to
     * @return void
     */
    public function __construct($site_key = null, $cookie_secure_flag = false, $cookie_name = 'csrf', $domain = null)
    {
        $this->_site_key = $site_key;
        $this->_cookie_name = $cookie_name;
        $this->_cookie_secure = $cookie_secure_flag;

        // If domain is not set use value from HTTP_HOST
        if ($domain == null) {
            $domain = $_SERVER['HTTP_HOST'];
        }

        // Ensure provided domain is valid
        if ($this->_verify_domain($domain)) {
            $this->_cookie_domain = $domain;
        } else {
            $this->_cookie_domain = null;
        }

        // Get CSRF token from cookie, if it does not exist
        // create a new token
        if (isset($_COOKIE[$this->_cookie_name])) {
            $this->_csrf_token = $_COOKIE[$this->_cookie_name];
        } else {
            $this->resetToken();
        }
    }
    
    /**
     * Get CSRF token
     * @param  string $salt Salt value for the token, allows for per page tokens
     * (Should be the same salt used in validateToken())
     * @return string       The value of the CSRF token
     */
    public function getToken($salt = null)
    {
        $raw = $this->websafeDecode($this->_csrf_token);
        $token = explode("|", $raw);

        // Calculate and verify HMAC of cookie data to ensure cookie
        // has not been modified
        $hmac_cookie = hash_hmac("sha1", $token[0], $this->_site_key, true);
        if ($hmac_cookie == $token[1]) {
            // Generate CSRF token based on random data in cookie and the salt
            // and HMAC'd with the sites private key
            $hash = hash_hmac("sha256", $raw.$salt, $this->_site_key, true);
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
            $name = $this->_cookie_name,
            $value = $this->_csrf_token,
            $expire = $this->_cookie_expires + time(),
            $path = "/",
            $path = $this->_cookie_domain,
            $secure = $this->_cookie_secure,
            $httponly = true
        );
    }
    
    /**
     * Verify the token provided in the request is valid
     * @param string $request The CSRF token from the request
     * @param string $salt    Salt used to create a per page CSRF token (Should be the same salt used in getToken())
     * @return boolean        True if the token is valid
     */
    public function validateToken($request, $salt = null)
    {
        $raw_request = $this->websafeDecode($request);
        $raw_cookie = $this->websafeDecode($_COOKIE[$this->_cookie_name]);

        $hmac_cookie = hash_hmac("sha256", $raw_cookie.$salt, $this->_site_key, true);
        if ($hmac_cookie == $raw_request) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verify provided domain name is in the correct format
     * @param  string $domain_name Domain to validate
     * @return boolean             True if domain is valid
     */
    private function _verify_domain($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }

    /**
     * Encode data using websafe base64
     * @param  string $text Text to encode
     * @return string       Encoded text
     */
    public static function websafeEncode($text)
    {
        $search = array("+", "/", "=");
        $replace = array("-", "_", ".");
        $string = base64_encode($text);
        return str_replace($search, $replace, $string);
    }
    
    /**
     * Decode data from websafe base64
     * @param  string $text Websafe base64 encoded string to decode
     * @return string       Decoded text
     */
    public static function websafeDecode($text)
    {
        $search = array("-", "_", ".");
        $replace = array("+", "/", "=");
        $string = str_replace($search, $replace, $text);
        return base64_decode($string);
    }
}
