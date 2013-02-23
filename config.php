<?php
$title = "Syslog Viewer"; //This appears a the <title> of your page and also the h1 header at the top of the page.
$hostname = "localhost"; //Change this to IP or hostname of your database server. Probably localhost.
$username = "user"; //This is a MySQL user that has access to the Syslog database.
$password = "password"; //Password for above user.
$database_name = "Syslog"; //The name of the database for the Syslog events.
$con = mysql_connect($hostname,$username,$password);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($database_name, $con);

?>
