<?php

include 'bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$sql_date = date("Y-n-j");	

$truncate_sql = "TRUNCATE TABLE `orders_dev`";
mysql_query($truncate_sql,$connection);
$copy_orders_sql = "INSERT INTO `service_activations`.`orders_dev` SELECT * FROM `service_activations`.`current_orders` WHERE activation_date = '$sql_date'";
$sql_result = mysql_query($copy_orders_sql, $connection);
	
?>

<html>
<head>
<meta http-equiv="Refresh" content="0;url=schedule_manager.php" />
</head>
<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>
<title>SA Schedule - Publish Schedule</title>
<body>
</body>
</html> 
