<?php
require("includes/init.php");
$authUser->logout();
header("Location: ./index.php");
?>
