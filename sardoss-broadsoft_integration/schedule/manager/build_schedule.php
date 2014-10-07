<HTML>

<HEAD>
<TITLE>Schedule Build Output</TITLE>
</HEAD>
<head>
     <link rel="stylesheet" href="../style/schedule_style.css" type="text/css">
</head>

<BODY>
<?php 

include '../bin/dbinfo.inc.php';

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


//SEARCH 2.0 ORDERS FUNCTION
/* function search_v2_orders($order){
	global $databasename;
	global $connection;
		
	$timeslot = sprintf("%04d",$order['timeslot']);
	$order_num = $order['order_num'];
	$slot_skill = "skill_" . $timeslot;
	$iad_type = 'skill_SIP';
	
	$sql_output = "SELECT * FROM current_v2_orders WHERE order_num = '$order_num'";
	$sql_result = @mysql_query($sql_output,$connection);
	$v2_count = mysql_num_rows($sql_result);
	
	if($v2_count == 1){
		
		while($v2_row = mysql_fetch_array($sql_result)){
			$v2_tech = $v2_row[v2_tech_id];
			
		}
		//UPDATE CUSTOMER_TYPE to 2.0 in orders_dev table
		$v2_order_update_SQL = "UPDATE orders_dev SET customer_type = 2 WHERE instance_id = '$order[instance_id]'";
		$v2_order_update_result = @mysql_query($v2_order_update_SQL,$connection) or die(mysql_error);
		
		$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$v2_tech' AND timeslot = '$timeslot'";
		$tech_slot_orders = @mysql_query($sql_output,$connection);
		$tech_slot_count = mysql_num_rows($tech_slot_orders);
		
		$get_tech_skill_SQL = "SELECT * FROM techs WHERE tech_id = '$v2_tech'";
		$tech_data = mysql_fetch_assoc( mysql_query($get_tech_skill_SQL,$connection) );
		$tech_slot_max = $tech_data[$slot_skill];
		$tech_on_duty = $tech_data['status'];
					
//----------FILTER OUT TECHS WITH TOO MANY ORDERS IN THE SLOT
		
		//echo "$order_num - tech: $v2_tech -  count: $tech_slot_count - max: $tech_slot_max - tech is:$tech_on_duty<br>";
		if($tech_slot_count < $tech_slot_max && $tech_on_duty == "on"){
			return $v2_tech;
		}

	}elseif($v2_count > 1){
		echo "ERROR: More than one 2.0 tech assigned to order $order_num";
	}
	
		

} */

