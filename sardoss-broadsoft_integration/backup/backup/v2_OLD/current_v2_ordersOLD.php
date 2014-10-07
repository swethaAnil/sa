<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];
	


if($_GET['lookup_date']){
	$sql_date = $_GET['lookup_date'];
	$display_date = date('l M j Y', strtotime($sql_date));
	//$current_location = "current_schedule.php?lookup_date=" . $sql_date;
}else{
	$sql_date = date("Y-n-j");
	$display_date = date("l M j Y");
//	$current_location = "current_schedule.php";
}




?>

<html>

<head>
<title>2.0 Orders - Service Activations</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->


<LINK REL="SHORTCUT ICON" HREF="icons/calendar.ico">


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
background-color:#F2F2F2;
max-width:300px;
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

.schedhead tr,th{

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

.nowrap_alt td{
white-space:nowrap;
text-align:left;
background-color:#C3C3C3;
}

.nowrap:hover td{
	background-color:gray;
	color:white;
}

.nowrap_alt:hover td{
	background-color:gray;
	color:white;
}

.bluetext td{
color:blue;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

.overflowstd td{
color:red;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

.overflowstd_alt td{
color:red;
white-space:nowrap;
text-align:left;
background-color:#D8D8D8;
}

.overflowstd:hover td{
	background-color:gray;
	color:white;
}

.overflowstd_alt:hover td{
	background-color:gray;
	color:white;
}

.overflowsip td{
color:#87CEEB;
white-space:nowrap;
text-align:left;
background-color:#8B4513;
}

.teamstd td{
white-space:nowrap;
text-align:left;
background-color:#FFFF00;
}

.teamsip td{
color:blue;
white-space:nowrap;
text-align:left;
background-color:#FFFF00;
}

.cancel td{
color: #A9A9A9;
white-space:nowrap;
text-align:left;
background-color:#B20000;
}

.complete td{
color:gray;
white-space:nowrap;
text-align:left;
background-color:#000099;
}

.complete_alt td{
color:gray;
white-space:nowrap;
text-align:left;
background-color:#000066;
}

.complete:hover td{
	background-color:gray;
	color:white;
}

.complete_alt:hover td{
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

td.overflowstd{
color:red;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
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
color: gray;
white-space:nowrap;
text-align:left;
background-color:#000099;
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
font-size:12px;
padding-right:40px;
font-weight:bold;
}

#jiralink:link {
	color:blue;
	text-decoration:none;
}

#jiralink:visited{
	color:blue;
	text-decoration:none;
}

#jiralink:hover{
	color:red;
	text-decoration:none;		
}


</style>

<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
</script>
<script> 
$(document).ready(function(){
  $("#min").click(function(){
		$.next("#orderbox").slideUp();
	});
  $("#max").click(function(){
		$.next("#orderbox").slideDown();
	});
});
</script> 

-->

<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;

var href;

if (typeof(mylink) == 'string')
	href=mylink;
else
	href=mylink.href;
	myWindow = window.open(href, windowname, 'width=1030,height=800,scrollbars=yes,status=no,toolbar=no,location=no');
	myWindow.focus();
	return false;
}
//-->

</script>


</head>
<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:0px;" src="../images/cbey_logo_small.png">
</div>

<div class="pagetitle"><a class="pagetitle" href="../../">Service Activations</a>
 / <a class="pagetitle" href="../current_schedule.php">Online Schedule</a>
 / 
<span class="location">
2.0 Orders
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="../search/">Order Search</a>
<a style="padding-left:10px;" href="../">Today's Schedule</a>
<a style="padding-left:10px;" href="view_schedules.php">View Other Schedules</a>
<a style="padding-left:10px;" href="./v2manager/">2.0 Order Manager</a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>

<div style="min-width:600px;position:relative;top:75px;margin-left:10px;width:100%;">

