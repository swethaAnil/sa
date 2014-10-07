

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 


<?php

require '../../bin/dbinfo.inc.php';
require '../../bin/log_func.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];

?>

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


#statusGraph{
position:relative;
top:100px;
margin-left:100px;
}

.graph {
background-color: #C8C8C8;
border: solid 1px black;
border-collapse:collapse;
}

.graph td {
font-family: verdana, arial, sans serif;
border: solid 1px black;
border-collapse:collapse;
font-size:80%;
}


.graph thead th {
font-family: verdana, arial, sans serif;
font-size:70%;
}

.graph tfoot td {
border-top: solid 1px #999999;
font-size: x-small;
text-align: center;
padding: 0.5em;
color: #666666;
}

.bar {
background-color: white;
text-align: right;
border-left: solid 1px black;
padding-right: 0.5em;
width: 400px;
font-size:8pt;
border-right:none;
}


.bar div { 
border-top: solid 2px #0077DD;
background-color: #004080;
border-bottom: solid 2px #002266;
text-align: right;
color: white;
float: left;
padding-top: 0;
height: 1em;
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
</div>
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

?>

<div id="statusGraph">
<?php
$today = date("F n, Y");
$time = date("g:i:s A");
echo "<b>2.0 Order Status</b> (note: checklists are expected on all orders as of 4/15/2013)";
$distinctTechSQL = "SELECT DISTINCT(v2_tech_id) FROM current_v2_orders ORDER BY v2_tech_id";
$distinctTechResult = mysql_query($distinctTechSQL,$connection);
echo "<table width=\"900\" class=\"graph\" >";
echo "<thead><tr><th width=\"175\">SA Tech</th><th width=\"125\"># Orders (New)</th><th width=\"150\"># Pending Orders (New)</th><th width=\"100\">Time Logged</th>";
echo "<th width=\"400\">Percent Checklists Complete<div></div></th>";
echo "</tr></thead>";
while($row = mysql_fetch_array($distinctTechResult)){
	
	if($row['v2_tech_id'] != 1000){
		$dataPop .= "<div id=\"data$row[tech_id]\" style=\"display:hide;\">";
		$techDataSQL = "SELECT * FROM current_v2_orders WHERE v2_tech_id = $row[v2_tech_id]";
		$techDataResult = mysql_query($techDataSQL,$connection);
		$orderCount = mysql_num_rows($techDataResult);
		$checklistCount = 0;
		$nonPendingCount = 0;
		$nonPendingNewCount = 0;
		$checklistCompleteCount = 0;
		$timeLogged = 0;
		$newOrderCount = 0;
		$tech_name = get_tech_info($row['v2_tech_id'],$connection);
		
		while($order = mysql_fetch_array($techDataResult)){
			$dataPop .= "Acct $order[acct_num] - $order[acct_name] - Order Num: $order[order_num] - Status: $order[status] - Checklist %: $order[checklist_percentage]<br />";
			if($order['order_type'] == "New"){ 
				if($order['activation_date'] >= "2013-04-15"){$checklistCount++;}
				$newOrderCount++;
			}
			if($order['status'] != "Pending"){ $nonPendingCount++; }
			if($order['status'] != "Pending" && $order['order_type'] == "New"){ $nonPendingNewCount++; }
			if($order['status'] == "Cancelled"){ $checklistCount--; }
			if($order['checklist_percentage'] == 100 && $order['activation_date'] >= "2013-04-15"){ $checklistCompleteCount++; }
			
			$activitySQL = "SELECT SUM(time_spent) FROM order_activity WHERE order_num = '$order[order_num]' AND user = '$tech_name[1]'";
			$activityResult = mysql_fetch_row(mysql_query($activitySQL,$connection));
			$timeLogged += $activityResult[0];
			
		}
		
		$totalWork = $checklistCount;
		$workUnit = 100/$totalWork;
		$workCompleted = $checklistCompleteCount;
		$percentComplete = round($workUnit * $workCompleted);
		//$percentComplete = round($workCompleted/$totalWork);
		$graphBar = $percentComplete * 0.9;
		//$loggedPerOrder = round($timeLogged/$orderCount,1);
		$pendingCount = $orderCount - $nonPendingCount;
		$pendingNewCount = $newOrderCount - $nonPendingNewCount;
		
		echo "<tr class=\"tech_row\"><td>$tech_name[0]</td><td>$orderCount ($newOrderCount)</td><td>$pendingCount ($pendingNewCount)</td><td>$timeLogged</td>";
		echo "<td class=\"bar\"><div style=\"width:" . $graphBar . "%\"></div>$percentComplete%</td>";
		echo "</tr>
		";
		$dataPop .= "</div>";
	}
	
	
}
echo "<table>";
?>


<br /><br /><br />
<?php



//echo $dataPop;
?>

 







</div>
</body>
</html> 