//ASSIGN 2.0 ORDERS TO PROPER 2.0 TECH
function assign_v2_order($order){

	global $databasename;
	global $connection;
		
	$timeslot = sprintf("%04d",$order['timeslot']);
	$order_num = $order['order_num'];
	$slot_skill = "skill_" . $timeslot;
	$iad_type = 'skill_SIP';
	
	//add 2.0 flag to order on Online Schedule
	$v2_order_update_SQL = "UPDATE orders_dev SET customer_type = 2 WHERE instance_id = '$order[instance_id]'";
	$v2_order_update_result = @mysql_query($v2_order_update_SQL,$connection) or die(mysql_error);
	
	$sql_output = "SELECT * FROM current_v2_orders WHERE order_num = '$order_num'";
	$sql_result = @mysql_query($sql_output,$connection);
	
	while($v2_row = mysql_fetch_array($sql_result)){
		$v2_tech = $v2_row['v2_tech_id'];
	}
	
	$get_tech_skill_SQL = "SELECT * FROM techs WHERE tech_id = '$v2_tech'";
	$tech_data = mysql_fetch_assoc( mysql_query($get_tech_skill_SQL,$connection) );
	$tech_slot_max = $tech_data[$slot_skill];
	$tech_on_duty = $tech_data['status'];
	
	if($tech_on_duty == "on"){
	
		$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$v2_tech' AND timeslot = '$timeslot'";
		$tech_slot_orders = @mysql_query($sql_output,$connection);
		$tech_slot_count = mysql_num_rows($tech_slot_orders);
	
		if($tech_slot_count < $tech_slot_max){
		
			$sql_input = "UPDATE orders_dev SET tech_id = '$v2_tech' WHERE instance_id = '$order[instance_id]'";
			mysql_query($sql_input,$connection);
			return 1;
		
		}else{
			return 0;
		}
	}else{
		return 0;
	}
}
 
 
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!  NEED WORK HERE!!!!!!!!!!!!!!!!!!!!!
//ASSIGN 2.0 ORDERS TO OTHER AVAILABLE 2.0 TECH
function get_v2_tech($order){

	global $databasename;
	global $connection;
		
	$timeslot = sprintf("%04d",$order['timeslot']);
	$order_num = $order['order_num'];
	$slot_skill = "skill_" . $timeslot;
		
	$v2_tech_sql = "SELECT * FROM techs WHERE is_deleted = 0 AND skill_v2 = '1' AND status = 'on' AND $slot_skill > 0 ORDER BY rand()";
	$v2_tech_result = @mysql_query($v2_tech_sql,$connection);
	$num_techs = mysql_num_rows($v2_tech_result);
	
	if($num_techs == 0){
	
		return 0;
		
	}elseif($num_techs >= 1){
		
		while($tech = mysql_fetch_array($v2_tech_result)){

			$v2_tech = $tech['tech_id'];	
			//GET TECH SLOT MAX
			$tech_slot_max = $tech[$slot_skill];
			
			//GET NUMBER OF ORDERS IN TIMESLOT CURRENTLY ASSIGNED TO TECH
			$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$v2_tech' AND timeslot = '$timeslot'";
			$tech_slot_orders = @mysql_query($sql_output,$connection);
			$tech_slot_count = mysql_num_rows($tech_slot_orders);
			
			if($tech_slot_count < $tech_slot_max){
				$sql_input = "UPDATE orders_dev SET tech_id = '$v2_tech' WHERE instance_id = '$order[instance_id]'";
				mysql_query($sql_input,$connection);
				return 1;
				break;
			}
		}
		
		return 0;
		
	}
}
  




