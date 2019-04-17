<?php 
	require_once("config.php");

	
	//Get Var
	$searchtext = $_GET['search'];
	$date = $_GET['date'];
?>

<html>
<head>
       <title><?=$title;?></title>
	<link href='http://fonts.googleapis.com/css?family=Inconsolata:400,700' rel='stylesheet' type='text/css'>
	<link href='http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css' rel='stylesheet' type='text/css'>
	
	<style>
		body {
			background-color: <?=$background_color;?>;
			font-family: 'Inconsolata', sans-serif;
			font-weight: 400;
			font-size: 15px;
			color: white;
			margin: 0;
			padding: 0;
			min-width: 600px;
		}
		.receivedAt {
			margin-left: 4px;
			color: <?=$date_color;?>;
			float: left;
		}
		.fromHost {
			color: <?=$host_color;?>;
			float: left;
			width: 100px;
			text-align: right;
			margin-left: 8px;
			cursor: pointer;
		}
		.Tag {
			color: <?=$tag_color;?>;
			float: left;
			width: 60px;
			text-align: left;
			margin-left: 8px;
			cursor: pointer;
		}
		.message {
			color: <?=$msg_color;?>;
			text-align: left;
			margin-left: 8px;
		}
		.warning {
			color: <?=$warning_color;?>;
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
			height: -moz-calc(100% - 55px);
                       height: calc(100% - 55px);
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
			background-color: <?=$header_color;?>;
			border-bottom: 2px solid rgb(38,38,38);
			line-height: 50px;
			padding-left: 15px;
		}
		#footer {
			height: px;
			width: calc(100% - 13px);
			padding-bottom: 4px;
			position: fixed;
			bottom: 0;
			left: 0;
			/*background: linear-gradient(transparent  0%, <?=$background_color;?> 90%);*/
			/*background: linear-gradient(to bottom, transparent 85%, <?=$background_color;?> 99%);*/
		}
		#colorBtn {
			position: absolute;
			bottom: 16px;
			right: 16px;
			float: right;
		}
		#setcolor {
			width: 200px;
			height: 400px;
			float: right;
			margin-right: 8px;
			margin-top: 8px;
			margin-bottom: 8px;
			border: 2px solid black;
			background: #282828;
		}
		#setcolor li {
			margin-left: 8px;
			margin-top: 8px;
		}
		.colorTxt {
			display: block;
			text-align: center;
		}
		#search {
			width: -webkit-calc(100% - 13px);
			width: -moz-calc(100% - 13px);
                       width: calc(100% - 13px);
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
		#reload {
			width: 170px;
		}
		#setdate {
			height: 31px;
		}
	</style>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>

