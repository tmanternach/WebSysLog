<?php require_once("config.php"); ?>
<html>
<head>
<title><?=$title;?></title>
<link href='http://fonts.googleapis.com/css?family=Inconsolata:400,700' rel='stylesheet' type='text/css'>
<link href='http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css' rel='stylesheet' type='text/css'>
<style>
body {
	background-color: rgb(17,17,17);
	font-family: 'Inconsolata', sans-serif;
	font-weight: 400;
	font-size: 15px;
	color: white;
	margin: 0;
	padding: 0;
	min-width: 600px;
}
.receivedAt {
	color: #009E52;
}
.fromHost {
	color: #FFFA9E;
	margin-left: 5px;
	cursor: pointer;
}
.Tag {
	color: #0081C2;
	margin-left: 5px;
	cursor: pointer;
}
ul {
	list-style-type: none;
	padding: 0;
	margin: 0;
}
li {
	display: inline-block;
	float:left;
	margin-bottom: 1px;
	clear:left;
}
#main {
	height: -webkit-calc(100% - 55px);
	position: fixed;
	bottom: 0;
	width: 100%;
	min-width: 400px;
	overflow-y: scroll;
	overflow-x: hidden;
	float: left;
}
#header {
	width: 100%;
	position: fixed;
	top: 0;
	height: 50px;
	background-color: rgba(38,38,38,0);
	border-bottom: 2px solid rgb(38,38,38);
	line-height: 50px;
	padding-left: 15px;
}
#search {
	width: -webkit-calc(100% - 13px);
	position: fixed;
	bottom:0;
	height: 55px;
	background-color: rgba(72,72,72,1);
	line-height: 55px;
}
#sidebar {
	height: 100%;
	width: 200px;
	float: right;
}
::-webkit-scrollbar {
    width: 13px;
}
::-webkit-scrollbar-track-piece {
	background-color: rgb(38, 38, 38);
}
::-webkit-scrollbar-thumb {
	background-color: rgba(255, 255, 255, .5);
}
h1 {
	padding: 0;
	margin: 0;
	display: inline;
	padding-right: 15px;
	cursor: pointer;
}
input {
	background-color: white;
	color: black;
	border: 0;
	padding: 5px;
	margin: 5px;
	font-family: 'Inconsolata', sans-serif;
	font-weight: 400;
	font-size: 20px;
}
#searchheader {
	display: inline;
	position: absolute;
	right: 50px;
}
#loader {
	display: none;
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
</head>
<body>
<div id="header"><h1><?=$title;?></h1>
	<div id="searchheader">
		<input type="text" id="searchbox" name="searchbox"></input>
		<input type="button" id="submit" name="submit" value="Search"</input>
	</div>
	<div id="loader">Loading...</div>
</div>
<div id="main">
<ul id="messages">
<?php
$sql="SELECT * FROM SystemEvents ORDER BY ID DESC LIMIT 100";

$result = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($result))
  {
  $ID = $row['ID'];
  $receivedAt = substr($row['ReceivedAt'], 5);
  $receivedDay = substr($receivedAt, 0, 5);
  $receivedTime = substr($receivedAt, 5);
  $today = date("m-d");
  if($today == $receivedDay) { $receivedDay = "Today"; }
  $fromtHost = $row['FromHost'];
  $syslogTag = $row['SysLogTag'];
  $message = $row['Message'];
  $syslogTagPos = strpos($syslogTag, "[");
  if($syslogTagPos == "0") {
    $syslogTagPos = strpos($syslogTag, ":");
  }
  echo "<li class=\"live\" id=\"$ID\">";
  echo "<span id=\"$ID\" class=\"receivedAt\">$receivedAt</span>";
  echo "<span id=\"$ID\" class=\"fromHost\">$fromtHost</span>";
  echo "<span id=\"$ID\" class=\"Tag\">".substr($syslogTag, 0, $syslogTagPos).":</span>";
  echo "<span id=\"$ID\" class=\"Message\">$message</span>";
  echo "</li>";
  if( $i == 0 ){
	  $lastId = $row['ID'];
  }
  $i++;
  }
