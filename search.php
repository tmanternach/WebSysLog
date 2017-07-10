<?php
	//Get Script time

	//Set Var
	$query = $_GET['q'];
	$query = trim($query);
	$date = $_GET['d'];
	$date = trim($date);
	$curDate = date("Y-m-d");
	
	require_once("config.php");
	include("function.php");
	
	if ($date == $curDate || $date == NULL || $date == "undefined") {
		if($_GET['limit'] == "hosts") {
			$sql="SELECT * FROM syslog.syslog WHERE host like \"%$query%\" ORDER BY timestamp DESC LIMIT $search_limit";
		}
		elseif($_GET['limit'] == "tags") {
			$sql="SELECT * FROM syslog.syslog WHERE level like \"%$query%\" ORDER BY timestamp DESC LIMIT $search_limit";
		}
		else {
			$sql="SELECT * FROM (
					SELECT * FROM syslog.syslog WHERE timestamp LIKE \".$date.%\" OR host LIKE \"%$query%\" OR msg LIKE \"%$query%\" ORDER BY id DESC
				)
				sub
				ORDER BY timestamp DESC
				LIMIT $search_limit";
		}
		$result = mysql_query($sql);
		if(mysql_num_rows($result) == 0) {
			noResFound();
		}
		else {
			while($row = mysql_fetch_array($result)) {
				$ID = $row['id'];
				if ($row['id'] > $lastId ) {
			  		$receivedAt = substr($row['timestamp'],0);
			  		$fromtHost = $row['host'];
			  		$syslogTag = $row['tag'];
					$syslogLevel = $row['level'];
					$message = htmlentities( $row['msg']);
			  		$syslogTagPos = strpos($syslogTag, "[");
			  		echo "<li class=\"search\" id=\"$id\">";
			  		echo "<span class=\"receivedAt\">$receivedAt</span>";
			  		echo "<span class=\"fromHost\">$fromtHost</span>";
			  		echo "<span id=\"$id\" class=\"Tag\">".$syslogLevel.": </span>";
			  		echo "<span class=\"Message\">$message</span>";
			  		echo "</li>";
			  	}
			}
		}
	}
	else if ($date > $curDate) {
		echo "<span class=\"warning\">Invalid Date!</span><br>";
	}
	//Get Results from 4 Days Archvie Table
	else if ($date < $curDate && $date >= date("Y-m-d", strtotime($curdate . "-4 days"))) {
		$db = "syslog.archive_4days";
		$result = selectFromArchive4DaysDBM($db, $date, $query);
		
		if(mysql_num_rows($result) == 0) {
			noResFound();
		}
		else {
			while($row = mysql_fetch_array($result)) {
				$ID = $row['id'];
				if ($row['id'] > $lastId ) {
			  		$receivedAt = substr($row['timestamp'],0);
			  		$fromtHost = $row['host'];
			  		$syslogTag = $row['tag'];
					$syslogLevel = $row['level'];
					$message = htmlentities( $row['msg']);
			  		$syslogTagPos = strpos($syslogTag, "[");
			  		echo "<li class=\"search\" id=\"$id\">";
			  		echo "<span class=\"receivedAt\">$receivedAt</span>";
			  		echo "<span class=\"fromHost\">$fromtHost</span>";
			  		echo "<span id=\"$id\" class=\"Tag\">".$syslogLevel.": </span>";
			  		echo "<span class=\"Message\">$message</span>";
			  		echo "</li>";
			  	}
			}
		}
	}
	//Get Results older than 1 Day and younger than 31 Days (1 Month) (Monthly Archive)
	else if ($date < date("Y-m-d", strtotime($curdate . "-4 days")) && $date >= date("Y-m-d", strtotime($curdate . "-31 days"))) {
		$db = "syslog.archive_month";
		$result = selectFromArchiveDBM($db, $date, $query);
		
		if(mysql_num_rows($result) == 0) {
			noResFound();
		}
		else {
			while($row = mysql_fetch_array($result)) {
				$ID = $row['id'];
				if ($row['id'] > $lastId ) {
			  		$receivedAt = substr($row['timestamp'],0);
			  		$fromtHost = $row['host'];
			  		$syslogTag = $row['tag'];
					$syslogLevel = $row['level'];
					$message = htmlentities( $row['msg']);
			  		$syslogTagPos = strpos($syslogTag, "[");
			  		echo "<li class=\"search\" id=\"$id\">";
			  		echo "<span class=\"receivedAt\">$receivedAt</span>";
			  		echo "<span class=\"fromHost\">$fromtHost</span>";
			  		echo "<span id=\"$id\" class=\"Tag\">".$syslogLevel.": </span>";
			  		echo "<span class=\"Message\">$message</span>";
			  		echo "</li>";
			  	}
			}
		}
	}
	//Get Results older than 31 Days (1 Month) (Yearly Archive)
	else if ($date < date("Y-m-d", strtotime($curdate . "-31 days"))) {
		//echo "<span class=\"warning\">Archive search ended! Limit reached.</span><br>";
		
		$db = "syslog.archive_year";
		$result = selectFromArchiveDBM($db, $date, $query);
		
		if(mysql_num_rows($result) == 0) {
			noResFound();
		}
		else {
			while($row = mysql_fetch_array($result)) {
				$ID = $row['id'];
				if ($row['id'] > $lastId ) {
			  		$receivedAt = substr($row['timestamp'],0);
			  		$fromtHost = $row['host'];
			  		$syslogTag = $row['tag'];
					$syslogLevel = $row['level'];
					$message = htmlentities( $row['msg']);
			  		$syslogTagPos = strpos($syslogTag, "[");
			  		echo "<li class=\"search\" id=\"$id\">";
			  		echo "<span class=\"receivedAt\">$receivedAt</span>";
			  		echo "<span class=\"fromHost\">$fromtHost</span>";
			  		echo "<span id=\"$id\" class=\"Tag\">".$syslogLevel.": </span>";
			  		echo "<span class=\"Message\">$message</span>";
			  		echo "</li>";
			  	}
			}
		}
	}
	else {
		echo "<span class=\"warning\">D'oh...!</span><br>";
	}
	
	mysql_close($con);
?>
