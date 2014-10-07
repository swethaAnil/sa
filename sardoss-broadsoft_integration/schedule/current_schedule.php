<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

include 'bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];
$useragent = $_SERVER['HTTP_USER_AGENT']; //get the user agent	

if(isset($_GET['lookup_date'])){
	if(strpos($useragent,"MSIE")){ header("Location: ./current_schedule_IE.php?lookup_date=$_GET[lookup_date]"); }
	$sql_date = $_GET['lookup_date'];
	$display_date = date('l, F j Y', strtotime($sql_date));
	$current_location = "current_schedule.php?lookup_date=" . $sql_date;
	$today_menu_item = "<a style=\"padding-left:10px;\" href=\"current_schedule.php\">Today's Schedule</a>";

}else{
	if(strpos($useragent,"MSIE")){ header("Location: ./current_schedule_IE.php"); }
	$sql_date = date("Y-n-j");
	$display_date = date("l, F j Y");
	$current_location = "current_schedule.php";
	$today_menu_item = "";
}

//GET TECH ID FROM USERNAME
$get_tech_id_SQL = "SELECT * FROM techs WHERE username = '$username'";
$get_tech_id_result = mysql_query($get_tech_id_SQL,$connection);

while($row = mysql_fetch_array($get_tech_id_result)){
	$user_id = $row['tech_id'];
}

//GET ORDERS ASSIGNED TO CURRENT USER
$get_users_orders_SQL = "SELECT * FROM current_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' ORDER BY timeslot";
$users_orders_result = mysql_query($get_users_orders_SQL,$connection);
$num_orders_for_user = mysql_num_rows($users_orders_result);

//GET NUMBER OF ANYTIMES AND OLD ORDERS
$pending_orders_result = mysql_query("SELECT * FROM current_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' AND timeslot <> 0 AND (status = 'Pending' OR status IS NULL OR status = '')",$connection);
$num_pending = mysql_num_rows($pending_orders_result);
$anytime_orders_result = mysql_query("SELECT * FROM current_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' AND timeslot = 0 AND (status = 'Pending' OR status IS NULL OR status = '')",$connection);
$num_anytimes = mysql_num_rows($anytime_orders_result);
$done_orders_result = mysql_query("SELECT * FROM current_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' AND (status = 'Cancelled' OR status = 'Complete')",$connection);
$num_done = mysql_num_rows($done_orders_result);

?>

<html>

<title>Online Schedule - Service Activations</title>
<head>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="style/tracker_style.css" />
<!--<![endif]-->


<LINK REL="SHORTCUT ICON" HREF="icons/calendar.ico">
</head>

<style type="text/css">



table, td, th{
border-collapse:collapse;
border-style:solid;
border:1px solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
}

table.orders{
border-collapse:collapse;
border-style:solid;
border:2px solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
margin:10px;
}


