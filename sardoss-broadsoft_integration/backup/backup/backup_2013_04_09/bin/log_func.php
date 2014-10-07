
<?php 
session_start();

function verify_access($access_level){

	$user_access_level = $_SESSION[user_access];
	
	if(is_null($_SESSION[username])){
		header("location:/tracker/main_login.php");
	}
	elseif($user_access_level < $access_level){
		header("location:/tracker/no_access.php");
	}
}

function create_entry($sql_input,$connection){
		$in_result = @mysql_query($sql_input,$connection) or die(mysql_error());
	}

function display_output($sql_output,$connection){

	$out_result = @mysql_query($sql_output,$connection) or die(mysql_error());
			
	echo "<table cellpadding=\"3\" width=\"100%\" id=\"logtable\"><tr><th class=\"log\" style=\"width:20%\" align=\"left\">Date/Time</th><th class=\"log\" style=\"width:10%\" align=\"left\">User</th><th class=\"log\" align=\"left\">Note</th><th class=\"log\" align=\"left\">Mins</th></tr>";

	while ($row = mysql_fetch_array($out_result)){
	
		$timestamp = $row['timedate'];
		$entry = $row['entries'];
		$user = $row['user'];
		$acct_num = $row['acct_num']; 
		$status = $row['status'];
		$trouble = $row['trouble'];
		$time_spent = $row['time_spent'];
		
		echo "<tr>";
		echo "<td id=\"leftlog\" valign=\"top\" align =\"right\">$timestamp</td><td id=\"leftlog\" valign=\"top\" align=\"center\">$user</td><td id=\"rightlog\" align=\"left\">$entry</td><td id=\"rightlog\" align=\"left\">$time_spent</td>";
		echo "</tr>";
	}
		
	echo "</table><br>";
		
}


?>

