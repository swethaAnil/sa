<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
	
//$truncate_sql = "TRUNCATE TABLE `current_orders`";
//mysql_query($truncate_sql,$connection);

$get_dev_orders_sql = "SELECT * FROM `service_activations`.`orders_dev`";
$get_orders_result = mysql_query($get_dev_orders_sql,$connection) or die(mysql_error());

$count = 0;
while($row = mysql_fetch_array($get_orders_result,MYSQL_ASSOC)){
	
	if($count == 0){
		$delete_orders_sql = "DELETE FROM `service_activations`.`current_orders` WHERE activation_date = '$row[activation_date]'";
		mysql_query($delete_orders_sql, $connection) or die(mysql_error());
		$count = 1;
	}
	$copy_order_sql = "INSERT INTO `service_activations`.`current_orders` 
						VALUES (NULL,'$row[upload_date]','$row[activation_date]','$row[order_num]','$row[acct_num]','$row[acct_name]','$row[order_type]','$row[market]','$row[package]','$row[timeslot]','$row[iad_type]','$row[status]','$row[skill]','$row[tech_id]','$row[access_type]','$row[trouble]','$row[fsp_info]','unspecified',0,1)";
	$sql_result = mysql_query($copy_order_sql, $connection) or die(mysql_error());
	
}

	
?>

<html>
<head>
<meta http-equiv="Refresh" content="3;url=schedule_manager.php" />
</head>
<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>
<title>SA Schedule - Publish Schedule</title>
<body>
<br><br><br>

<?php

if($sql_result == '1'){
	echo "Schedule published to Current Schedule.  Re-directing to Schedule Manager page in 3 seconds.";
}
?>
</body>
</html> 

