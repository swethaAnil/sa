<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title>Note Editor - Service Activations</title>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->

</head>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
	
	$("#deleteNote").click(function(){
	var answer = confirm('Are you sure you want to delete this entry?');
		if(answer){
			var formData = $('#logForm').serialize();
			
			$.get('deleteNote.php',formData,function(data,status){
				window.opener.location.reload();
				window.close();
			});
		}
	});
	
	$("#updateNote").click(function(){
	var answer = confirm('Are you sure you want to update this entry?');
		if(answer){
			var formData = $('#logForm').serialize();
			
			$.get('updateNote.php',formData,function(data,status){
				window.opener.location.reload();
				window.close();
			});

		}
	});
	
});
</script>



<style>


body {
	background-color:#F2F2F2;
	position:relative;
	left: 10px;
}

#editForm{
	position:relative;
	left: 10px;
	top: 7px;
	font-size:12px;
	
}

</style>

<body >

<?php

//GLOBAL OPTIONS
include '../bin/log_func.php';
include '../bin/dbinfo.inc.php';

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$noteID = $_GET['noteID'];


$sql_output = "SELECT * FROM " . $logtable . " WHERE noteID = '" . $noteID . "'";
$order_note_result = mysql_query($sql_output,$connection);

$note = mysql_fetch_row($order_note_result) or die(mysql_error());

$curr_note_start = substr($note[3],0,1);
$curr_note_end = substr($note[3],-1);

echo "<strong>Note Editor</strong><br /><br />";

?>
<div id="editForm">
<?php 
echo "Note created on <strong>$note[2]</strong> for Order Number <strong>$note[7]</strong>.<br /><br />";

if($curr_note_start == "(" && $curr_note_end == ")"){
	
	echo "<b><i>$note[3]</i></b> is a system note.<br /><br /> System notes cannot be edited or deleted.";
	
}else{
	echo "<form name=\"logForm\" id=\"logForm\" method=\"post\" action=\"javascript:void(0);\" style=\"display:inline;\">";
	echo "<input type=\"hidden\" name=\"noteID\" value=\"$noteID\" />";
	echo "Entry &nbsp;&nbsp;<i>(Do not paste emails here)</i><br />";
	echo "<input type=\"text\" autocomplete=\"off\" class=\"text\" id=\"logEntry\" name=\"entry\" size=\"80\" value=\"$note[3]\" />";
	echo "<br /><br />";
	echo "Time Spent <i>(minutes)</i> <input type=\"text\" autocomplete=\"off\" class=\"text\" id=\"logTime\" name=\"time_spent\" size=\"6\" value=\"$note[9]\" />";
	echo "<br /><br />";
	echo "</form>";
	echo "<button class=\"button\" id=\"updateNote\">Update Note</button> ";
	echo "<button class=\"button\" id=\"deleteNote\">Delete Note</button>";
}

?>

</div>

</body>


</html>





