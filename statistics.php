<?php
include_once('mysql.php'); //connect to MySQL
//load dive data
$sql="SELECT Number, Divedate, Divetime, Depth, City, Place, Buddy, Watertemp FROM DL_Logbook";
$result = mysql_query($sql);
mysql_error();

//initialise
$totalDives=mysql_num_rows($result);
$totalMins=0;
$longest[Divetime]=0;
$shortest[Divetime]=500;
$hottest[Watertemp]=0;
$coldest[Watertemp]=100;

//loop through dives
while($row=mysql_fetch_array($result)){
  //Total dive time
  $totalMins=$totalMins+$row[Divetime];
 
  //Longest Dive
  if ($row[Divetime]>$longest[Divetime])
    $longest=$row;
    
  //Shortest Dive
  if ($row[Divetime]<$shortest[Divetime])
    $shortest=$row;
  
  if($row[Watertemp]!=""){      
    //Hottest Dive
    if ($row[Watertemp]>$hottest[Watertemp])
      $hottest=$row;
       
    //Coldest Dive
    if ($row[Watertemp]<$coldest[Watertemp])
      $coldest=$row;
  }
}

//Reformat total dive time
$totaldivetime="";

$days = (int)($totalMins/1440);
if ($days>1)
  $totaldivetime = "$days Days";
else if ($days==1)
  $totaldivetime = "1 Day";

$hrs = (int)(($totalMins-($days*1440))/60);
if ($hrs>1)
  $totaldivetime = $totaldivetime." $hrs Hours";
else if ($days==1)
  $totaldivetime = $totaldivetime." 1 Hour";
  
$mins = $totalMins-($days*1440)-($hrs*60);
if ($mins>1)
  $totaldivetime = $totaldivetime." $mins Minutes";
else if ($mins==1)
  $totaldivetime = $totaldivetime." 1 Minute";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Dive Logbook</title>
<link rel="stylesheet" type="text/css" href="../styles/global.css" />
<link rel="stylesheet" type="text/css" href="log.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
</head>
<body>
<h1><a href="/divelog" style="color:white;text-decoration:none;">Diving Logbook</a> | <a href="buddies.php" style="color:white;text-decoration:none;">Buddies</a> | <a href="statistics.php" style="color:#FFCC00;text-decoration:none;">Statistics</a></h1>
<table>
<tr><td class="stat">Total Dive Time</td><td><? echo $totaldivetime; ?></td></tr>
<tr><td class="stat">Total Number of Dives</td><td><? echo "$totalDives Dives"; ?></td></tr>
<tr></tr>
<tr><td class="stat">Longest Dive</td><td><? echo "$longest[Divetime] Minutes with $longest[Buddy] (<a href=\"index.php?id=$longest[Number]\">#$longest[Number]</a>)"; ?></td></tr>
<tr><td class="stat">Shortest Dive</td><td><? echo "$shortest[Divetime] Minutes with $shortest[Buddy] (<a href=\"index.php?id=$shortest[Number]\">#$shortest[Number]</a>)"; ?></td></tr>
<tr><td class="stat">Average Dive</td><td><? echo (int)($totalMins/$totalDives)." Minutes"; ?></td></tr>
<tr></tr>
<tr><td class="stat">Hottest Dive</td><td><? echo "$hottest[Watertemp] &deg;C with $hottest[Buddy] (<a href=\"index.php?id=$hottest[Number]\">#$hottest[Number]</a>)"; ?></td></tr>
<tr><td class="stat">Coldest Dive</td><td><? echo "$coldest[Watertemp] &deg;C with $coldest[Buddy] (<a href=\"index.php?id=$coldest[Number]\">#$coldest[Number]</a>)"; ?></td></tr>
</table>
</body> 
</html>
