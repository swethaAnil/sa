<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 


<html>


<head>
<title>2.0 Orders - Service Activations</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../../style/tracker_style.css" />
<!--<![endif]-->


<style>

.topfull{
border-width:0px;
border-style:hidden;
font-family:"arial";
font-size:10px;
width:100%;
padding-right:40px;
}

.top{
border-width:0px;
border-style:hidden;
font-family:"arial";
font-size:12px;
padding-right:40px;
font-weight:bold;
}

</style>

</head>

<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:0px;" src="../../images/cbey_logo_small.png">
</div>


<div class="pagetitle">
<a class="pagetitle" href="../../../">Service Activations</a>
 / <a class="pagetitle" href="../../current_schedule.php">Online Schedule</a>
 / <a class="pagetitle" href="../current_v2_orders.php">2.0 Orders</a>
 / 
<span class="location">
<u>Manager</u>
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="current_schedule.php">Today's Schedule</a>
<a style="padding-left:10px;" href="view_schedules.php">View Other Schedules</a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>

<div style="min-width:600px;position:relative;top:75px;margin-left:10px;width:100%;text-align:center;">
<br>
<h2>2.0 Manager Menu</h2>

<a href="v2_order_upload.htm" class="bodylink" style="padding:7px;">UPLOAD NEW 2.0 ORDER LIST</a>
<br><br>
<a href="add_v2_order.htm" class="bodylink" style="padding:7px;">ADD INDIVIDUAL 2.0 ORDER</a>
<br><br>
<a href="export_v2_orders.php" class="bodylink" style="padding:7px;">EXPORT EXCEL SPREADSHEET OF ALL 2.0 ORDERS</a>

<br><br><br>

<?php
/* 
require '../../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

//create techs array
$techs_array = array();
$tech_output = "SELECT * FROM techs";
$tech_result = mysql_query($tech_output,$connection);
while($tech = mysql_fetch_array($tech_result)){
	$id = $tech['tech_id'];
	$techs_array[$id] = $tech['first_name'] . " " . $tech['last_name'];
}
$techs_array['1000'] = "UNASSIGNED";

//declare array for order information by tech
$orders_array = array();

echo "<br><br>";

$SQL = "SELECT v2_tech_id, COUNT(instance_id), COUNT(jira_ticket), COUNT(jira_total_mins), COUNT(truck_roll_mins) FROM $v2ordertable GROUP BY v2_tech_id";
$result = mysql_query($SQL,$connection);
while($row = mysql_fetch_array($result)){
	$tech_id = $row['v2_tech_id'];
	$orders_array[$tech_id]["total_orders"] = $row['COUNT(instance_id)'];
	$orders_array[$tech_id]["jira_tickets"] = $row['COUNT(jira_ticket)'];
	$orders_array[$tech_id]["jira_total_mins"] = $row['COUNT(jira_total_mins)'];
	$orders_array[$tech_id]["truck_roll_mins"] = $row['COUNT(truck_roll_mins)'];
}
 */
/* $SQL = "SELECT tech_id, COUNT(jira_ticket) FROM $v2ordertable GROUP BY tech_id";
$result = mysql_query($SQL,$connection);
while($row = mysql_fetch_array($result)){
	$tech_id = $row['tech_id'];
	$orders_array[$tech_id]["jira_tickets"] = $row['COUNT(jira_ticket)'];
} 
 */
//print_r($orders_array);
echo "<br>";



echo "<table>";

 





?>

</div>
</body>
</html> 