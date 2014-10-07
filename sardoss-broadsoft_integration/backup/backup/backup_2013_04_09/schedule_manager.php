<?php

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
	
?>

<html>
<head>
<title>SA Schedule - Schedule Manager</title>

<script type="text/javascript">

function show_confirm(){

//issue alert to verify publishing
var answer = confirm("WARNING! - Publishing this schedule will OVERWRITE the orders for this activation date in the current_schedule database.\n\nAre you sure you want to proceed?");
if(answer){
	window.location = "publish_schedule.php";
	}
else{
	}	
}


</script>

<style type="text/css">



table, td, th{
border-collapse:collapse;
border-style:solid;
border:thin solid;
border-color:#4D4D4D;
font-family:"arial";
font-size:10px;
}

.schedhead th{
white-space:nowrap;
text-align:left;
color:white;
background-color:#4D4D4D;
padding-right:15px;

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

.change td{
white-space:nowrap;
text-align:left;
background-color:#CCCCCC;
}

.sipchange td{
white-space:nowrap;
text-align:left;
background-color:#CCCCCC;
color:blue;
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

.border td{
white-space:nowrap;
text-align:left;
background-color:#4D4D4D;
}



body{
font-size:80%;
font-family:"arial";
background-color:#FFFFFF;
color:black;
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

</style>
</head>
<body>
<h3>NOTE: Changes made here are written to a working copy of the schedule (orders_dev table).  Changes must be published to the Current_Orders table in order to appear on the "Current_Schedule" page.</h3>
<FORM>
<INPUT TYPE="BUTTON" VALUE="View/Change Settings" ONCLICK="window.location.href='schedule_settings.php'">
<INPUT TYPE="BUTTON" VALUE="Export this Schedule to Excel" ONCLICK="window.location.href='export_schedule.php'">
<INPUT TYPE="BUTTON" VALUE="Publish to Current_Orders table" ONCLICK="show_confirm()">
<INPUT TYPE="BUTTON" VALUE="Import today's orders from Current_Orders table" ONCLICK="window.location.href='import_current_schedule.php'">
<INPUT TYPE="BUTTON" VALUE="Add an Order to Current_Orders table" ONCLICK="window.location.href='add_order.htm'">
</FORM>

<br>

<?php
$sum = 0;
$tech_count = 0;
$sql_techcount = "SELECT tech_id, COUNT(*) FROM orders_dev GROUP BY tech_id";
$result_techcount = @mysql_query($sql_techcount,$connection);

while($tech = mysql_fetch_array($result_techcount,$connection)){
	if($tech[0] != "1000"){
		$id_array[] = $tech[0];
		$count_array[] = $tech[1];
	}
}


echo "<br><b>Order Counts</b><table><tr>";
$num_techs = count($id_array);

for($i=0;$i<$num_techs;$i++){
	$tech_outputA = "SELECT * FROM techs WHERE tech_id = '$id_array[$i]'";
	$tech_resultA = mysql_query($tech_outputA,$connection);
	while($tech = mysql_fetch_array($tech_resultA)){
		$last_name = $tech['username'];
	}
	echo "<td width=\"45\">$last_name</td>";	
}

echo "</tr><tr>";
for($i=0;$i<$num_techs;$i++){
		echo "<td>$count_array[$i]</td>";
		$sum = $sum + $count_array[$i];
}
echo "</tr></table><br>";

//calc average (excluding overflow)
$avg = round($sum/$num_techs,2);

foreach($count_array as $this_count){
	$value = $this_count - $avg;
	$squared = pow($value,2);
	$stddev_data[] = $squared;
}
$int_value = array_sum($stddev_data)/($num_techs - 1);
$stddev = round(sqrt($int_value),3);

echo "Average: $avg<br>";
echo "Standard Deviation: $stddev";

?>
<br>
<br>
<form action="submit_manager_update.php" method="post">
<INPUT TYPE="SUBMIT" VALUE="Submit Assignment Updates">
<br>
<br>
<?php

$schedule_output = "SELECT * FROM orders_dev ORDER BY tech_id, timeslot";
$schedule_result = mysql_query($schedule_output,$connection);

echo "<table><tr class=\"schedhead\"><th>Account Name</th><th>Acct #</th><th>Order #</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th><th>Re-Assign</th></tr>";
$count = 1;
$max = 999;
$min = 0;
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
	}elseif($checkforsip != "sip" && $id == "1000"){
		$newtimeslot = "Anytime";
		$tech_name = "TEAM";
		$trclass = "class=\"teamstd\"";
	}elseif($checkforsip == "sip" && $id == "1000"){
		$newtimeslot = "Anytime";
		$tech_name = "TEAM";
		$trclass = "class=\"teamsip\"";
	}elseif($row['timeslot'] == 0){
		$newtimeslot = "Anytime";
	}
	
	if($row['order_type'] == "Change" && $id != "1000" && $checkforsip == "sip"){
		$trclass = "class=\"sipchange\"";
	}elseif($row['order_type'] == "Change" && $id != "1000" && $checkforsip != "sip"){
		$trclass = "class=\"change\"";
	}
	
	
	if($row['status'] == "Cancelled"){
		$trclass = "class=\"cancel\"";
	}
	
	if($row['status'] == "Complete"){
		$trclass = "class=\"complete\"";
	}
	
	
		

	echo "<tr " . $trclass . "><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td>";
	echo "<td>" . $tech_name . "</td>";
	
	//echo "<td><input type=\"hidden\" name=\"assign_data\" value=\"$instance_id\">";
	
	
	echo "<td><select name=\"" . $instance_id . "\">";
	echo "<option value=\"\"></option>";

	//GET CURRENT TECH INFO
	$techs_output = "SELECT * FROM techs ORDER BY first_name";
	$techs_result = mysql_query($techs_output,$connection);
	while($tech = mysql_fetch_array($techs_result)){
		echo "<option value=\"" . $tech['tech_id'] . "\">" . $tech['first_name'] . " " . $tech['last_name'] . "</option>";
	}
	echo "<option value=\"1000\">OVERFLOW</option>";

	echo "</select></td></tr>";



	$count++;
	}

echo "</table>";


?> 



</body>
</html>

  

  

