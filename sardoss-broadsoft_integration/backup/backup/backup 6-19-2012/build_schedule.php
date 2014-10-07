<HTML>

<HEAD>
<TITLE>Schedule Build Output</TITLE>
</HEAD>
<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>

<BODY>
<?php 
$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());


//----------------------GET SETTINGS AND DEFINE VARIABLES----------------------

$sql_output = "SELECT * FROM settings";
$sql_result = @mysql_query($sql_output,$connection);
while ($row = mysql_fetch_array($sql_result)){
	$early_slot_max = $row['early_slot_max'];
	$late_slot_max = $row['late_slot_max'];
	$target_SD = $row['target_std_dev'];
	$max_runs = $row['max_runs'];
	$max_overflow = $row['max_overflow'];
}





//*****************************************************************************
//-------------------------------FUNCTIONS-------------------------------------
//*****************************************************************************


//UNCONDITIONAL COUNT MYSQL FUNCTION
function uncondition_count($table){
	global $databasename;
	global $connection;
	$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
	$sql_output = "SELECT * FROM $table";
	$sql_result = @mysql_query($sql_output,$connection);
	$num_rows = mysql_num_rows($sql_result);
	return $num_rows;
}


//CONDITIONAL COUNT MYSQL FUNCTION
function condition_count($table,$column,$value){
	global $databasename;
	global $connection;
	$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
	$sql_output = "SELECT * FROM $table WHERE $column = '$value'";
	$sql_result = @mysql_query($sql_output,$connection);
	$num_rows = mysql_num_rows($sql_result);
	return $num_rows;
}


//FIND ALL QUALIFIED TECHS FOR AN ORDER
function get_qualified_techs($order){
	global $databasename;
	global $connection;
	//$qual_techs = array();
	
	$timeslot = sprintf("%04d",$order['timeslot']);
	$timeslot = "skill_" . $timeslot;
	$iad_type = 'skill_SIP';
	
	$checkforsip = substr($order['package'],0,3);
		
	if($checkforsip == "sip"){
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE $timeslot='1' AND $iad_type >= '1' AND status = 'on'";
		$sql_result = @mysql_query($sql_output,$connection);
	}else{
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE $timeslot='1' AND status = 'on'";
		$sql_result = @mysql_query($sql_output,$connection);
	}
	
	while ($row = mysql_fetch_array($sql_result)){
		$qual_techs[] = $row['tech_id'];
	}
	shuffle($qual_techs);
	return $qual_techs;	
}


//GET RANDOM TECH
function get_random_tech($qualified_techs){
	$num_techs = count($qualified_techs) - 1;
	$selector = rand(0,$num_techs);
	return $qualified_techs[$selector];
}


