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

/* if($_GET['instance_id']){
	$order_output = "SELECT * FROM $ordertable WHERE instance_id = '$_GET[instance_id]'";
	$current_location = "order_dashboard.php?instance_id=" . $_GET['instance_id'];
	//$latest_status_query = "SELECT * FROM $ordertable WHERE acct_num LIKE '$_SESSION[acct_num]' LIMIT 1";
}else{
	$current_location = "order_dashboard.php";
} */

if($_GET['order_num']){
	//GET MOST RECENT ORDER INSTANCE
	$order_output = "SELECT * FROM tcps_orders WHERE order_num = '$_GET[order_num]' ORDER BY activation_date DESC LIMIT 1";
	//$current_location = "order_dashboard.php?order_num=" . $_GET['order_num'];
}else{
	//$current_location = "order_dashboard.php";
} 


$sql_input = "INSERT INTO $logtable (entries,user,acct_num,status,trouble) VALUES ('$clean_entry','$_SESSION[username]','$_SESSION[acct_num]','$_POST[status]','$_POST[trouble]')";
//$sql_output = "SELECT * FROM " . $logtable . " WHERE instance_id = " . $_GET['instance_id'] . " ORDER BY timedate DESC";
$sql_output = "SELECT * FROM " . $logtable . " WHERE order_num = " . $_GET['order_num'] . " ORDER BY timedate DESC";


//GET ALL ORDER INFO
/*
$order_result = @mysql_query($order_output,$connection);

while ($row = mysql_fetch_array($order_result)){

	$instance_id = $row['instance_id'];
	$activation_date = $row['activation_date'];
	$display_date = date('l M j Y', strtotime($activation_date));
	$acct_num = $row['acct_num'];
	$acct_name = $row['acct_name'];
	$order_type = $row['order_type'];
	$market = $row['market'];
	$package = $row['package'];
	$timeslot = $row['timeslot'];
	$access_type = $row['access_type'];
	$tech = $row['tech_id'];
	$status = $row['status'];
	$checklist_percentage = $row['checklist_percentage'];

	if($tech == "1000"){
		$tech_info = "OVERFLOW";
	}else{
		$tech_sql = "SELECT * FROM `techs` WHERE `tech_id` = $tech";
		$tech_result = @mysql_query($tech_sql,$connection);

		while ($tech_data = mysql_fetch_array($tech_result)){
			$tech_info = $tech_data[first_name] . " " . $tech_data[last_name];
		}
	}
}

$order_num = $_GET['order_num'];


*/
//GET ALL ORDER INFO
$order_result = @mysql_query($order_output,$connection);

while ($row = mysql_fetch_array($order_result)){

	$upload_date = $row['upload_date'];
	$activation_date = $row['activation_date'];
	$display_date = date('l M j Y', strtotime($activation_date));
	$instance_id = $row['instance_id'];
	$acct_num = $row['acct_num'];
	$acct_name = $row['acct_name'];
	$order_type = $row['order_type'];
	$market = $row['market'];
	$package = $row['package'];
	$timeslot = $row['timeslot'];
	$access_type = $row['access_type'];
	$tech = $row['v2_tech_id'];
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
	$reserved_by = $row['reserved_by'];
	
	
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


$order_num = $_GET['order_num'];

?>

<html>

<head>

<title>TCPS Order Dashboard - Service Activations</title>
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


function editNotePopup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=500,height=225,scrollbars=no,status=no,toolbar=no,location=no');
return false;
}

//-->

</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>

<script>
$(document).ready(function(){
	 
	//$("#order_checklist").load("./v2checklist/v2viewChecklist.php?order_num=<?php echo $order_num; ?>");
	$("#logOutput").load("activityLog.php?order_num=<?php echo $order_num; ?>");
	//$("#iadFsp").load("../dashboard/iadFspDisplay.php?instance_id=<?php echo $instance_id; ?>");
	$("#order_variable_data").load("tcps_order_data.php?instance_id=<?php echo $instance_id; ?>");
	
	$("#update_order_data").click(function(){
		var formData = $('#dataForm').serialize();
		$.post('tcpsOrderDataPost.php',formData,function(data,status){});
		$("#order_variable_data").load("tcps_order_data.php?instance_id=<?php echo $instance_id; ?>");
	});
	
	$("#update_log").click(function(){
		var formData = $('#logForm').serialize();
		$.post('../dashboard/orderDashboardSubmit.php',formData,function(data,status){});
		$("#logOutput").load("../dashboard/activityLog.php?order_num=<?php echo $order_num; ?>");
		$("#logEntry").val("");
		$("#logTime").val("");
		document.forms.logForm.entry.focus();
	});
	
	$("#logEntry").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#logForm').serialize();
			$.post('../dashboard/orderDashboardSubmit.php',formData,function(data,status){});
			$("#logOutput").load("../dashboard/activityLog.php?order_num=<?php echo $order_num; ?>");
			$("#logEntry").val("");
			$("#logTime").val("");
			document.forms.logForm.entry.focus();
		}
	});
	
	$("#logTime").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#logForm').serialize();
			$.post('../dashboard/orderDashboardSubmit.php',formData,function(data,status){});
			$("#logOutput").load("../dashboard/activityLog.php?order_num=<?php echo $order_num; ?>");
			$("#logTime").val("");
			$("#logEntry").val("");
			document.forms.logForm.entry.focus();
		}
	});
});
</script>

