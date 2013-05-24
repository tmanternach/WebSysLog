<?php
require_once("config.php");
$sql="SELECT DISTINCT FromHost FROM SystemEvents ORDER BY FromHost DESC";
$result = mysql_query($sql);
$arr = array();
while($row = mysql_fetch_array($result))
{
	//if (strpos($row['FromHost'],$_GET['term']) !== false) {
		$arr[] = $row['FromHost'];
	//}
}
echo json_encode($arr);
mysql_close($con);
?>
