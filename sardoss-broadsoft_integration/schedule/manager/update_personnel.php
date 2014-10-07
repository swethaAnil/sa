<html>

<title>SA Schedule - Personnel Update</title>
<head>
     <link rel="stylesheet" href="../style/schedule_style.css" type="text/css">
</head>
<body>

<h2>Service Activations Schedule - Personnel Update</h2>

<?php

include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$techs_output = "SELECT * FROM techs WHERE is_deleted = 0 ORDER BY first_name";
$techs_result = mysql_query($techs_output,$connection);

echo "<FORM METHOD=\"POST\" ACTION=\"submit_personnel_update.php\">";
echo "<table><tr><th class=\"coverage\">Tech Name</th><th class=\"personnel\">On-Duty</th><th class=\"personnel\">SIP</th><th class=\"personnel\">2.0</th><th class=\"personnel\">8:30</th><th class=\"personnel\">9:30</th><th class=\"personnel\">10:30</th><th class=\"personnel\">11:30</th><th class=\"personnel\">12:30</th><th class=\"personnel\">13:30</th><th class=\"personnel\">14:30</th><th class=\"personnel\">15:30</th><th class=\"personnel\">16:30</th><th class=\"personnel\">17:30</th><th class=\"personnel\">18:30</th><th class=\"personnel\">19:30</th></tr>";
$row_count = 0;
while($tech = mysql_fetch_array($techs_result)){
	$tech_data = array();
	$tech_data[] = $tech['tech_id'] . "_status";
	$tech_data[] = $tech['tech_id'] . "_sip";
	$tech_data[] = $tech['tech_id'] . "_0830";
	$tech_data[] = $tech['tech_id'] . "_0930";
	$tech_data[] = $tech['tech_id'] . "_1030";
	$tech_data[] = $tech['tech_id'] . "_1130";
	$tech_data[] = $tech['tech_id'] . "_1230";
	$tech_data[] = $tech['tech_id'] . "_1330";
	$tech_data[] = $tech['tech_id'] . "_1430";
	$tech_data[] = $tech['tech_id'] . "_1530";
	$tech_data[] = $tech['tech_id'] . "_1630";
	$tech_data[] = $tech['tech_id'] . "_1730";
	$tech_data[] = $tech['tech_id'] . "_1830";
	$tech_data[] = $tech['tech_id'] . "_1930";
	$tech_data[] = $tech['tech_id'] . "_v2";
	
		if($row_count == '0'){
			$cell_def = "class=\"personnel1\"";
			$row_count = 1;
		}elseif($row_count == '1'){
			$cell_def = "class=\"personnel2\"";
			$row_count = 0;
		}

	echo "<tr><td $cell_def>" . $tech['first_name'] . " " . $tech['last_name'] . "</td>";
	
	echo "<td $cell_def align=\"center\"><input type=\"checkbox\" name=$tech_data[0]";
	if($tech['status'] == 'on'){echo " checked";}
	echo "></td>";

	echo "<td $cell_def align=\"center\"><input type=\"checkbox\" value=\"1\" name=$tech_data[1]";
	if($tech['skill_SIP'] >= '1'){echo " checked";}
	echo "></td>";
	
	echo "<td $cell_def align=\"center\"><input type=\"checkbox\" value=\"1\" name=$tech_data[14]";
	if($tech['skill_v2'] >= '1'){echo " checked";}
	echo "></td>";

	$counter = 2;
	
	for($i=6;$i<=17;$i++){
		//echo "<td $cell_def align=\"center\"><input type=\"checkbox\" value=\"1\" name=$tech_data[$counter]";
		//if($tech[$i] == '1'){echo " checked";}
		//echo "></td>";
		echo "<td $cell_def align=\"center\"><input type=\"text\" value=$tech[$i] name=$tech_data[$counter] size=\"1\"></td>";
		$counter++;
	}
	
	
}
echo "</table><br>";
?>
<INPUT TYPE="submit" NAME="submit" VALUE="Submit Update">
</FoRM>
<FORM><INPUT TYPE="BUTTON" VALUE="Cancel" ONCLICK="window.location.href='schedule_settings.php'"></form>

</body>
</html> 