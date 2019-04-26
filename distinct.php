<?php
	require_once("config.php");
	$sql="SELECT DISTINCT " $tbl->long_columnName('FromHost') . " FROM " . $tbl->TableName() . " ORDER BY " . $tbl->long_columnName('FromHost') . " DESC";
	$result = mysql_query($sql, $database->getDBcon());
	$arr = array();
	
	while($row = mysql_fetch_array($result)) {
		//if (strpos($row[$tbl->columnName('FromHost')],$_GET['term']) !== false) {
			  $arr[] = $row[$tbl->columnName('FromHost')];
		//}
	}
	
	echo json_encode($arr);
?>
