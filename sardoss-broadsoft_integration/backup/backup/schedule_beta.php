<HTML>

<HEAD>
<TITLE>Schedule Beta Output </TITLE>
</HEAD>

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
	$early_slot_max = $row[early_slot_max];
	$late_slot_max = $row[late_slot_max];
}





//*****************************************************************************
//-------------------------------FUNCTIONS-------------------------------------
//*****************************************************************************


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
	$timeslot = sprintf("%04d",$order[timeslot]);
	$timeslot = "skill_" . $timeslot;
	$iad_type = 'skill_SIP';
	$access_type = "skill_EFM";
	
	$checkforsip = substr($order[package],0,3);
	
	if($checkforsip == "sip"){
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE $timeslot='1' AND $iad_type='1' AND status = 'on'";
		$sql_result = @mysql_query($sql_output,$connection);
	}else{
		$database = @mysql_select_db($databasename,$connection) or die(mysql_error());
		$sql_output = "SELECT * FROM techs WHERE $timeslot='1' AND status = 'on'";
		$sql_result = @mysql_query($sql_output,$connection);
	}
	
	while ($row = mysql_fetch_array($sql_result)){
		$qual_techs[] = $row[tech_id];
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
		
		$sql_output = "SELECT * FROM current_orders WHERE tech_id = '$qualified_tech'";
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
					
			$sql_output = "SELECT * FROM current_orders WHERE tech_id = '$qualified_tech' AND timeslot = '$slot'";
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

		$sql_output = "SELECT * FROM current_orders WHERE tech_id = '$remaining_tech'";
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
		
		$sql_output = "SELECT * FROM current_orders WHERE tech_id = '$remaining_tech' AND timeslot = '$slot'";
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
//	if(count($slot_fewest_tech) < 2){return $slot_fewest_tech;}
	return $slot_fewest_tech;


}







//*****************************************************************************
//-------------------------------CORE PROGRAM----------------------------------
//*****************************************************************************

$num_orders = condition_count('current_orders','activation_date','2011-11-17');
echo "There are $num_orders orders<br>";

$num_techs = condition_count('techs','status','on');
echo "There are $num_techs techs<br>";	

$orders_per_tech = ceil($num_orders/$num_techs);
echo "<br>Orders per tech: " . $orders_per_tech . "<br>";
echo "<br><br>";

//FIND AND DISPLAY NUMBER OF ORDERS PER TIMESLOT
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

//************DATABASE TIMESLOT RESET FOR DEBUGGING**********************
$sql_output = "UPDATE current_orders SET tech_id = NULL";
@mysql_query($sql_output,$connection);

$sql_output = "SELECT timeslot, COUNT(*) FROM current_orders GROUP BY timeslot";
$sql_result = @mysql_query($sql_output,$connection);

while ($row = mysql_fetch_array($sql_result, MYSQL_NUM)){
	echo "Timeslot: " . $row[0] . " - Orders: " . $row[1] . "<br>";
}
echo "<br><br>";

//DISPLAY ALL ORDER INFO
$sql_output = "SELECT * FROM current_orders ORDER BY timeslot";
$sql_result = @mysql_query($sql_output,$connection);

while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)){
	$instance_id = $row[instance_id];
	$timeslot = $row[timeslot];
	
	$qual_techs = get_qualified_techs($row);
	$tech_ids = get_tech($qual_techs,$timeslot);
	$tech_id = get_random_tech($tech_ids);
	
	$sql_input = "UPDATE current_orders SET tech_id = '$tech_id' WHERE instance_id = '$instance_id'";
	mysql_query($sql_input,$connection);
}

echo "<br><br><br>";

$sql_output = "SELECT * FROM current_orders";
$sql_result = @mysql_query($sql_output,$connection);

while ($row = mysql_fetch_array($sql_result)){

	if($row[tech_id]){
		echo "$row[tech_id] - $row[timeslot] - $row[acct_num] - $row[acct_name]<br>";
	}else{
		echo "UNASSIGNED - $row[timeslot] - $row[acct_num] - $row[acct_name]<br>";
	}
	
}

echo "<br><br><br>";

//FIND AND DISPLAY TECHS AND SKILLS
$sql_output = "SELECT tech_id, COUNT(*) FROM current_orders GROUP BY tech_id";
$sql_result = @mysql_query($sql_output,$connection);

while ($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)){
	print_r($row);
	echo "<br>";
}



?> 

</BODY>
</HTML>