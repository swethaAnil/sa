<?php

include '../bin/dbinfo.inc.php';

$new_early_max = $_POST['early_max'];
$new_late_max = $_POST['late_max'];
$sd_target = $_POST['sd_target'];
$max_runs = $_POST['max_runs'];


//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$techs_output = "SELECT * FROM techs WHERE is_deleted = 0";
$techs_result = mysql_query($techs_output,$connection);

while($tech = mysql_fetch_array($techs_result)){
	
	$id = $tech['tech_id'];
	
	$tech_data = array();
	$tech_data[] = $id . "_status";
	$tech_data[] = $id . "_sip";
	$tech_data[] = $id . "_0830";
	$tech_data[] = $id . "_0930";
	$tech_data[] = $id . "_1030";
	$tech_data[] = $id . "_1130";
	$tech_data[] = $id . "_1230";
	$tech_data[] = $id . "_1330";
	$tech_data[] = $id . "_1430";
	$tech_data[] = $id . "_1530";
	$tech_data[] = $id . "_1630";
	$tech_data[] = $id . "_1730";
	$tech_data[] = $id . "_1830";
	$tech_data[] = $id . "_1930";
	$tech_data[] = $id . "_v2";	
	
	$status = $tech_data[0];
	$sip = $tech_data[1];
	$t8 = $tech_data[2];
	$t9 = $tech_data[3];
	$t10 = $tech_data[4];
	$t11 = $tech_data[5];
	$t12 = $tech_data[6];
	$t13 = $tech_data[7];
	$t14 = $tech_data[8];
	$t15 = $tech_data[9];
	$t16 = $tech_data[10];
	$t17 = $tech_data[11];
	$t18 = $tech_data[12];
	$t19 = $tech_data[13];
	$v2 = $tech_data[14];
	
	if($_POST[$status] == ""){
		$newstatus = "off";
	}elseif($_POST[$status] == "on"){
		$newstatus = "on";
	}
	
	if($_POST[$sip]){$newsip = "1";}else{$newsip = "0";}
	if($_POST[$t8]){$new8 = $_POST[$t8];}else{$new8 = "0";}
	if($_POST[$t9]){$new9 = $_POST[$t9];}else{$new9 = "0";}
	if($_POST[$t10]){$new10 = $_POST[$t10];}else{$new10 = "0";}
	if($_POST[$t11]){$new11 = $_POST[$t11];}else{$new11 = "0";}
	if($_POST[$t12]){$new12 = $_POST[$t12];}else{$new12 = "0";}
	if($_POST[$t13]){$new13 = $_POST[$t13];}else{$new13 = "0";}
	if($_POST[$t14]){$new14 = $_POST[$t14];}else{$new14 = "0";}
	if($_POST[$t15]){$new15 = $_POST[$t15];}else{$new15 = "0";}
	if($_POST[$t16]){$new16 = $_POST[$t16];}else{$new16 = "0";}
	if($_POST[$t17]){$new17 = $_POST[$t17];}else{$new17 = "0";}
	if($_POST[$t18]){$new18 = $_POST[$t18];}else{$new18 = "0";}
	if($_POST[$t19]){$new19 = $_POST[$t19];}else{$new19 = "0";}
	if($_POST[$v2]){$newv2 = $_POST[$v2];}else{$newv2 = "0";}
	
	$personnel_input = "UPDATE techs SET 
		status = '$newstatus',
		skill_SIP = '$newsip',
		skill_0830 = '$new8',
		skill_0930 = '$new9',
		skill_1030 = '$new10',
		skill_1130 = '$new11',
		skill_1230 = '$new12',
		skill_1330 = '$new13',
		skill_1430 = '$new14',
		skill_1530 = '$new15',
		skill_1630 = '$new16',
		skill_1730 = '$new17',
		skill_1830 = '$new18',
		skill_1930 = '$new19',
		skill_v2 = '$newv2'
			WHERE tech_id = '$id'";
	$update_result = mysql_query($personnel_input, $connection) or die(mysql_error());
	
	

}

?>

<html>
<head>

     <link rel="stylesheet" href="schedule_style.css" type="text/css">
	 <meta http-equiv="Refresh" content="0;url=schedule_settings.php" />
</head>
<title>SA Schedule - SETTINGS UPDATE SUBMIT</title>
<body>
<br><br><br>

<?php
if($update_result == '1'){
	echo "Settings updated.  Re-directing to Current Settings.";
}
?>
</body>
</html> 