</head>
<body>
	<div id="header">
		<h1><?=$title; ?></h1>
		
		<div id="searchheader">
			<input type="date" id="setdate" name="setdate" onchange="getDate()"></input>
			<input type="text" id="searchbox" name="searchbox" placeholder="Keyword"></input>
			<input type="button" id="submit" name="submit" value="Search"</input>
			<input type="button" id="reload" name="reload" value="Stop AutoReload"</input>
			<input type="button" id="clear" name="clear" value="Clear"</input>
		</div>
		
		<div id="loader" class="warning">Loading...</div>
	</div>
	
	<div id="main">
		<ul id="messages">
			<?php
                               $sql="SELECT * FROM " . $tbl->tableName() ." ORDER BY ID DESC LIMIT $search_limit";
                               $result = mysql_query($sql, $database->getDBcon());
				$i = 0;
				while($row = mysql_fetch_array($result)) {
                                       $receivedAt = substr($row[$tbl->columnName('ReceivedAt')], 0);
                                       $fromHost = $row[$tbl->columnName('FromHost')];
                                       $DisplaySyslogTag = $row[$tbl->columnName('SyslogTag')];
                                       $DisplaySyslogLevel = $row[$tbl->columnName('Priority')];
                                       $message = $row[$tbl->columnName('Message')];
                                       $id = $row[$tbl->columnName('id')];

                                       $syslogTagPos = strpos($DisplaySyslogTag, "[");
					if($syslogTagPos == "0") {
                                               $syslogTagPos = strpos($DisplaySyslogTag, ":");
					}
				  
					//Log Output
					echo "<li class=\"live\" id=\"$id\">";
						echo "<span id=\"$id\" class=\"receivedAt\">$receivedAt</span>";
                                               echo "<span id=\"$id\" class=\"fromHost\">$fromHost</span>";
                                               echo "<span id=\"$id\" class=\"Tag\">".$DisplaySyslogLevel.": </span>";
						echo "<span id=\"$id\" class=\"Message\">$message</span>";
					echo "</li>";
				
					if( $i == 0 ){
                                               $lastId = $id;
					}
					$i++;
					
					if ($t == 0) {
                                               $lastTime = $receivedAt;
					}
					$t++;
				}
			?>
		</ul>
	</div>
	
	<div id="footer">
		<!--<input type="button" id="colorBtn" name="colorBtn" value="Color"></input>
		
		<form id="setcolor" method="POST" action="<?php $_PHP_SELF ?>">
			<li>Background:	<input type="text" name="background" class="colorTxt" size="8" placeholder="#111111"></input></li>
			<li>Date: 		<input type="text" name="date" class="colorTxt" size="8" placeholder="#009E52"></input></li>
			<li>IP:			<input type="text" name="host" class="colorTxt" size="8" placeholder="#FFFA9E"></input></li>
			<li>Tag:		<input type="text" name="tag" class="colorTxt" size="8" placeholder="#0081C2"></input></li>
			<li>Message:	<input type="text" name="msg" class="colorTxt" size="8" placeholder="#ffffff"></input></li>
			<li>Warning:	<input type="text" name="warning" id="colorTxt" class="colorTxt" size="8" placeholder="#AA0000"></input></li>
			<input type="hidden" id="enterColor"> </input>
		</form>-->
	</div>

	<!-- JavaScript / jQuery -->
	<script type="text/javascript">
		//Select Date Filed
		var sDate;
		function getDate() {
			sDate = document.getElementById("setdate").value;
		}
		
		//Autocomplete for Search Box
		var items = [];
		$.getJSON('distinct.php', function(data) {
			$.each(data, function(key, val) {
		    	items.push(val);
		  	});
		});
		$( "#searchbox" ).autocomplete({ minLength: 2, source: items });
		
		//Text selection
		if(!window.Kolich){
			Kolich = {};
		}
		Kolich.Selector = {};
		Kolich.Selector.getSelected = function(){
			var t = '';
			if(window.getSelection){
				t = window.getSelection();
			}
			else if(document.getSelection){
				t = document.getSelection();
			}
			else if(document.selection){
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
				$('#warning').hide();
			},
			complete: function(){
				$('#loader').hide();
				$('#warning').show();
			},
			success: function() {}
		});
		
		$(document).ready(function() {
			//Auto Reload	
			var skipReload = 0;
			$("#reload").click(function() {
				//Stop Reload
				if (skipReload == 0) {
					skipReload = 1;
					$("#reload").effect("highlight", {color: "<?=$warning_color;?>"}, 2000);
					this.value = "Start AutoReload"
				}
				//Start Reload
				else if (skipReload == 1){
					skipReload = 0;
					$("#reload").effect("highlight", {color: "<?=$warning_color;?>"}, 2000);
					this.value = "Stop AutoReload"
				}
			});
			
			//AutoReload
			var refreshId = setInterval(function () {
				messageID = $('ul#messages li:first').attr('id');
				firstMessageID = $('ul#messages li:last').attr('id');
				$("#loadmore").html("<a href=\"update.php?firstId="+firstMessageID+"\">Load Old Items</a>");
				//Skip Reload when Stopped
				if (skipReload == 1) {
					//Skip Reload
				}
				else {
					if ($("#searchbox").val().length > 0) {
						//Nothing
					}
					else {
						$.ajax({
							beforeSend: function () {},
							url: "update.php",
							data: {lastId: messageID},
							success: function( data ) {
								$(data).hide().prependTo('#messages').effect("highlight", {color: "rgb(38, 38, 38)"}, 1000).slideDown("slow");
							}
						})
					}
				}
				var list = $('#messages li.live:gt(600)'); //Allow Max Query Lines in Standard view
				//list.hide();
				list.remove();
			}, 1000); //Refresh every Second
			
			//Direct search in URL
			var phpSearchText = "<?php print($searchtext); ?>";
			var phpDate = "<?php print($date); ?>";
			if (phpSearchText || phpDate) {
				skipReload = 1; //Stop AutoReload
				document.getElementById("reload").value = "Start AutoReload";
				document.getElementById("searchbox").value = phpSearchText;
				document.getElementById("setdate").value = phpDate;
				$("#reload").effect("highlight", {color: "<?=$warning_color;?>"}, 2000);
				$("#messages li").hide();
				$("#messages span").hide(); //fix Message hideing
				$.ajax({
					url: "search.php",
					data: { q: phpSearchText, d: phpDate},
					success: function( data ) {
						$(data).hide().prependTo('#messages').effect("highlight", {color: "rgb(38, 38, 38)"}, 1000).slideDown("slow");
					}
				});
			}
			
			//Search Button pressed
			$("#submit").click(function() {
				skipReload = 1; //Stop AutoReload
				document.getElementById("reload").value = "Start AutoReload";
				$("#reload").effect("highlight", {color: "<?=$warning_color;?>"}, 2000);
				searchtext = $("#searchbox").val();
				//document.location = "?search=" + searchtext + "&date=" + sDate;
				document.location.hash = searchtext + "#" + sDate;
				if ($("#searchbox").val().length < 0) {
					$("#messages li").show();
					$("#messages li.search").hide();
				}
				else {
					$("#messages li").hide();
					$("#messages span").hide(); //fix Message hideing
					$.ajax({
						url: "search.php",
						data: {q: searchtext, d: sDate},
						success: function( data ) {
							$(data).hide().prependTo('#messages').effect("highlight", {color: "rgb(38, 38, 38)"}, 1000).slideDown("slow");
						}
					});
				}
			});
			
			//Clear Button pressed
			$("#clear").click(function() {
				$("#messages li").hide();
			});
			
			//Title pressed
			$("#header h1").click(function() {
				/*
				$("#loader").hide();
				$("#warning").hide();
				$("#messages span.search").remove();
				$("#messages span.warning").remove();
				$("#messages li.search").remove();
				$("#searchbox").val("");
				$("#messages li.live").show();
				$("#messages span").show(); //fix Message hideing
				document.getElementById("setdate").value = "";
				skipReload = 0; //Start Auto Reload
				document.getElementById("reload").value = "Stop AutoReload";
				*/
				location.reload();
				//document.location.hash = "";
				document.location.assign("/syslog/")
				$("#reload").effect("highlight", {color: "<?=$warning_color;?>"}, 2000);
			});
			
			$('#searchbox').keypress(function (e) {
				if (e.which == 13) {
					$('#submit').click();
				}
			});
			
			//Color Button pressed
			$("#setcolor").hide();
			$("#colorBtn").click(function() {
				$("#setcolor").toggle(1000);
			});
			
			//Color Submit
			$("#colorTxt").keypress(function(e) {
				if (e.keyCode==13)
				$("#enterColor").click();
				alert("test");
			})
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
