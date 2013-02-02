<?php
require("includes/Config.ini.php");
setcookie($name=AUTH_KEY1, $value='', $expire=1, $path="/", 
			$path=DOMAIN, $secure=USE_SSL, $httponly=TRUE);
setcookie($name=AUTH_KEY2, $value='', $expire=1, $path="/",           
                        $path=DOMAIN, $secure=USE_SSL, $httponly=TRUE);
if(isset($_COOKIE[session_name()])){                       
	$params = session_get_cookie_params();
	setcookie($name=session_name(), $value='', $expire=1, $path="/", 
				$domain=DOMAIN, $secure=USE_SSL, $httponly=TRUE);
	session_destroy();
}
header("Location: /index.php");
?>
