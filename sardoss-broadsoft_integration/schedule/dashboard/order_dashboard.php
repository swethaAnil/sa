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

if($_GET['order_num']){
	//GET MOST RECENT ORDER INSTANCE
	$order_output = "SELECT * FROM $ordertable WHERE order_num = '$_GET[order_num]' ORDER BY activation_date DESC LIMIT 1";
	$current_location = "order_dashboard.php?order_num=" . $_GET['order_num'];
}else{
	$current_location = "order_dashboard.php";
} 

//GET ALL ORDER INFO
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
			$tech_info = $tech_data['first_name'] . " " . $tech_data['last_name'];
		}
	}
}

$order_num = $_GET['order_num'];

?>

<html>

<head>

<title>Acct <?php echo $acct_num; ?> - Order Dashboard</title>
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
window.open(href, windowname, 'width=700,height=600,scrollbars=yes,status=no,toolbar=no,location=no');
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
	
	$("#order_checklist").load("../checklist/viewChecklist.php?order_num=<?php echo $order_num; ?>");
	$("#logOutput").load("activityLog.php?order_num=<?php echo $order_num; ?>");
	$("#iadFsp").load("iadFspDisplay.php?instance_id=<?php echo $instance_id; ?>");
	
	
	
	$("#update_log").click(function(){
		var formData = $('#logForm').serialize();
		$.post('orderDashboardSubmit.php',formData,function(data,status){});
		$("#logOutput").load("activityLog.php?order_num=<?php echo $order_num; ?>");
		$("#logEntry").val("");
		$("#logTime").val("");
		document.forms.logForm.entry.focus();
	});
	
	$("#logEntry").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#logForm').serialize();
			$.post('orderDashboardSubmit.php',formData,function(data,status){});
			$("#logOutput").load("activityLog.php?order_num=<?php echo $order_num; ?>");
			$("#logEntry").val("");
			$("#logTime").val("");
			document.forms.logForm.entry.focus();
		}
	});
	
	$("#logTime").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#logForm').serialize();
			$.post('orderDashboardSubmit.php',formData,function(data,status){});
			$("#logOutput").load("activityLog.php?order_num=<?php echo $order_num; ?>");
			$("#logTime").val("");
			$("#logEntry").val("");
			document.forms.logForm.entry.focus();
		}
	});
	
	$("#update_iad").click(function(){
		var formData = $('#iadUpdateForm').serialize();
		$.get('orderDashboardSubmit.php',formData,function(data,status){});
		$("#iadEntry").val("");
		$("#iadFsp").load("iadFspDisplay.php?instance_id=<?php echo $instance_id; ?>");
	});
	
	$("#iadEntry").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#iadUpdateForm').serialize();
			$.get('orderDashboardSubmit.php',formData,function(data,status){});
			$("#iadEntry").val("");
			$("#iadFsp").load("iadFspDisplay.php?instance_id=<?php echo $instance_id; ?>");
		}
	});
	
	$("#update_fsp").click(function(){
		var formData = $('#fspUpdateForm').serialize();
		$.get('orderDashboardSubmit.php',formData,function(data,status){});
		$("#fspEntry").val("");
		$("#iadFsp").load("iadFspDisplay.php?instance_id=<?php echo $instance_id; ?>");
	});
	
 	$("#fspEntry").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#fspUpdateForm').serialize();
			$.get('orderDashboardSubmit.php',formData,function(data,status){});
			$("#fspEntry").val("");
			$("#iadFsp").load("iadFspDisplay.php?instance_id=<?php echo $instance_id; ?>");	
		
		}
	}); 
	
	  
});
</script>

<script>
<?php

$bits = explode("/",$_SERVER['HTTP_REFERER']);
$count = count($bits)-2;
if($bits[$count] != "search"){

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
	float:left;
	width: 345px;
	left: 5px;
	
	text-align:left;
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

#account_barOLD{
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
	min-width:850px;
	
	
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


#account_bar table{
	position:relative;
	margin-left:auto;
	margin-right:auto;
	width:85%;
	
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
	text-align:center;
	float:left;
	margin-left:10px;
	margin-top:3px;
	font-size:120%;
	font-weight:bold;
	text-align:center;
	clear:both;
	color:darkblue;
	text-shadow: -1px 1px 1px rgba(150, 150, 150, 1);
	
	
		
}

