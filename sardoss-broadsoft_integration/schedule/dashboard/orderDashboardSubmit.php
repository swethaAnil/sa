<?php
//GLOBAL OPTIONS
include '../bin/log_func.php';
include '../bin/dbinfo.inc.php';

$user = $_SERVER['PHP_AUTH_USER'];

//session_start();
//verify_access(1);

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

if($_POST['entry'] != NULL){

	$clean_entry = stripslashes($_POST['entry']);
	$clean_entry = mysql_real_escape_string($clean_entry);
	
	if($_POST['time_spent']){
		$sql_input = "INSERT INTO $logtable (entries,user,order_num,time_spent) VALUES ('$clean_entry','$user','$_POST[order_num]','$_POST[time_spent]')";
	}else{
		$sql_input = "INSERT INTO $logtable (entries,user,order_num) VALUES ('$clean_entry','$user','$_POST[order_num]')";
	}
	
	create_entry($sql_input,$connection);//input data from from if data present
	
}


if($_GET['entry'] != NULL){

	$clean_entry = stripslashes($_GET['entry']);
	$clean_entry = mysql_real_escape_string($clean_entry);
	
	if($_GET['time_spent']){
		$sql_input = "INSERT INTO $logtable (entries,user,order_num,time_spent) VALUES ('$clean_entry','$user','$_GET[order_num]','$_GET[time_spent]')";
	}else{
		$sql_input = "INSERT INTO $logtable (entries,user,order_num) VALUES ('$clean_entry','$user','$_GET[order_num]')";
	}
	
	create_entry($sql_input,$connection);//input data from from if data present
	
}


if($_GET['fsp_contact']){
	$sql_fsp_contact_in = "UPDATE $ordertable SET fsp_info = '$_GET[fsp_contact]' WHERE `current_orders`.`instance_id` = '$_GET[instance_id]' LIMIT 1";
	create_entry($sql_fsp_contact_in,$connection);
	
}

if($_GET['iad_name']){
	$sql_iad_in = "UPDATE $ordertable SET iad_name = '$_GET[iad_name]' WHERE `current_orders`.`instance_id` = '$_GET[instance_id]' LIMIT 1";
	create_entry($sql_iad_in,$connection);
	
}




?>


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

