<?php

//GLOBAL OPTIONS
include '../bin/log_func.php';
include '../bin/dbinfo.inc.php';

//session_start();
//verify_access(1);

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$sql_output = "SELECT * FROM " . $logtable . " WHERE order_num = '" . $_GET['order_num'] . "' ORDER BY timedate DESC";
$order_notes_result = mysql_query($sql_output,$connection);

$order_num = $_GET['order_num'];

display_output($sql_output,$connection,$order_num);
	
?>






