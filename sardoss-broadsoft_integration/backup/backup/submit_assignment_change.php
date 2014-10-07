<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$instance = $_POST['instance'];
$new_tech = $_POST['newtech'];
$status = $_POST['status'];

if($_POST['status']){
	$status_update = "UPDATE current_orders SET status = '$status' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
}elseif($_POST['status'] == "Pending"){
	$status_update = "UPDATE current_orders SET status = 'NULL' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
}

if($_POST['newtech']){
	$assign_update = "UPDATE current_orders SET tech_id = '$new_tech' WHERE instance_id = '$instance'";
	mysql_query($assign_update, $connection);
}

?>

<html>
<head>
<meta http-equiv="Refresh" content="0;url=current_schedule.php" />
</head>
</html>