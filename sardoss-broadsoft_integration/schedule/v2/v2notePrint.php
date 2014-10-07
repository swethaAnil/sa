<?php
//GLOBAL OPTIONS
include '../bin/log_func.php';
include '../bin/dbinfo.inc.php';

//session_start();
//verify_access(1);

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$query = "SELECT * FROM " . $logtable . " WHERE order_num = '" . $_GET['order_num'] . "' ORDER BY timedate ASC";

$out_result = @mysql_query($query,$connection) or die(mysql_error());



echo "Activity Log for Order $_GET[order_num]<br><br>";

echo "<table>";

while ($row = mysql_fetch_array($out_result)){

	$timestamp = substr($row['timedate'],0,-3);
	$entry = $row['entries'];
	$user = $row['user'];
	$acct_num = $row['acct_num']; 
	$order_num = $row['order_num'];
	
		
	if(empty($user)){
	}elseif($entry != NULL){	
		echo "<tr>";
		echo "<td>$timestamp - $user - $entry</td>";
		echo "</tr><tr></tr>";
	}
}
	
echo "</table>";

?>




