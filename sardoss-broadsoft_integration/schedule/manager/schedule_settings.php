<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<html>

<title>SA Schedule Builder</title>

<head>
     <link rel="stylesheet" href="../style/schedule_style.css" type="text/css">
</head>


<body>

<h2>Service Activations Schedule Builder</h2><hr>



<?php

include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$settings_output = "SELECT * FROM settings";
$settings_result = mysql_query($settings_output,$connection);

while($setting = mysql_fetch_array($settings_result)){
	//$early_slot_max = $setting['early_slot_max'];
	//$late_slot_max = $setting['late_slot_max'];
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
?>

<table width="60%">
<tr><th>Current Schedule Settings</th><th>Current Schedule Considerations</th></tr>
<tr><td align="center" valign="top">
<?php

//echo "Early slot max = " . $early_slot_max . "<br>";
//echo "Late slot max = " . $late_slot_max . "<br>";
echo "Target standard deviation = " . $target_SD . "<br>";
echo "Maximum number of runs = " . $max_runs . "<br>";
?>
</td></tr></table>
<div style="float:right;border:thin solid black;margin-right:50px;padding:10px;">
<u><b>Techs off duty</u></b><br>


<?php

$sql_output = "SELECT * FROM techs WHERE is_deleted = 0 AND status = 'off' AND last_name != 'Team' ORDER BY first_name,last_name";
$sql_result = mysql_query($sql_output,$connection);
mysql_data_seek($sql_result, 0);

while ($row = mysql_fetch_array($sql_result)){

	if($row['status'] == "off"){
		
		echo $row['first_name'] . " " . $row['last_name'] . "<br>";
	
	}

}

?>
</div>

<?php
echo "<br>";
echo "<b><u>1.0 Techs & Coverage</u></b><br>";
echo "<table>";

echo "<tr><th class=\"coverage\">Tech Name</th><th class=\"coverage\">8:30</th><th class=\"coverage\">9:30</th><th class=\"coverage\">10:30</th><th class=\"coverage\">11:30</th><th class=\"coverage\">12:30</th><th class=\"coverage\">13:30</th><th class=\"coverage\">14:30</th><th class=\"coverage\">15:30</th><th class=\"coverage\">16:30</th><th class=\"coverage\">17:30</th><th class=\"coverage\">18:30</th><th class=\"coverage\">19:30</th><th class=\"coverage\">TOTAL</th>";

$sql_output = "SELECT * FROM techs  WHERE is_deleted = 0 AND skill_v2 = 0 ORDER BY skill_1930, skill_1830, skill_1730, skill_1630, skill_1530, skill_1430, skill_1330, skill_1230, skill_1130, skill_1030, skill_0930, skill_0830";
$sql_result = mysql_query($sql_output,$connection);

$row_count = 0;

while ($row = mysql_fetch_array($sql_result)){
	
	$techs_total = 0;
	
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
		

/* 		$slot_marker = 1;
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
		echo "</tr>"; */
		
		for($i=6;$i<18;$i++){

			if($row[$i] > 0){
				if(isset($tech_count[$i])){
					$tech_count[$i] = $tech_count[$i] + $row[$i];	
				}else{
					$tech_count[$i] = $row[$i];
				}
				
				$value = "<td width=\"50\" ". $cell_highlight . " align=center>" . $row[$i] . "</td>";
				$techs_total += $row[$i];
			}elseif($row[$i] == '0'){
				$value = "<td width=\"50\" " . $cell_def . "></td>";
			}
			echo $value;
		}
		echo "<td  class=\"totals\" align=\"center\">$techs_total</td></tr>";
	}
}
$sum = array_sum($tech_count);
echo "<tr><td class=\"totals\"></td>";
for($i=6;$i<18;$i++){
	if(isset($tech_count[$i])){
		$percentage = round(100 * ($tech_count[$i]/$sum),1);	
	}else{
		$percentage = 0;
		$tech_count[$i] = 0;
	}
	
	echo "<td align=\"center\" class=\"totals\">" . $tech_count[$i] . " (" . $percentage . "%)</td>";
}
echo "<td class=\"totals\" align=\"center\">$sum</td></tr>";

