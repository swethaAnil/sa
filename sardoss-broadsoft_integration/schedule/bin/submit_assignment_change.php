<?php

include 'bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$instance = $_POST['instance_id'];

if($_POST['status']){
	$status = $_POST['status'];
	$status_update = "UPDATE current_orders SET status = '$status' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
}elseif($_POST['status'] == "Pending"){
	$status_update = "UPDATE current_orders SET status = 'NULL' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
}

if($_POST['update']){
	if($_POST['newtech']){
		$new_tech = $_POST['newtech'];
		$assign_update = "UPDATE current_orders SET tech_id = '$new_tech' WHERE instance_id = '$instance'";
		mysql_query($assign_update, $connection);
	}

	if($_POST['newtime']){
		$new_time = $_POST['newtime'];
		if($new_time == "ANYTIME"){ $new_time = NULL; }
		$time_update = "UPDATE current_orders SET timeslot = '$new_time' WHERE instance_id = '$instance'";
		mysql_query($time_update, $connection);
	}
}
?>

<html>
<head>
<?php echo "<meta http-equiv=\"REFRESH\" content=\"0;url=order_dashboard.php?instance_id=$instance\">";  ?>
</head>
</html>