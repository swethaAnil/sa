
<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());


//ASSIGN VALUES FROM FORM
$acct_num = $_POST['acct_num'];
$acct_name = $_POST['acct_name'];
$order_num = $_POST['order_num'];
$order_type = $_POST['order_type'];
$market = $_POST['market'];
$package = $_POST['package'];
$timeslot = $_POST['timeslot'];
$access = $_POST['access'];

$sql_add_order = "INSERT INTO  current_orders (acct_num, acct_name, order_num, order_type, market, package, timeslot, access_type, tech_id)
VALUES ('$acct_num', '$acct_name', '$order_num', '$order_type', '$market', '$package', '$timeslot', '$access', '1000')";
$add_result = mysql_query($sql_add_order,$connection);

echo "$add_result";

?>
<html>
<head>

     <link rel="stylesheet" href="schedule_style.css" type="text/css">
	 <meta http-equiv="Refresh" content="0;url=schedule_manager.php" />
</head>

</html> 
