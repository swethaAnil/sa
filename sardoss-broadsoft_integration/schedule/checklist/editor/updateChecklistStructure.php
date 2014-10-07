
<?php
include '../../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];

$listname = $_POST['listname'];
$list = $_POST['list'];

$SQL = "UPDATE checklist_structure SET structure = '$list' WHERE listName = '$listname'";
//echo $SQL . "<br>";
$result = mysql_query($SQL,$connection) or die(mysql_error());
//echo $result;

header('Location: ./');

?>

