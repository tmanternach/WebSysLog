<?php
	require_once("config.php");
	$sql="SELECT DISTINCT host FROM syslog.syslog ORDER BY host DESC";
	$result = mysql_query($sql);
	$arr = array();
	
	while($row = mysql_fetch_array($result)) {
		//if (strpos($row['FromHost'],$_GET['term']) !== false) {
			$arr[] = $row['host'];
		//}
	}
	
	echo json_encode($arr);
	mysql_close($con);
?>
