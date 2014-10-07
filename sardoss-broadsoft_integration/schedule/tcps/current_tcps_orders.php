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
<title>TCPS Orders - Service Activations</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->


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

.cancel_alt td{
color: #A9A9A9;
white-space:nowrap;
text-align:left;
background-color:#7D0000;
}

.cancel:hover td{
	background-color:gray;
	color:white;
}

.cancel_alt:hover td{
	background-color:gray;
	color:white;
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
font-size:10px;
padding-right:40px;
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

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script> 
$(document).ready(function(){
	
	$( "#tabs" ).tabs({ active: 1 });
	
	$(function(){
		$("#tabs").tabs();
	});
	
	
});
</script> 



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
	myWindow = window.open(href, windowname, 'width=600,height=800,scrollbars=yes,status=no,toolbar=no,location=no');
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
 / <a class="pagetitle" href="./">TCPS</a>
 / 
<span class="location">
All Orders
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="./">TCPS Calendar</a>
<a style="padding-left:10px;" href="./manager/">TCPS Manager</a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>

<div style="min-width:600px;position:relative;top:80px;margin-left:10px;width:100%;">
<!--
<table class="topfull">
<tr><td align="left" class="top">
<table><tr class="nowrap">
<td class="overflowstd">Unassigned</td>
<td class="nowrap">Pending</td>
<td class="complete">Complete</td>
<td class="cancel">Cancelled</td>
</tr></table><td align="left" class="top" >
Last Refresh: <?php echo date("g:i A"); ?>
</td></tr>
<tr><td class="top"> </td></tr>
<tr><td class="top"> </td></tr>
</table>
-->
<table class="topfull">
<tr><td align="right" class="top">
Last Refresh: <?php echo date("g:i A"); ?>
</tr><tr>
<td align="right" class="top">
<table><tr class="nowrap">
<td class="overflowstd">Unassigned</td>
<td class="nowrap">Pending</td>
<td class="complete">Complete</td>
<td class="cancel">Cancelled</td>
</tr></table>
</td></tr></table>

<div id="all_orders" style="float:left;">

<div id="tabs">
<ul>

<?php

$date_output = "SELECT DISTINCT MONTH(activation_date),YEAR(activation_date) FROM tcps_orders ORDER BY activation_date";
$date_result = mysql_query($date_output,$connection);
$curr_tab = NULL;
$tab_count = 1;
while($row = mysql_fetch_array($date_result)){
	//echo $row[0] . " " . $row[1] . "<br>";
	
	if($tab_count == 1 && $row[1] == "1899"){
		echo "<li><a href=\"#tabs-$tab_count\">ON HOLD</a></li>
		";
		$tab_count++;
	}elseif($curr_tab != $row[0]){
		$curr_tab = $row[0];
		$monthName = date("F", mktime(0, 0, 0, $row[0], 10));
		echo "<li><a href=\"#tabs-$tab_count\">$monthName $row[1]</a></li>
		";
		$tab_count++;
	}
	

}

echo "</ul>";

//BUILD TABLE FOR ALL OTHER USERS' ORDERS
$schedule_output = "SELECT * FROM tcps_orders ORDER BY activation_date, timeslot";
$schedule_result = mysql_query($schedule_output,$connection);
$count = 1;
$tab_count = 1;
$old_date = NULL;
$curr_month = NULL;
while($row = mysql_fetch_array($schedule_result)){

	
	if($count == 1){ 
		$old_date = $row['activation_date']; 
		$curr_month =  substr($row['activation_date'],0,7);
		echo "<div id=\"tabs-$tab_count\">";
		echo "<table><tr class=\"schedhead\"><th>TCPS Tech</th><th>Activation Date</th><th>Time Slot</th><th>Acct #</th><th>Account Name</th><th>Order #</th>
			<th>Order Type</th><th>TCPS Status</th></tr>";
		
	}
	
	if($curr_month != substr($row['activation_date'],0,7)){
		$curr_month = substr($row['activation_date'],0,7);
		$tab_count++;
		echo "</table></div>";
		echo "<div id=\"tabs-$tab_count\">";
		echo "<table><tr class=\"schedhead\"><th>TCPS Tech</th><th>Activation Date</th><th>Time Slot</th><th>Acct #</th><th>Account Name</th><th>Order #</th>
			<th>Order Type</th><th>TCPS Status</th></tr>";
		
		
	}
	
	if($row['activation_date'] != $old_date && $count != 1){
		echo "<tr class=\"border\"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		$old_date = $row['activation_date'];
	}
	
	
	$tech_name = NULL;
	$newtimeslot = NULL;
	
	$id = $row['tcps_tech_id'];
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
	}else{
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
	}

	
	if($row['status'] == "Activation Complete") {
		$trclass = "class=\"complete";
	}elseif($row['status'] == "Order Cancelled") {
		$trclass = "class=\"cancel";
	}
			
	if($username != "guest"){
	
		if($test = $count % 2){
			echo "<tr " . $trclass . "\" style=\"height:20px;\" ondblClick=\"return popup('tcps_order_dashboard.php?order_num=$order_num', $instance_id)\">";
		}else{
			echo "<tr " . $trclass . "_alt\" style=\"height:20px;\" ondblClick=\"return popup('tcps_order_dashboard.php?order_num=$order_num', $instance_id)\">";
		}
		
	}else{
	
		if($test = $count % 2){
			echo "<tr " . $trclass . "\" style=\"height:20px;\" >";
		}else{
			echo "<tr " . $trclass . "_alt\" style=\"height:20px;\" >";
		}

	
	}
	
	echo "<td>" . $tech_name . "</td>";
	echo "<td>" . $row['activation_date'] . "</td><td>" . $newtimeslot . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['acct_name'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td>";
		echo "<td>" . $row['status'] . "</td>";
	echo "</tr>";
	

	$count++;
	
	}

echo "</table></div>";
?> 

</div>





</div>
</div>

</body>
</html>

  

  

