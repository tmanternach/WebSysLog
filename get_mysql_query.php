<?php
	require_once("config.php");
	
	//Get Proc list
	$sql="SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST WHERE USER LIKE 'web' AND COMMAND LIKE 'Query' AND TIME > 600";
	$result = mysql_query($sql);
	
	while($row = mysql_fetch_array($result)) {
		$id = $row['ID'];
		$rtime = $row['TIME'];
		
		echo $id ." ". $rtime ."<br>";
		
		//Kill
		$kill_proc = "KILL $id";
		mysql_query($kill_proc);
	}
		
	mysql_close($con);
?>
