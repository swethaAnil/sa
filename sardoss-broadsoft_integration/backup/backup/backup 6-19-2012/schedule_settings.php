<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<html>

<title>SA Schedule Builder</title>

<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>


<body>

<h2>Service Activations Schedule Builder</h2><hr>



<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$settings_output = "SELECT * FROM settings";
$settings_result = mysql_query($settings_output,$connection);

while($setting = mysql_fetch_array($settings_result)){
	$early_slot_max = $setting['early_slot_max'];
	$late_slot_max = $setting['late_slot_max'];
	$target_SD = $setting['target_std_dev'];
	$max_runs = $setting['max_runs'];
}
echo "<FORM><INPUT TYPE=\"BUTTON\" VALUE=\"Upload New Orders List\" ONCLICK=\"window.location.href='schedule_upload.htm'\"> ";
echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Change Schedule Settings\" ONCLICK=\"window.location.href='update_settings.php'\"> ";
echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Change Personnel Settings\" ONCLICK=\"window.location.href='update_personnel.php'\"> ";
//echo "<INPUT TYPE=\"BUTTON\" VALUE=\"View/Export Schedule\" ONCLICK=\"window.location.href='view_schedule.php'\">";
echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Build Schedule\" ONCLICK=\"window.location.href='build_schedule.php'\">";
echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Schedule Manager\" ONCLICK=\"window.location.href='schedule_manager.php'\"></FORM>";
echo "<br>";
echo "<b><u>Current Schedule Settings</b></u><br>";
echo "Early slot max = " . $early_slot_max . "<br>";
echo "Late slot max = " . $late_slot_max . "<br>";
echo "Target standard deviation = " . $target_SD . "<br>";
echo "Maximum number of runs = " . $max_runs . "<br>";



?>
<br>
<table>

<?php

echo "<tr><th class=\"coverage\">Tech Name</th><th class=\"coverage\">8:30</th><th class=\"coverage\">9:30</th><th class=\"coverage\">10:30</th><th class=\"coverage\">11:30</th><th class=\"coverage\">12:30</th><th class=\"coverage\">13:30</th><th class=\"coverage\">14:30</th><th class=\"coverage\">15:30</th><th class=\"coverage\">16:30</th><th class=\"coverage\">17:30</th><th class=\"coverage\">18:30</th><th class=\"coverage\">19:30</th>";

$sql_output = "SELECT * FROM techs ORDER BY skill_1930, skill_1830, skill_1730, skill_1630, skill_1530, skill_1430, skill_1330, skill_1230, skill_1130, skill_1030, skill_0930, skill_0830";
$sql_result = mysql_query($sql_output,$connection);

$row_count = 0;

while ($row = mysql_fetch_array($sql_result)){

	if($row['status'] == "on"){
		
		if($row['skill_SIP'] > '0'){
			$color = "class=\"sip_tech\"";
		}elseif($row['skill_SIP'] == '0'){
			$color = "class=\"std_tech\"";
		}
		
		if($row_count == '0'){
			$cell_def = "bgcolor=\"#FFFFFF\"";
			$cell_highlight = "bgcolor=\"#888888\"";
			$row_count = 1;
		}elseif($row_count == '1'){
			$cell_def = "bgcolor=\"#E8E8E8\"";
			$cell_highlight = "bgcolor=\"#686868\"";
			$row_count = 0;
		}

		echo "<tr><td " . $color . " " . $cell_def . ">" . $row['first_name'] . " " . $row['last_name'] . "</font></td>";
		

		$slot_marker = 1;
		for($i=6;$i<18;$i++){
			$j = $i+1;
			$k = $i+2;
			if($row[$i] == '1'){
				if($slot_marker == '0'){
					$tech_count[$i] = $tech_count[$i] + $late_slot_max;
				}else{
					$tech_count[$i] = $tech_count[$i] + $early_slot_max;
				}
				$value = "<td width=\"50\" ". $cell_highlight . " align=center></td>";
				if($row[$j] == '1' && $row[$k] == '1'){}else{$slot_marker = 0;}
			}elseif($row[$i] == '0'){
				$value = "<td width=\"50\" " . $cell_def . "></td>";
				$slot_marker = 1;
			}
			echo $value;
		}
		echo "</tr>";
	}
}
$sum = array_sum($tech_count);
echo "<tr><td class=\"totals\">Total (Overall: $sum)</td>";
for($i=6;$i<18;$i++){
	echo "<td align=\"center\" class=\"totals\">$tech_count[$i]</td>";
}
echo "</tr>";

?>
</table><br>
<table class="key"><tr><td class="std_tech">Non-SIP Tech</td></tr><tr><td class="sip_tech">SIP Tech</td></tr></table><br>
<u><b>Techs off the schedule</u></b><br>


<?php

mysql_data_seek($sql_result, 0);

while ($row = mysql_fetch_array($sql_result)){

	if($row['status'] == "off"){
		
		echo $row['first_name'] . " " . $row['last_name'] . "<br>";
	
	}

}





?>

</body>
</html> 