<?php


include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$instance = $_POST['instance_id'];
$order_num = $_POST['order_num'];

if($_POST['status']){
	$status = $_POST['status'];
	$status_update = "UPDATE current_orders SET status = '$status' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
	
}elseif($_POST['status'] == "Pending"){
	$status_update = "UPDATE current_orders SET status = 'NULL' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
	
}

if($_POST['update']){
	if($_POST['newtech']){
		$new_tech = $_POST['newtech'];
		$assign_update = "UPDATE current_orders SET tech_id = '$new_tech' WHERE instance_id = '$instance'";
		mysql_query($assign_update, $connection);
	}

	if($_POST['newtime']){
		$new_time = $_POST['newtime'];
		if($new_time == "ANYTIME"){ $new_time = NULL; }
		$time_update = "UPDATE current_orders SET timeslot = '$new_time' WHERE instance_id = '$instance'";
		mysql_query($time_update, $connection);
	}
}

echo "<html><head><meta http-equiv=\"REFRESH\" content=\"0;url=order_dashboard.php?order_num=$order_num\"></head></html>";

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



