<?php

include '../../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());


//print_r($_POST);
$count = 0;
$curr_dateID = NULL;
$successCount = 0;
foreach($_POST as $key => $value){
	
	$keyParts = explode("_",$key);
	$dateID = $keyParts[0];	
	$column = $keyParts[1];
	if($key == "submit"){ continue; }
	$count++;
	if($column == "active"){
		if($value == "on"){ $value = 1; }elseif($value == "off"){ $value = 0; }
	}
	$updateSQL = "UPDATE tcps_time_slots SET $column = '$value' WHERE dateID = '$dateID'";
	$updateResult = mysql_query($updateSQL,$connection) or die(mysql_error());
	if($updateResult == 1){$successCount++;}
}

echo "<br /><br />";

if($count == $successCount){
	echo "All records updated successfully. Re-directing to TCPS Manager Menu.";
}else{
	echo "Some record updates failed. Re-directing to TCPS Manager Menu.";
}

?>

<html>
<head>

     <meta http-equiv="Refresh" content="5;url='./'" />
</head>
<title>SA Schedule - TCPS TIME SLOTS UPDATE SUBMIT</title>
<body>
<br><br><br>


</body>
</html> 