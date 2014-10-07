<?php

require '../../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());


//ASSIGN VALUES FROM FORM
$activation_date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
$upload_date = date("Y-n-j");
$acct_num = $_POST['acct_num'];
$acct_name = $_POST['acct_name'];
$order_num = $_POST['order_num'];
$order_type = $_POST['order_type'];
$market = $_POST['market'];
$package = $_POST['package'];
$timeslot = $_POST['timeslot'];
$access = $_POST['access'];

$sql_add_order = "INSERT INTO $v2ordertable (upload_date, activation_date, acct_num, acct_name, order_num, order_type, market, package, timeslot, access_type, tech_id)
VALUES ('$upload_date', '$activation_date', '$acct_num', '$acct_name', '$order_num', '$order_type', '$market', '$package', '$timeslot', '$access', '1000')";
$add_result = mysql_query($sql_add_order,$connection);

echo $v2ordertable;
echo $add_result;

?>
<html>
<head>

     <!--  <link rel="stylesheet" href="schedule_style.css" type="text/css">
	<meta http-equiv="Refresh" content="0;url=schedule_manager.php" />  -->
</head>

<body>

</body>

</html> 
