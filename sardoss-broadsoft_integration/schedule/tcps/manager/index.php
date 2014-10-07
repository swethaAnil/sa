<?php 
$username = $_SERVER['PHP_AUTH_USER'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 


<html>


<head>
<title>TCPS Orders - Service Activations</title>

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
 / <a class="pagetitle" href="../">TCPS Orders</a>
 / 
<span class="location">
<u>Manager</u>
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="../../current_schedule.php">Today's Schedule</a>
<a style="padding-left:10px;" href="../../view_schedules.htm">View Other Schedules</a>
<div class="current_user">User: <?= $username; ?></div>
</div>
</div>

<div style="min-width:600px;position:relative;top:75px;margin-left:10px;width:100%;text-align:center;">
<br>
<h2>TCPS Manager Menu</h2>
<a href="update_time_slots.php" class="bodylink" style="padding:7px;">UPDATE TCPS TIME SLOTS</a>
<br><br>
<a href="add_tcps_order.htm" class="bodylink" style="padding:7px;">ADD INDIVIDUAL TCPS ORDER</a>
<br><br>
<a href="export_tcps_orders.php" class="bodylink" style="padding:7px;">EXPORT EXCEL SPREADSHEET OF ALL TCPS ORDERS</a>

<br><br><br>


</div>
</body>
</html> 