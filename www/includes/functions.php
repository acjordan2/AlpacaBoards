<?php
/*
 * functions.php
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

function verify_domain($domain_name)
{
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
}

function get_root_path()
{
    $root_path = "";
    $root_path_array = explode("/", dirname(__FILE__));
    for ($i=0; $i<sizeof($root_path_array)-1; $i++) {
        $root_path .= $root_path_array[$i]."/";
    }
    $root_path = substr($root_path, 0, strlen($root_path)-1);
    return $root_path;
}

function get_base_url($root_path)
{
    $time = explode(' ', microtime());
    $start = $time[1] + $time[0];

    $tempPath1 = explode('/', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
    $tempPath2 = explode('/', substr($root_path, 0, -1));
    $tempPath3 = explode('/', str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])));

    for ($i = count($tempPath2); $i < count($tempPath1); $i++) {
        array_pop($tempPath3);
    }

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }

    if (verify_domain($_SERVER['HTTP_HOST'])) {
        $urladdr = $protocol.urlencode($_SERVER['HTTP_HOST']).implode('/', $tempPath3);
        if (!($urladdr{strlen($urladdr) - 1}== '/')) {
            $urladdr .= "/";
        }
    } else {
        $urladdr = $tempPath3;
    }
    return $urladdr;
}

function getCombinations($data)
{
    $combo = array();
    sort($data);

    for ($i = 0; $i<sizeof($data); $i++) {
        for ($j = $i+1; $j<sizeof($data); $j++) {
            $combo[] = array($data[$i], $data[$j]);
        }
    }

    return $combo;
}
