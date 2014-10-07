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

.nowrap_alt td{
white-space:nowrap;
text-align:left;
background-color:#D8D8D8;
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
color: green;
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

.complete_alt td{
color: green;
white-space:nowrap;
text-align:left;
background-color:#D8D8D8;
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

include '../../bin/dbinfo.inc.php';

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=2.0_Orders');

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$schedule_output = "SELECT * FROM current_v2_orders ORDER BY activation_date, tech_id, timeslot";
$sql_result = mysql_query($schedule_output,$connection);


echo "<table border=1 style=\"border-collapse:collapse;white-space:nowrap;font-size:12px;\">";
$count=0;
while($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)){
	while($count == 0){
		$headers = array_keys($row);
		echo "<tr>";
		foreach($headers as $header){
			echo "<th>$header</th>";
		}
		echo "</tr>";
		$count++;
	}
	echo "<tr>";
	foreach($row as $object){
		echo "<td>$object</td>";
	}
	echo "</tr>";
}
echo "</table><br><br><br>";


/*
echo "<table><tr class=\"schedhead\"><th>SA Tech</th><th>Activation Date</th><th>Time Slot</th><th>Account Name</th><th>Acct #</th><th>Order #</th>
	<th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Bandwidth</th>
	<th>TCPS</th><th>TCDC</th><th>Managed Services</th><th>JIRA Ticket</th>
	<th>JIRA Status</th><th>JIRA Total Work (mins)</th><th>Siebel Order Status</th><th>Activation Truck Roll Time (mins)</th>
	<th>Survey Completed?</th><th>Survey Response</th></tr>";
$count = 1;
$old_date = NULL;
while($row = mysql_fetch_array($schedule_result)){

/* 	if($count == 1){ $old_date = $row['activation_date']; }
	
	if($row['activation_date'] != $old_date && $count != 1){
		echo "<tr class=\"border\"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		$old_date = $row['activation_date'];
	} */
	
/*
	
	$id = $row['tech_id'];
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
			
	
	echo "<tr " . $trclass . "\" >";
	
		
	echo "<td>" . $tech_name . "</td>";
	echo "<td>" . $row['activation_date'] . "</td><td>" . $newtimeslot . "</td><td>" . $row['acct_name'] . "</td><td>" . $row['acct_num'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td>";
	echo "<td>" . $row['bandwidth'] . "</td><td>" . $row['tcps'] . "</td><td>" . $row['tcdc'] . "</td><td>" . $row['managed_services'] . "</td><td>" . $row['jira_ticket'] . "</td><td>" . $row['jira_ticket_status'] . "</td><td>" . $row['jira_total_mins'] . "</td><td>" . $row['siebel_order_status'] . "</td><td>" . $row['truck_roll_mins'] . "</td>";
	echo "<td>" . $row['survey_completed'] . "</td><td>" . $row['survey_result'] . "</td>";
	echo "</tr>";
	

	$count++;
	
	}

echo "</table>";*/
?> 

</body>
</html>

  

  

