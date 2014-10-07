<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php
	include '../../bin/dbinfo.inc.php';

	//CONNECT TO MYSQL
	$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
	$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

	$username = $_SERVER['PHP_AUTH_USER'];

	$result = 0;

	function remove_specials($value){
		$new_value = mysql_real_escape_string(str_replace("'", "", $value));
		return $new_value;
	}

	if($_POST){

		$full_text = remove_specials($_POST['full_text']);
		$category = remove_specials($_POST['category']);
		$subcategory = remove_specials($_POST['subcategory']);
		$question_abbrev = remove_specials($_POST['question_abbrev']);
		
		$SQL = "INSERT INTO checklist_questions (category, subcategory, form_type, full_text, comments, question_abbrev)
				VALUES ('" . $category . "','" . $subcategory . "','" . $_POST['form_type'] . "','" . $full_text . "','" . $_POST['comments'] . "','" . $question_abbrev . "')";
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
			border:1px solid black;
			border-collapse: collapse;
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
		<?php if($result): ?>
			<div id="result">
				<p>QUESTION ADDED</p>
			</div>
		<?php endif ?>
		<h3>NOTICE: CHANGES MADE TO CHECKLIST QUESTIONS WILL TAKE EFFECT IMMEDIATELY!<br />
		<i>Questions will be changed on checklists that were completed in the past.</i></h3>

		<p><a href="add_question.php" class="button" style="margin:10px;padding:10px;"> ADD NEW CHECKLIST QUESTION</a></p>

		<div>
			<?php
				$questionsSQL = "SELECT * FROM checklist_questions ORDER BY qID";
				$questionsResult = mysql_query($questionsSQL,$connection);
			?>
			<table>
				<tr>
					<th>Question ID</th>
					<th>Category</th>
					<th>Sub-category</th>
					<th>Form Type</th>
					<th>Full Text</th>
					<th>Comments?</th>
					<th>Abbreviation</th>
					<th></th>
				</tr>
				<?php while($question = mysql_fetch_array($questionsResult)): ?>
					<tr>
						<td><?= $question['qID'] ?></td>
						<td><?= $question['category'] ?></td>
						<td><?= $question['subcategory'] ?></td>
						<td><?= $question['form_type'] ?></td>
						<td><?= $question['full_text'] ?></td>
						<td><?php if($question['comments']){ echo "Y"; }else{ echo "N"; } ?></td>
						<td><?= $question['question_abbrev'] ?></td>
						<td class="button" style="margin:5px;"><a href="edit_question.php?qID=<?= $question['qID'] ?>" style="padding:5px;margin:5px;">EDIT</a></td>
					</tr>	
				<?php endwhile ?>
			</table>
		</div>
	</div>
</body>
</html> 