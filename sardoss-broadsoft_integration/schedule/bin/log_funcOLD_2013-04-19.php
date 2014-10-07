
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

function display_output($sql_output,$connection,$order_num){

	$notes_sql = "SELECT * FROM order_activity WHERE order_num = '" . $order_num . "' ORDER BY timedate DESC";
	$notes_result = mysql_query($notes_sql,$connection);
	
	echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script>";

	echo "<script>";
	
	while($row = mysql_fetch_array($notes_result)){
	
		echo "$(\"#delete$row[noteID]\").click(function(){
			var answer = confirm('Are you sure you want to delete this entry?');
			if(answer){
				var formData = $('#noteForm$row[noteID]').serialize();
				$.get('deleteNote.php',formData,function(data,status){});
				$(\"#logOutput\").load(\"activityLog.php?order_num=$_GET[order_num]\");
			}
		})
		";
	} 
	echo "</script>";

	$out_result = @mysql_query($sql_output,$connection) or die(mysql_error());
			
	echo "<table cellpadding=\"3\" width=\"100%\" id=\"logtable\"><tr><th class=\"log\" style=\"width:20%\" align=\"left\">Date/Time</th><th class=\"log\" style=\"width:10%\" align=\"left\">User</th><th class=\"log\" align=\"left\">Note</th><th class=\"log\" align=\"left\">Mins</th><th class=\"log\" style=\"width:12px;\" align=\"left\"></th></tr>";

	while ($row = mysql_fetch_array($out_result)){
	
		$timestamp = $row['timedate'];
		$entry = $row['entries'];
		$user = $row['user'];
		$acct_num = $row['acct_num']; 
		$status = $row['status'];
		$trouble = $row['trouble'];
		$time_spent = $row['time_spent'];
		$id = $row['noteID'];
		
		echo "<tr>";
		echo "<td id=\"leftlog\" valign=\"top\" align =\"right\">$timestamp</td><td id=\"leftlog\" valign=\"top\" align=\"center\">$user</td><td id=\"rightlog\" align=\"left\">$entry</td><td id=\"rightlog\" align=\"left\" style=\"width:20px;\">$time_spent</td>";
	 	if($_SERVER['PHP_AUTH_USER'] == $row['user']){
			echo "<td id=\"rightlog\" align=\"left\"><form name=\"noteForm$id\" id=\"noteForm$id\"><input type=\"hidden\" name=\"noteID\" value=\"$id\"></form><a href=\"#\" ><img id=\"delete$id\" value=\"DELETE ENTRY\" src=\"../images/red_x_icon.png\" width=\"12px\" height=\"12px\" align=\"center\" /></a></td>";
		}else{
			echo "<td id=\"rightlog\" align=\"left\"></td>";
		} 
		
		echo "</tr>";
	}
		
	echo "</table><br>";
		
}



function get_tech_name($id,$connection){

	$SQL = "SELECT * FROM techs WHERE tech_id = $id";
	$result = mysql_query($SQL,$connection);
	$row = mysql_fetch_row($result);
	$name = $row[1] . " " . $row[2];
	return $name;

}



?>

