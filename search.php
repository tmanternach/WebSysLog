<?php
	//Get Script time

	//Set Var
	$curDate = date("Y-m-d");
	$query = NULL;
	$date = NULL;
	

	require_once("config.php");
	include("function.php");

	$id_col = $tbl->long_columnName('id');
	$host_col = $tbl->long_columnName('FromHost');
	$tag_col = $tbl->long_columnName('SyslogTag');
	$message_col = $tbl->long_columnName('Message');
	$receivedAt_col = $tbl->long_columnName('ReceivedAt');
	$validSearch = FALSE;

	if(array_key_exists("d", $_GET)){
		 $date = $_GET['d'];
		 $date = trim($date);
		 $sql_date = $receivedAt_col . " LIKE \"%$date%\" ";
		 $validSearch = TRUE;
	} else {
		 $sql_date = "1=1 ";
	}
	if(array_key_exists("q", $_GET) && $_GET['q'] != ''){
		 $query = $_GET['q'];
		 $query = trim($query);
		 $sql_query = "AND ($host_col LIKE \"%$query%\" OR $message_col LIKE \"%$query%\")";
		 $validSearch = TRUE;
	} else {
		 $sql_query = "";
	}
	

	
	if ($date <= $curDate || $date == NULL || $date == "undefined") {
		if($_GET['limit'] == "hosts") {
			  $sql="SELECT * FROM " . $tbl->TableName() . " WHERE $host_col like \"%$query%\" ORDER BY $id_col DESC LIMIT $search_limit";
		}
		elseif($_GET['limit'] == "tags") {
			  $sql="SELECT * FROM " . $tbl->TableName() . " WHERE $tag_col like \"%$query%\" ORDER BY $id_col DESC LIMIT $search_limit";
		}
		 elseif($validSearch) {
			$sql="SELECT * FROM (
					    SELECT * FROM " . $tbl->TableName() . " WHERE " .  
					    $sql_date . $sql_query . " ORDER BY $id_col DESC
				)
				sub
				   ORDER BY " . $tbl->columnName('receivedAt') . " DESC
				LIMIT $search_limit";
		}
		 else {
			   die("<span class=\"warning\">Invalid Parameter!</span><br>");  
		 }
		 $result = mysql_query($sql, $database->getDBcon());
		if(mysql_num_rows($result) == 0) {
			noResFound();
		}
		else {
			while($row = mysql_fetch_array($result)) {
				   $id = $row[$tbl->columnName('id')];
				   if ($id > $lastId ) {
					    $receivedAt = substr($row[$tbl->columnName('ReceivedAt')],0);
					    $fromtHost = $row[$tbl->columnName('FromHost')];
					    $DisplaySyslogTag = $row[$tbl->columnName('SyslogTag')];
					    $DisplaySyslogLevel = $row[$tbl->columnName('Priority')];
					    $message = htmlentities( $row[$tbl->columnName('Message')]);
					    $syslogTagPos = strpos($DisplaySyslogTag, "[");

			  		echo "<li class=\"search\" id=\"$id\">";
			  		echo "<span class=\"receivedAt\">$receivedAt</span>";
			  		echo "<span class=\"fromHost\">$fromtHost</span>";
					    echo "<span id=\"$id\" class=\"Tag\">".$DisplaySyslogLevel.": </span>";
			  		echo "<span class=\"Message\">$message</span>";
			  		echo "</li>";
			  	}
			}
		}
	}
	else if ($date > $curDate) {
		echo "<span class=\"warning\">Invalid Date!</span><br>";
	}
	/*
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
	} */
	else {
		echo "<span class=\"warning\">D'oh...!</span><br>";
	}
	
?>
