<?php
session_start();
$error = 0;
//DEFINE DATE VARIABLE
if($_SESSION['data']){
	$data = $_SESSION['data'];
	//print_r($data);
	
}else{
	echo "<h1>ERROR:  NO DATA FOUND.  TRY AGAIN!  <h1>";
	$error = 1;
}
$upload_date = date("Y-n-j");

function get_date_elements($date){

	$new_date = split("-",$date);
	
	return $new_date;
}

function remove_specials($value){

	$new_value = mysql_real_escape_string(str_replace("'", "", $value));
	return $new_value;
}

require '../../bin/dbinfo.inc.php';


?>


<html>

<style type="text/css">
#customers
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:100%;
border-collapse:collapse;
}
#customers td, #customers th 
{
font-size:0.75em;
white-space: nowrap;
border:1px solid #191970;
padding:3px 7px 2px 7px;
}
#customers th 
{
font-size:1em;
white-space: nowrap;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#191970;
color:#ffffff;
}
#customers tr.alt td 
{
color:#000000;
background-color:#EAF2D3;
}
</style>



<body>

<H3>This data has been uploaded to the 2.0 Order Database ('current_v2_orders')</H3>
<INPUT TYPE="BUTTON" VALUE="View/Change Settings" ONCLICK="window.location.href='schedule_settings.php'"></FORM>
<br><br>

<table id="customers">
<tr>
<th>ACTION TAKEN</th>
<th>Account Name</th>
<th>Account Number</th>
<th>Access Type</th>
<th>Order Number</th>
<th>Order Type</th>
<th>Market</th>
<th>Package</th>
<th>Activation Date</th>
<th>Timeslot</th>
<th>Status</th>
<th>Bandwidth</th>
</tr>




<?php

if($error != 1){  //ERROR FOR NO DATE 
	//CONNECT TO MYSQL
	$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die('Connect to SQL Fail' . mysql_error());

	//Select database
	mysql_select_db($databasename) or die('Select DB FAIL: ' . mysql_error());

	

	foreach( $data as $row ) { 
		
		$result = NULL;
		
		$acct_name = remove_specials($row['acct_name']);
		$acct_num = remove_specials($row['acct_num']);
		$access_type = remove_specials($row['access_type']);
		$order_num = remove_specials($row['order_num']);
		$order_type = remove_specials($row['order_type']);
		$market = remove_specials($row['market']);
		$package = remove_specials($row['package']);
		$activation_date = remove_specials($row['activation_date']);
		$timeslot = remove_specials($row['timeslot']);
		$status = remove_specials($row['status']);
		$bandwidth = remove_specials($row['bandwidth']);
		
		
		//define SQL find order query
		$SQL_find_order = "SELECT * FROM $v2ordertable WHERE order_num = '$order_num'";
		$find_order_result = mysql_query($SQL_find_order);
		$order_count = mysql_num_rows($find_order_result);
		
		if($order_count == 1){
		
			//define SQL update query
			$SQL_update = "UPDATE $v2ordertable SET siebel_order_status = '$status', bandwidth = '$bandwidth', upload_date = '$upload_date',activation_date = '$activation_date',access_type = '$access_type',order_type = '$order_type',package = '$package',timeslot = '$timeslot' WHERE order_num = '$order_num'";
			$update_result = mysql_query($SQL_update);
			$result = "UPDATED";
		}elseif($order_count == 0 ){
			//define SQL full insert query
			$SQL_insert = "INSERT IGNORE INTO $v2ordertable (siebel_order_status, bandwidth, tech_id, upload_date, activation_date, acct_name, acct_num, access_type, order_num, order_type, market, package, timeslot) VALUES ('$status', '$bandwidth', '1000','$upload_date','$activation_date','$acct_name','$acct_num','$access_type','$order_num','$order_type','$market','$package','$timeslot')";
			$insert_result = mysql_query($SQL_insert)or die(mysql_error());
			$result = "ADDED";
		}else{
			$result = "ERROR: DUPLICATE!";
		}
		?>
		<tr>
		<td><?php echo( $result ); ?></td>
		<td><?php echo( $row['acct_name'] ); ?></td>
		<td><?php echo( $row['acct_num'] ); ?></td>
		<td><?php echo( $row['access_type'] ); ?></td>
		<td><?php echo( $row['order_num'] ); ?></td>
		<td><?php echo( $row['order_type'] ); ?></td>
		<td><?php echo( $row['market'] ); ?></td>
		<td><?php echo( $row['package'] ); ?></td>
		<td><?php echo( $row['activation_date'] ); ?></td>
		<td><?php echo( $row['timeslot'] ); ?></td>
		<td><?php echo( $row['status'] ); ?></td>
		<td><?php echo( $row['bandwidth'] ); ?></td>
		</tr>
<?php
	}
}
?>

</table>




</body>
</html>

