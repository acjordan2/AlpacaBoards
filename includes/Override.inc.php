<?php
/*
 * Override.inc.php
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
namespace override;
require_once("Config.ini.php");

function htmlentities($string, $whitelist=null){
	$string = str_replace("<br>", "<br/>", $string);
	$string = str_replace("<br />", "<br/>", $string);
	$encoded = \htmlentities($string);
	if(isset($whitelist) && is_array($whitelist)){
			/**
			foreach($whitelist as $key=> $allowed){
				$encoded = preg_replace("/".\htmlentities($allowed)."/", stripslashes($allowed)."\\1\\2", $encoded);
			}
			*/
			for($i=0; $i<sizeof($whitelist['search']); $i++){
				$encoded = preg_replace("/".\htmlentities($whitelist['search'][$i])."/", $whitelist['replace'][$i], $encoded);
			}
	}
	return $encoded;
}

function closeTags($html){
	#put all opened tags into an array
	preg_match_all ("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
	$openedtags = $result[1];
	#put all closed tags into an array
	preg_match_all("#</([a-z]+)>#iU", $html, $result);
	$closedtags = $result[1];
	preg_match_all("#<([a-z]+)( )?/>#iU", $html, $result);
	array_merge($closedtags, $result[1]);
	$len_opened = count($openedtags);
	# all tags are closed
	if( count($closedtags) == $len_opened){
		return $html;
	}
	$openedtags = array_reverse($openedtags);
	# close tags
	for($i = 0; $i<$len_opened; $i++){
		if(!in_array($openedtags[$i], $closedtags)){
			$html .= "</".$openedtags[$i].">";
		}
		else{
			unset($closedtags[array_search($openedtags[$i], $closedtags)]);
		}
	}
	return $html;
}

function formatComments($string){
	$string = preg_replace("/\<quote msgid=\"t,(\d+),(\d+)@(\d+)\"\>/", "<div class=\"quoted-message\" msgid=\"t,$1,$2@$3\">", $string);
	$string = preg_replace("/\<\/quote>/", "</div>", $string);
	return $string;
}

function makeURL($URL) {
	$URL = eregi_replace('(((f|ht){1}tp(s)?://)[-a-zA-Z0-9;@:\+.~#?&//=]+)','<a href="\\1" target="_blank">\\1</a>', $URL);
	//$URL = eregi_replace('((www\.)[-a-zA-Z0-9@:\+.~#?&//=]+)','<a href="http://\\1" target="_blank">\\1</a>', $URL);
	$URL = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})','<a href=\\1>\\1</a>', $URL);
	return $URL;
}

function random(){
	$pr_bits = '';
	// Unix/Linux platform?
	$fp = @fopen('/dev/urandom','rb');
	if ($fp !== FALSE) {
		$pr_bits .= @fread($fp,24);
		@fclose($fp);
	}
	// MS-Windows platform?
	if (@class_exists('COM')) {
		// http://msdn.microsoft.com/en-us/library/aa388176(VS.85).aspx
		try {
			$CAPI_Util = new COM('CAPICOM.Utilities.1');
			$pr_bits .= $CAPI_Util->GetRandom(16,0);

			// if we ask for binary data PHP munges it, so we
			// request base64 return value.  We squeeze out the
			// redundancy and useless ==CRLF by hashing...
			if ($pr_bits) { $pr_bits = md5($pr_bits,TRUE); }
		} catch (Exception $ex) {
			// echo 'Exception: ' . $ex->getMessage();
		}
	}

	if (strlen($pr_bits) < 16) {
		// do something to warn system owner that
		// pseudorandom generator is missing
	}
	return $pr_bits;
}

?>