//CHOOSE A TECH FROM AN ARRAY OF QUALIFIED TECHS
function get_tech($qualified_techs,$slot){

	global $orders_per_tech;
	global $early_slot_max;
	global $late_slot_max;
	global $databasename;
	global $connection;
	$remaining_techs = array();
	
	$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
	
	foreach($qualified_techs as $qualified_tech){
		
		$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$qualified_tech'";
		$tech_all_orders = @mysql_query($sql_output,$connection);
		$tech_order_count = mysql_num_rows($tech_all_orders);

//------FILTER OUT TECHS WITH TOO MANY OVERALL ORDERS
		if($tech_order_count < $orders_per_tech){
		
			$next_slot = $slot + 100;
			$next_slot = sprintf("%04d",$next_slot);
			$next_slot = "skill_" . $next_slot;
			$sql_output = "SELECT * FROM techs WHERE tech_id = '$qualified_tech' AND $next_slot='1'";
			$next_slot_result = @mysql_query($sql_output,$connection);
			$next_slot_true = mysql_num_rows($next_slot_result);
					
			$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$qualified_tech' AND timeslot = '$slot'";
			$tech_slot_orders = @mysql_query($sql_output,$connection);
			$tech_slot_count = mysql_num_rows($tech_slot_orders);

//----------FILTER OUT TECHS WITH TOO MANY ORDERS IN THE SLOT
			if($next_slot_true == 1 && $tech_slot_count < $early_slot_max){
				$remaining_techs[] = $qualified_tech;
			}elseif($next_slot_true == 0 && $tech_slot_count < $late_slot_max){
				$remaining_techs[] = $qualified_tech;
			}
		}
	}
	
//--RETURN TECHS IF THERE ARE FEWER THAN 2 QUALIFIED TECHS			
	if(count($remaining_techs) < 2){return $remaining_techs;}

	$count = 1;
	
//--FIND TECHS WITH FEWEST OVERALL ORDERS
	foreach($remaining_techs as $remaining_tech){

		$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$remaining_tech'";
		$tech_all_orders = @mysql_query($sql_output,$connection);
		$tech_order_count = mysql_num_rows($tech_all_orders);
		
		if($count == 1){
			$current_fewest = $tech_order_count;
			$overall_fewest_tech[] = $remaining_tech;
			$count++;
		}elseif($tech_order_count < $current_fewest){
			$current_fewest = $tech_order_count;
			unset($overall_fewest_tech);
			$overall_fewest_tech[] = $remaining_tech;
		}elseif($tech_order_count == $current_fewest){
			$overall_fewest_tech[] = $remaining_tech;
		}
		
		$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$remaining_tech' AND timeslot = '$slot'";
		$tech_slot_orders = @mysql_query($sql_output,$connection);
		$tech_slot_count = mysql_num_rows($tech_slot_orders);
		$count = 1;
		
		if($count == 1){
			$current_fewest_slot_count = $tech_slot_count;
			$slot_fewest_tech[] = $remaining_tech;
			$count++;
		}elseif($tech_order_count < $current_fewest_slot_count){
			$current_fewest_slot_count = $tech_slot_count;
			unset($slot_fewest_tech);
			$slot_fewest_tech[] = $remaining_tech;
		}elseif($tech_slot_count == $current_fewest_slot_count){
			$slot_fewest_tech[] = $remaining_tech;
		}

	}
	
//------RETURN OVERALL FEWEST TECHS IF THERE ARE FEWER THAN 2 QUALIFIED TECHS			
	if(count($overall_fewest_tech) < 2){return $overall_fewest_tech;}
	
//------RETURN SLOT FEWEST TECHS IF THERE ARE FEWER THAN 2 QUALIFIED TECHS			
	return $slot_fewest_tech;
}



//*****************************************************************************
//-------------------------------CORE PROGRAM----------------------------------
//*****************************************************************************


$num_orders = uncondition_count('orders_dev');
$num_techs = condition_count('techs','status','on');
$orders_per_tech = ceil($num_orders/$num_techs);

echo "<table border=\"1\"><tr><th class=\"coverage\">Orders</th><th class=\"coverage\">Techs on-duty</th><th class=\"coverage\">Max orders per tech</th></tr>";
echo "<tr><td class=\"coverage\">$num_orders</td><td class=\"coverage\">$num_techs</td><td class=\"coverage\">$orders_per_tech</td></tr>";
echo "</table><br>";	


//FIND AND DISPLAY NUMBER OF ORDERS PER TIMESLOT
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
$sql_output = "SELECT timeslot, COUNT(*) FROM orders_dev GROUP BY timeslot";
$sql_result = @mysql_query($sql_output,$connection);

echo "<b>Schedule Outlook</b><table><tr>
<th class=\"coverage\">Anytime</th>
<th class=\"coverage\">0830</th>
<th class=\"coverage\">0930</th>
<th class=\"coverage\">1030</th>
<th class=\"coverage\">1130</th>
<th class=\"coverage\">1230</th>
<th class=\"coverage\">1330</th>
<th class=\"coverage\">1430</th>
<th class=\"coverage\">1530</th>
<th class=\"coverage\">1630</th>
<th class=\"coverage\">1730</th>
<th class=\"coverage\">1830</th>
<th class=\"coverage\">1930</th>
</tr><tr>";
while ($row = mysql_fetch_array($sql_result, MYSQL_NUM)){
	echo "<td class=\"coverage\">" . $row[1] . "</td>";
}
echo "</tr></table>";





