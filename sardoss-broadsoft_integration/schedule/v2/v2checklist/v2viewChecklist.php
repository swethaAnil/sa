<?php

//GLOBAL OPTIONS
require '../../bin/log_func.php';
require '../../bin/dbinfo.inc.php';
require 'v2checklist_func.php';

$username = $_SERVER['PHP_AUTH_USER'];

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$order_num = $_GET['order_num'];

$orderChecklistSQL = "SELECT checklist,checklist_percentage,order_type FROM $v2ordertable WHERE order_num = '$order_num'";
$orderChecklistResult = mysql_query($orderChecklistSQL,$connection);
$orderChecklist = mysql_fetch_row($orderChecklistResult);
 
?>

<head>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>

<script>
$(document).ready(function(){
	
	$("#update").click(function(){
		var formData = $('#checklistForm').serialize();
		$.post('./v2checklist/v2checklistSubmit.php',formData,function(data,status){ 
			if(status == "success"){
				var d = new Date();
				var currTime = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
				$("#updateStatus").text("Successfully updated at " + currTime);			
				$('#viewShell').fadeOut(250).load('./v2checklist/v2viewChecklist.php?order_num=<?php echo $order_num; ?> #viewChecklist').fadeIn(250);
			};
		});
			
	});
	  
});
</script>



</head>



<html>

<body>


<br />
<button id="update" class="button">Save Checklist</button><div style="display:inline;margin-left:15px;font-style:italic;" id="updateStatus"></div>
<div id="viewShell"></id>
<div id="viewChecklist">
<form name="checklist" id="checklistForm" method="POST">
<?php


if($orderChecklist[2] != "New"){

	echo "<br /><br /><div style=\"font-weight:bold;padding-bottom:20px;padding-left:20px;font-size:120%\">No 2.0 checklist for non-New orders at this time.</div><br /><br /><br />";
	exit();

}elseif($orderChecklist[0] == NULL){
	
	$question_list = build_checklist($order_num,$connection);
	$checklist_percentage = $orderChecklist[1];
	
	
}elseif($orderChecklist[0]){

	$question_list = $orderChecklist[0];
	$checklist_percentage = $orderChecklist[1];
	
}

//remove comma from end of question list string if present
if(substr($question_list,-1) == ","){
	$question_list = substr($question_list,0,-1);
}

$checklist_questions_SQL = "SELECT * FROM checklist_questions WHERE qID in ($question_list) ORDER BY FIND_IN_SET(qID,\"$question_list\")";
$checklist_questions_result = mysql_query($checklist_questions_SQL,$connection) or die(mysql_error());

$checklist_html = "<ul style=\"position:relative;left:-15px;list-style:none;\">";

while($question = mysql_fetch_array($checklist_questions_result)){
	
	$question_data_SQL = "SELECT * FROM checklist_data WHERE order_num = '" . $order_num . "' AND qID = " . $question['qID'];
	$question_data_result = mysql_query($question_data_SQL,$connection);
	
	$yes = $no = $na = $green = $red = $yellow = $answer = $comment = NULL;	
	while($datum = mysql_fetch_array($question_data_result)){
		
		$answer = $datum['answer'];
		$comment = $datum['comments'];
		
		switch($answer){
			case "yes":
				$yes = "checked";
				break;
			case "no":
				$no = "checked";
				break;
			case "na":
				$na = "checked";
				break;
			case "green":
				$green = "checked";
				break;
			case "yellow":
				$yellow = "checked";
				break;
			case "red":
				$red = "checked";
				break;
			case "on":
				$on = "checked";
				break;
		}
	}
	
	$question_count++;
	$checklist_html .= "<li><div style=\"max-width:300px;float:left;font-weight:bold;\">" . $question['full_text'] . "</div>";
	
	switch($question['form_type']){
		case "checkbox":
			$checklist_html .= "&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"" . $question['qID'] . "_answer\" $on><br />";
			break;
		
		case "yesno":
			$checklist_html .= "<table style=\"display:inline;float:right;margin-right:30px;top:-5px;position:relative;\"><tr><td style=\"font-weight:bold;text-align:center;\">YES</td><td style=\"font-weight:bold;text-align:center;\">NO</td></tr>
			<tr><td><input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"yes\" $yes></td>
			<td><input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"no\" $no></td></tr></table>";
			break;
		
		case "yesnona":
			$checklist_html .= "<table style=\"display:inline;float:right;margin-right:30px;top:-5px;position:relative;\"><tr><td style=\"font-weight:bold;text-align:center;\">YES</td><td style=\"font-weight:bold;text-align:center;\">NO</td><td style=\"font-weight:bold;text-align:center;\">N/A</td></tr>
			<tr><td><input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"yes\" $yes></td>
			<td><input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"no\" $no></td>
			<td><input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"na\" $na></td></tr></table>";
			break;
		
		case "greenred":
			$checklist_html .= "<br /><hr /><div style=\"position:relative;text-align:center;margin-bottom:10px;font-size:110%;font-weight:bold;\">&nbsp;&nbsp;&nbsp;GREEN<input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"green\" $green>&nbsp;&nbsp;&nbsp;YELLOW<input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"yellow\" $yellow>&nbsp;&nbsp;&nbsp;RED<input type=\"radio\" class=\"radio\" name=\"" . $question['qID'] . "_answer\" value=\"red\" $red></div>";
			break;
			
	}
	
	if($question['comments'] == 1){
		$checklist_html .= "<div style=\"margin-left:15px;position:relative;top:-5px;clear:both;\">Comments: <input type=\"text\" class=\"text\" size=\"30\" name=\"" . $question['qID'] . "_comment\" value=\"" . $comment . "\"></div>";
	}else{
		$checklist_html .= "<div style=\"margin-left:15px;position:relative;top:-5px;clear:both;\"></div>";
	}
	
	$checklist_html .= "</li><hr />";
	
}

$checklist_html .= "</ul>";

echo "<input type=\"hidden\" name=\"order_num\" value=\"" . $order_num . "\">";
echo "<input type=\"hidden\" name=\"question_count\" value=\"" . $question_count . "\">";
echo "<input type=\"hidden\" name=\"question_list\" value=\"" . $question_list . "\">";


echo "<br><b>2.0 ORDER PRE-CHECKS CHECKLIST</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $question_count . " Questions (" . $checklist_percentage . "% Complete)<br />";
echo "<hr><br />";
echo $checklist_html;

?>

</form>
</div>

</body>
</html>