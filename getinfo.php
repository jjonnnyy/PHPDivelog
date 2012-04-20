<?php
include('mysql.php');

$sql="SELECT Number, Divedate, Divetime, Depth, City, Place, Buddy, Watertemp, Comments, Profile, ProfileInt FROM DL_Logbook WHERE Number=$_GET[id]";
$result=mysql_query($sql);
$data=mysql_fetch_array($result);

$date = new DateTime($data[Divedate]);
$p_date = $date->format("jS M Y");

echo "<h1>#$data[Number] - $data[Place], $data[City]</h1>";
echo "<p><b>Date :</b> $p_date &nbsp;&nbsp;&nbsp;&nbsp; <b>Depth :</b> $data[Depth] meters &nbsp;&nbsp;&nbsp;&nbsp; <b>Divetime :</b> $data[Divetime] minutes</p>";

//start echo buddies
echo "<p><b>Buddy :</b> ";
$i=0;
while ($i<strlen($data[Buddy])){
  if ($data[Buddy][$i]==','){
    echo "<a href=\"?buddy=$Buddy\" target=\"_self\" class=\"buddy\">$Buddy</a>, ";
    $i=$i+2; $Buddy="";
  }
  
  $Buddy=$Buddy.$data[Buddy][$i];
  $i++;
}
echo "<a href=\"?buddy=$Buddy\" target=\"_self\" class=\"buddy\">$Buddy</a>";
echo  "&nbsp;&nbsp;&nbsp;&nbsp; ";
//end echo buddies

if ($data[Watertemp]!=NULL)
	echo "<b>Water Temperature :</b> $data[Watertemp] &deg;C";
echo "</p>";
	
if ($data[Profile]!="") {
	$profileData_str = str_split($data[Profile],6); //profile data as array of strings 
	$incr = $data[ProfileInt]/60; //time interval between depth readings
	
	echo "<p><b>Profile :</b></p>";
	echo "<div id=\"profile\"></div>\n";
	echo "<script type=\"text/javascript\">\n";

	echo "var d1 = [];\n";

	$time=0;$depth=0;
	echo "d1.push([$time, $depth]);\n";
	$time+=$incr;
		  
	foreach ($profileData_str as $i => $str) { //loop through array of strings
		if (($i%2)==0) {
			$depth = -(floatval($str)/1000);
			echo "d1.push([$time, $depth]);\n";
			$time+=$incr;
		} else {
			//discard odd i values
		}	
	}
	//$maxD = (intval($data[Depth]/5)+1)*5;
	$maxD = intval($data[Depth])+1;
	echo "$.jqplot('profile', [d1], {
			series:[{showMarker:false}],
			axesDefaults:{
				tickOptions:{
					textColor: '#FFFFFF'
				}			
			},
			axes:{
				xaxis:{
					min:0,
					max:$data[Divetime]
				},
				yaxis:{
					max:0,
					min:-$maxD
				}
			},
			grid:{
				background: '#616D7E',
				gridLineColor: '#95B9C7'
			}
		});";
	echo "</script>\n";
}

if ($data[Comments]!="")
	echo "<p><b>Comments :</b> $data[Comments]</p>";

?>
