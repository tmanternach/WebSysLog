<?php
	$title = "Syslog WebViewer"; //This appears as the <title> of your page and also the h1 header at the top of the page.
	$hostname = "localhost"; //Change this to IP or hostname of your database server. Probably localhost.
	$username = "user"; //This is a MySQL user that has access to the Syslog database.
	$password = "pass"; //Password for above user.
	$database_name = "syslog"; //The name of the database for the Syslog events.
	$search_limit = 200;
	
	# COLORS
	$background_color = "#111111";
	$header_color = "#111111";
	$date_color = "#009E52";
	$host_color = "#FFFA9E";
	$tag_color = "#0081C2";
	$msg_color = "#ffffff";
	$warning_color = "#AA0000";
	
	
	
	# ---DO NOT EDIT BELOW THIS LINE---
	
	$con = mysql_connect($hostname,$username,$password);
	if (!$con) {
		die('Could not connect: ' . mysql_error());
	}
	
	set_time_limit(300);	
	mysql_select_db($database_name, $con);
?>