span.orders{
border-collapse:collapse;
border-style:solid;
border:2px solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
float:left;
margin-top:5px;
margin-left:10px;
margin-right:10px;
margin-bottom:10px;
padding:5px;
height:225px;
box-shadow: -10px 10px 5px #18181A;
filter:progid:DXImageTransform.Microsoft.Shadow(color=#18181A,direction=230,strength=8); 
background-color:#F2F2F2;
max-width:300px;
}

div.orders{
position:relative;
border-collapse:collapse;
border-style:solid;
border:2px solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
float:left;
margin-top:5px;
margin-left:10px;
margin-right:10px;
margin-bottom:10px;
padding:5px;
height:225px;
box-shadow: -10px 10px 5px #18181A;
filter:progid:DXImageTransform.Microsoft.Shadow(color=#18181A,direction=230,strength=8); 
background-color:#F2F2F2;
width:290px;
}

span.orderscancel{
color: #A9A9A9;
border-collapse:collapse;
border-style:solid;
border:2px solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
float:left;
margin:10px;
padding:5px;
box-shadow: -3px 3px 5px #18181A;
background-color:#B20000;
}

span.orderscomplete{
color: #A9A9A9;
border-collapse:collapse;
border-style:solid;
border:2px solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
float:left;
margin:10px;
padding:5px;
box-shadow: -3px 3px 5px #18181A;
background-color:#000066;
}


span.anytime_table{
border-collapse:collapse;
border-style:solid;
border:1px solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
float:left;
background-color:#F2F2F2;
}



span.orderheader{
font-family:"arial";
font-size:15px;
font-weight:bold;

}

span.boldname{
font-family:"arial";
font-size:11px;
font-weight:bold;
}

span.notes{
font-family:"arial";
font-size:12px;
}

.schedhead th{
white-space:nowrap;
text-align:left;
color:white;
background-color:#4D4D4D;
padding-right:20px;
}

.nowrap td{
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

.nowrap:hover td{
	background-color:gray;
	color:white;
}



.bluetext td{
color:blue;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

.bluetext:hover td{
	background-color:gray;
	color:white;
}

.v2 td{
color:#C71585;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

.v2:hover td{
	background-color:gray;
	color:white;
}


.overflowstd td{
color:yellow;
white-space:nowrap;
text-align:left;
background-color:#8B4513;
}

.overflowstd:hover td{
	background-color:gray;
	color:white;
}

.overflowsip td{
color:#87CEEB;
white-space:nowrap;
text-align:left;
background-color:#8B4513;
}

.overflowsip:hover td{
	background-color:gray;
	color:white;
}

.overflowv2 td{
color:#E38AC2;
white-space:nowrap;
text-align:left;
background-color:#8B4513;
}

.overflowv2:hover td{
	background-color:gray;
	color:white;
}

.teamstd td{
white-space:nowrap;
text-align:left;
background-color:#FFFF00;
}

.teamstd:hover td{
	background-color:gray;
	color:white;
}


.teamsip td{
color:blue;
white-space:nowrap;
text-align:left;
background-color:#FFFF00;
}

.teamsip:hover td{
	background-color:gray;
	color:white;
}

.cancel td{
color: #A9A9A9;
white-space:nowrap;
text-align:left;
background-color:#B20000;
}

.cancel:hover td{
	background-color:gray;
	color:white;
}

.complete td{
color: #A9A9A9;
white-space:nowrap;
text-align:left;
background-color:#000066;
}

.complete:hover td{
	background-color:gray;
	color:white;
}

.mysubmit {
	-moz-box-shadow:inset 6px 0px 10px 2px #ffffff;
	-webkit-box-shadow:inset 6px 0px 10px 2px #ffffff;
	box-shadow:inset 6px 0px 10px 2px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #d1d1d1), color-stop(1, #6b6b6b) );
	background:-moz-linear-gradient( center top, #d1d1d1 5%, #6b6b6b 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#d1d1d1', endColorstr='#6b6b6b');
	background-color:#d1d1d1;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border-radius:4px;
	border:1px solid #5e5e5e;
	display:inline-block;
	color:#000000;
	font-family:Verdana;
	font-size:12px;
	font-weight:bold;
	padding:2px 2px;
	text-decoration:none;
	text-shadow:1px 0px 0px #fcfcfc;
}.mysubmit:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #6b6b6b), color-stop(1, #d1d1d1) );
	background:-moz-linear-gradient( center top, #6b6b6b 5%, #d1d1d1 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#6b6b6b', endColorstr='#d1d1d1');
	background-color:#6b6b6b;
}.mysubmit:active {
	color:white;
	}

form { 
display: inline; 
}


td.nowrap{
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

td.bluetext {
color:blue;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

td.v2 {
color:#C71585;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}


td.overflowstd{
color:yellow;
white-space:nowrap;
text-align:left;
background-color:#8B4513;
}

td.teamstd{
white-space:nowrap;
text-align:left;
background-color:#FFFF00;
}

td.cancel{
color: #A9A9A9;
white-space:nowrap;
text-align:left;
background-color:#B20000;
}

td.complete{
color: #A9A9A9;
white-space:nowrap;
text-align:left;
background-color:#000066;
}

.border td{
white-space:nowrap;
text-align:left;
background-color:#4D4D4D;
}

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
font-size:10px;
padding-right:40px;
}


.graph {
background-color: #4D4D4D;
border: solid 1px black;
border-collapse:collapse;

}


.graph td {
font-family: verdana, arial, sans serif;
border: solid 1px black;
border-collapse:collapse;
background-color: #F2F2F2;
font-size:110%;

}


.graph thead th {

white-space:nowrap;
text-align:left;
color:white;
background-color:#4D4D4D;
padding-right:20px;

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
font-weight:bold;
}


.bar div { 
border-top: solid 2px #000067;
background-color: #000066;
border-bottom: solid 2px #002266;
text-align: right;
color: white;
float: left;
padding-top: 0;
height: 1em;
}

</style>


<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname, event)
{
	if (event.shiftKey == 1){
		return false;
	}
	
	if (! window.focus)return true;
	
	var href;

	if (typeof(mylink) == 'string')
		href=mylink;
	else
		href=mylink.href;
	
	myWindow = window.open(href, windowname, 'width=1150,height=800,scrollbars=yes,status=no,toolbar=no,location=no');
	myWindow.focus();
	return false;
	
}
//-->

</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
<script>
$(document).ready(function(){
	
<?php while($row = mysql_fetch_array($pending_orders_result)): ?>
	
	$("#latestNote<?= $row['instance_id'] ?>").load("./dashboard/scheduleLogDisplay.php?order_num=<?= $row['order_num'] ?>");

	$("#update_log<?= $row['instance_id'] ?>").click(function(){
		var formData = $("#logForm<?= $row['instance_id'] ?>").serialize()
		$.get('./dashboard/orderDashboardSubmit.php',formData,function(data,status){});
		$("#logEntry<?= $row['instance_id'] ?>").val("");
		$("#timeSpent<?= $row['instance_id'] ?>").val("");
		$("#latestNote<?= $row['instance_id'] ?>").load("./dashboard/scheduleLogDisplay.php?order_num=<?= $row['order_num'] ?>");
	});

	
 	$("#timeSpent<?= $row['instance_id'] ?>").keypress(function(e) {
		if(e.which == 13) {
			var formData = $("#logForm<?= $row['instance_id'] ?>").serialize()
			$.get('./dashboard/orderDashboardSubmit.php',formData,function(data,status){});
			$("#logEntry<?= $row['instance_id'] ?>").val("");
			$("#timeSpent<?= $row['instance_id'] ?>").val("");
			$("#latestNote<?= $row['instance_id'] ?>").load("./dashboard/scheduleLogDisplay.php?order_num=<?= $row['order_num'] ?>");
		}
	});

<?php endwhile ?>



});
</script>

<script type="text/JavaScript">
<!--
function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
}
//   -->
</script>

<body onload="JavaScript:timedRefresh(900000);">

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:0px;" src="images/cbey_logo_small.png">
</div>

<div class="pagetitle"><a class="pagetitle" href="../">Service Activations</a>
 / 
<span class="location">
Online Schedule</span><span class="schedule_date"> - <?php echo $display_date; ?></span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="./search/">Order Search</a>
<?php echo $today_menu_item;  ?>
<a style="padding-left:10px;" href="view_schedules.htm">View Other Schedules</a>
<a style="padding-left:10px;" href="./manager/">Schedule Manager</a>
<a style="padding-left:10px;" href="./v2/current_v2_orders.php">2.0 Orders</a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>

<div class="page_body">

<table class="topfull">
<tr><td align="right" class="top">
<?php
$reloadtime = date("g:i A",strtotime('+15 minutes'));
echo "Next auto-reload at $reloadtime"; 
?>
</tr><tr>
<td align="right" class="top">
<table><tr class="nowrap">
<td class="nowrap">Pending</td>
<td class="bluetext">SIP</td>
<td class="v2">2.0</td>
<td class="complete">Complete</td>
<td class="cancel">Cancelled</td>
</tr></table>
</td></tr></table>

<?php

//BUILD TABLE FOR CURRENT USER'S ORDERS
if($num_orders_for_user >= 1){
	
	$checklistCount = 0;
	$checklistCompleteCount = 0;
	$timeLogged = 0;
	$count = 1;
	$anytime_count = 0;
	$done_count = 0;
	$tech_output = "SELECT * FROM techs WHERE tech_id = '$user_id'";
	$tech_result = mysql_query($tech_output,$connection);
	while($tech = mysql_fetch_array($tech_result)){
		$user_title = $tech['first_name'] . " " . $tech['last_name'];
		$username = $tech['username'];
	}

	echo "<div style=\"clear:both;margin-top:-15px;font-weight:bold;\">";
	if($num_pending > 0){
		echo "Pending Time Slot Orders - " . $user_title;
	}
	echo "<br></div>";

	while($row = mysql_fetch_array($users_orders_result)){
		
		$instance_id = $row['instance_id'];
		$order_num = $row['order_num'];
		$acct = $row['acct_num'];
		$fsp_info = $row['fsp_info'];
		
		$checkforsip = substr($row['package'],0,3);
		if($row['customer_type'] == "2"){
			$trclass = "class=\"v2\"";
		}elseif($checkforsip == "sip"){
			$trclass = "class=\"bluetext\"";
		}else{
			$trclass = "class=\"nowrap\"";
		}
		
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		
		if($row['timeslot'] == 0){
			$newtimeslot = "Anytime";
		}else{
			$newtimeslot = date("g:i A",$time);
		}
		
		if($row['status'] == "Cancelled"){
			$trclass = "class=\"cancel\"";
		}
		
		if($row['status'] == "Complete" || $row['status'] == "Activation Complete"){
			$trclass = "class=\"complete\"";
		}
		
		
		if($row['status'] == "Cancelled" || $row['status'] == "Complete"){
		
		$done_count++;
	
		if($done_count == 1){
			$done_table = "<div style=\"clear:both;padding-top:15px;font-weight:bold;\">Order History - " . $user_title . "</div><span class=\"anytime_table\"><table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>Checklist %</th>";
			$done_table = $done_table . "</tr>";
		}
		
		$done_table = $done_table .  "<tr " . $trclass . " style=\"height:20px;\" ondblClick=\"return popup('dashboard/order_dashboard.php?order_num=$order_num', $instance_id, event)\"><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
		$checkForChange = 0;
		if($row['order_type'] == "Change" || $row['order_type'] == "Online Change"){ $checkForChange = 1; }
		if($row['status'] == "Complete" && $checkForChange == 0 && $row['checklist_percentage'] < 100){
			$done_table = $done_table . "<td style=\"color:red;\">" . $row['checklist_percentage'] . "</td>";
		}elseif($row['status'] == "Complete" && $checkForChange == 0){
			$done_table = $done_table . "<td>" . $row['checklist_percentage'] . "</td>";
		}else{
			$done_table = $done_table . "<td>N/A</td>";
		}

		$done_table = $done_table .  "</tr>";
		
		if($done_count == $num_done){
			$done_table = $done_table . "</table></span>";
		}
		 
		
		
		}elseif($row['timeslot'] == 0){
			$anytime_count++;
				
			if($anytime_count == 1){

				$anytime_table = "<div style=\"clear:both;padding-top:15px;font-weight:bold;\">Pending Anytime Orders - " . $user_title . "</div><span class=\"anytime_table\"><table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th>";
				$anytime_table = $anytime_table . "</tr>";
			}
			
			$anytime_table = $anytime_table .  "<tr " . $trclass . " style=\"height:20px;\" ondblClick=\"return popup('dashboard/order_dashboard.php?order_num=$order_num', $instance_id , event)\"><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
			$anytime_table = $anytime_table .  "</tr>";
			  
			if($anytime_count == $num_anytimes){
				$anytime_table = $anytime_table . "</table></span>";
			} 
			 
					
		}else{
		
			
				
			echo "<div class=\"orders\">";
			echo "<span class=\"orderheader\">Account " . $row['acct_num'] . " - $newtimeslot</span>";
			echo "<a class=\"button\" href=\"./dashboard/order_dashboard.php?order_num=$order_num\" onClick=\"return popup(this, $instance_id, event)\" style=\"float:right;\">Dashboard</a>";
			echo "<a class=\"button\" href=\"./bin/get_broadsoft.php?account=" . $row['acct_num'] . "\" onClick=\"return popup(this, 'BWAS" . $instance_id . "', event)\" style=\"float:right;margin-right:2px;\">BWAS</a><hr style=\"clear:both;\">";

			echo "<span class=\"boldname\">" . $row['acct_name'] . "</span><br>";
			echo "<span class=\"boldname\">Order Num: </span>" . $row['order_num'] . "<br>";
			echo "<span class=\"boldname\">Order Type: </span>" . $row['order_type'] . "<br>";
			if($row['status'] != "Cancelled" && $row['status'] != "Complete"){
			
				echo "<span class=\"boldname\">Access: </span>" . $row['access_type'] . "<br>";
				echo "<span class=\"boldname\">Market: </span>" . $row['market'] . "<br>";
				echo "<span class=\"boldname\">Package: </span>" . $row['package'] . "<br>";
				echo "<span class=\"boldname\">Checklist Status: </span>" . $row['checklist_percentage'] . "% Complete<br>";			
				
				echo "<span class=\"boldname\">IAD Name: </span>" . $row['iad_name'] . "<br>";
				echo "<span class=\"boldname\">FSP Info: </span>" . $row['fsp_info'] . "<br>"; 
				
				echo "<hr><span class=\"boldname\">Latest Note: </span><span class=\"notes\"><div style=\"display:inline\" id=\"latestNote$instance_id\"></div></span><br>";
							
				echo "<span style=\"position:absolute;bottom:4px;right:4px;\"><form name=\"log_update\" id=\"logForm$instance_id\" method=\"post\" style=\"display:inline;margin-top:3px;text-align:right;\">";
				echo "<input type=\"hidden\" name=\"order_num\" value=$order_num>";
				echo "<input type=\"text\" class=\"text\" name=\"entry\" id=\"logEntry$instance_id\" size=\"27\" ><input type=\"text\" class=\"text\" name=\"time_spent\" id=\"timeSpent$instance_id\" size=\"3\" > 
					</form><button NAME=\"update_log\" id=\"update_log$instance_id\" class=\"button\" VALUE=\"Add Note\">Add Note</button></span>";
				
			}else{
				echo "<hr><div align=\"center\"><p style=\"font-family:arial;font-size:11px;font-weight:bold;display:inline;\">Order " . $row['status'] . "</p></div>";
			}
		
			echo "</div>";	
		}
				
		$checkForChange = 0;
		if($row['order_type'] == "Change" || $row['order_type'] == "Online Change"){ $checkForChange = 1; }
		if($checkForChange == 0){ $checklistCount++; }
		if($row['status'] != "Pending"){ $nonPendingCount++; }
		if($row['status'] == "Cancelled" && $checkForChange == 0){ $checklistCount--; }
		if($row['checklist_percentage'] == 100 && $row['status'] != "Cancelled"){ $checklistCompleteCount++; }

			
		$count++;
		
	}
	
	$activitySQL = "SELECT SUM(time_spent) FROM order_activity WHERE user = '$username' AND DATE(timedate) = DATE('$sql_date')";
	$activityResult = mysql_fetch_row(mysql_query($activitySQL,$connection));
	$timeLogged += $activityResult[0];
			
	$totalWork = $num_orders_for_user + $checklistCount;
	$workUnit = 100/$totalWork;
	$workCompleted = $done_count + $checklistCompleteCount;
	$percentComplete = round($workUnit * $workCompleted);
	$graphBar = $percentComplete * 0.9;
	
	echo $anytime_table;
	echo $done_table;	
	
	echo "<div style=\"clear:both;padding-top:15px;\"><table width=\"240\" class=\"graph\" >";
	echo "<thead><tr><th width=\"60\">Orders Closed</th><th width=\"60\">Checklists Completed</th><th width=\"60\">Time Logged</th>";
	echo "<th width=\"60\">Percent Complete<div></div></th>";
	echo "</tr></thead>";
	echo "<tr><td><b>$done_count</b> of <b>$num_orders_for_user</b></td><td><b>$checklistCompleteCount</b> of <b>$checklistCount</b></td><td><b>$timeLogged</b> mins</td>";
	echo "<td ><b>$percentComplete%</b></td>";
	echo "</tr></table></div>
	";
		
}
?>


<?php 
if($num_orders_for_user > 0){
	echo "<div style=\"clear:both;font-weight:bold;padding-top:20px;\">All Other Orders</div>";
}
?>
<div id="all_orders" style="float:left;">


<?php

//GET ORDERS ASSIGNED TO ALL OTHER USERS
if($user_id){
	$schedule_output = "SELECT * FROM current_orders WHERE tech_id <> $user_id AND activation_date = '$sql_date' ORDER BY tech_id, timeslot";
	$schedule_result = mysql_query($schedule_output,$connection);
}else{
	$schedule_output = "SELECT * FROM current_orders WHERE activation_date = '$sql_date' ORDER BY tech_id, timeslot";
	$schedule_result = mysql_query($schedule_output,$connection);
}


//BUILD TABLE FOR ALL OTHER USERS' ORDERS
echo "<table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th></tr>";
$count = 1;
$id = 0;
$overdue = 0;
while($row = mysql_fetch_array($schedule_result)){
	if($id != $row['tech_id'] && $count != 1){
		echo "<tr class=\"border\"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	}
	$id = $row['tech_id'];
	$instance_id = $row['instance_id'];
	$order_num = $row['order_num'];
	$acct = $row['acct_num'];
	if($id != "1000"){
		$tech_output = "SELECT * FROM techs WHERE tech_id = '$id'";
		$tech_result = mysql_query($tech_output,$connection);
		while($tech = mysql_fetch_array($tech_result)){
			$tech_name = $tech['first_name'] . " " . $tech['last_name'] . " - " . $tech['extension'];
		}
	}else{
		$tech_name = "Overflow";
		
	}
	
	$checkforsip = substr($row['package'],0,3);
	if($row['customer_type'] == "2"){
		$trclass = "class=\"v2\"";
	}elseif($checkforsip == "sip"){
		$trclass = "class=\"bluetext\"";
	}else{
		$trclass = "class=\"nowrap\"";
	}
	
	if($row['timeslot'] && $id != "1000" && $id != ""){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
	}elseif($row['timeslot'] && $id == "1000" && $checkforsip != "sip"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowstd\"";
		if(strtotime($newtimeslot) < strtotime(date("g:i A")) && !isset($_GET['lookup_date'])){
			$overdue++;
		}
	}elseif($row['timeslot'] && $id == "1000" && $row['customer_type'] == "2"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowv2\"";
		if(strtotime($newtimeslot) < strtotime(date("g:i A")) && is_null($_GET['lookup_date'])){
			$overdue++;
		}
	}elseif($row['timeslot'] && $id == "1000" && $checkforsip == "sip"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowsip\"";
		if(strtotime($newtimeslot) < strtotime(date("g:i A")) && is_null($_GET['lookup_date'])){
			$overdue++;
		}
	}elseif($checkforsip != "sip" && $tech_name == "Overflow"){
		$newtimeslot = "Anytime";
		$tech_name = "TEAM";
		$trclass = "class=\"teamstd\"";
	}elseif($checkforsip == "sip" && $tech_name == "Overflow"){
		$newtimeslot = "Anytime";
		$tech_name = "TEAM";
		$trclass = "class=\"teamsip\"";
	}elseif($row['timeslot'] == 0){
		$newtimeslot = "Anytime";
	}
	
	if($row['status'] == "Cancelled"){
		$trclass = "class=\"cancel\"";
	}
	
	if($row['status'] == "Complete" || $row['status'] == "Activation Complete"){
		$trclass = "class=\"complete\"";
	}
		

	if($username != "guest"){
		echo "<tr " . $trclass . " style=\"height:20px;\" ondblClick=\"return popup('dashboard/order_dashboard.php?order_num=$order_num', $instance_id, event)\"><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
		//echo "<td><form action=\"dashboard/order_dashboard.php\" method=\"get\"><input type=\"hidden\" name=\"instance_id\" value=\"$instance_id\"><button name =\"submit\" class=\"mysubmit\" value=\"...\">...</button></form></td></tr>";
	}else{
		echo "<tr " . $trclass . " style=\"height:20px;\" ><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
		//echo "<td></td></tr>";
	}
	echo "<td>" . $tech_name . "</td>";
	$count++;
	}

echo "</table>";


?> 
</div>
</div>
<?php
if(isset($overdue) && $overdue > 0){
	echo "<div style=\"position:absolute;top:80px;left:300px;color:red;\"><b>ALERT<b>: $overdue OVERFLOW ORDER(S) OVERDUE FOR ASSIGNMENT!</div>";

}

?>
</body>
</html>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39762922-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>



  

