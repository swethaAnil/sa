<?php

include 'bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());


require_once "excel.php";
    

$fp = fopen("xlsfile://www/schedule/file.xls", "wb");

$assoc = array(
    array("Sales Person" => "Sam Jackson", "Q1" => "$3255", "Q2" => "$3167", "Q3" => 3245, "Q4" => 3943),
    array("Sales Person" => "Jim Brown", "Q1" => "$2580", "Q2" => "$2677", "Q3" => 3225, "Q4" => 3410),
    array("Sales Person" => "John Hancock", "Q1" => "$9367", "Q2" => "$9875", "Q3" => 9544, "Q4" => 10255),
);


fwrite($fp, serialize($assoc));

fclose($fp);



header ("Content-Type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"file.xls\"" );
readfile("xlsfile://www/schedule/file.xls");
exit;

?>

<html>
<head>
</head>
<title>SA Schedule - SETTINGS UPDATE SUBMIT</title>
<body>
<br><br><br>


</body>
</html> 