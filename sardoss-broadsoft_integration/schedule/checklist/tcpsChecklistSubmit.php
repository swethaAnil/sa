<?php

include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$order_num = $_POST['order_num'];
$user = $_SERVER['PHP_AUTH_USER'];

$question_list = $_POST['question_list'];

//iterate through POST and add answers to checklist_data table
foreach($_POST as $key => $value){
	
	if(substr($key,0,1) == 1){			//verify key is a question value
		$key_pieces = explode("_",$key);
		$check_data_SQL = "SELECT * FROM checklist_data WHERE order_num = '$order_num' AND qID = '$key_pieces[0]';";
		$check_result = mysql_query($check_data_SQL, $connection);
		$check = mysql_num_rows($check_result);
			
		if($check == 0){
			
			if($key_pieces[1] == "answer"){
				$insert_answer = "INSERT INTO checklist_data (order_num,answer,qID,last_user) VALUES ('$order_num','$value','$key_pieces[0]','$user');";
				$insert_result = mysql_query($insert_answer,$connection) or die(mysql_error($connection));
			}
			
			if($key_pieces[1] == "comment" && $value != ""){
			
				$clean_entry = stripslashes($value);
				$clean_entry = mysql_real_escape_string($clean_entry);
				$insert_comment = "INSERT INTO checklist_data (order_num,comments,qID,last_user) VALUES ('$order_num','$clean_entry','$key_pieces[0]','$user');";
				$insert_result = mysql_query($insert_comment,$connection) or die(mysql_error($connection));
				$clean_entry = NULL;
			}
		
		}elseif($check > 0){
		
			while($curr_data = mysql_fetch_array()){
				$curr_answer = $curr_data['answer'];
				$curr_comment = $curr_data['comments'];
			}
			
			if($key_pieces[1] == "answer" && $value != $curr_answer){
				$insert_answer = "UPDATE checklist_data SET answer='$value',last_user = '$user' WHERE order_num = '$order_num' AND qID = '$key_pieces[0]';";
				$insert_result = mysql_query($insert_answer,$connection) or die(mysql_error($connection));
			}
			
			if($key_pieces[1] == "comment" && $value != ""){
				$clean_entry = stripslashes($value);
				$clean_entry = mysql_real_escape_string($clean_entry);
				if($clean_entry != $curr_comment){
					$insert_comment = "UPDATE checklist_data SET comments='$clean_entry',last_user = '$user' WHERE order_num = '$order_num' AND qID = '$key_pieces[0]';";
					$insert_result = mysql_query($insert_comment,$connection) or die(mysql_error($connection));
					$clean_entry = NULL;
				}
			}
		}
	}
}

//check for existing checklist on order record
$orderChecklistSQL = "SELECT tcps_checklist FROM $ordertable WHERE order_num = $order_num";
$orderChecklistResult = mysql_query($orderChecklistSQL,$connection);
$orderChecklist = mysql_fetch_row($orderChecklistResult);

//if order record does not have a checklist, add question_list to record
if($orderChecklist[0] == NULL){
	$saveOrderChecklistSQL = "UPDATE $ordertable SET tcps_checklist = '$question_list' WHERE order_num = '$order_num'";
	$saveOrderChecklistResult = mysql_query($saveOrderChecklistSQL,$connection) or die(mysql_error());
}

//get all checklist_questions that match the question IDs in the question list
//$checklist_questions_SQL = "SELECT * FROM checklist_questions WHERE qID in ($question_list) ORDER BY FIND_IN_SET(qID,\"$question_list\")";
//$checklist_questions_result = mysql_query($checklist_questions_SQL,$connection) or die(mysql_error());

//get all checklist answers for this order
$questions_complete_SQL = "SELECT answer FROM checklist_data WHERE answer != '0' AND order_num = '$order_num' AND qID in ($question_list);";
$questions_complete_result = mysql_query($questions_complete_SQL,$connection);
$num_questions_complete = mysql_num_rows($questions_complete_result);
$complete_percentage = round(($num_questions_complete/$_POST['question_count']) * 100,0);

//update checklist percentage on order record
$update_percentage_SQL = "UPDATE current_orders SET tcps_checklist_percentage = '$complete_percentage' WHERE order_num = '$order_num';";
$update_percentage_result = mysql_query($update_percentage_SQL,$connection) or die(mysql_error($connection));

?>

<html>
<head>
<?php echo "<meta http-equiv=\"REFRESH\" content=\"0;url=order_dashboard.php?order_num=$order_num\">";  ?>
</head>
</html>