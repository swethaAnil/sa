<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
	
$truncate_sql = "TRUNCATE TABLE `current_orders`";
mysql_query($truncate_sql,$connection);
$copy_orders_sql = "INSERT INTO `service_activations`.`current_orders` SELECT * FROM `service_activations`.`orders_dev`";
$sql_result = mysql_query($copy_orders_sql, $connection);
	
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
