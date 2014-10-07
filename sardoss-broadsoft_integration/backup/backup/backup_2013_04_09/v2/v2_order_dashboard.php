<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php
//GLOBAL OPTIONS
require '../bin/log_func.php';
require '../bin/dbinfo.inc.php';

$username = $_SERVER['PHP_AUTH_USER'];

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

if($_GET['instance_id']){
	$order_output = "SELECT * FROM $v2ordertable WHERE instance_id = '$_GET[instance_id]'";
}else{
	echo "ERROR: No order ID provided.";
	exit;
}

/*

$sql_input = "INSERT INTO $logtable (entries,user,acct_num,status,trouble) VALUES ('$clean_entry','$_SESSION[username]','$_SESSION[acct_num]','$_POST[status]','$_POST[trouble]')";
$sql_output = "SELECT * FROM " . $logtable . " WHERE instance_id = " . $_GET['instance_id'] . " ORDER BY timedate DESC";
*/

//GET ALL ORDER INFO
$order_result = @mysql_query($order_output,$connection);

while ($row = mysql_fetch_array($order_result)){

	$upload_date = $row['upload_date'];
	$activation_date = $row['activation_date'];
	$display_date = date('l M j Y', strtotime($activation_date));
	$order_num = $row['order_num'];
	$acct_num = $row['acct_num'];
	$acct_name = $row['acct_name'];
	$order_type = $row['order_type'];
	$market = $row['market'];
	$package = $row['package'];
	$timeslot = $row['timeslot'];
	$access_type = $row['access_type'];
	$tech = $row['tech_id'];
	$fsp_info = $row['fsp_info'];
	$iad_name = $row['iad_name'];
	$status = $row['status'];
	$checklist_percentage = $row['checklist_percentage'];
	$bandwidth = $row['bandwidth'];
	$jira_ticket = $row['jira_ticket'];
	$jira_status = $row['jira_ticket_status'];
	$jira_total_mins = $row['jira_total_mins'];
	$siebel_order_status = $row['siebel_order_status'];
	$truck_roll_mins = $row['truck_roll_mins'];
	$v2_reason = $row['2.0_reason'];
	$survey_completed = $row['survey_completed'];
	$survey_result = $row['survey_result'];
	
	
	if($tech == "1000"){
		$tech_info = "UNASSIGNED";
	}else{
		$tech_sql = "SELECT * FROM `techs` WHERE `tech_id` = $tech";
		$tech_result = @mysql_query($tech_sql,$connection);

		while ($tech_data = mysql_fetch_array($tech_result)){
			$tech_info = $tech_data[first_name] . " " . $tech_data[last_name];
		}
	}
}


$instance_id = $_GET['instance_id'];

?>

<html>

<head>

<title>Service Activations Order Dashboard</title>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->




<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=700,height=600,scrollbars=no,status=no,toolbar=no,location=no');
return false;
}
//-->

</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>

<script>
$(document).ready(function(){
	$("#order_variable_data").load("v2_order_data.php?instance_id=<?php echo $instance_id; ?>");
	
	
	
	$("#update_order_data").click(function(){
		var formData = $('#dataForm').serialize();
		$.post('v2OrderDataPost.php',formData,function(data,status){});
		$("#order_variable_data").load("v2_order_data.php?instance_id=<?php echo $instance_id; ?>");
	});
	
});

</script>

<script>
     window.onunload = refreshParent;
        function refreshParent() {
            window.opener.location.reload();
        } 
</script>


<STYLE>

#order_info {
	position:relative;
	z-index:30;
	margin-top:10px;
	font-size:8pt;
	border-style:solid;
	border-width:1px;
	box-shadow: -2px 2px 2px #18181A;
	padding:5px;
	background-color:#F2F2F2;
	margin-left:auto;
	margin-right:auto;
		
}


#product_info {
	position:relative;
	text-align:center;
	z-index:30;
	padding:5px;
	text-align:left;
	font-size:8pt;
	border-style:solid;
	border-width:1px;
	background-color:#F2F2F2;
	box-shadow: -2px 2px 2px #18181A;
	
		
	
}


#order_status {
	position: absolute;
	z-index: 30;
	width: 270px;
	padding-left: 10px;
	padding-top: 250px;
	text-align:left;
	font-size:10px;
}


#order_log {
	position:relative;
	float:left;
	z-index: 20;
	width: 650px;
	left: 5px;
	top: 7px;
	padding-left:5px;
	padding-right:5px;
	margin-bottom:20px;
	text-align:left;
	font-size:10px;
	border-style:solid;
	border-width:1px;
	background-color:#F2F2F2;
	box-shadow: -2px 2px 2px #18181A;
		
}

