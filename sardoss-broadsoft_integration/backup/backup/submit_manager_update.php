<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$sql_orders = "SELECT * FROM orders_dev";
$orders_output = mysql_query($sql_orders,$connection);

while($order = mysql_fetch_array($orders_output)){
	$instance = $order['instance_id'];
	$curr_tech = $order['tech_id'];
	$new_tech = $_POST[$instance];
	if($_POST[$instance]){
		$assign_update = "UPDATE orders_dev SET tech_id = '$new_tech' WHERE instance_id = '$instance'";
		mysql_query($assign_update, $connection);
	}
}	
?>

<html>

<head>
<meta http-equiv="Refresh" content="0;url=schedule_manager.php" />
</head>

</html>