//FIND ALL QUALIFIED 1.0 TECHS FOR AN ORDER
function get_qualified_techs($order){
	global $databasename;
	global $connection;
	//$qual_techs = array();
	
	$timeslot = sprintf("%04d",$order['timeslot']);
	$timeslot = "skill_" . $timeslot;
	$iad_type = 'skill_SIP';
	
	$checkforsip = substr($order['package'],0,3);
		
/* 	if($checkforsip == "sip"){
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE $timeslot='1' AND $iad_type >= '1' AND status = 'on'";
		$sql_result = @mysql_query($sql_output,$connection);
	}else{
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE $timeslot='1' AND status = 'on'";
		$sql_result = @mysql_query($sql_output,$connection);
	} */
	
	if($checkforsip == "sip"){
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE is_deleted = 0 AND $timeslot > 0 AND $iad_type >= '1' AND status = 'on' AND skill_v2 = 0";
		$sql_result = @mysql_query($sql_output,$connection);
	}else{
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE is_deleted = 0 AND $timeslot > 0 AND status = 'on' AND skill_v2 = 0";
		$sql_result = @mysql_query($sql_output,$connection);
	}
	
	if(!$sql_result){ return array();}
	
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
	if(strlen($slot) < 4){
		$slot_skill = "skill_0" . $slot;
	}else{
		$slot_skill = "skill_" . $slot;
	}
	
	$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
	
	foreach($qualified_techs as $qualified_tech){
		
		$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$qualified_tech'";
		$tech_all_orders = @mysql_query($sql_output,$connection);
		$tech_order_count = mysql_num_rows($tech_all_orders);

//------FILTER OUT TECHS WITH TOO MANY OVERALL ORDERS
		if($tech_order_count <= $orders_per_tech){
		
/* 			$next_slot = $slot + 100;
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
			} */
			
			$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '$qualified_tech' AND timeslot = '$slot'";
			$tech_slot_orders = @mysql_query($sql_output,$connection);
			$tech_slot_count = mysql_num_rows($tech_slot_orders);
			
			$get_tech_skill_SQL = "SELECT * FROM techs WHERE tech_id = '$qualified_tech'";
			$tech_data = mysql_fetch_assoc( mysql_query($get_tech_skill_SQL,$connection) );
			$tech_slot_max = $tech_data[$slot_skill];
						
//----------FILTER OUT TECHS WITH TOO MANY ORDERS IN THE SLOT
			
			if($tech_slot_count < $tech_slot_max){
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

//PROCESS 2.0 ORDER ASSIGNMENTS
$sql_output = "SELECT orders_dev.instance_id,orders_dev.timeslot,orders_dev.order_num FROM orders_dev,current_v2_orders WHERE orders_dev.order_num = current_v2_orders.order_num";
$sql_result = @mysql_query($sql_output,$connection);
$v2_order_count = mysql_num_rows($sql_result);
$assign_result = 0;
$total_assigned = 0;
$total_random_assigned = 0;

while($row = mysql_fetch_array($sql_result)){
	$assign_result = assign_v2_order($row);
	$total_assigned += $assign_result;
}
echo "<br>Number of 2.0 orders: $v2_order_count<br>";
echo "Assigned to preferred 2.0 tech: $total_assigned<br>";

if($total_assigned < $v2_order_count){

	$sql_output = "SELECT orders_dev.instance_id,orders_dev.timeslot,orders_dev.order_num FROM orders_dev,current_v2_orders WHERE orders_dev.order_num = current_v2_orders.order_num AND orders_dev.tech_id = '0' OR orders_dev.tech_id = '1000' OR orders_dev.tech_id IS NULL ";
	$sql_result = @mysql_query($sql_output,$connection);
	
	while($row = mysql_fetch_array($sql_result)){
		$assign_random_result = get_v2_tech($row);
		$total_random_assigned += $assign_random_result;
	}
}

$v2_leftover = $v2_order_count - ($total_assigned + $total_random_assigned);
echo "Assigned to random 2.0 tech: $total_random_assigned<br>";
echo "Number of 2.0 orders assigned to 1.0 tech: $v2_leftover<br>";


//do{
	//RETRIEVE ORDER INFO FOR UNASSIGNED ORDERS SORTED BY TIMESLOT
	$sql_output = "SELECT * FROM orders_dev WHERE tech_id = '0' OR tech_id = '1000' OR tech_id IS NULL ORDER BY package DESC, order_type DESC, timeslot";
	$sql_result = @mysql_query($sql_output,$connection);

	while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)){
		
		$instance_id;
		$timeslot;
		
		$instance_id = $row['instance_id'];
		$timeslot = $row['timeslot'];
		
		if(substr($row['package'],0,8) == "Multinet"){
			
			$sql_input = "UPDATE orders_dev SET tech_id = 1145 WHERE instance_id = '$instance_id'";
			mysql_query($sql_input,$connection);
			
		}elseif($row['order_type'] == "Re-Home"){
		
			$sql_input = "UPDATE orders_dev SET tech_id = 1152 WHERE instance_id = '$instance_id'";
			mysql_query($sql_input,$connection);
		
		
		}else{

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
	}
//********************| END SCHEDULE BUILD LOOP |****************************



//*****************| POST-BUILD DATA PRESENTATION |**************************

/* 	unset($order_count);
	unset($stddev_data);
	unset($overflow_count);
	
	//FIND AND DISPLAY TECHS AND NUMBER OF ORDERS FOR 1.0
	$sql_output = "SELECT tech_id, COUNT(*) FROM orders_dev WHERE customer_type = '1' GROUP BY tech_id";
	$sql_result = @mysql_query($sql_output,$connection);
	
	while ($row = mysql_fetch_array($sql_result)){
		if($row['tech_id'] != '1000'){
			$order_count[] = $row[1];
			$tech_id = $row['tech_id'];
			$tech_assignments[$tech_id] = $row[1];
		}else{
			$overflow_count = $row[1];
		}
	} */

/* 	$assignment_average = array_sum($order_count)/count($order_count);
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
	$run_count++; */
//	echo " -";
//}while($stddev > $target_SD && $run_count < $max_runs);

//$lowest_std_dev = round($lowest_std_dev,3);

$all_techs = array();
$all_techs_SQL = "SELECT * FROM techs WHERE is_deleted = 0";
$all_techs_result = @mysql_query($all_techs_SQL, $connection);
while($tech_row = mysql_fetch_array($all_techs_result)){
	$id = $tech_row['tech_id'];
	$all_techs[$id] = $tech_row['first_name'] . " " . $tech_row['last_name'];
}

echo "<table style=\"border-style:none;\"><tr><td style=\"border-style:none;padding:20px;\">";
$sql_techcount = "SELECT tech_id, timeslot, COUNT(*) FROM orders_dev WHERE timeslot != '0' AND customer_type = '1' GROUP BY tech_id ORDER BY tech_id";
$result_techcount = @mysql_query($sql_techcount,$connection);
echo "<br><b>1.0 Time Slot Orders</b><table><tr><th class=\"coverage\">Tech</th><th class=\"coverage\"># Orders</th></tr>";
while($order_row = mysql_fetch_array($result_techcount)){
	$this_tech_id = $order_row['tech_id'];
	if($this_tech_id == '1000'){
		echo "<tr><td class=\"coverage\"> OVERFLOW </td><td class=\"coverage\"> $order_row[2] </td></tr>";
	}else{
		echo "<tr><td class=\"coverage\"> $all_techs[$this_tech_id] </td><td class=\"coverage\"> $order_row[2] </td></tr>";
	}
}
echo "</table>";
echo "</td><td style=\"border-style:none;padding:20px;\">";
$sql_techcount = "SELECT tech_id, timeslot, COUNT(*) FROM orders_dev WHERE timeslot != '0' AND customer_type = '2' GROUP BY tech_id ORDER BY tech_id";
$result_techcount = @mysql_query($sql_techcount,$connection);
echo "<br><b>2.0 Time Slot Orders</b><table><tr><th class=\"coverage\">Tech</th><th class=\"coverage\"># Orders</th></tr>";
while($order_row = mysql_fetch_array($result_techcount)){
	$this_tech_id = $order_row['tech_id'];
	if($this_tech_id == '1000'){
		echo "<tr><td class=\"coverage\"> OVERFLOW </td><td class=\"coverage\"> $order_row[2] </td></tr>";
	}else{
		echo "<tr><td class=\"coverage\"> $all_techs[$this_tech_id] </td><td class=\"coverage\"> $order_row[2] </td></tr>";
	}
}
echo "</table>";
echo "</td></tr></table>";

/* 
if($stddev > $target_SD){
	echo "<br><br>FAILED TO MEET TARGET STANDARD DEVIATION FOR 1.0 ORDERS ($target_SD).<br>Lowest standard deviation: " . $lowest_std_dev;
}elseif($stddev <= $target_SD){
	echo "<br><br>TARGET STANDARD DEVIATION ($target_SD) MET.<br>Standard deviation: " . $lowest_std_dev;
}  */

echo "<br><br>";

?>


<FORM>
<INPUT TYPE="BUTTON" VALUE="Schedule Manager" ONCLICK="window.location.href='schedule_manager.php'">
<INPUT TYPE="BUTTON" VALUE="Build Schedule Again" ONCLICK="window.location.href='build_schedule.php'">
<INPUT TYPE="BUTTON" VALUE="View/Change Settings" ONCLICK="window.location.href='schedule_settings.php'">
</FORM>

</BODY>
</HTML>