<table class="topfull">
<tr><td align="left" class="top">
<table><tr class="nowrap">
<td class="overflowstd">Unassigned</td>
<td class="nowrap">Pending</td>
<td class="complete">Complete</td>
</tr></table><td align="left" class="top" >
Last Refresh: <?php echo date("g:i A"); ?>
</td></tr>
<tr><td class="top"> </td></tr>
<tr><td class="top"> </td></tr>
</table>

<?php
/*
//GET TECH ID FROM USERNAME
$get_tech_id_SQL = "SELECT * FROM techs WHERE username = '$username'";
$get_tech_id_result = mysql_query($get_tech_id_SQL,$connection);

while($row = mysql_fetch_array($get_tech_id_result)){
	$user_id = $row['tech_id'];
}

//GET ORDERS ASSIGNED TO CURRENT USER
$get_users_orders_SQL = "SELECT * FROM current_v2_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' ORDER BY timeslot";
$users_orders_result = mysql_query($get_users_orders_SQL,$connection);
$num_orders_for_user = mysql_num_rows($users_orders_result);

//GET NUMBER OF ANYTIMES AND OLD ORDERS
$pending_orders_result = mysql_query("SELECT * FROM current_v2_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' AND timeslot <> 0 AND (status = 'Pending' OR status IS NULL)",$connection);
$num_pending = mysql_num_rows($pending_orders_result);
$anytime_orders_result = mysql_query("SELECT * FROM current_v2_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' AND timeslot = 0 AND (status = 'Pending' OR status IS NULL)",$connection);
$num_anytimes = mysql_num_rows($anytime_orders_result);
$done_orders_result = mysql_query("SELECT * FROM current_v2_orders WHERE tech_id = $user_id AND activation_date = '$sql_date' AND (status = 'Cancelled' OR status = 'Complete')",$connection);
$num_done = mysql_num_rows($done_orders_result);

//BUILD TABLE FOR CURRENT USER'S ORDERS
if($num_orders_for_user >= 1){

$count = 1;
$anytime_count = 0;
$done_count = 0;
$tech_output = "SELECT * FROM techs WHERE tech_id = '$user_id'";
$tech_result = mysql_query($tech_output,$connection);
while($tech = mysql_fetch_array($tech_result)){
	$user_title = $tech['first_name'] . " " . $tech['last_name'];
}

echo "<div style=\"clear:both;margin-top:-15px;font-weight:bold;\">";
if($num_pending > 0){
	echo "Pending Time Slot Orders - " . $user_title;
}
echo "<br></div>";

while($row = mysql_fetch_array($users_orders_result)){
	
	$instance_id = $row['instance_id'];
	$acct = $row['acct_num'];
	
	$checkforsip = substr($row['package'],0,3);
	if($checkforsip == "sip"){
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
	
	$order_span_class = "orders";
	
	if($row['status'] == "Cancelled"){
		$trclass = "class=\"cancel\"";
		$order_span_class = "orderscancel";
	}
	
	if($row['status'] == "Complete" || $row['status'] == "Activation Complete"){
		$trclass = "class=\"complete\"";
		$order_span_class = "orderscomplete";
	}
	
	$fsp_info = NULL;
	$fsp_info_query = "SELECT fsp_info FROM current_v2_orders WHERE instance_id = $instance_id";
	$fsp_info_result = mysql_query($fsp_info_query,$connection);
	while($fsp_result_row = mysql_fetch_array($fsp_info_result)){
		$fsp_info = $fsp_result_row['fsp_info'];
	}
	
	$latest_entry = NULL;
	$latest_entry_query = "SELECT * FROM $logtable WHERE instance_id = $instance_id ORDER BY timedate DESC LIMIT 1";
	$latest_entry_result = mysql_query($latest_entry_query,$connection);
	while($latest_entry_row = mysql_fetch_array($latest_entry_result)){
		$latest_entry = $latest_entry_row['entries'];
	}
	if($latest_entry == NULL){
		$latest_entry = "no notes yet";
	}
	
	
	if($row['status'] == "Cancelled" || $row['status'] == "Complete"){
	
		$done_count++;
	
		if($done_count == 1){
			$done_table = "<div style=\"clear:both;padding-top:15px;font-weight:bold;\">Order History - " . $user_title . "</div><span class=\"anytime_table\"><table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th>";
			$done_table = $done_table . "<th></th></tr>";
		}
		
		$done_table = $done_table .  "<tr " . $trclass . "><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
		$done_table = $done_table .  "<td><form action=\"order_dashboard.php\" method=\"get\"><input type=\"hidden\" name=\"instance_id\" value=\"$instance_id\"><button name =\"submit\" class=\"mysubmit\" value=\"...\">...</button></form></td></tr>";
		
		if($done_count == $num_done){
			$done_table = $done_table . "</table></span>";
		}
		 
	
	
	}elseif($row['timeslot'] == 0){
		$anytime_count++;
			
		if($anytime_count == 1){

			$anytime_table = "<div style=\"clear:both;padding-top:15px;font-weight:bold;\">Pending Anytime Orders - " . $user_title . "</div><span class=\"anytime_table\"><table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th>";
			$anytime_table = $anytime_table . "<th></th></tr>";
		}
		
		$anytime_table = $anytime_table .  "<tr " . $trclass . "><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
		$anytime_table = $anytime_table .  "<td><form action=\"order_dashboard.php\" method=\"get\"><input type=\"hidden\" name=\"instance_id\" value=\"$instance_id\"><button name =\"submit\" class=\"mysubmit\" value=\"...\">...</button></form></td></tr>";
		  
		if($anytime_count == $num_anytimes){
			$anytime_table = $anytime_table . "</table></span>";
		} 
		 
				
	}else{
			
		echo "<span class=\"" . $order_span_class . "\">";
		echo "<span class=\"orderheader\">Account " . $row['acct_num'] . " - $newtimeslot</span>";
		echo "<form action=\"order_dashboard.php\" method=\"get\"><input type=\"hidden\" name=\"instance_id\" value=\"$instance_id\">&nbsp;&nbsp;&nbsp;&nbsp;<button name=\"submit\" value=\"Dashboard\" class=\"button\" style=\"float:right\">Dashboard</button></form> <hr style=\"clear:both;\">";
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
			
			if(strlen($latest_entry) > 60){
				echo "<hr><span class=\"boldname\">Latest Note: </span><span class=\"notes\">" . substr($latest_entry,0,60) . "...</span><br>";
			}else{
				echo "<hr><span class=\"boldname\">Latest Note: </span><span class=\"notes\">" . $latest_entry . "</span><br>";
			}
			
			echo "<form name=\"log_update\" action=\"order_dashboard_update.php\" method=\"post\" style=\"float:right;margin-top:3px;\">";
			echo "<input type=\"hidden\" name=\"instance_id\" value=$instance_id><input type=\"hidden\" name=\"origin\" value=\"" . $current_location . "\">";
			echo "<input type=\"text\" class=\"text\" name=\"entry\" size=\"27\" ><input type=\"text\" class=\"text\" name=\"time_spent\" size=\"3\" > <button NAME=\"update_log\" class=\"button\" VALUE=\"Add Note\">Add Note</button></form>";
		}else{
			echo "<hr><div align=\"center\"><p style=\"font-family:arial;font-size:11px;font-weight:bold;display:inline;\">Order " . $row['status'] . "</p></div>";
		}
		
		echo "</span>";	
	}
	
	$count++;
	
	}

	echo $anytime_table;
	echo $done_table;	
	
}

*/
?>


