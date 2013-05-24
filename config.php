<?php

$title = "Syslog Viewer"; //This appears as the <title> of your page and also the h1 header at the top of the page.
$hostname = "localhost"; //Change this to IP or hostname of your database server. Probably localhost.
$username = "root"; //This is a MySQL user that has access to the Syslog database.
$password = "password"; //Password for above user.
$database_name = "Syslog"; //The name of the database for the Syslog events.
$search_limit = 300;

# COLORS
$background_color = "rgb(17,17,17)";
$header_color = "rgb(17,17,17)";
$date_color = "#009E52";
$host_color = "#FFFA9E";
$tag_color = "#0081C2";

# DO NOT EDIT BELOW THIS LINE

$con = mysql_connect($hostname,$username,$password);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($database_name, $con);
?>
