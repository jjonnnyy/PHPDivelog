<?php
include_once('mysql.php'); //connect to MySQL
if(isset($_GET[buddy])){
  $buddy=htmlentities(mysql_real_escape_string($_GET[buddy]));

  $sql="SELECT Number, Divedate, City, Place FROM DL_Logbook WHERE Buddy LIKE '%$buddy%' ORDER BY Number DESC";
} else {
  $sql="SELECT Number, Divedate, City, Place FROM DL_Logbook ORDER BY Number DESC";
 }
$result=mysql_query($sql);
mysql_error();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Dive Logbook</title>
<link rel="stylesheet" type="text/css" href="../styles/global.css" />
<link rel="stylesheet" type="text/css" href="log.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> 
<script type="text/javascript">
 function showdives(start, end){
	$('.dive').each(function(){ //show to n
	if (($(this).attr('id')>=start) && ($(this).attr('id')<=end))
		$(this).show();
	else
		$(this).hide();
	});
 }
 
 $(document).ready(function(){
	var total = $('.dive').length;
	var n = total;
	var shownum = Math.floor((window.innerHeight - 120)/40);
	if (n<=shownum)
	  $('#olderdives').hide();
	$('#newerdives').hide();
	showdives((n-shownum),n);
	
	$('.dive').click(function(){ //click function, loads dive information
		var page = "getinfo.php?id=" + $(this).children('.num').html();
		$('#diveinfo').load(page);
    });
   
    $('#olderdives').click(function(){ //click for show more dives
		n=n-shownum-1;
		showdives((n-shownum), n);
		if (n<=(shownum+1))
			$('#olderdives').hide();
		else
			$('#olderdives').show();
		if (n==total)
			$('#newerdives').hide();
		else
			$('#newerdives').show();
	});
	
    $('#newerdives').click(function(){ //click for show more dives
		n=n+shownum+1;
		showdives((n-shownum), n);
		if (n<=(shownum+1))
			$('#olderdives').hide();
		else
			$('#olderdives').show();
		if (n==total)
			$('#newerdives').hide();
		else
			$('#newerdives').show();
	});
 });
</script>

<!--[if IE]><script language="javascript" type="text/javascript" src="jqplot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="jqplot/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.min.css" />

</head>
<body>
<h1><a href="/divelog" style="color:#FFCC00;text-decoration:none;">Diving Logbook</a> | <a href="buddies.php" style="color:white;text-decoration:none;">Buddies</a> | <a href="statistics.php" style="color:white;text-decoration:none;">Statistics</a></h1>
<div id="diveTable">
<table>
<tr>
<th style="text-align:right;">#</th><th>Date</th><th>Location</th>
</tr>
<tr><td></td><td colspan=2 id="newerdives">Next</td></tr>
<?
$i=mysql_num_rows($result);
while($row=mysql_fetch_array($result)){
	$date = new DateTime($row[Divedate]);
	$p_date = $date->format("d/m/Y");
	echo "<tr id=\"$i\" class=\"dive\"><td class=\"num\">$row[Number]</td><td>$p_date</td><td>$row[Place], $row[City]</td></tr>\n";
	$i--;
}
?>
<tr><td></td><td colspan=2 id="olderdives">Previous</td></tr>
</table>
</div>
<div id="infocontainer">
<div id="diveinfo">
<?php 
if (isset($_GET[id])){
  $_GET[id] = mysql_real_escape_string($_GET[id]);
  include 'getinfo.php';
} else { ?>
<h1><? if (isset($_GET[buddy])) echo "Dives with $_GET[buddy]<br />";?>Select a dive for more information</h1>
<? } ?>
</div>
</div>
</body> 
</html>