<?php 
/*
if($num_orders_for_user > 0){
	echo "<div style=\"clear:both;font-weight:bold;padding-top:20px;\">All Other Orders</div>";
}
*/
?>
<div id="all_orders" style="float:left;">


<?php

//GET ORDERS ASSIGNED TO ALL OTHER USERS
/*
if($user_id){
	$schedule_output = "SELECT * FROM current_v2_orders WHERE tech_id <> $user_id AND activation_date = '$sql_date' ORDER BY tech_id, timeslot";
	$schedule_result = mysql_query($schedule_output,$connection);
}else{
	$schedule_output = "SELECT * FROM current_v2_orders WHERE activation_date = '$sql_date' ORDER BY tech_id, timeslot";
	$schedule_result = mysql_query($schedule_output,$connection);
}
*/

$schedule_output = "SELECT * FROM current_v2_orders ORDER BY activation_date, v2_tech_id, timeslot";
$schedule_result = mysql_query($schedule_output,$connection);

//BUILD TABLE FOR ALL OTHER USERS' ORDERS
echo "<table><tr class=\"schedhead\"><th>2.0 Tech</th><th>Activation Date</th><th>Time Slot</th><th>Account Name</th><th>Acct #</th><th>Order #</th>
	<th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Bandwidth</th>
	<th>TCPS</th><th>TCDC</th><th>Managed Services</th><th>Siebel Order Status</th><th>Activation Truck Roll Time (mins)</th>
	<th>Survey Completed?</th><th>Survey Response</th></tr>";
