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

<title>Manager Dashboard - SA Schedule</title>


<style type="text/css">



table, td, th{
border-collapse:collapse;
border-style:solid;
border:thin solid black;
font-family:"arial";
font-size:14px;
}

.schedhead th{
white-space:nowrap;
text-align:left;
background-color:#E5E5E5;
padding-right: 10px;

}

.nowrap td{
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


<table><tr class="schedhead"><th>SA Tech</th><th>Pending</th><th>Complete</th><th>Cancelled</th><th>Total Orders</th></tr>


<?php

$techs_output = "SELECT * FROM techs";
$techs_result = mysql_query($techs_output,$connection);


while($tech = mysql_fetch_array($techs_result)){
	
	$total = 0;
	$pending = 0;
	$complete = 0;
	$cancelled = 0;
	$tech_id = $tech['tech_id'];
	
	$sql_orders = "SELECT * FROM current_orders WHERE tech_id = '$tech_id'";
	$orders_result = mysql_query($sql_orders, $connection);
	while($order = mysql_fetch_array($orders_result)){
		$total++;
		
		switch($order['status']){
			case NULL:
				$pending++;
				break;
			case "Complete":
				$complete++;
				break;
			case "Cancelled":
				$cancelled++;
				break;
		}
	}
	
	$sql_techname = "SELECT * FROM techs WHERE tech_id = '$tech_id'";
	$techname_result = mysql_query($sql_techname, $connection);
	while($tech_data = mysql_fetch_array($techname_result)){
		$tech_name = $tech_data['first_name'] . " " . $tech_data['last_name'];
	}
	
	echo "<tr class=\"nowrap\"><td>$tech_name<td>$pending</td><td>$complete</td><td>$cancelled</td><td>$total</td></tr>";
	
}

?> 

</table>



</body>
</html>

  

  

