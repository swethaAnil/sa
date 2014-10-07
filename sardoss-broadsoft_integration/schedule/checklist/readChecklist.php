<?php

//GLOBAL OPTIONS
require '../bin/log_func.php';
require '../bin/dbinfo.inc.php';
require 'checklist_func.php';

$username = $_SERVER['PHP_AUTH_USER'];

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$order_num = $_GET['order_num'];

$orderChecklistSQL = "SELECT checklist,checklist_percentage,acct_num,acct_name,activation_date,order_type FROM $ordertable WHERE order_num = '$order_num'";
$orderChecklistResult = mysql_query($orderChecklistSQL,$connection) or die(mysql_error());
$orderChecklist = mysql_fetch_row($orderChecklistResult);

$question_list = $orderChecklist[0];
$checklist_percentage = $orderChecklist[1];
$acct_num = $orderChecklist[2];
$acct_name = $orderChecklist[3];
$activation_date = $orderChecklist[4];
$order_type = $orderChecklist[5];

?>

<html>

<body>

<div id="viewShell"></id>
<div id="viewChecklist">
<form name="checklist" id="checklistForm" method="POST">
<?php


//remove comma from end of question list string if present
if(substr($question_list,-1) == ","){
	$question_list = substr($question_list,0,-1);
}

//echo $question_list;
$checklist_questions_SQL = "SELECT * FROM checklist_questions WHERE qID in ($question_list) ORDER BY FIND_IN_SET(qID,'$question_list')";
$checklist_questions_result = mysql_query($checklist_questions_SQL,$connection) or die(mysql_error());

$checklist_html = "<ul style=\"position:relative;left:-30px;list-style:none;\">";
$qNum = 1;
while($question = mysql_fetch_array($checklist_questions_result)){
	$answer = NULL;
	$qID = $question['qID'];
		
	$answer_data_SQL = "SELECT * FROM checklist_data WHERE order_num = '" . $order_num . "' AND qID = '" . $qID . "'";
	$answer_data_result = mysql_query($answer_data_SQL,$connection);
	
	while($data = mysql_fetch_array($answer_data_result)){
		$answer = $data;
	}
	
	$question_count++;
	$checklist_html .= "<li><b>$qNum. $question[full_text]</b>";
	
	$checklist_html .= "<br><blockquote>Answer: <b><i>$answer[answer]</i></b><br>Comments: <b><i>$answer[comments]</i></b>";
	$checklist_html .= "<br>Last updated by <b><i>$answer[last_user]</i></b> at <b><i>$answer[timedate]</i></b></blockquote>";
	
	$checklist_html .= "</li><hr />";
	$qNum++;
}

$checklist_html .= "</ul>";

echo "<br><b>ORDER CHECKLIST</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $question_count . " Questions (" . $checklist_percentage . "% Complete)<hr>";
echo "<b>Account: $acct_num - $acct_name<br>Order Type: $order_type<br>Order Number: $order_num<br>Activation Date: $activation_date</b><hr><br>";
echo $checklist_html;



?>

</form>
</div>

</body>
</html>