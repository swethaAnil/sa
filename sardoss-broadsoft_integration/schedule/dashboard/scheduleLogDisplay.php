<?php
//GLOBAL OPTIONS
include '../bin/log_func.php';
include '../bin/dbinfo.inc.php';

//session_start();
//verify_access(1);

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$latest_entry = NULL;

$latest_entry_query = "SELECT * FROM $logtable WHERE order_num = '$_GET[order_num]' ORDER BY timedate DESC LIMIT 1";
$latest_entry_result = mysql_query($latest_entry_query,$connection);
while($latest_entry_row = mysql_fetch_array($latest_entry_result)){
	$latest_entry = $latest_entry_row['entries'];
}

if($latest_entry == NULL){
	echo "no notes yet";
}else{
	if(strlen($latest_entry) > 60){
		echo substr($latest_entry,0,60) . "...";
	}else{
		echo $latest_entry;
	}
	
}
	

?>





