<?php
session_start();
$error = 0;
//DEFINE DATE VARIABLE - NO LONGER APPLICABLE
//if($_POST['month'] && $_POST['day'] && $_POST['year']){
	//$activation_date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
	$data = $_SESSION['data'];
//}else{
	//echo "<h1>ERROR:  NO ACTIVATION DATE PROVIDED.  GO BACK!  <h1>";
	//$error = 1;
//}
$upload_date = date("Y-n-j");

function get_date_elements($date){

	$new_date = split("-",$date);
	
	return $new_date;
}

function remove_specials($value){

	$new_value = mysql_real_escape_string(str_replace("'", "", $value));
	return $new_value;
}

include '../bin/dbinfo.inc.php';


if($error != 1){  //ERROR FOR NO DATE 
	//CONNECT TO MYSQL
	$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die('Connect to SQL Fail' . mysql_error());

	//Select database
	mysql_select_db($databasename) or die('Select DB FAIL: ' . mysql_error());


	$truncate_orders = "TRUNCATE TABLE orders_dev";
	mysql_query($truncate_orders)or die(mysql_error());

	foreach( $data as $row ) { 
		
		$activation_date = remove_specials($row['activation_date']);
		$acct_name = remove_specials($row['acct_name']);
		$acct_num = remove_specials($row['acct_num']);
		$access_type = remove_specials($row['access_type']);
		$order_num = remove_specials($row['order_num']);
		$order_type = remove_specials($row['order_type']);
		$market = remove_specials($row['market']);
		$package = remove_specials($row['package']);
		$timeslot = remove_specials($row['timeslot']);
		
		//define SQL query
		$SQL = "INSERT IGNORE INTO orders_dev (upload_date, activation_date, acct_name, acct_num, access_type, order_num, order_type, market, package, timeslot) VALUES ('$upload_date','$activation_date','$acct_name','$acct_num','$access_type','$order_num','$order_type','$market','$package','$timeslot')";
			
		mysql_query($SQL)or die(mysql_error());
	}
}
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

<H3>This data has been uploaded to the <strong>working copy</strong> of the SA Online Schedule</H3>

<INPUT TYPE="BUTTON" VALUE="View/Change Settings" ONCLICK="window.location.href='schedule_settings.php'"></FORM>
<br><br>

<table id="customers">
<tr>
<th>Activation Date</th>
<th>Account Name</th>
<th>Account Number</th>
<th>Access Type</th>
<th>Order Number</th>
<th>Order Type</th>
<th>Market</th>
<th>Package</th>
<th>Timeslot</th>
</tr>
<?php foreach( $data as $row ) { ?>
<tr>
<td><?php echo( $row['activation_date'] ); ?></td>
<td><?php echo( $row['acct_name'] ); ?></td>
<td><?php echo( $row['acct_num'] ); ?></td>
<td><?php echo( $row['access_type'] ); ?></td>
<td><?php echo( $row['order_num'] ); ?></td>
<td><?php echo( $row['order_type'] ); ?></td>
<td><?php echo( $row['market'] ); ?></td>
<td><?php echo( $row['package'] ); ?></td>
<td><?php echo( $row['timeslot'] ); ?></td>
</tr>
<?php } ?>
</table>




</body>
</html>
