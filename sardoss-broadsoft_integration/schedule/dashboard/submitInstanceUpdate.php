<?php


require '../bin/dbinfo.inc.php';
require '../bin/log_func.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$instance = $_POST['instance_id'];
$order_num = $_POST['order_num'];
$username = $_SERVER['PHP_AUTH_USER'];

if($_POST['status']){
	$status = $_POST['status'];
	$status_update = "UPDATE current_orders SET status = '$status' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
	$log_update = "INSERT INTO $logtable (entries,user,order_num) VALUES ('(Status changed to $status)','$username','$order_num')";
	mysql_query($log_update, $connection);
	
}
/*
elseif($_POST['status'] == "Pending"){
	$status_update = "UPDATE current_orders SET status = 'NULL' WHERE instance_id = '$instance'";
	mysql_query($status_update, $connection);
	
}*/

if($_POST['update']){
	if($_POST['newtech']){
	
		$new_tech = $_POST['newtech'];
		$check_tech = mysql_query("SELECT tech_id FROM current_orders WHERE instance_id = '$instance'",$connection);
		$curr_tech = mysql_fetch_row($check_tech);
		
		if($curr_tech[0] != $new_tech){
		
			if($curr_tech[0] == "1000"){ 
				$curr_tech_name = "OVERFLOW";
			}else{
				$curr_tech_name = get_tech_name($curr_tech[0],$connection);
			}
			
			if($new_tech == "1000"){ 
				$new_tech_name = "OVERFLOW";
			}else{
				$new_tech_name = get_tech_name($new_tech,$connection);
			}
			
			$assign_update = "UPDATE current_orders SET tech_id = '$new_tech' WHERE instance_id = '$instance'";
			mysql_query($assign_update, $connection);
			$log_update = "INSERT INTO $logtable (entries,user,order_num) VALUES ('(SA Tech changed from $curr_tech_name to $new_tech_name)','$username','$order_num')";
			mysql_query($log_update, $connection);
		}
	}

	if($_POST['newtime']){
		$new_time = $_POST['newtime'];
		if($new_time == "ANYTIME"){ $post_time = NULL; }else{ $post_time = $new_time; }
		$check_time = mysql_query("SELECT timeslot FROM current_orders WHERE instance_id = '$instance'",$connection);
		$curr_time = mysql_fetch_row($check_time);
		if($curr_time[0] == NULL || $curr_time[0] == 0){ $current_slot = "ANYTIME"; }else{ $current_slot = $curr_time[0]; }
		
		if($curr_time[0] != $post_time){
			$time_update = "UPDATE current_orders SET timeslot = '$post_time' WHERE instance_id = '$instance'";
			mysql_query($time_update, $connection);
			$log_update = "INSERT INTO $logtable (entries,user,order_num) VALUES ('(Time slot changed from $current_slot to $new_time)','$username','$order_num')";
			mysql_query($log_update, $connection);
		}
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