#assign_bar{
	font-size:110%;
	text-align:center;
	float:right;
	z-index:-1;
	margin-right:20px;
	margin-top:3px;
	color:darkblue;
	text-shadow: -1px 1px 1px rgba(150, 150, 150, 1);
	font-weight:bold;
	
}


select {
	font-family:arial,sans-serif;
	padding: 1px;
	font-weight:bold;
	font-size:90%;
	border-radius: 2;
	-webkit-appearance: none;
	background: #EEEEEE; 
	border: 1px solid #0000FF;
	color:darkblue;
	border: 2px solid #0000FF;
	border-style:inset;

}

#subheader{
min-width:850px;
margin-top:5px;




}

div.dash_header{
	
	width:100%;
	min-width:850px;
	z-index:200;
}


</STYLE>

</head>

<body onLoad="document.forms.logForm.entry.focus()">

<div id="dashboard_title">Service Activations Order Dashboard</div>
<?php
echo "<div id=\"account_bar\">
<table><tr><td style=\"text-align:right;padding-right:7px;\">Account</td><td style=\"text-align:left;\">$acct_num</td><td>$acct_name</td><td style=\"text-align:right;padding-right:7px;\">Order</td><td style=\"text-align:left;\">$order_num</td></tr>
</table></div>";
?>


<div id="subheader">

<form name="reassign" action="submitInstanceUpdate.php" method="post" >
<?php echo "<input type=\"hidden\" name=\"instance_id\" value=$instance_id>"; ?>
<?php echo "<input type=\"hidden\" name=\"order_num\" value=$order_num>"; ?>


<div id="status_bar">Order Status: <b><?php echo $status; ?></b>&nbsp;&nbsp;&nbsp;
<?php

if($status != "Pending" && $status != ""){ echo "<button type=\"submit\" name=\"status\" value=\"Pending\" class=\"button\">Set to Pending</button> "; }


if($status != "Complete"){ echo "<button type=\"submit\" name=\"status\" value=\"Complete\" class=\"button\">Set to Complete</button> "; }
if($status != "Cancelled"){ echo "<button type=\"submit\" name=\"status\" value=\"Cancelled\" class=\"button\">Set to Cancelled</button> "; }

?>
</div>
<div id="assign_bar">
SA Tech:
<select name="newtech">
	<option value=""></option>
<?php 
$techs_output = "SELECT * FROM techs WHERE is_deleted = 0 ORDER BY first_name";
$techs_result = mysql_query($techs_output,$connection);
while($tech_list = mysql_fetch_array($techs_result)){
	echo "<option value=\"" . $tech_list['tech_id'] . "\"";
	if($tech == $tech_list['tech_id']){
		echo "selected";
	}	
	echo ">" . $tech_list['first_name'] . " " . $tech_list['last_name'] . "</option>
	";
}
?>
<option value="1000">OVERFLOW</option>
</select>
Time Slot:
<select id="timeSlot" name="newtime">
	<option value=""></option>
	<option value="830" <?php if($timeslot=="830"){ echo "selected"; } ?>>8:30 AM</option>
	<option value="930" <?php if($timeslot=="930"){ echo "selected"; } ?>>9:30 AM</option>
	<option value="1030" <?php if($timeslot=="1030"){ echo "selected"; } ?>>10:30 AM</option>
	<option value="1130" <?php if($timeslot=="1130"){ echo "selected"; } ?>>11:30 AM</option>
	<option value="1230" <?php if($timeslot=="1230"){ echo "selected"; } ?>>12:30 PM</option>
	<option value="1330" <?php if($timeslot=="1330"){ echo "selected"; } ?>>1:30 PM</option>
	<option value="1430" <?php if($timeslot=="1430"){ echo "selected"; } ?>>2:30 PM</option>
	<option value="1530" <?php if($timeslot=="1530"){ echo "selected"; } ?>>3:30 PM</option>
	<option value="1630" <?php if($timeslot=="1630"){ echo "selected"; } ?>>4:30 PM</option>
	<option value="1730" <?php if($timeslot=="1730"){ echo "selected"; } ?>>5:30 PM</option>
	<option value="1830" <?php if($timeslot=="1830"){ echo "selected"; } ?>>6:30 PM</option>
	<option value="1930" <?php if($timeslot=="1930"){ echo "selected"; } ?>>7:30 PM</option>
	<option value="ANYTIME" <?php if($timeslot==""){ echo "selected"; } ?>>ANYTIME</option>
