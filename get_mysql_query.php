<?php
	require_once("config.php");
	
	//Get Proc list
	$sql="SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST WHERE USER LIKE \"" . $database.getUserName() . "\" AND COMMAND LIKE 'Query' AND TIME > 600";
	$result = mysql_query($sql, $database->getDBcon());
	
	while($row = mysql_fetch_array($result)) {
		$id = $row['ID'];
		$rtime = $row['TIME'];
		
		echo $id ." ". $rtime ."<br>";
		
		//Kill
		$kill_proc = "KILL $id";
		mysql_query($kill_proc, $database->getDBcon());
	}
?>
