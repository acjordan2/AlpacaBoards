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
     * 
     * @param string  $site_key The secret key used to generate token
     * @param array   $options  Options for CSRF token generation
     * 
     * @return void
     */
    public function __construct($site_key = null, $options = null)
    { 
        $this->_site_key = $site_key;
        $default_options = array(
            "namespace" => "csrf",
            "session_salt" => null,
            "global_timespan" => 60 * 60 * 24,
            "enfore_timespan" => true,
            "reusable" => true,
            "domain" => null,
            "path" => null,
            "ssl" => false
        );

        foreach ($default_options as $key => $value) {
            $this->_options[$key] = isset($options[$key]) ? $options[$key] : $value;
            unset($options[$key]);
        }

        if (count($options) > 0) {
            foreach ($options as $key => $value) {
                trigger_error("Unknown option ".htmlentities($key), E_USER_WARNING);
            }
        }

        // Get CSRF token from cookie, if it does not exist
        // create a new token
        if (isset($_COOKIE[$this->_options['namespace']])) {
            $this->_csrf_token = json_decode($this->websafeDecode($_COOKIE[$this->_options['namespace']]), true);
            if ($this->_options['enfore_timespan'] === true && 
                (time() - $this->_options['global_timespan']) > $this->_csrf_token['ts']) {
                $this->resetToken();
            }
        } else {
            $this->resetToken();
        }
    }

    /**
     * Set an salt to give each page a unique CSRF token
     * without creating a new cookie
     * 
     * @param mixed $salt String or array to be used as the salt
     * 
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
     * Set the time span for an individual CSRF token 
     * (This does not effect the global timespan)
     * 
     * @param integer $timespan Time in seconds the token should be valid for
     */
    public function setTokenTimeSpan($timespan)
    {
        $this->_tokenTimeSpan = $timespan;
    }

    /**
     * Get CSRF token
     * 
     * @return string The value of the CSRF token
     */
    public function getToken($token_salt = null)
    {
        if (!is_null($this->_options['global_timespan']) && $this->_options['global_timespan'] < 1) {
            $this->_csrf_token['ts'] = null;
        }

        // Make sure cookie has not been modified
        $hmac = hash_hmac("sha256", $this->_csrf_token['ts'].$this->websafeDecode($this->_csrf_token['t']), $this->_site_key, true);
        if ($hmac == $this->websafeDecode($this->_csrf_token['h'])) {
            // Generate CSRF token based on random data in cookie and the salt
            // and HMAC'd with the sites private key
            $hash = hash_hmac(
                "sha256",
                $this->_csrf_token['ts'].$this->websafeDecode($this->_csrf_token['t']).$this->_options['session_salt'].$token_salt.$this->_page_salt,
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
     * 
     * @return void
     */
    public function resetToken()
    {
        if (!is_null($this->_options['global_timespan']) && $this->_options['global_timespan'] > 0) {
            $time_string = time();
        } else {
            $time_string = null;
        }
        // Generate random data for CSRF token
        if (function_exists("mcrypt_create_iv")) {
            $r = mcrypt_create_iv(26, MCRYPT_DEV_URANDOM);
        } elseif (function_exists("random_bytes")) {
            $r = random_bytes(26);
        }

        $hmac = hash_hmac("sha256", $time_string.$r, $this->_site_key, true);
        
        // Append HMAC to random data, encode in websafe base64
        // and store in a cookie
        $this->_csrf_token['t'] = $this->websafeEncode($r);
        $this->_csrf_token['ts'] = time();
        $this->_csrf_token['h'] = $this->websafeEncode($hmac);
        
        setcookie(
            $name = $this->_options['namespace'],
            $value = $this->websafeEncode(json_encode($this->_csrf_token)),
            $expire = $this->_options['global_timespan'] + time(),
            $path = $this->_options['path'],
            $domain = $this->_options['domain'],
            $secure = $this->_options['ssl'],
            $httponly = true
        );
    }
    
    /**
     * Verify the token provided in the request is valid
     * 
     * @param  string $request The CSRF token from the request
     * 
     * @return boolean         True if the token is valid
     */
    public function validateToken($request, $token_salt = null)
    {
        if ($this->getToken($token_salt) === $request) {
            return true;
        }
    }

    /**
     * Encode data using websafe base64
     * 
     * @param  string $text Text to encode
     * 
     * @return string       Encoded text
     */
    public static function websafeEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Decode data from websafe base64
     * 
     * @param  string $text Websafe base64 encoded string to decode
     * 
     * @return string       Decoded text
     */
    public static function websafeDecode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
