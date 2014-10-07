<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php
//GLOBAL OPTIONS
require '../bin/log_func.php';
require '../bin/dbinfo.inc.php';

$username = $_SERVER['PHP_AUTH_USER'];

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

if($_GET['instance_id']){
	$order_output = "SELECT * FROM tcps_orders WHERE instance_id = '$_GET[instance_id]'";
}else{
	echo "ERROR: No order ID provided.";
	exit;
}

//GET ALL ORDER INFO
$order_result = @mysql_query($order_output,$connection);

while ($row = mysql_fetch_array($order_result)){

	$upload_date = $row['upload_date'];
	$activation_date = $row['activation_date'];
	$display_date = date('l M j Y', strtotime($activation_date));
	$order_num = $row['order_num'];
	$acct_num = $row['acct_num'];
	$acct_name = $row['acct_name'];
	$order_type = $row['order_type'];
	$timeslot = $row['timeslot'];
	$tech = $row['tcps_tech_id'];
	$status = $row['status'];
	$checklist_percentage = $row['checklist_percentage'];
	$num_phones = $row['num_phones'];
	$build_time = $row['build_time'];
	$activation_time = $row['activation_time'];
	$activation_issues = $row['activation_issues'];
	$implementation_vendor = $row['implementation_vendor'];
	
	$tech_info = NULL;
	if($tech == "1000"){
		$tech_info = "UNASSIGNED";
	}else{
		$tech_sql = "SELECT * FROM `techs` WHERE `tech_id` = $tech";
		$tech_result = @mysql_query($tech_sql,$connection);

		while ($tech_data = mysql_fetch_array($tech_result)){
			$tech_info = $tech_data[first_name] . " " . $tech_data[last_name];
		}
	}
}


$instance_id = $_GET['instance_id'];

?>

<html>

<body>
<center>
<form id="dataForm" method="POST">
<input type="hidden" name="instance_id" value="<?php echo $instance_id; ?>">
<table width="100%">
<tr><td>TCPS Tech: <b><?php echo $tech_info; ?></b></td><td>...........................</td><td> 
<select name="tcps_tech_id">
	<option value=""></option>
	<option value="1060">Darren Foy</option>
	<option value="1100">John Carder</option>
	<option value="1105">John Cornelius</option>
	<option value="1130">Kevin Head</option>
	<option value="1135">Kevin Kosmicki</option>
	<option value="1160">Quinton Burton</option>
	<option value="1195">Ryan Lewis</option>
	<option value="1212">Tavares Wilson</option>
	<option value="1000">UNASSIGNED</option>
</select></td></tr>

<tr><td>Number of phones: <b><?php echo $num_phones; ?></b></td><td>...........................</td><td><input type="text" name="num_phones" ></td></tr>

<tr><td>Build Time: <b><?php echo $build_time; ?></b></td><td>...........................</td><td><input type="text" name="build_time" ></td></tr>

<tr><td>Build Issues: <b><?php echo $build_issues; ?></b></td><td>...........................</td><td><input type="text" name="build_issues" ></td></tr>

<tr><td>Implementation Vendor: <b><?php echo $implementation_vendor; ?></b></td><td>...........................</td><td><input type="text" name="implementation_vendor" ></td></tr>

<tr><td>Activation Time: <b><?php echo $activation_time; ?></b></td><td>...........................</td><td><input type="text" name="activation_time" ></td></tr>

<tr><td>Activation Issues: <b><?php echo $activation_issues; ?></b></td><td>...........................</td><td><input type="text" name="activation_issues" ></td></tr>

<tr><td>TCPS Activation Status: <b><?php echo $status; ?></b></td><td>...........................</td><td>
<select name="status" >
	<option value=""></option>
	<option value="Build Not Started">Build Not Started</option>
	<option value="Build Issue">Build Issue</option>
	<option value="Build Complete">Build Complete</option>
	<option value="Activation Complete">Activation Complete</option>
	<option value="Order Cancelled">Order Cancelled</option>
</select></td></tr>

</table>
</form>

</body>
</html>


