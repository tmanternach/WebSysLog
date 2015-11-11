<?php
$query = $_GET['q'];
$query = trim($query);
require_once("config.php");
if($_GET['limit'] == "hosts") {
	$sql="SELECT * FROM SystemEvents WHERE FromHost like \"%$query%\" ORDER BY ID DESC LIMIT $search_limit";
}
elseif($_GET['limit'] == "tags") {
	$sql="SELECT * FROM SystemEvents WHERE SysLogTag like \"%$query%\" ORDER BY ID DESC LIMIT $search_limit";
}
else {
	$sql="SELECT * FROM SystemEvents WHERE FromHost like \"%$query%\" or SysLogTag like \"%$query%\" or Message like \"%$query%\" ORDER BY ID DESC LIMIT $search_limit";
}
$result = mysql_query($sql);
if(mysql_num_rows($result) == 0) { echo "<li class=\"search\">No results found.</li>";}
else {
while($row = mysql_fetch_array($result))
  {
  $ID = $row['ID'];
  if ($row['ID'] > $lastId )
		{
  		$receivedAt = substr($row['ReceivedAt'],5);
  		$fromtHost = $row['FromHost'];
  		$syslogTag = $row['SysLogTag'];
		$message = htmlentities( $row['Message']);
  		$syslogTagPos = strpos($syslogTag, "[");
  		echo "<li class=\"search\" id=\"$ID\">";
  		echo "<span class=\"receivedAt\">$receivedAt</span>";
  		echo "<span class=\"fromHost\">$fromtHost</span>";
  		echo "<span class=\"Tag\">".substr($syslogTag, 0, $syslogTagPos).":</span>";
  		echo "<span class=\"Message\">$message</span>";
  		echo "</li>";
  	}
  }
}
mysql_close($con);
?>
