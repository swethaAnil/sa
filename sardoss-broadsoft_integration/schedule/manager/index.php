<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php
require '../bin/dbinfo.inc.php';
require '../bin/log_func.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];

if($_POST){	$statusDate = $_POST['statusDate']; }
?>
<html>


<head>
<title>Manager - Service Activations</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
<script>
$(document).ready(function(){

});

</script>


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
<img style="margin:0px;" src="../images/cbey_logo_small.png">
</div>


<div class="pagetitle">
<a class="pagetitle" href="../../">Service Activations</a>
 / <a class="pagetitle" href="../current_schedule.php">Online Schedule</a>
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
<a href="schedule_settings.php" class="bodylink" style="padding:7px;">SCHEDULE BUILDER</a>
&nbsp;&nbsp;
<a href="add_order.htm" class="bodylink" style="padding:7px;">ADD INDIVIDUAL ORDER</a>
&nbsp;&nbsp;
<a href="../checklist/editor/" class="bodylink" style="padding:7px;">CHECKLIST EDITOR</a>
&nbsp;&nbsp;
<a href="order_database_query.php" class="bodylink" style="padding:7px;">SA DATABASE QUERY</a>

</div>

<div id="statusGraph">
<?php
$today = date("F j, Y");
$time = date("g:i:s A");
if(isset($statusDate)){
	echo "<b>Order status for $statusDate</b>";
}else{
	echo "<b>Order status for $today as of $time</b>";
}

echo "<div style=\"position:relative;left:150px;display:inline;font-size:80%;\">View another day <form action=\"#\" method=\"POST\" style=\"display:inline;\"><input type=\"text\" name=\"statusDate\" value=\"YYYY-MM-DD\"> <button>Go</button></form></div>";
if(isset($statusDate)){
	$distinctTechSQL = "SELECT DISTINCT(tech_id) FROM current_orders WHERE activation_date = '$statusDate' ORDER BY tech_id";
}else{
	$distinctTechSQL = "SELECT DISTINCT(tech_id) FROM current_orders WHERE DATE(activation_date) = DATE(NOW()) ORDER BY tech_id";
}
$distinctTechResult = mysql_query($distinctTechSQL,$connection);
echo "<table width=\"750\" class=\"graph\" >";
echo "<thead><tr><th width=\"175\">SA Tech</th><th width=\"75\"># Orders</th><th width=\"100\">Time Logged</th>";
echo "<th width=\"400\">Percent Complete<div></div></th>";
echo "</tr></thead>";
while($row = mysql_fetch_array($distinctTechResult)){
	
	if($row['tech_id'] != 1000){
		$dataPop .= "<div id=\"data$row[tech_id]\" style=\"display:hide;\">";
		if($statusDate){
			$techDataSQL = "SELECT * FROM current_orders WHERE tech_id = $row[tech_id] AND activation_date = '$statusDate'";
		}else{
			$techDataSQL = "SELECT * FROM current_orders WHERE tech_id = $row[tech_id] AND DATE(activation_date) = DATE(NOW())";
		}
		$techDataResult = mysql_query($techDataSQL,$connection);
		$orderCount = mysql_num_rows($techDataResult);
		$checklistCount = 0;
		$nonPendingCount = 0;
		$checklistCompleteCount = 0;
		$timeLogged = 0;
		$tech_name = get_tech_info($row['tech_id'],$connection);
		
		while($order = mysql_fetch_array($techDataResult)){
			$dataPop .= "Acct $order[acct_num] - $order[acct_name] - Order Num: $order[order_num] - Status: $order[status] - Checklist %: $order[checklist_percentage]<br />";
			$checkForChange = 0;
			if($order['order_type'] == "Change" || $order['order_type'] == "Online Change"){ $checkForChange = 1; }
			if($checkForChange == 0){ $checklistCount++; }
			if($order['status'] == "Complete" || $order['status'] == "Cancelled"){ $nonPendingCount++; }
			if($order['status'] == "Cancelled" && $checkForChange == 0){ $checklistCount--; }
			if($order['checklist_percentage'] == 100 && $order['status'] != "Cancelled"){ $checklistCompleteCount++; }
			

			
		}
		
		if($statusDate){
			$activitySQL = "SELECT SUM(time_spent) FROM order_activity WHERE user = '$tech_name[1]' AND DATE(timedate) = DATE('$statusDate')";
		}else{
			$activitySQL = "SELECT SUM(time_spent) FROM order_activity WHERE user = '$tech_name[1]' AND DATE(timedate) = DATE(NOW())";
		}
		$activityResult = mysql_fetch_row(mysql_query($activitySQL,$connection));
		$timeLogged += $activityResult[0];
		
		$totalWork = $orderCount + $checklistCount;
		$workUnit = 100/$totalWork;
		$workCompleted = $nonPendingCount + $checklistCompleteCount;
		$percentComplete = round($workUnit * $workCompleted);
		
		$graphBar = $percentComplete * 0.9;
		
		echo "<tr class=\"tech_row\"><td>$tech_name[0]</td><td>$orderCount</td><td>$timeLogged</td>";
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


</body>
</html> 
