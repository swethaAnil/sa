<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

include 'bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];
	
$useragent = $_SERVER['HTTP_USER_AGENT']; //get the user agent	

if($_GET['lookup_date']){
	if(strpos($useragent,"Firefox")){ header("Location: ./current_schedule.php?lookup_date=$_GET[lookup_date]"); }
	$sql_date = $_GET['lookup_date'];
	$display_date = date('l, F j Y', strtotime($sql_date));
	$current_location = "current_schedule.php?lookup_date=" . $sql_date;
	$today_menu_item = "<a style=\"padding-left:10px;\" href=\"current_schedule.php\">Today's Schedule</a>";
}else{
	if(strpos($useragent,"Firefox")){ header("Location: ./current_schedule.php"); }
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





.bluetext td{
color:blue;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}



.v2 td{
color:#C71585;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}




.overflowstd td{
color:yellow;
white-space:nowrap;
text-align:left;
background-color:#8B4513;
}



.overflowsip td{
color:#87CEEB;
white-space:nowrap;
text-align:left;
background-color:#8B4513;
}



.overflowv2 td{
color:#E38AC2;
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
color: #A9A9A9;
white-space:nowrap;
text-align:left;
background-color:#000066;
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
color:green;
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

</style>


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
	
<?php

while($row = mysql_fetch_array($pending_orders_result)){
	
	echo "$(\"#latestNote$row[instance_id]\").load(\"./dashboard/scheduleLogDisplay.php?order_num=$row[order_num]\")
	";
	echo "$(\"#update_log$row[instance_id]\").click(function(){
		var formData = $('#logForm$row[instance_id]').serialize()
		$.get('./dashboard/orderDashboardSubmit.php',formData,function(data,status){});
		$(\"#logEntry$row[instance_id]\").val(\"\");
		$(\"#timeSpent$row[instance_id]\").val(\"\");
		$(\"#latestNote$row[instance_id]\").load(\"./dashboard/scheduleLogDisplay.php?order_num=$row[order_num]\")
	})
	";
	
 	echo "$(\"#timeSpent$row[instance_id]\").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#logForm$row[instance_id]').serialize()
			$.get('./dashboard/orderDashboardSubmit.php',formData,function(data,status){});
			$(\"#logEntry$row[instance_id]\").val(\"\");
			$(\"#timeSpent$row[instance_id]\").val(\"\");
			$(\"#latestNote$row[instance_id]\").load(\"./dashboard/scheduleLogDisplay.php?order_num=$row[order_num]\")
		}
	});
	";	

}

?>

});
</script>

<script type="text/JavaScript">
<!--
function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
}
//   -->
</script>

<body onload="JavaScript:timedRefresh(300000);">

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
<!-- Last Refresh: <?php echo date("g:i A"); ?> -->
Auto-reload every 5 mins
</tr><tr>
<td align="right" class="top">
<table><tr class="nowrap">
<td class="nowrap">Pending</td>
<td class="complete">Complete</td>
<td class="cancel">Cancelled</td>
</tr></table>
</td></tr></table>

<div style="position:relative;top:-20px;left:10px;color:red;font-size:110%;">NOTE: Internet Explorer offers limited functionality. Use Firefox or Chrome for full operation.</div>
<div id="all_orders" style="float:left;">


<?php

//GET ORDERS ASSIGNED TO ALL USERS
$schedule_output = "SELECT * FROM current_orders WHERE activation_date = '$sql_date' ORDER BY tech_id, timeslot";
$schedule_result = mysql_query($schedule_output,$connection);



//BUILD TABLE FOR ALL OTHER USERS' ORDERS
echo "<table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th></tr>";
$count = 1;
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
		if(strtotime($newtimeslot) < strtotime(date("g:i A"))){
			$overdue++;
		}
	}elseif($row['timeslot'] && $id == "1000" && $row['customer_type'] == "2"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowv2\"";
		if(strtotime($newtimeslot) < strtotime(date("g:i A"))){
			$overdue++;
		}
	}elseif($row['timeslot'] && $id == "1000" && $checkforsip == "sip"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowsip\"";
		if(strtotime($newtimeslot) < strtotime(date("g:i A"))){
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
		

	echo "<tr " . $trclass . " style=\"height:20px;\" ><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";

	echo "<td>" . $tech_name . "</td>";
	$count++;
	}

echo "</table>";


?> 
</div>
</div>
<?php
if($overdue > 0){
	echo "<div style=\"position:absolute;top:80px;left:300px;color:red;\"><b>ALERT<b>: $overdue OVERFLOW ORDER(S) OVERDUE FOR ASSIGNMENT!</div>:";

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



  