$count = 1;
$old_date = NULL;
while($row = mysql_fetch_array($schedule_result)){

	if($count == 1){ $old_date = $row['activation_date']; }
	
	if($row['activation_date'] != $old_date && $count != 1){
		echo "<tr class=\"border\"><td></td><td></td><td></td><td></td><td></td><td></td>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		$old_date = $row['activation_date'];
	}
	
	
	$tech_name = NULL;
	$newtimeslot = NULL;
	
	$id = $row['v2_tech_id'];
	$order_num = $row['order_num'];
	$instance_id = $row['instance_id'];
	$acct = $row['acct_num'];
	if($id != "1000"){
		$tech_output = "SELECT * FROM techs WHERE tech_id = '$id'";
		$tech_result = mysql_query($tech_output,$connection);
		while($tech = mysql_fetch_array($tech_result)){
			$tech_name = $tech['first_name'] . " " . $tech['last_name'];
		}
	}else{
		$tech_name = "Unassigned";
	}
	
	
	$trclass = "class=\"nowrap";
	
	
	if($row['timeslot'] && $id != "1000" && $id != ""){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
	}elseif($row['timeslot'] && $id == "1000"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowstd";
	}elseif($row['timeslot'] == 0){
		$newtimeslot = "Anytime";
	}
	

	
	if($row['status'] == "Complete" || $row['status'] == "Activation Complete" || $row['jira_ticket_status'] == "CLOSED") {
		$trclass = "class=\"complete";
	}
			
	if($username != "guest"){
	
		if($test = $count % 2){
			echo "<tr " . $trclass . "\" style=\"height:20px;\" ondblClick=\"return popup('v2_order_dashboard.php?order_num=$order_num', $instance_id)\">";
		}else{
			echo "<tr " . $trclass . "_alt\" style=\"height:20px;\" ondblClick=\"return popup('v2_order_dashboard.php?order_num=$order_num', $instance_id)\">";
		}
		
	}else{
	
		if($test = $count % 2){
			echo "<tr " . $trclass . "\" style=\"height:20px;\" >";
		}else{
			echo "<tr " . $trclass . "_alt\" style=\"height:20px;\" >";
		}

	
	}
	
	echo "<td>" . $tech_name . "</td>";
	echo "<td>" . $row['activation_date'] . "</td><td>" . $newtimeslot . "</td><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td>";
	echo "<td>" . $row['bandwidth'] . "</td><td>" . $row['tcps'] . "</td><td>" . $row['tcdc'] . "</td><td>" . $row['managed_services'] . "</td><td>" . $row['siebel_order_status'] . "</td><td>" . $row['truck_roll_mins'] . "</td>";
	echo "<td>" . $row['survey_completed'] . "</td><td>" . $row['survey_result'] . "</td>";
	echo "</tr>";
	

	$count++;
	
	}

echo "</table>";
?> 

</div>
</div>

</body>
</html>

  

  

