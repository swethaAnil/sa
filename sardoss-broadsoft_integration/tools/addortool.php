
<HTML>

<HEAD>
<TITLE> OR Tool Results </TITLE>
</HEAD>

<BODY>

<?php 

if ($_POST[submit] == "Add ORs") {
	$result = str_replace("\n"," <b>OR</b> ",$_POST[text1]);
	echo "<h1>Results:</h1><br><br>$result";
	}

else if ($_POST[submit] == "Add your own") {
	$result = str_replace("\n"," $_POST[text2] ",$_POST[text1]);
	echo "<h1>Results:</h1><br><br>$result";
	}


?>

<P><a href="ortool.html">Go Back</a></P>

</BODY>

</HTML>