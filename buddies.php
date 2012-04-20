<?php
include_once('mysql.php'); //connect to MySQL
//load dive data
$sql="SELECT Buddy FROM DL_Logbook";
$result=mysql_query($sql);
mysql_error();

function foundBuddy($name){
  global $buddies;
  
  if(!isset($buddies[$name])) //count
    $buddies[$name]=1;
  else
    $buddies[$name]++;
}

//loop through data counting buddies
while ($row=mysql_fetch_array($result)){
  //extract buddy name
  $i=0;$n="";
  while ($i<strlen($row[Buddy])){
    if ($row[Buddy][$i]==','){
      foundBuddy($n);
      $i=$i+2; $n="";
    }
    $n=$n.$row[Buddy][$i];
    $i++;
  }
  foundBuddy($n);
}
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
</script>

<!--[if IE]><script language="javascript" type="text/javascript" src="jqplot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="jqplot/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.min.css" />

</head>
<body>
<h1><a href="buddies.php" style="color:#FFCC00;text-decoration:none;">Buddies</a> | <a href="/divelog" style="color:white;text-decoration:none;">Diving Logbook</a> | <a href="statistics.php" style="color:white;text-decoration:none;">Statistics</a></h1>
<table>
<tr><th style="text-align:right">No. Dives</th><th style="vertical-align:bottom">Buddy</th></tr>
<?php //echo dive buddies
array_multisort(array_values($buddies), SORT_DESC, array_keys($buddies), $buddies);

$i=0;
foreach($buddies as $name => $numdives){
  echo "<tr><td style=\"text-align:right\">";
  if ($i!=$numdives){
    echo $numdives; $i=$numdives;}
  echo "</td><td><a href=\"index.php?buddy=$name\" class=\"buddy\">$name</a></td></tr>\n";
  }
?>
</table>
</body> 
</html>
