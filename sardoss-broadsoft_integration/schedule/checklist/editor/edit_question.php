<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php
	include '../../bin/dbinfo.inc.php';

	//CONNECT TO MYSQL
	$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
	$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

	$username = $_SERVER['PHP_AUTH_USER'];
	$qID = $_GET['qID'];
	$result = 0;

	if($_POST){
		$SQL = "UPDATE checklist_questions SET 
				category = '" . $_POST['category'] . "',
				subcategory = '" . $_POST['subcategory'] . "',
				form_type = '" . $_POST['form_type'] . "',
				full_text = '" . $_POST['full_text'] . "',
				comments = " . $_POST['comments'] . ",
				question_abbrev = '" . $_POST['question_abbrev'] . "' WHERE qID = '$qID'";
		$result = mysql_query($SQL,$connection) or die(mysql_error());
	}

?>

<html>

<head>
	<title>Question Editor - Service Activations</title>

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="../../style/tracker_style_ie.css" />
	<![endif]-->

	<!--[if !IE]><!-->
	<link rel="stylesheet" type="text/css" href="../../style/tracker_style.css" />
	<!--<![endif]-->


	<style>

		.topfull{
		border-width:0px;
		border-style:hidden;
		font-family:"arial";
		font-size:10px;
		width:100%;
		padding-right:40px;
		}

		.top{
		border-width:0px;
		border-style:hidden;
		font-family:"arial";
		font-size:12px;
		padding-right:40px;
		font-weight:bold;
		}

		table, tr, td, th{
			padding: 5px;
		}

		#result{
			font-size: 140%;
			width:200px;
			background-color: #90EE90;
			color:#006400;
			padding: 5px;
			text-align: center;
		}

	</style>

	<script>
		window.onload = function(){
			var textarea = document.getElementById('question_text');
			var char_count = document.getElementById('char_count');

			char_count.innerHTML = textarea.value.length;

			textarea.onkeyup = function(){
				char_count.innerHTML = textarea.value.length;
			}


		}
	</script>

</head>

<body>

	<div class="full_header">
		<div class="titlecolor">

			<div class="title">
				<img style="margin:0px;" src="../../images/cbey_logo_small.png">
			</div>

			<div class="pagetitle">
				<a class="pagetitle" href="../../../">Service Activations</a>
				 / <a class="pagetitle" href="../../current_schedule.php">Online Schedule</a>
				 / <a class="pagetitle" href="../../manager/">Manager</a>
 				 / <a class="pagetitle" href="./">Checklist Editor</a>
				 / 
				<span class="location">
				<u>Questions</u>
				</span>
			</div>
		</div>

		<hr class="topline" />
		<div class="optionbar">
			<a style="padding-left:10px;" href="current_schedule.php"></a>
			<a style="padding-left:10px;" href="view_schedules.php"></a>
			<div class="current_user">User: <?php echo $username; ?></div>
		</div>
	</div>

	<div style="min-width:600px;position:relative;top:75px;margin-left:20px;width:100%;">
		<br>
			<?php
				$questionsSQL = "SELECT * FROM checklist_questions WHERE qID = '$qID'";
				$questionsResult = mysql_query($questionsSQL,$connection);
				while($question = mysql_fetch_array($questionsResult)){
					$question_data = $question;
				}

			?>
			
			<?php if($result): ?>
				<div id="result">
					<p>QUESTION UPDATED</p>
				</div>
			<?php endif ?>
	
			<h3>Editing Checklist Question <?= $question_data['qID'] ?></h3>
			<form action="" method="POST">
				<input type="hidden" name="qID" value="<?= $question_data['qID'] ?>">
				<table>
					<tr>
						<td align="right">Category<br>(Max: 10 chars)</td>
						<td><input type="text" value="<?= $question_data['category'] ?>" name="category"></td>
					</tr>
					<tr>
						<td align="right">Sub-category<br>(Max: 10 chars)</td>
						<td><input type="text" value="<?= $question_data['subcategory'] ?>" name="subcategory"></td>
					</tr>
					<tr>
						<td align="right">Form Type</td>
						<td>
							<select name="form_type">
								<option value=""></option>
								<option value="yesno" <?php if($question_data['form_type']=="yesno"){ echo "selected";} ?>>Yes/No</option>
								<option value="yesnona" <?php if($question_data['form_type']=="yesnona"){ echo "selected";} ?>>Yes/No/NA</option>
								<option value="checkbox" <?php if($question_data['form_type']=="checkbox"){ echo "selected";} ?>>Checkbox</option>
								<option value="greenred" <?php if($question_data['form_type']=="greenred"){ echo "selected";} ?>>Green/Yellow/Red</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" align="right">Full Text (Max: 80 chars)<br><br>Character Count: <div id="char_count"></div></td>
						<td>
							<input type="text"id="question_text" name="full_text" size="80" value="<?= $question_data['full_text'] ?>">
						</td>
					</tr>
					<tr>
						<td align="right">Comments Allowed?</td>
						<td>
							<select name="comments">
									<option value=""></option>
									<option value="1" <?php if($question_data['comments']=="1"){ echo "selected";} ?>>Yes</option>
									<option value="0" <?php if($question_data['comments']=="0"){ echo "selected";} ?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right">Question Abbreviation<br>(Max: 15 chars)</td>
						<td><input type="text" name="question_abbrev" value="<?= $question_data['question_abbrev'] ?>"></td>
					</tr>		
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="center"><a href="questions.php" class="button">Back to Questions List</a></td>
						<td align="center"><button class="button" type="submit">SAVE</button></td>
					</tr>			
				</table>
			</form>
		</div>
	</div>
</body>
</html> 