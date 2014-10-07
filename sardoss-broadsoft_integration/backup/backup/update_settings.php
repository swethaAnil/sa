<html>

<title>SA Schedule - SETTINGS UPDATE</title>
<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>
<body>

<h2>Service Activations Schedule - Settings Update</h2>

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

echo "<b><u>Current Settings</b></u><br>";
echo "Early slot max = " . $early_slot_max . "<br>";
echo "Late slot max = " . $late_slot_max . "<br>";
echo "Target Standard Deviation = " . $target_SD . "<br>";
echo "Maximum Number of Runs = " . $max_runs . "<br>";

?>
<br><br>


<FORM METHOD="POST" ACTION="submit_settings_update.php">
<b>Enter New Settings</b>
<table border=1>
<tr><td>Early Slot Maximum:</td><td><INPUT TYPE="text" MAXLENGTH="2" NAME="early_max" value="<?php echo $early_slot_max;?>" size="4"></td></tr>
<tr><td>Late Slot Maximum:</td><td><INPUT TYPE="text" MAXLENGTH="2" NAME="late_max" value="<?php echo $late_slot_max;?>" size="4"></td></tr>
<tr><td>Target Standard Deviation:</td><td><INPUT TYPE="text" MAXLENGTH="4" NAME="sd_target" value="<?php echo $target_SD;?>" size="4"></td></tr>
<tr><td>Maximum Number of Runs:</td><td><INPUT TYPE="text" MAXLENGTH="2" NAME="max_runs" value="<?php echo $max_runs;?>" size="4"></td></tr>
</table><br>
<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
</FoRM>
<FORM><INPUT TYPE="BUTTON" VALUE="Cancel" ONCLICK="window.location.href='schedule_settings.php'"></form>






</body>
</html> 