//********************| SCHEDULE BUILD LOOP |****************************

//ORDER ASSIGNMENT RESET
$sql_output = "UPDATE orders_dev SET tech_id = NULL";
mysql_query($sql_output,$connection);

do{
	//RETRIEVE ALL ORDER INFO SORTED BY TIMESLOT
	$sql_output = "SELECT * FROM orders_dev ORDER BY package DESC, order_type DESC, timeslot";
	$sql_result = @mysql_query($sql_output,$connection);

	while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)){
		
		$instance_id;
		$timeslot;
		
		$instance_id = $row['instance_id'];
		$timeslot = $row['timeslot'];
		
		$qual_techs = get_qualified_techs($row);
		$tech_ids = get_tech($qual_techs,$timeslot);
		
		$tech_count = count($tech_ids);
		
		if($tech_count > 1){
			$tech_id = get_random_tech($tech_ids);
		}elseif($tech_count == 1){
			$tech_id = $tech_ids[0];
		}elseif($tech_count == 0){
			$tech_id = "1000";
		}
		$sql_input = "UPDATE orders_dev SET tech_id = '$tech_id' WHERE instance_id = '$instance_id'";
		mysql_query($sql_input,$connection);
	}
//********************| END SCHEDULE BUILD LOOP |****************************



//*****************| POST-BUILD DATA PRESENTATION |**************************

	unset($order_count);
	unset($stddev_data);
	unset($overflow_count);
	
	//FIND AND DISPLAY TECHS AND NUMBER OF ORDERS
	$sql_output = "SELECT tech_id, COUNT(*) FROM orders_dev GROUP BY tech_id";
	$sql_result = @mysql_query($sql_output,$connection);
	
	while ($row = mysql_fetch_array($sql_result)){
		if($row['tech_id'] != '1000'){
			$order_count[] = $row[1];
			$tech_id = $row['tech_id'];
			$tech_assignments[$tech_id] = $row[1];
		}else{
			$overflow_count = $row[1];
		}
	}

	$assignment_average = array_sum($order_count)/count($order_count);
	foreach($order_count as $this_count){
		$value = $this_count - $assignment_average;
		$squared = pow($value,2);
		$stddev_data[] = $squared;
	}

	$sum = array_sum($stddev_data)/(count($order_count) - 1);
	$stddev = sqrt($sum);
		
	if($run_count == 0){
		$lowest_std_dev = $stddev;
	}elseif($stddev < $lowest_std_dev){
		$lowest_std_dev = $stddev;
	}
	$run_count++;
	echo " -";
}while($stddev > $target_SD && $run_count < $max_runs);

$lowest_std_dev = round($lowest_std_dev,3);

$sql_techcount = "SELECT tech_id, COUNT(*) FROM orders_dev GROUP BY tech_id";
$result_techcount = @mysql_query($sql_output,$connection);
echo "<br><b>Order Counts</b><table><tr><th class=\"coverage\">Tech ID</th><th class=\"coverage\"># Orders</th></tr>";
while($tech = mysql_fetch_array($result_techcount,$connection)){
	echo "<tr><td class=\"coverage\">$tech[0]</td><td class=\"coverage\">$tech[1]</td></tr>";
}
echo "</table>";

if($stddev > $target_SD){
	echo "<br><br>FAILED TO MEET TARGET STANDARD DEVIATION ($target_SD).<br>Lowest standard deviation: " . $lowest_std_dev;
}elseif($stddev <= $target_SD){
	echo "<br><br>TARGET STANDARD DEVIATION ($target_SD) MET.<br>Standard deviation: " . $lowest_std_dev;
}

echo "<br><br>";

?>


<FORM>
<INPUT TYPE="BUTTON" VALUE="Schedule Manager" ONCLICK="window.location.href='schedule_manager.php'">
<INPUT TYPE="BUTTON" VALUE="Build Schedule Again" ONCLICK="window.location.href='build_schedule.php'">
<INPUT TYPE="BUTTON" VALUE="View/Change Settings" ONCLICK="window.location.href='schedule_settings.php'">
</FORM>

</BODY>
</HTML>