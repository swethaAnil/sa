


<?php

$starttime = microtime(true);
$databasename='service_activations'; // Name of the database
$mysqladd='localhost'; // Address to the MySQL Server - Usually localhost or an IP address
$mysqluser='query_user'; // Your MySQL UserName
$mysqlpass='456567'; // Your MySQL Password

$ordersTable='current_orders'; // Name of the order activity table
$ordersDevTable='orders_dev'; // Name of the orders table

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());



if($_POST['query']){

	$sql_query = $_POST['query'];
	echo "<i>Result from query: $sql_query</i><br /><br />";
	$sql_result = mysql_query($sql_query,$connection) or die(mysql_error());
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

$endtime = microtime(true);
$duration = $endtime - $starttime; //calculates total time taken
$queryTime = round($duration * 1000,2);

echo "<div style=\"font-size:80%;position:relative;margin-top:40px;left:800px;margin-bottom:30px;\">Query time: $queryTime ms</div>";

?>

