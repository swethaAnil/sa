<html>

<title>SA Schedule - Current Schedule</title>

<style type="text/css">



table, td, th{
border-collapse:collapse;
border-style:solid;
border:thin solid black;
font-family:"arial";
font-size:10px;
}

.schedhead th{
white-space:nowrap;
text-align:left;
background-color:#E5E5E5;

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



body{
font-size:80%;
font-family:"arial";
background-color:#FFFFFF;
color:black;
}

	
</style>




<body>

<?php

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=SA_Schedule');

$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$schedule_output = "SELECT * FROM current_orders ORDER BY tech_id, timeslot";
$schedule_result = mysql_query($schedule_output,$connection);

echo "<table><tr class=\"schedhead\"><th>Account Name</th><th>Account #</th><th>Order Number</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th></tr>";
$count = 1;
while($row = mysql_fetch_array($schedule_result)){
	
	if($row['tech_id'] != "9999"){
		$id = $row['tech_id'];
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
	
	
	if($row['timeslot']){
		$timeslot = $row['timeslot'];
		$hour = substr($timeslot,0,-2);
		$time = mktime($hour,30,0,0,0,0);
		$newtimeslot = date("g:i A",$time);
	}elseif($row['tech_id'] != NULL){
		$newtimeslot = "ANYTIME";
	}else{
		$tech_name = "TEAM";
		$newtimeslot = "ANYTIME";
	}
	echo "<tr " . $trclass . "><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td><td>" . $tech_name . "</td></tr>";

	$count++;
	}
echo "</table>";


?> 

</body>
</html>

  

  

