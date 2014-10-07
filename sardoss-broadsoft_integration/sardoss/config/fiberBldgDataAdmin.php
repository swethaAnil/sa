
<?php
require 'dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

if($_POST){
	
	
	if(strlen($_POST['siteID']) < 4){
		$error = "ERROR: Site ID (\"" . $_POST['siteID'] . "\") is invalid!";
	}elseif(strlen($_POST['vlan']) < 3){
		$error = "ERROR: VLAN ID (\"" . $_POST['vlan'] . "\") is invalid!";
	}else{
				
		$checkDBSQL = "SELECT * FROM " . $fiberBldgDatabase . " WHERE siteID ='" . $_POST['siteID'] . "'";
		$checkResult = mysql_query($checkDBSQL,$connection);
		$checkMatchCount = mysql_num_rows($checkResult);
		
		if($checkMatchCount > 0){
			$error = "ERROR: Site ID (\"" . $_POST['siteID'] . "\") already exists!";
		}else{
		
			$addEntrySQL = "INSERT INTO " . $fiberBldgDatabase . " (siteID,VLAN) VALUES ('" . $_POST['siteID'] . "','" . $_POST['vlan'] . "')";
			$addResult = mysql_query($addEntrySQL,$connection);
			if($addResult == 1){ 
				$error = "Database updated successfully (Site " . $_POST['siteID'] . "/VLAN " . $_POST['vlan'] . " added).";
			}else{
				$error = "ERROR: Database updated failed.";
			}
		}
	}
}

?>

<html>
<head>
<title>Fiber Building Data for ReDCON - Admin</title>

<style>
p{color:red;}
</style>
</head>

<body>

<h3>Fiber Building Data for ReDCON - Admin</h3><hr><br>

<b><u>Add new site and logical port (vlan ID) to database</u></b><br><br>
<form action="fiberBldgDataAdmin.php" method="POST">
Site ID: <input type="text" name="siteID" size="8">&nbsp;&nbsp;&nbsp;
Logical Port Name (VLAN ID): <input type="text" name="vlan" size="4">&nbsp;&nbsp;&nbsp;
<input type="submit" name="submit" value="Submit">
</form>

<?php if($error){ echo "<p>$error</p>";} ?>

<br><hr><br>
<b>Current Values</b> ('<?php echo $databasename . "/" . $fiberBldgDatabase; ?>')<br>
<table border=1 style="border-collapse:collapse;">
<tr><th>Site ID</th><th>Logical Port Name (VLAN ID)</th></tr>

<?php

$getFiberDataSQL = "SELECT * FROM " . $fiberBldgDatabase . " ORDER BY siteID";
$FiberData = mysql_query($getFiberDataSQL,$connection);

while($row = mysql_fetch_array($FiberData)){

	echo "<tr><td>" . $row['siteID'] . "</td><td>" . $row['VLAN'] . "</td></tr>";

}


?> 

</table>
</body>
 </html>
 
 <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39762922-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

