<html>

<title>SA Schedule - Current Schedule</title>

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

<?php

include 'bin/dbinfo.inc.php';

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=SA_Schedule');

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$schedule_output = "SELECT * FROM orders_dev ORDER BY tech_id, timeslot";
$schedule_result = mysql_query($schedule_output,$connection);

echo "<table><tr class=\"schedhead\"><th>Account Name</th><th>Account #</th><th>Order Number</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th></tr>";
$count = 1;
while($row = mysql_fetch_array($schedule_result)){
	
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
		$tech_name = "OVERFLOW";
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


	echo "<tr " . $trclass . "><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td><td>" . $tech_name . "</td></tr>";

	$count++;
	}
echo "</table>";


?> 

</body>
</html>

  

  

