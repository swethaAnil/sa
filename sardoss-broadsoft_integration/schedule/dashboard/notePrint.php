<?php
//GLOBAL OPTIONS
include '../bin/log_func.php';
include '../bin/dbinfo.inc.php';

//session_start();
//verify_access(1);

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

function remove_zero($num){

	if(substr($num,0,1) == 0){
		$num = substr($num,1);
	}
	
	return $num;
}

$query = "SELECT * FROM " . $logtable . " WHERE order_num = '" . $_GET['order_num'] . "' ORDER BY timedate ASC";

$out_result = @mysql_query($query,$connection) or die(mysql_error());

//$url = "http://sa.cbeyond.net/schedule/checklist/readChecklist.php?order_num=" . $_GET['order_num'];

echo "--- Service Activations Activity Log for Order $_GET[order_num] ---<br><br>";
//echo "Order checklist: $url<br><br>";

echo "<table>";

while ($row = mysql_fetch_array($out_result)){

	$timestamp = substr($row['timedate'],0,-3);
	$year = substr($timestamp,0,4);
	$day = remove_zero(substr($timestamp,8,2));
	$month = remove_zero(substr($timestamp,5,2));
	$time = substr($timestamp,11,5);
	$entry = $row['entries'];
	$user = $row['user'];
	$acct_num = $row['acct_num']; 
	$order_num = $row['order_num'];
	
		
	if(empty($user)){
	}elseif($entry != NULL){	
		echo "<tr>";
		echo "<td>" . $month . "/" . $day . "/" . $year . " " . $time . " - " . $user . ": " . $entry . "</td>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "</tr><tr></tr>";
	}
}
	
echo "</table>";

?>