mysql_close($con);
?>
</ul>
</div>
<script type="text/javascript">
var items = [];
$.getJSON('distinct.php', function(data) {
  $.each(data, function(key, val) {
    items.push(val);
  });
});
$( "#searchbox" ).autocomplete({ minLength: 2, source: items });

if(!window.Kolich){
  Kolich = {};
}

Kolich.Selector = {};
Kolich.Selector.getSelected = function(){
  var t = '';
  if(window.getSelection){
    t = window.getSelection();
  }else if(document.getSelection){
    t = document.getSelection();
  }else if(document.selection){
    t = document.selection.createRange().text;
  }
  return t;
}

Kolich.Selector.mouseup = function(){
  var st = Kolich.Selector.getSelected();
  if(st!=''){
    $("#searchbox").val(st);
    $("#searchbox").focus();
  }
}

$(document).ready(function(){
  $(document).bind("mouseup", Kolich.Selector.mouseup);
});


jQuery.ajaxSetup({
  beforeSend: function() {
     $('#loader').show();
  },
  complete: function(){
     $('#loader').hide();
  },
  success: function() {}
});

$(document).ready(function()
{
  var refreshId = setInterval(function()
  {
  	messageID = $('ul#messages li:first').attr('id');
  	firstMessageID = $('ul#messages li:last').attr('id');
  	$("#loadmore").html("<a href=\"update.php?firstId="+firstMessageID+"\">Load Old Items</a>");
  	if ($("#searchbox").val().length > 0) {}
  	else {
  	$.ajax({
  		beforeSend: function () {},
	  	url: "update.php",
	  	data: {lastId: messageID },
	  	success: function( data ) {
		  	$(data).hide().prependTo('#messages').effect("highlight", {color: "rgb(38, 38, 38)"}, 1000).slideDown("slow");
	  	}
  	})
  	}
  	var list = $('#messages li.live:gt(1500)');
  	list.hide();

  }, 1000);

  $("#submit").click(function() {
  	searchtext = $("#searchbox").val();
	document.location.hash = searchtext;
  	if ($("#searchbox").val().length < 1) {
  		$("#messages li").show();
		$("#messages li.search").hide();
  	}
  	else {
  	$("#messages li").hide();
  	$.ajax({
	  	url: "search.php",
	  	data: {q: searchtext },
	  	success: function( data ) {
		  	$(data).hide().prependTo('#messages').effect("highlight", {color: "rgb(38, 38, 38)"}, 1000).slideDown("slow");
		}
	}); }
  });
  
  $("#header h1").click(function() {
	 $("#searchbox").val("");
	 $("#messages li.live").show();
	 $("#messages li.search").remove();
	 document.location.hash = "";
  });
  
  $('#searchbox').keypress(function (e) {
  	if (e.which == 13) {
    	$('#submit').click();
  }
  });
});
$(document).on("click", ".fromHost", function(){
          searchtext = $(this).text();
          document.location.hash = searchtext;
          $("#searchbox").val(searchtext);
          $("#messages li").hide();
          $.ajax({
                url: "search.php",
                data: {q: searchtext, limit: "hosts" },
                success: function( data ) {
                        $(data).hide().prependTo('#messages').effect("highlight", {color: "rgb(38, 38, 38)"}, 1000).slideDown("slow");
                }
        });
});
$(document).on("click", ".Tag", function(){
	          searchtext = $(this).text();
          searchtext = searchtext.slice(0, -1);
          document.location.hash = searchtext;
          $("#searchbox").val(searchtext);
          $("#messages li").hide();
          $.ajax({
                url: "search.php",
                data: {q: searchtext, limit: "tags" },
                success: function( data ) {
                        $(data).hide().prependTo('#messages').effect("highlight", {color: "rgb(38, 38, 38)"}, 1000).slideDown("slow");
                }
        });
});
</script>
</body>
</html>
