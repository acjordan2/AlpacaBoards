<?php
/*
 * CSRFGuard.class.php
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
 
class CSRFGuard{
	
	private $pdo_conn;

    private $csrf_token;
	
	function __construct(){
		if (!isset($_COOKIE['csrf'])) {
            $this->resetToken();
        } else {
            $this->csrf_token = $_COOKIE['csrf'];
        }
	}
	
	private function websafeEncode($text){
		$search = array("+", "/", "=");
		$replace = array("-", "_", ".");
		$string = base64_encode($text);
		return str_replace($search, $replace, $string);
	}
	
	private function websafeDecode($text){
		$search = array("-", "_", ".");
		$replace = array("+", "/", "=");
		$string = str_replace($search, $replace, $text); 
		return base64_decode($string);
	}
	
	public function getToken(){
        $raw = $this->websafeDecode($this->csrf_token);
        $token = explode("|", $raw);
        $hmac_cookie = hash_hmac("sha1", $token[0], SITE_KEY, true);
        if ($hmac_cookie == $token[1]) { 
            $hash = hash_hmac("sha256", $raw, SITE_KEY, true);
            return $this->websafeEncode($hash);
        } else {
            $this->resetToken();
            return $this->getToken();
        }
	}

    public function resetToken(){
        $r = override\random(24);
        $hmac = hash_hmac("sha1", $r, SITE_KEY, true);
        $this->csrf_token = $this->websafeEncode($r."|".$hmac);
        setcookie($name="csrf", $value=$this->csrf_token, $expire=-1, $path="/", 
            $path=DOMAIN, $secure=USE_SSL, $httponly=TRUE);
    }
	
	public function validateToken($request){
        $raw_request = $this->websafeDecode($request);
        $raw_cookie = $this->websafeDecode($_COOKIE['csrf']);

        $hmac_cookie = hash_hmac("sha256", $raw_cookie, SITE_KEY, true);
		if($hmac_cookie == $raw_request) 
			return TRUE;
		else 
			return FALSE;
	}
	
}
?>
