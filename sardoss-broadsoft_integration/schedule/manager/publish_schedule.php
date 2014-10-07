<?php

include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$get_dev_orders_sql = "SELECT * FROM $databasename.$ordersDevTable";
$get_orders_result = mysql_query($get_dev_orders_sql,$connection) or die(mysql_error());

$count = 0;
$orders = $timeslot_orders = $anytime_orders = $assigned_timeslot_orders = $assigned_anytimes = $overflow_timeslot_orders = $overflow_anytimes = 0;
while($row = mysql_fetch_array($get_orders_result,MYSQL_ASSOC)){
	
	if($count == 0){
		$delete_orders_sql = "DELETE FROM $databasename.$ordertable WHERE activation_date = '$row[activation_date]'";
		mysql_query($delete_orders_sql, $connection) or die(mysql_error());
		$count = 1;
		$activation_date = $row['activation_date'];
	}
	$copy_order_sql = "INSERT INTO $databasename.$ordertable 
						VALUES (NULL,'$row[upload_date]','$row[activation_date]','$row[order_num]','$row[acct_num]','$row[acct_name]','$row[order_type]','$row[market]','$row[package]','$row[timeslot]','$row[iad_type]','Pending','$row[skill]','$row[tech_id]','$row[access_type]','$row[trouble]','unspecified','unspecified',0,'$row[customer_type]','$row[checklist]',NULL,0)";
	$sql_result = mysql_query($copy_order_sql, $connection) or die(mysql_error());
	
	$orders++;
	if($row['timeslot'] == '0'){ $anytime_orders++;	}else{	$timeslot_orders++;	}
	if($row['timeslot'] == '0' && $row['tech_id'] == '1000'){ $overflow_anytimes++;	}
	if($row['tech_id'] == '1000'){ $total_overflows++;	}	
}



$current_date = date("Y-n-j");
$overflow_timeslot_orders = $total_overflows - $overflow_anytimes;
$assigned_timeslot_orders = $timeslot_orders - $overflow_timeslot_orders;
$assigned_anytimes = $anytime_orders - $overflow_anytimes;


//check metadata table for existing record for this activation date
$result = @mysql_query("SELECT * FROM schedule_metadata WHERE activation_date = '$activation_date'",$connection);
$result_count = mysql_num_rows($result);

if($result_count == 0){
	$dataloadSQL = "INSERT INTO schedule_metadata VALUES ('$activation_date','$current_date','$orders','$timeslot_orders',
		'$anytime_orders','$assigned_timeslot_orders','9999','na','$assigned_anytimes','9999','na','$overflow_timeslot_orders',
		'$overflow_anytimes','$total_overflows')";
}elseif($result_count > 0){
	$dataloadSQL = "UPDATE schedule_metadata SET last_update = '$current_date',total_orders = '$orders',total_timeslot_orders = '$timeslot_orders',
		total_anytime_orders = '$anytime_orders',assigned_timeslot_orders = '$assigned_timeslot_orders',assigned_anytimes = '$assigned_anytimes',
		overflow_timeslot_orders = '$overflow_timeslot_orders',overflow_anytimes = '$overflow_anytimes',total_overflow = '$total_overflows' WHERE activation_date = '$activation_date'";
}

$dataloadresult = @mysql_query($dataloadSQL,$connection) or die(mysql_error());

echo "<b>Schedule Metadata table updated with the following information:</b><br>";
echo "activation_date: $activation_date<br>";
echo "total orders: $orders<br>";
echo "timeslot orders: $timeslot_orders<br>";
echo "anytimes: $anytime_orders<br>";
echo "assigned timeslot: $assigned_timeslot_orders<br>";
echo "assigned anytimes: $assigned_anytimes<br>";
echo "overflow timeslot: $overflow_timeslot_orders<br>";
echo "overflow anytime: $overflow_anytimes<br>";
echo "<br>";
echo "<br>";
?>

<html>
<head>
<meta http-equiv="Refresh" content="5;url=../" />
</head>
<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>
<title>SA Schedule - Publish Schedule</title>
<body>
<br><br><br>

<?php

if($sql_result == '1'){
	echo "Schedule published to Current Schedule.  Re-directing to Current Schedule page in 5 seconds.";
}
?>
</body>
</html> 

