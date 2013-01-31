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
	
	function __construct(){
		session_set_cookie_params(0, "/", DOMAIN, USE_SSL, TRUE);
		session_start();
		if(!isset($_SESSION['csrf_token']))	
			$_SESSION['csrf_token'] = override\random(24);
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
		return $this->websafeEncode($_SESSION['csrf_token']);
	}
	
	public function validateToken($request){
		if($this->websafeDecode($request) == $_SESSION['csrf_token'])
			return TRUE;
		else 
			return FALSE;
	}
	
}
?>
