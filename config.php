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


	// DB-Table
	$tbl_name = "syslog"; //The Table where Syslog events are stored.
	//$tbl_name = "SystemEvents";

	//Translation-Table for the various columnNames
	// Hint: Change only the right values! Left-Values are the indexes!
	$columnNames = array(
		 "id" => "id",
		 "ReceivedAt"  => "timestamp",
		 "FromHost" => "host",
		 "SysLogTag" => "tag",
		 "Priority" => "level",
		 "Message"  => "msg",
	);
	/*
	$columnNames = array(
		 "id" => "ID",
		 "ReceivedAt"  => "ReceivedAt",
		 "FromHost" => "FromHost",
		 "SysLogTag" => "SysLogTag",
		 "Priority" => "Priority",
		 "Message"  => "Message",
	);
	*/

	# ---DO NOT EDIT BELOW THIS LINE---
	require_once('dbhelper.php');

	$database = new DBObject($hostname, $username, $password, $database_name);
	$tbl = new TableObject($database, $tbl_name, $columnNames);
?>
