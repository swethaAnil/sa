<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php
include '../../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$username = $_SERVER['PHP_AUTH_USER'];

?>

<html>


<head>
<title>Checklist Editor - Service Activations</title>

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
 / 
<span class="location">
<u>Checklist Editor</u>
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="questions.php">Add/Edit Questions</a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>

<div style="min-width:600px;position:relative;top:75px;margin-left:20px;width:100%;">
<br>
<h3>NOTICE: CHANGES MADE TO CHECKLIST STRUCTURES WILL TAKE EFFECT IMMEDIATELY!<br />
<i>All unstarted checklists will be altered.</i></h3>
<div style="float:left;">
<?php

$structuresSQL = "SELECT * FROM checklist_structure ORDER BY listName";
$structuresResult = mysql_query($structuresSQL,$connection);

while($structure = mysql_fetch_array($structuresResult)){
	echo "<form action=\"updateChecklistStructure.php\" method=\"POST\">";
	echo "<b>$structure[1] </b><button class=\"button\" > Update </button><br />";
	echo "<input type=\"hidden\" name=\"listname\" value=\"$structure[1]\" />";
	echo "<textarea rows=\"3\" cols=\"70\" name=\"list\">$structure[2]</textarea><br /><br />";
	echo "</form>";

} 
?>
</div>
<?php
$questionsBriefSQL = "SELECT qID,question_abbrev FROM checklist_questions ORDER BY qID";
$questionsBriefResult = mysql_query($questionsBriefSQL,$connection);

?>

<div style="font-size:90%;position:fixed;float:right;top:150px;left:700px;border:thin solid black;background-color:white;padding:20px;">
<b>Bundles</b><br />
90001 - Access Type Bundle<br />
90002 - Voice Type Bundle<br />
90003 - VPN Bundle<br />
90004 - WBU Bundle<br />
90005 - TCPS Bundle<br /><br />

<b>Question ID Reference (Full question text below)</b><br />
<div style="font-size:80%;float:left">

<?php
$count = 0;

while($abbrev = mysql_fetch_array($questionsBriefResult)){
	
	if($count % 25 == 0){ echo "</div><div style=\"font-size:80%;float:left\">"; }
	
	echo $abbrev['qID'] . " - " . $abbrev['question_abbrev'] . "<br />";
	$count++;

}

?>
</div>

</div>
<br /><br /><br />
<div style="clear:both;">
<b>FULL QUESTION DETAILS</b>
<?php
$questionsSQL = "SELECT * FROM checklist_questions ORDER BY qID";
$questionsResult = mysql_query($questionsSQL,$connection);


echo "<table style=\"border-collapse:collapse;font-size:80%;\" border=\"1\"><tr><th>Question ID</th><th>Full Text</th><th>Abbreviation</th><th>Category</th><th>Subcategory</th><th>Comments?</th><th>Form Type</th></tr>";
while($question = mysql_fetch_array($questionsResult)){
		echo "<tr><td>$question[0]</td><td>$question[3]</td><td>$question[5]</td><td>$question[1]</td><td>$question[6]</td><td>$question[4]</td><td>$question[2]</td></tr>";
}


?>
</div></div>
</body>
</html> 