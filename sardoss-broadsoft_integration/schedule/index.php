<?php
$useragent = $_SERVER['HTTP_USER_AGENT']; //get the user agent

if(strpos($useragent,"MSIE")){
	header("Location: ./current_schedule_IE.php");
}else{
	header("Location: ./current_schedule.php");
}



?>

