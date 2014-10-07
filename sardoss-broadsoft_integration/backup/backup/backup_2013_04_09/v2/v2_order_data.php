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
	$order_output = "SELECT * FROM $v2ordertable WHERE instance_id = '$_GET[instance_id]'";
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
	$market = $row['market'];
	$package = $row['package'];
	$timeslot = $row['timeslot'];
	$access_type = $row['access_type'];
	$tech = $row['tech_id'];
	$fsp_info = $row['fsp_info'];
	$iad_name = $row['iad_name'];
	$status = $row['status'];
	$checklist_percentage = $row['checklist_percentage'];
	$bandwidth = $row['bandwidth'];
	$jira_ticket = $row['jira_ticket'];
	$jira_status = $row['jira_ticket_status'];
	$jira_total_mins = $row['jira_total_mins'];
	$siebel_order_status = $row['siebel_order_status'];
	$truck_roll_mins = $row['truck_roll_mins'];
	$survey_completed = $row['survey_completed'];
	$survey_result = $row['survey_result'];
	$tcps = $row['tcps'];
	$tcdc = $row['tcdc'];
	$managed_services = $row['managed_services'];
	
	
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

<form id="dataForm" method="POST">
<input type="hidden" name="instance_id" value="<?php echo $instance_id; ?>">
<table>
<tr><th>Current Values</th><th></th><th>Enter new values here</th></tr>
<tr><td>SA Tech: <b><?php echo $tech_info; ?></b></td><td>...........................</td><td> 
<select name="tech_id">
	<option value=""></option>
	<option value="1007">Andre Ruddock</option>
	<option value="1070">Darryl Jordan</option>
	<option value="1073">Edwin DeLeon</option>
	<option value="1110">Juan Nunez</option>
	<option value="1210">Shane Kirkland</option>
</select></td></tr>

<tr><td>JIRA Ticket: <b><?php echo $jira_ticket; ?></b></td><td>...........................</td><td><input type="text" name="jira_ticket" ></td></tr>


<tr><td>JIRA Ticket Status: <b><?php echo $jira_status; ?></b></td><td>...........................</td><td>
<select name="jira_ticket_status" >
	<option value=""></option>
	<option value="OPEN">OPEN</option>
	<option value="ASSIGNED">ASSIGNED</option>
	<option value="CLOSED">CLOSED</option>
</select></td></tr>

<tr><td>JIRA Total Work Time (mins): <b><?php echo $jira_total_mins; ?></b></td><td>...........................</td><td><input type="text" name="jira_total_mins" ></td></tr>

<tr><td>Activation Truck Roll Time (mins): <b><?php echo $truck_roll_mins; ?></b></td><td>...........................</td><td><input type="text" name="truck_roll_mins" ></td></tr>

<tr><td>TCPS: <b><?php echo $tcps; ?></b></td><td>...........................</td>
<td align="center">
YES<input type="radio" name="tcps" value="YES">&nbsp;&nbsp;&nbsp; NO<input type="radio" name="tcps" value="NO">
</td></tr>

<tr><td>TCDC: <b><?php echo $tcdc; ?></b></td><td>...........................</td>
<td align="center">
YES<input type="radio" name="tcdc" value="YES">&nbsp;&nbsp;&nbsp; NO<input type="radio" name="tcdc" value="NO">
</td></tr>

<tr><td>Managed Services: <b><?php echo $managed_services; ?></b></td><td>...........................</td>
<td align="center">
YES<input type="radio" name="managed_services" value="YES">&nbsp;&nbsp;&nbsp; NO<input type="radio" name="managed_services" value="NO">
</td></tr>

<tr><td>Survey Completed?: <b><?php echo $survey_completed; ?></b></td><td>...........................</td>
<td align="center">
YES<input type="radio" name="survey_completed" value="YES">&nbsp;&nbsp;&nbsp; NO<input type="radio" name="survey_completed" value="NO">
</td></tr>

<tr><td>Survey Result: <b><?php echo $survey_result; ?></b></td><td>...........................</td><td><input type="text" name="survey_result" ></td></tr>

</table>
</form>

</body>
</html>