#order_checklist {
	position: absolute;
	z-index: 30;
	min-width: 450px;
	left: 675px;
	margin-top:3px;
	margin-bottom:10px;
	padding-bottom:0px;
	padding-left:5px;
	text-align:left;
	font-size:75%;
	float:right;
	text-align:left;
	border-style:solid;
	border-width:1px;
	background-color:#F2F2F2;
	box-shadow: -2px 2px 2px #18181A;
}

#account_bar{
	font-size:140%;
	font-weight:bold;
	text-align:center;
	clear:both;
	color:darkblue;
	margin-top:5px;
	margin-left:3px;
	background-color:#F2F2F2;
	border-style:solid;
	border-width:1px;
	text-shadow: -1px 1px 2px rgba(150, 150, 150, 1);
	padding:5px;
	min-width:750px;
	
	
}

#status_bar{
	font-size:100%;
	text-align:center;
	float:left;
	margin-left:10px;
	margin-top:5px;
	
		
}

#assign_bar{
	font-size:100%;
	text-align:center;
	float:right;
	z-index:-1;
	margin-right:20px;
	margin-top:3px;
	
	
}


select {
   font-family:arial,sans-serif;
   padding: 2px;
   font-weight:bold;
   font-size:100%;
   border: 1px solid #333333;
   border-radius: 2;
   -webkit-appearance: none;
   width:120px;
   }

#subheader{
min-width:850px;
}

div.dash_header{
	
	width:100%;
	min-width:850px;
	z-index:200;
}




</STYLE>

</head>

<body>

<!--

<div class="dash_header">
<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="images/SA_title_words_small.png">
</div>

<div class="pagetitle">
<a href="/sardoss">Home</a>
 / <a href="../current_schedule.php">Online Schedule</a>
 / <a href="./current_v2_orders.php">2.0 Orders</a>
<span class="location">
 / Order Dashboard
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">

<a style="padding-left:10px;" href="order_dashboard.php">Home</a>
 | <a href="user_history.php" onClick="return popup(this, 'notes')">User History</a>
 | <a href="trackerboss.php">Supervisor View</a>
 | <a href="admin.php">Settings</a>
 | <a href="bin/logout.php">Logout</a>
_
<div class="current_user"><?php echo "User: $username"; ?></div>
</div>

</div>

-->


<?php
echo "<div id=\"account_bar\"> Account $acct_num &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $acct_name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Order $order_num</div>";
?>


<div class="page_body">
<center>
<table><tr><td>
<div id="order_info">
<b>SIEBEL ORDER INFORMATION</b> - Last update: <b><?php echo $upload_date; ?></b><hr>
<?php
echo "<div style=\"font-size:110%;text-align:center;float:left;margin-left:10px;\">Order Type: <span style=\"font-weight:bold;font-size:110%\">$order_type</span></div>";
echo "<div style=\"font-size:110%;text-align:center;float:right;margin-right:10px;\">Market: <span style=\"font-weight:bold;font-size:110%\">$market</span></div>";
echo "<div style=\"font-size:110%;text-align:center;clear:both;margin-top:30px;\">Package: <span style=\"font-weight:bold;font-size:110%\">$package</span></div>";
echo "<div style=\"font-size:110%;text-align:center;float:left;margin-top:5px;margin-left:10px;\">Access Type: <span style=\"font-weight:bold;font-size:110%\">$access_type</span></div>";
echo "<div style=\"font-size:110%;text-align:center;float:right;margin-top:5px;margin-right:10px;\">Bandwidth: <span style=\"font-weight:bold;font-size:110%\">$bandwidth</span></div>";
echo "<div style=\"clear:both;\"></div>";
echo "<div style=\"font-size:110%;text-align:center;float:left;margin-top:5px;margin-left:10px;\">Activation Date: <span style=\"font-weight:bold;font-size:110%\">$activation_date</span></div>";
echo "<div style=\"font-size:110%;text-align:center;float:right;margin-top:5px;margin-right:10px;\">Time Slot: <span style=\"font-weight:bold;font-size:110%\">$timeslot</span></div>";
echo "<div style=\"clear:both;\"></div>";
echo "<div style=\"font-size:110%;text-align:center;clear:both;margin-top:5px;margin-right:10px;\">Siebel Order Status: <span style=\"font-weight:bold;font-size:110%\">$siebel_order_status</span></div>";
echo "<div style=\"clear:both;\"></div>";
?>
</div>
<br>
<div id="product_info">
<b>VARIABLE ORDER INFORMATION</b>

<hr>
<div id="order_variable_data"></div>
<br>
<center><button id="update_order_data" class="bodylink">Submit Update</button></center>

</div>
</div>
<br>

<button style="float:right;" class="bodylink" onClick="window.close()">Close Window</button>

</body>
</html>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39762922-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


