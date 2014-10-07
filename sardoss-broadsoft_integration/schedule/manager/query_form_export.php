<html>

<body>


<?php


$databasename='service_activations'; // Name of the database
$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='query_user'; // Your MySQL UserName
$mysqlpass='456567'; // Your MySQL Password

$ordersTable='current_orders'; // Name of the order activity table
$ordersDevTable='orders_dev'; // Name of the orders table

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=SA_Database_Query');

if($_POST['query']){
	$sql_query = $_POST['query'];
	$sql_result = mysql_query($sql_query,$connection);
	echo "<table border=1 style=\"border-collapse:collapse;white-space:nowrap;font-size:12px;\">";
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
}


?>


</body>
</html>
