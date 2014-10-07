<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];
	
?>

<html>

<title>Service Activations - Online Schedule</title>
<head>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="tracker_style.css" />
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

#mysubmit { 
background-color: #B8B8B8;
font-size: 90%;
color: black;
padding: 1px;
font-weight: bold;
}

form { display: inline; }


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

.top{
border-width:0px;
border-style:hidden;
font-family:"arial";
font-size:10px;
}

</style>

<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="images/SA_title_words_small.png">
</div>

<div class="pagetitle">Online Schedule<span class="location"></span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href=""></a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>


<div style="min-width:600px;position:relative;top:75px;margin-left:10px;">


<table width="900" class="top">
<tr><td class="top">
<table><tr class="nowrap">
<td class="nowrap">Standard</td>
<td class="overflowstd">Overflow</td>
<td class="teamstd">Team MAC</td>
<td class="complete">Complete</td>
<td class="cancel">Cancelled</td>
</tr></table>
<td align="right" class="top">
Last Refresh: <?php echo date("g:i A"); ?>
</td></tr></table>



<?php

//GET TECH ID FROM USERNAME
$get_tech_id_SQL = "SELECT * FROM techs WHERE username = '$username'";
$get_tech_id_result = mysql_query($get_tech_id_SQL,$connection);

while($row = mysql_fetch_array($get_tech_id_result)){
	$user_id = $row['tech_id'];
}

//GET ORDERS ASSIGNED TO CURRENT USER
$get_users_orders_SQL = "SELECT * FROM current_orders WHERE tech_id = $user_id";
$users_orders_result = mysql_query($get_users_orders_SQL,$connection);
$num_orders_for_user = mysql_num_rows($users_orders_result);
//BUILD TABLE FOR CURRENT USER'S ORDERS
if($num_orders_for_user >= 1){
echo "<table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th><th></th></tr>";
$count = 1;
while($row = mysql_fetch_array($users_orders_result)){
	if($id != $row['tech_id'] && $count != 1){
		echo "<tr class=\"border\"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	}
	$id = $row['tech_id'];
	$instance_id = $row['instance_id'];
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
	if($checkforsip == "sip"){
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
	}elseif($row['timeslot'] && $id == "1000" && $checkforsip == "sip"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowsip\"";
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
		

	echo "<tr " . $trclass . "><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
	echo "<td>" . $tech_name . "</td><td><form action=\"assignment_change.php\" method=\"post\"><input type=\"hidden\" name=\"assign_data\" value=\"$instance_id\"><input type=\"submit\" id=\"mysubmit\" value=\"...\"></form></td></tr>";

	$count++;
	}

echo "</table>";

//SEPARATE TWO TABLES
echo "<br><br>";

}

//GET ORDERS ASSIGNED TO ALL OTHER USERS
$schedule_output = "SELECT * FROM current_orders WHERE tech_id <> $user_id ORDER BY tech_id, timeslot";
$schedule_result = mysql_query($schedule_output,$connection);

//BUILD TABLE FOR ALL OTHER USERS' ORDERS
echo "<table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th><th></th></tr>";
$count = 1;
while($row = mysql_fetch_array($schedule_result)){
	if($id != $row['tech_id'] && $count != 1){
		echo "<tr class=\"border\"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	}
	$id = $row['tech_id'];
	$instance_id = $row['instance_id'];
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
	if($checkforsip == "sip"){
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
	}elseif($row['timeslot'] && $id == "1000" && $checkforsip == "sip"){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
		$trclass = "class=\"overflowsip\"";
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
		

	echo "<tr " . $trclass . "><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
	echo "<td>" . $tech_name . "</td><td><form action=\"assignment_change.php\" method=\"post\"><input type=\"hidden\" name=\"assign_data\" value=\"$instance_id\"><input type=\"submit\" id=\"mysubmit\" value=\"...\"></form></td></tr>";

	$count++;
	}

echo "</table>";


?> 

</div>

</body>
</html>

  

  

