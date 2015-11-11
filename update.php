<?php
$lastId = $_GET['lastId'];
if ($_GET['firstId'] != NULL){ echo "<li class=\"live\">woah there!</li>";}
require_once("config.php");
$sql="SELECT * FROM SystemEvents WHERE ID > $lastId ORDER BY ID DESC";

$result = mysql_query($sql);
while($row = mysql_fetch_array($result))
  {
  $ID = $row['ID'];
  $receivedAt = substr($row['ReceivedAt'], 5);
  $receivedDay = substr($receivedAt, 0, 5);
  $receivedTime = substr($receivedAt, 5);
  $today = date("m-d");
  if($today == $receivedDay) { $receivedDay = "Today"; }
  $fromtHost = $row['FromHost'];
  $syslogTag = $row['SysLogTag'];
  $message = htmlentities( $row['Message']);
  $syslogTagPos = strpos($syslogTag, "[");
  if($syslogTagPos == "0") {
    $syslogTagPos = strpos($syslogTag, ":");
  }
  echo "<li class=\"live\" id=\"$ID\">";
  echo "<span id=\"$ID\" class=\"receivedAt\">$receivedAt</span>";
  echo "<span class=\"fromHost\">$fromtHost</span>";
  echo "<span class=\"Tag\">".substr($syslogTag, 0, $syslogTagPos).":</span>";
  echo "<span class=\"Message\">$message</span>";
  echo "</li>";
  }
mysql_close($con);
?>
