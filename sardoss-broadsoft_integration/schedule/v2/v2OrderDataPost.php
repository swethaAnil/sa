<?php
//GLOBAL OPTIONS
require '../bin/log_func.php';
require '../bin/dbinfo.inc.php';

$username = $_SERVER['PHP_AUTH_USER'];

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$SQLupdate = "UPDATE $v2ordertable SET ";
$count = 0;
foreach($_POST as $key => $value){
	
	if($value != "" && $key != "instance_id"){
	
		if($count == 0){
			$SQLupdate .= $key . " = '$value'";
		}else{
			$SQLupdate .= "," . $key . "= '$value'";
		}
		$count++;
	}
}

$SQLupdate .= " WHERE instance_id = '$_POST[instance_id]'";

if($count > 0){

	mysql_query($SQLupdate,$connection);
	echo $SQLupdate;
}



	


?>

