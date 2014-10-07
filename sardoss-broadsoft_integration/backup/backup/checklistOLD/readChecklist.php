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
$checklist_questions_result = build_checklist($order_num,$connection);

?>

<html>

<body>

<div id="viewShell"></id>
<div id="viewChecklist">
<form name="checklist" id="checklistForm" method="POST">
<?php

$question_count = 0;
$checklist_html = NULL;

$checklist_html = "<ul style=\"position:relative;left:-30px;list-style:none;\">";

while($question = mysql_fetch_array($checklist_questions_result)){
	$answer = NULL;
	$qID = $question['qID'];
	
	$answer_data_SQL = "SELECT * FROM checklist_data WHERE order_num = '" . $order_num . "' AND qID = " . $qID;
	$answer_data_result = mysql_query($answer_data_SQL,$connection);
	
	while($data = mysql_fetch_array($answer_data_result)){
		
		$answer = $data;
		
		
	}
	
	$question_count++;
	$checklist_html .= "<li><b>$question[full_text]</b>";
	
	$checklist_html .= "<br><blockquote>Answer: <b><i>$answer[answer]</i></b><br>Comments: <b><i>$answer[comments]</i></b>";
	$checklist_html .= "<br>Last updated by <b><i>$answer[last_user]</i></b> at <b><i>$answer[timedate]</i></b></blockquote>";
	
	$checklist_html .= "</li><hr />";
	
}

$checklist_html .= "</ul>";

$getPercentageSQL = "SELECT acct_num, acct_name, order_num, checklist_percentage, activation_date, order_type FROM $ordertable WHERE order_num = '$order_num'";
$getPercentageResult = @mysql_query($getPercentageSQL,$connection);
while ($row = mysql_fetch_array($getPercentageResult)){
	$checklist_percentage = $row['checklist_percentage'];
	$acct_num = $row['acct_num'];
	$order_num = $row['order_num'];
	$acct_name = $row['acct_name'];
	$activation_date = $row['activation_date'];
	$order_type = $row['order_type'];
}
echo "<br><b>ORDER CHECKLIST</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $question_count . " Questions (" . $checklist_percentage . "% Complete)<hr>";
echo "<b>Account: $acct_num - $acct_name<br>Order Type: $order_type<br>Order Number: $order_num<br>Activation Date: $activation_date</b><hr><br>";
echo $checklist_html;



?>

</form>
</div>

</body>
</html>