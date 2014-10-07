<html>
<head>

<title>2.0 Manager Menu - Service Activations</title>
<style>

body{
	
	text-align:center;

}

</style>

</head>

<body>


<br><br>
<h4>Service Activations Online Schedule</h4>
<h2>2.0 Manager Menu</h2>
<br><br>
<a href="v2_order_upload.htm">UPLOAD NEW 2.0 ORDER LIST</a>
<br><br>
<a href="add_v2_order.htm">ADD INDIVIDUAL 2.0 ORDER</a>
<br><br>
<a href="export_v2_orders.php">EXPORT EXCEL SPREADSHEET OF ALL 2.0 ORDERS</a>

<br><br><br>

<?php

require '../../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

//create techs array
$techs_array = array();
$tech_output = "SELECT * FROM techs";
$tech_result = mysql_query($tech_output,$connection);
while($tech = mysql_fetch_array($tech_result)){
	$id = $tech['tech_id'];
	$techs_array[$id] = $tech['first_name'] . " " . $tech['last_name'];
}
$techs_array['1000'] = "UNASSIGNED";

echo "<br><br>";
echo "<table border=\"1\">";
echo "<tr><th colspan=\"2\"># ORDERS ASSIGNED</th></tr>";
$tech_totals_SQL = "SELECT tech_id, COUNT(instance_id) FROM $v2ordertable GROUP BY tech_id";
$tech_totals_result = mysql_query($tech_totals_SQL,$connection);
while($row = mysql_fetch_array($tech_totals_result)){
	$tech_id = $row['tech_id'];
	echo "<tr><td>" . $techs_array[$tech_id] . "</td><td>" . $row['COUNT(instance_id)'] . "</td></tr>";
}

echo "</table>";

/* if($_POST['query']){
	echo "<br><hr><br><br>";
	$sql_query = $_POST['query'];
	$sql_result = mysql_query($sql_query,$connection);
	echo "<table border=1 style=\"border-collapse:collapse;white-space:nowrap;\">";
	$count=0;
	while($row = mysql_fetch_array($sql_result, MYSQL_ASSOC)){
		while($count == 0){
			$headers = array_keys($row);
			echo "<tr>";
			foreach($headers as $header){
				echo "<th>$header</th>";
			}
			echo "</tr>";
			$count++;
		}
		echo "<tr>";
		foreach($row as $object){
			echo "<td>$object</td>";
		}
		echo "</tr>";
	}
	echo "</table><br><br><br>";
} */

?>
</body>
</html> 