?>
</table>
<br><b>KEY:</b> <table class="key"><tr><td class="std_tech">Non-SIP Tech</td><td class="sip_tech">SIP Tech</td></tr></table><br>

<u><b>1.0 Coverage Graph</b></u><br>
<table>
<?php

for($i=6;$i<18;$i++){
	$curr_slot = $i+2 . ":30";
	echo "<tr><td align=\"right\" width=\"40\">$curr_slot</td>";
	for($chart_count=0;$chart_count<$tech_count[$i];$chart_count++){
		echo "<td style=\"color:black;background-color:black;border-color:darkgrey;width:40px;\"> -- </td>";
	}
	echo "</tr>";
}



?>
</table><br><br>


<?php
$tech_count = NULL;
echo "<b><u>2.0 Techs & Coverage</u></b><br>";
echo "<table>";

echo "<tr><th class=\"coverage\">Tech Name</th><th class=\"coverage\">8:30</th><th class=\"coverage\">9:30</th><th class=\"coverage\">10:30</th><th class=\"coverage\">11:30</th><th class=\"coverage\">12:30</th><th class=\"coverage\">13:30</th><th class=\"coverage\">14:30</th><th class=\"coverage\">15:30</th><th class=\"coverage\">16:30</th><th class=\"coverage\">17:30</th><th class=\"coverage\">18:30</th><th class=\"coverage\">19:30</th><th class=\"coverage\">TOTAL</th>";

$v2_sql_output = "SELECT * FROM techs WHERE is_deleted = 0 AND skill_v2 = 1 ORDER BY skill_1930, skill_1830, skill_1730, skill_1630, skill_1530, skill_1430, skill_1330, skill_1230, skill_1130, skill_1030, skill_0930, skill_0830";
$v2_sql_result = mysql_query($v2_sql_output,$connection);

$row_count = 0;

while ($row = mysql_fetch_array($v2_sql_result)){

	$techs_total = 0;

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
		
		for($i=6;$i<18;$i++){

			if($row[$i] > 0){
				$tech_count[$i] = $tech_count[$i] + $row[$i];
				$value = "<td width=\"50\" ". $cell_highlight . " align=center>" . $row[$i] . "</td>";
				$techs_total += $row[$i];
			}elseif($row[$i] == '0'){
				$value = "<td width=\"50\" " . $cell_def . "></td>";
			}
			echo $value;
		}
		echo "<td  class=\"totals\" align=\"center\">$techs_total</td></tr>";
	}
}

if(isset($tech_count)){ $sum = array_sum($tech_count); }

echo "<tr><td class=\"totals\"></td>";
for($i=6;$i<18;$i++){
	if(isset($tech_count[$i])){
		$percentage = round(100 * ($tech_count[$i]/$sum),1);	
	}else{
		$percentage = 0;
		$tech_count[$i] = 0;
	}
	echo "<td align=\"center\" class=\"totals\">" . $tech_count[$i] . " (" . $percentage . "%)</td>";
}
echo "<td class=\"totals\" align=\"center\">$sum</td></tr>";

?>
</table><br>

<u><b>2.0 Coverage Graph</b></u><br>
<table>
<?php

for($i=6;$i<18;$i++){
	$curr_slot = $i+2 . ":30";
	echo "<tr><td align=\"right\" width=\"40\">$curr_slot</td>";
	for($chart_count=0;$chart_count<$tech_count[$i];$chart_count++){
		echo "<td style=\"color:black;background-color:black;border-color:darkgrey;width:40px;\"> -- </td>";
	}
	echo "</tr>";
}



?>
</table>


<br><br>

</body>
</html> 