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

	$id_col = $tbl->long_columnName('id');  
	$sql="SELECT * FROM " . $tbl->tableName() . " WHERE $id_col > $lastId ORDER BY $id_col DESC;";
	
	$result = mysql_query($sql, $database->getDBcon());

	while($row = mysql_fetch_array($result)) {
		 $id = $row[$tbl->columnName('id')];
		 $receivedAt = substr($row[$tbl->columnName('ReceivedAt')], 0);
		 $fromtHost = $row[$tbl->ColumnName('FromHost')];
		 $syslogTag = $row[$tbl->ColumnName('SysLogTag')];
		 $syslogLevel = $row[$tbl->ColumnName('Priority')];
		 $message = $row[$tbl->ColumnName('Message')];
		$syslogTagPos = strpos($syslogTag, "[");
		if($syslogTagPos == "0") {
			$syslogTagPos = strpos($syslogTag, ":");
		}
		echo "<li class=\"live\" id=\"$id\">";
			echo "<span id=\"$id\" class=\"receivedAt\">$receivedAt</span>";
			echo "<span id=\"$id\" class=\"fromHost\">$fromtHost</span>";
			  echo "<span id=\"$id\" class=\"Tag\">".$syslogLevel."</span>";
			echo "<span id=\"$id\" class=\"Message\">$message</span>";
		echo "</li>";
	}
?>
