<html>

<title>SA Schedule - Assignment Change</title>
<head>
     <link rel="stylesheet" href="schedule_style.css" type="text/css">
</head>


<body>
<center>



<?php


$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='webserver'; // Your MySQL UserName
$mysqlpass='345456'; // Your MySQL Password

$databasename='service_activations'; // Name of the schedule database

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
echo "<b>Current Assignment</b><br>";
echo "<table><tr><td>Account Name</td><td>$acct_name</td></tr><tr><td>Account Number</td><td>$acct_num</td></tr><tr><td>Order Number</td><td>$order_num</td></tr><tr><td>SA Tech</td><td>$tech_info</td></tr></table><br>";

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
<input type="submit" value="Submit">
</form>
<FORM><INPUT TYPE="BUTTON" VALUE="Cancel" ONCLICK="window.location.href='current_schedule.php'"></FORM>




	

</body>
</html>

  

  

