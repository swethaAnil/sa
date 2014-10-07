
<?php



//ASSIGN VALUES FROM FORM
$activation_date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
$upload_date = date("Y-n-j");
$acct_num = $_POST['acct_num'];
$acct_name = $_POST['acct_name'];
$order_num = $_POST['order_num'];
$order_type = $_POST['order_type'];
$market = $_POST['market'];
$package = $_POST['package'];
$timeslot = $_POST['timeslot'];
$access = $_POST['access'];


?>
<html>
<head>

     <link rel="stylesheet" href="schedule_style.css" type="text/css">
	 
</head>

<body>
<center>
<br /><br /><br />

<form action="current_schedule.php" method="GET">
Enter date in this format (YYYY-MM-DD): <input type="text" name="lookup_date"></input> <input type="submit" value="Submit">
</form>
</center>
</body>

</html> 
