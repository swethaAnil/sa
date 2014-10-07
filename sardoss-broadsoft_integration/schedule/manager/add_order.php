
<?php

include '../bin/dbinfo.inc.php';


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

$sql_add_order = "INSERT INTO  current_orders (upload_date, activation_date, acct_num, acct_name, order_num, order_type, market, package, timeslot, access_type, tech_id)
VALUES ('$upload_date', '$activation_date', '$acct_num', '$acct_name', '$order_num', '$order_type', '$market', '$package', '$timeslot', '$access', '1000')";
$add_result = mysql_query($sql_add_order,$connection) or die(mysql_error());

if($add_result == 1){
	echo "<br /><br /><br />&nbsp;&nbsp;&nbsp;&nbsp;Order added successfully. <br /><br /><br />";
}else{
	echo "<br /><br /><br />&nbsp;&nbsp;&nbsp;&nbsp;Order add failed. <br /><br /><br />";
}
?>
<html>
<head>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->


</head>

<body>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="add_order.htm" class="bodylink" style="padding:7px;">Add Another Order</a> 
<a href="./" class="bodylink" style="padding:7px;">Back to Manager Menu</a>

</body>

</html> 
