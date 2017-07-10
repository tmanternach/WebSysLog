<?php
	//Include
	require_once("config.php");

	//Function for no results found
	function noResFound() {
		echo "<span class=\"warning\">No results found! ¯\_(ツ)_/¯</span><br>";
	}
	
	//Function for SELECT in search.php, 4 Days Archive
	function selectFromArchive4DaysDBM($db, $date, $query, $limit) {
		$sql="SELECT * FROM (
					SELECT * FROM ".$db." WHERE timestamp LIKE '".$date."%' AND (timestamp LIKE '%".$query."%' OR host LIKE '%".$query."%' OR msg LIKE '%".$query."%')
					ORDER BY id DESC
				)
				sub
				ORDER BY timestamp DESC LIMIT 500";
		$result = mysql_query($sql);
		
		return $result;
	}
	
	//Function for SELECT in search.php
	function selectFromArchiveDBM($db, $date, $query, $limit) {
		$sql="SELECT id,timestamp,host,facility,level,tag,msg FROM ".$db." WHERE timestamp LIKE '".$date."%' AND (timestamp LIKE '%".$query."%' OR host LIKE '%".$query."%'
				OR msg LIKE '%".$query."%')
				ORDER BY timestamp DESC LIMIT 500";
		$result = mysql_query($sql);
		
		return $result;
	}
?>
