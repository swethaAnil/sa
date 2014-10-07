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

require '../../bin/dbinfo.inc.php';

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=TCPS_Orders');

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$schedule_output = "SELECT * FROM tcps_orders ORDER BY activation_date, tcps_tech_id, timeslot";
$sql_result = mysql_query($schedule_output,$connection);
if (!$sql_result) { // add this check.
    die('Invalid query: ' . mysql_error());
}


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

?> 

</body>
</html>

  

  

