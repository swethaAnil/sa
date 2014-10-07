<html>

<title>SA Schedule - Assignment Change</title>
<head>
     <!--[if IE]>
<link rel="stylesheet" type="text/css" href="tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="tracker_style.css" />
<!--<![endif]-->

</head>
<style type="text/css">

body{
	background-color:#F6F6F6;
}

table, td, th{
	border-collapse:collapse;
	border-style:solid;
	border:1px solid;
	border-color:#4D4D4D;
	font-family:"arial";
	font-size:10px;
}


</style>


<body>
<center>



<?php


include 'bin/dbinfo.inc.php';


//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$instance = $_POST[assign_data];

//GET ORDER INFO
$order_output = "SELECT * FROM current_orders WHERE instance_id='$instance'";
$order_result = mysql_query($order_output,$connection);
while($order = mysql_fetch_array($order_result)){
	$acct_num = $order['acct_num'];
	$acct_name = $order['acct_name'];
	$order_num = $order['order_num'];
	$tech_id = $order['tech_id'];
}

//GET CURRENT TECH INFO
$tech_output = "SELECT * FROM techs WHERE tech_id = '$tech_id'";
$tech_result = mysql_query($tech_output,$connection);
while($tech = mysql_fetch_array($tech_result)){
	$tech_info = $tech['first_name'] . " " . $tech['last_name'];
}
echo "<br><br><b>Current Assignment</b><br>";
echo "<table border=\"1\"><tr><td>Account Name</td><td>$acct_name</td></tr><tr><td>Account Number</td><td>$acct_num</td></tr><tr><td>Order Number</td><td>$order_num</td></tr><tr><td>SA Tech</td><td>$tech_info</td></tr></table><br>";

//GET ALL TECHS INFO
$tech_output = "SELECT * FROM techs WHERE tech_id = '$tech_id'";
$tech_result = mysql_query($tech_output,$connection);
while($tech = mysql_fetch_array($tech_result)){
	$tech_info = $tech['first_name'] . " " . $tech['last_name'];
}
?>

<form action="submit_assignment_change.php" method="post">
<b>UPDATE STATUS</b><br>
<input type="radio" name="status" value="Pending" />Pending 
<input type="radio" name="status" value="Complete" />Complete 
<input type="radio" name="status" value="Cancelled" />Cancelled<br><br>


<?php 
//SET HIDDEN INSTANCE VALUE FOR FORM
echo "<input type=\"hidden\" name=\"instance\" value=\"$instance\" />";

echo "<b>RE-ASSIGN: </b>";
echo "<select name=\"newtech\">";
echo "<option value=\"\"></option>";

//GET CURRENT TECH INFO
$techs_output = "SELECT * FROM techs ORDER BY first_name";
$techs_result = mysql_query($techs_output,$connection);
while($tech = mysql_fetch_array($techs_result)){
	echo "<option value=\"" . $tech['tech_id'] . "\">" . $tech['first_name'] . " " . $tech['last_name'] . "</option>";
}
echo "<option value=\"1000\">OVERFLOW</option>";
?>
</select><br><br>

<b>CHANGE TIMESLOT: </b>
<select name="newtime">
	<option value=""></option>
	<option value="830">8:30AM</option>
	<option value="930">9:30AM</option>
	<option value="1030">10:30AM</option>
	<option value="1130">11:30AM</option>
	<option value="1230">12:30PM</option>
	<option value="1330">1:30PM</option>
	<option value="1430">2:30PM</option>
	<option value="1530">3:30PM</option>
	<option value="1630">4:30PM</option>
	<option value="1730">5:30PM</option>
	<option value="1830">6:30PM</option>
	<option value="1930">7:30PM</option>
	<option value="ANYTIME">ANYTIME</option>
</select><br><br>

<input type="submit" value="Submit">
</form>
<FORM><INPUT TYPE="BUTTON" VALUE="Cancel" ONCLICK="window.location.href='current_schedule.php'"></FORM>




	

</body>
</html>

  

  