<script>
<?php
$bits = explode("/",$_SERVER['HTTP_REFERER']);
$count = count($bits)-1;
if($bits[$count] == "current_tcps_orders.php"){

	echo "window.onunload = refreshParent";
	
}
?>
     
        function refreshParent() {
            window.opener.location.reload();
        } 
</script>


<STYLE>

#order_info {
	position:relative;
	z-index:30;
	
	width: 525px;
	margin-left:auto;
	margin-right:auto;
	
	text-align:center;
	font-size:8pt;
	border-style:solid;
	border-width:1px;
	box-shadow: -2px 2px 2px #18181A;
	padding:5px;
	background-color:#F2F2F2;
		
}


#product_info {
	position:relative;
	float:left;
	z-index:30;
	width:288px;
	left:10px;
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
	
	z-index: 20;
	width: 525px;
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
	left: 550px;
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
	color:white;
	background-color:darkgray;
	border-bottom:thin solid darkblue;
	text-shadow: -1px 1px 2px darkblue;
	padding:5px;
	min-width:850px;
	box-shadow: -3px 3px 3px #18181A;
	z-index:100;
	
	
}



#dashboard_title{
	font-size:120%;
	font-weight:bold;
	text-align:left;
	clear:both;
	color:white;
	background-color:darkgray;
	border-bottom:thin solid darkblue;
	text-shadow: -1px 1px 2px darkblue;
	padding:2px;
	padding-left:15px;
	min-width:850px;
	box-shadow: -3px 3px 3px #18181A;
	z-index:100;
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

<body onLoad="document.forms.logForm.entry.focus()">

<div id="dashboard_title">TCPS Order Dashboard - Account <?php echo $acct_num ?></div>
<?php
//echo "<div id=\"account_bar\"> Account $acct_num &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $acct_name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Order $order_num</div>";
?>


<div style="position:relative;top:5px;">
<center>
<table style=""><tr><td>
<div id="order_info">
<b>ORDER INFORMATION</b> - Last update: <b><?php echo $upload_date; ?></b><hr>
<?php
echo "<div style=\"font-size:130%;text-align:left;float:left;margin-left:10px;\">Account Number: <span style=\"font-weight:bold;font-size:110%\">$acct_num</span></div>";
echo "<div style=\"font-size:130%;text-align:left;float:right;margin-right:10px;\">Order Number: <span style=\"font-weight:bold;font-size:110%\">$order_num</span></div>";
echo "<div style=\"clear:both;\"></div><hr>";
echo "<div style=\"font-size:140%;text-align:center;margin-right:10px;\"><span style=\"font-weight:bold;font-size:110%\">$acct_name</span></div>";
echo "<div style=\"clear:both;\"></div><br />";
echo "<div style=\"font-size:130%;text-align:left;float:left;margin-left:10px;\">Order Type: <span style=\"font-weight:bold;font-size:110%\">$order_type</span></div>";
echo "<div style=\"font-size:130%;text-align:left;float:right;margin-right:10px;\">Scheduled By: <span style=\"font-weight:bold;font-size:110%\">$reserved_by</span></div>";
echo "<div style=\"clear:both;\"></div>";
echo "<div style=\"font-size:130%;text-align:left;float:left;margin-top:5px;margin-left:30px;\">Activation Date: <span style=\"font-weight:bold;font-size:110%\">$activation_date</span></div>";
echo "<div style=\"font-size:130%;text-align:left;float:right;margin-top:5px;margin-right:80px;\">Time Slot: <span style=\"font-weight:bold;font-size:110%\">$timeslot</span></div>";
echo "<div style=\"clear:both;\"></div><hr>";
?>

<b>ORDER OPTIONS</b>

<hr>
<div id="order_variable_data"></div>
<br>
<center><button id="update_order_data" class="bodylink">Submit Update</button></center>


</div>

</td></tr>
<tr><td>

<div id="order_log">

<h3>ORDER ACTIVITY LOG</h3>
<table><tr><td>
<form name="logForm" id="logForm" method="post" action="javascript:void(0);" style="display:inline;">
<?php echo "<input type=\"hidden\" name=\"order_num\" value=$order_num>"; ?>
New Entry &nbsp;&nbsp;&nbsp;<i><b>(Do not paste emails here)</b></i><br />
<input type="text" id="logEntry" autocomplete="off" class="text" name="entry" size="80" />
</td></tr><tr><td></td></tr><tr><td>
Time Spent <i>(minutes)</i> <input type="text" autocomplete="off" class="text" id="logTime" name="time_spent" size="6" />
</td></tr><tr><td></td></tr><tr><td>
</form>
<button NAME="update_log" id="update_log" class="button" VALUE="Add Note" style="margin-left:10px;">Add Note</button>
<a class="bodylink" href="v2notePrint.php?order_num=<?php echo $order_num; ?>" onClick="return popup(this, 'notes')" style="margin-left:10px;">Export Log for Siebel</a>
</td></tr></table>

<div id="logOutput"></div>

</div>

</td></tr></table>

<!--<div id="order_checklist"></div>-->
</div>
</center>
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