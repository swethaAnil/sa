<?php
//GLOBAL OPTIONS
include '../bin/log_func.php';
include '../bin/dbinfo.inc.php';

//session_start();
//verify_access(1);

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$latest_entry = NULL;

$iadFsp_query = "SELECT iad_name,fsp_info FROM current_orders WHERE instance_id = '$_GET[instance_id]'";
$iadFSP_result = mysql_query($iadFsp_query,$connection);
while($row = mysql_fetch_array($iadFSP_result)){
	$fsp_info = $row['fsp_info'];
	$iad_name = $row['iad_name'];
}

echo "IAD: <b>" . $iad_name . "</b><br>";
echo "<div style=\"margin-top:10px;\">FSP: <b>" . $fsp_info . "</b></div>";
	

?>