</select>

<button  type="submit" class="button" value="Update" name="update">Update Tech/Time Slot</button>

</form>
</div>
</div>





<div name="main" class="main">





<table style="float:left;"><tr><td>
<div id="order_info">
<b>ORDER INFORMATION</b><hr>
<?php
echo "<div style=\"font-size:110%;text-align:center;float:left;margin-left:10px;\">Order Type: <span style=\"font-weight:bold;font-size:110%\">$order_type</span></div>";
echo "<div style=\"font-size:110%;text-align:center;float:right;margin-right:10px;\">Market: <span style=\"font-weight:bold;font-size:110%\">$market</span></div>";
echo "<div style=\"font-size:110%;text-align:center;clear:both;margin-top:25px;\">Package: <span style=\"font-weight:bold;font-size:110%\">$package</span></div>";
echo "<div style=\"font-size:110%;text-align:center;margin-top:3px;\">Access Type: <span style=\"font-weight:bold;font-size:110%\">$access_type</span></div><hr />";
?>


<div id="iadFsp" style="width:170px;text-align:center;margin-top:8px;float:left;"></div>
<div style="float:right;width:170px;text-align:right;">
<table><tr><td>
<form name="iadUpdateForm" id="iadUpdateForm" method="POST" action="javascript:void(0);" style="display:inline;">
<?php echo "<input type=\"hidden\" name=\"instance_id\" value=$instance_id>";  ?>
<input type="text" autocomplete="off" class="text" name="iad_name" id="iadEntry" size="12" />
</form>
</td><td>
<button NAME="update_iad" id="update_iad" class="button" style="display:inline;" VALUE="Update IAD">Update IAD</button>
</td></tr></table>


<table><tr><td>
<form name="fspUpdateForm" id="fspUpdateForm" method="post" action="javascript:void(0);" style="display:inline;">
<?php echo "<input type=\"hidden\" name=\"instance_id\" value=$instance_id>";  ?>
<input type="text" autocomplete="off" class="text" name="fsp_contact" id="fspEntry" size="12" />
</form>
</td><td>
<button NAME="update_fsp" id="update_fsp" class="button" style="display:inline;" VALUE="Update FSP">Update FSP</button>
</td></tr></table>

</div>
</div>





</div>
<div id="product_info">
<b>PRODUCT INFORMATION</b><hr>
<center>
For detailed product information, click the <b>'Order Detail Summary' button</b> on the <i>Orders List</i> screen in Siebel.
<img style="margin:10px;border:thin solid black;" src="../images/orderDetailButton.png">
</center>


</div>

</td></tr>
<tr><td>

<div id="order_log">

<h3>ORDER ACTIVITY LOG</h3>
<table><tr><td>
<form name="logForm" id="logForm" method="post" action="javascript:void(0);" style="display:inline;">
<input type="hidden" name="order_num" value="<?php echo $order_num; ?>" />
New Entry &nbsp;&nbsp;&nbsp;<i><b>(Do not paste emails here)</b></i><br />
<input type="text" autocomplete="off" class="text" id="logEntry" name="entry" size="80" />
</td></tr><tr><td></td></tr><tr><td>
Time Spent <i>(minutes)</i> <input type="text" autocomplete="off" class="text" id="logTime" name="time_spent" size="6" />
</td></tr><tr><td></td></tr><tr><td>
</form>
<button NAME="update_log" id="update_log" class="button" VALUE="Add Note" style="margin-left:10px;">Add Note</button>
<a class="bodylink" href="notePrint.php?order_num=<?php echo $order_num; ?>" onClick="return popup(this, 'notes')" style="margin-left:10px;">Export Log for Siebel</a>
</td></tr></table>

<div id="logOutput"></div>

</div>

</td></tr></table>

<div id="order_checklist"></div>

</div>
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