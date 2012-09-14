<?php
	exit;
	// Ran this script. html_decoded all sigs (then ran the script again because I forgot to include the paramater that include quote marks)
	mysql_connect('localhost', 'andrew', 'xC4sL7p8');
	mysql_select_db('spergs2');

	$result = mysql_query("SELECT user_id, signature FROM Users");
	while ($row = mysql_fetch_array($result)) {
		$sig = str_replace('&#039;', '\'', $row['signature']);
		mysql_query("UPDATE Users SET signature='".mysql_real_escape_string($sig)."' WHERE user_id = '{$row['user_id']}'");
	}
?>