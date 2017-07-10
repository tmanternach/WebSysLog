<?php
	//Set Var
	$query = $_GET['q'];
	$query = trim($query);
	$date = $_GET['d'];
	$date = trim($date);
	$curDate = date("Y-m-d");
	$lastId = $_GET['lastId'];
	
	require_once("config.php");

	if ($_GET['firstId'] != NULL){
		echo "<li class=\"live\">D'oh...!</li>";
	}
	
	if ($query == NULL && $date == NULL) {
		
	}
	
	$sql="SELECT * FROM syslog.syslog WHERE id > $lastId ORDER BY timestamp DESC;";
	
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$id = $row['id'];
		$receivedAt = substr($row['timestamp'], 0);
		$fromtHost = $row['host'];
		$syslogTag = $row['tag'];
		$syslogLevel = $row['level'];
		$message = $row['msg'];
		$syslogTagPos = strpos($syslogTag, "[");
		if($syslogTagPos == "0") {
			$syslogTagPos = strpos($syslogTag, ":");
		}
		echo "<li class=\"live\" id=\"$id\">";
			echo "<span id=\"$id\" class=\"receivedAt\">$receivedAt</span>";
			echo "<span id=\"$id\" class=\"fromHost\">$fromtHost</span>";
			echo "<span id=\"$id\" class=\"Tag\">".$syslogLevel.": </span>";
			echo "<span id=\"$id\" class=\"Message\">$message</span>";
		echo "</li>";
	}
	
	mysql_close($con);
?>
