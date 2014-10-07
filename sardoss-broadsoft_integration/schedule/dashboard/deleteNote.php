<?php

//GLOBAL OPTIONS
include '../bin/dbinfo.inc.php';

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

if($_GET['noteID']){
	$SQL = "DELETE FROM " . $logtable . " WHERE noteID = '" . $_GET['noteID'] . "'";
	$result = mysql_query($SQL,$connection) or die(mysql_error());
	echo $result[0];
}


?>





