<?php

include '../bin/dbinfo.inc.php';

//$new_early_max = $_POST['early_max'];
//$new_late_max = $_POST['late_max'];
$sd_target = $_POST['sd_target'];
$max_runs = $_POST['max_runs'];

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

//$settings_input = "UPDATE settings SET early_slot_max = $new_early_max, late_slot_max = '$new_late_max', target_std_dev = '$sd_target', max_runs = '$max_runs'";
$settings_input = "UPDATE settings SET target_std_dev = '$sd_target', max_runs = '$max_runs'";
$sql_result = mysql_query($settings_input,$connection) or die(mysql_error);
?>

<html>
<head>
<meta http-equiv="Refresh" content="0;url=schedule_settings.php" />
</head>
<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>
<title>SA Schedule - SETTINGS UPDATE SUBMIT</title>
<body>
<br><br><br>

<?php
if($sql_result == '1'){
	echo "Settings updated.  Re-directing to Current Settings.";
}
?>
</body>
</html> 