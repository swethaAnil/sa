<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php


include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$date = $_GET['date'];

$getOrdersSQL = "SELECT * FROM tcps_orders WHERE DATE(activation_date) = DATE('$date') ORDER BY timeslot";
$getOrdersResult = mysql_query($getOrdersSQL,$connection);


?>



<html>

<head>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->



<style>

#close{
	position:absolute;
	right:5px;
	font-size:90%;
	top:4px;
	cursor:hand;
	
}

#reserveButtons{
	position:relative;
	width:175px;
	height:470px;
	float:left;
	background-color:#333333;

}

#slotButtonOverflow{
	height:439px;
	overflow:auto;
}




table.reserveSlotButtonTable{
	position:relative;
	margin-left:auto;
	margin-right:auto;
	top: 15px;
	
}

table.reserveSlotButtonTable th{

	text-align:center;
	padding: 2px;
	color:black;
	background-color:lightgray;
	border:none;
	font-weight:normal;

}

table.reserveSlotButtonTable td{
	background-color:#333333;
	text-align:center;
	padding:7px;
	border:none;
	border-bottom:1px solid white;
	
}


#currentOrders{
	
	position:relative;
	float:left;
	height:470px;
	width:725px;
	
	
	
	
}

#ordersList{
	position:relative;
	height:429px;
	overflow:auto;
	
	
	
	
	

}

table.currentOrders{
	position:relative;
	width:100%;
	
	
}



table.currentOrders th{

	text-align:center;
	padding: 2px;
	color:black;
	background-color:lightgray;
	border:none;
	border-bottom:2px solid #333333;
	font-weight:normal;
	font-size:70%;
	white-space:nowrap;

}

table.currentOrders td{

	text-align:center;
	padding: 8px;
	border:none;
	border-bottom:1px solid #333333;
	font-size:80%;
	white-space:nowrap;
	

}

.reserveSlotButton{
	
	width:150px;

}

.detailTitleBar{

	height:30px;
	background-color:#333333;
	
}

#dateTitle{
	position:relative;
	top:3px;
	margin-left:7px;
	font-size: 100%;
	color:white;
	background-color:#333333;
	
}

#reserveSlotForm{
	display:none;
	position:absolute;
	height:300px;
	width:400px;
	border:1px solid black;
	left:210px;
	top:40px;
	background-color:lightgray;


}

#reserveButtonsTitle{
	position:relative;
	border-bottom:1px solid #333333;
	height:30px;
	width:100%;
	background-color:white;
	font-size:90%;
	font-weight:bold;
	text-align:center;
	
}


#currentOrdersTitle{
	position:relative;
	border-bottom:1px solid #333333;
	height:30px;
	width:100%;
	background-color:white;
	font-size:90%;
	font-weight:bold;
	text-align:center;

}

#clickToReserve{
	padding-top:7px;
	font-size:70%;
	color:white;
	text-align:center;
}

.slotLink{

	color:white;
	cursor:pointer; 
	
}
	

</style>	 
	 
</head>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
<script>
$(document).ready(function(){

	$.ajaxSetup({ cache:false });

	$("#close").click(function(){
		
		$("#dateDetail").animate({
			left:'600px',
			width:'250px',
			height:'450px'
		});
		$("#dateDetail").css("display","none");
		$("#dateDetail").load("blank.htm");
		$("#calendar").animate({opacity:'1.0'});
		$("#keyTable").animate({opacity:'1.0'});
		
	}); 
	
	/*
	$(".reserveSlotButton").click(function(){
		var timeToReserve = $(this).val();
		$("#reserveSlotForm").css("display","block");
		$("#reserveSlotForm").load("reserveSlot.php?slot=" + timeToReserve);
	});
	*/
	
});
</script>



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
	
	myWindow = window.open(href, windowname, 'width=400,height=400,scrollbars=no,status=no,toolbar=no,location=no,top=200,left=200');
	myWindow.focus();
	return false;
}
//-->

</script>





<body>
<?php
$day = substr($date,-2);
if(substr($day,0,1) == 0){$day = substr($day,-1); }
$month =  substr($date,5,2);
$year = substr($date,0,4);
$mktimeDate = mktime(0,0,0,$month,$day,$year);
$date_output = date('l, F jS, Y',$mktimeDate);

?>

<div class="detailTitleBar"><div id="dateTitle"><?php echo $date_output; ?></div><a href class="slotLink" id="close" value="<?php echo $date; ?>" ><u>Close Window</u></a></div>

<div id="reserveButtons">
<div id="reserveButtonsTitle"><div style="padding-top:7px;">Available Slots</div></div>
<div id="slotButtonOverflow">
<div id="clickToReserve">Select time to reserve a slot</div>
<?php


$curr_day = 0;
$curr_slot = 0;



$slotLimitsSQL = "SELECT * FROM tcps_time_slots WHERE DATE(date) = DATE('$date') ORDER BY date";
$slotLimitsResult = mysql_query($slotLimitsSQL,$connection);
while($row = mysql_fetch_array($slotLimitsResult,MYSQL_ASSOC)){
	
	$date = $row['date'];
	$slotLimitData[$date] = $row;

}


$slotDataSQL = "SELECT count(timeslot) AS used_slots,activation_date,timeslot FROM tcps_orders WHERE DATE(activation_date) = DATE('$date') GROUP BY activation_date, timeslot";
$slotDataResult = mysql_query($slotDataSQL,$connection);
while($row = mysql_fetch_array($slotDataResult)){
	
	$activation_date = $row['activation_date'];
	$timeslot = $row['timeslot'];
	$usedSlots[$activation_date][$timeslot] = $row['used_slots'];

}
	

foreach($slotLimitData as $date => $data){
	
	$this_day = substr($date,-2);
	if(substr($this_day,0,1) == 0){$this_day = substr($this_day,-1); }
	$this_month =  substr($date,5,2);
	$this_year = substr($date,0,4);
	$this_date = mktime(0,0,0,$this_month,$this_day,$this_year);
	$date_output = date('l, F jS, Y',$this_date);
	

	echo "<table class=\"reserveSlotButtonTable\">";
	foreach($data as $key => $value){
		if($key == "dateID" || $key == "date" || $key == "active"){ continue; }
		$this_slot = substr($key,8);
		if(substr($this_slot,0,1) == 0){ $this_slot = substr($this_slot,1); }
		$hour = substr($this_slot,0,-2);
		if(isset($usedSlots[$date][$this_slot])){
			$availSlots = $value - $usedSlots[$date][$this_slot];	
		}else{
			$availSlots = $value;
		}
		if($availSlots > 0){ 
			$time = mktime($hour,30,0,$this_month,$this_day,$this_year);
			$timeDisplay = date("g:i A", $time);
			//echo "<div class=\"slotItem\">" . $timeDisplay . " - " . $availSlots . "</div>
			//";
			//echo "<tr><td><button class=\"reserveSlotButton\" onClick=\"return popup('reserveSlotForm.php?slot=$time', 'reserveSlotWindow')\" value=\"$time\">Reserve " . $timeDisplay . " Slot</button></td></tr>
			//";
			echo "<tr><td><a href class=\"slotLink\" onClick=\"return popup('reserveSlotForm.php?slot=$time', 'reserveSlotWindow')\" >" . $timeDisplay . "</a></td></tr>
			";

		}
	}
	echo "</table>";
}
	
?>
</div>
</div>



<div id="currentOrders">

<div id="currentOrdersTitle"><div style="padding-top:7px;">Currently Scheduled Orders</div></div>
<div id="ordersList">
<table class="currentOrders">
<tr><th>Time Slot</th><th>Account Number</th><th>Account Name</th><th>Order Number</th><th>Type *</th><th>Reserved By</th><th></th></tr>
<?php

while($row = mysql_fetch_array($getOrdersResult)){

	$this_slot = $row['timeslot'];
	if(substr($this_slot,0,1) == 0){ $this_slot = substr($this_slot,1); }
	$hour = substr($this_slot,0,-2);
	$time = mktime($hour,30,0,0,0,0);
	$timeDisplay = date("g:i A", $time);
	$orderTypeInitial = substr($row['order_type'],0,1);

	if($row['off_net'] == "1"){

		$off_net = ",O";
	}

	echo "<tr><td>$timeDisplay</td><td>$row[acct_num]</td><td>$row[acct_name]</td><td>$row[order_num]</td><td>$orderTypeInitial$off_net</td><td>$row[reserved_by]</td>
	<td style=\"padding-left:0px;margin:0px;width:18px;\">
	<a href name=\"Remove Slot\" value=\"Remove Slot\" onClick=\"return popup('removeSlot.php?instance_id=$row[instance_id]', 'reserveSlotWindow')\" 
	style=\"margin-left:0px;cursor:hand;\"><img src=\"../images/red_x_icon.png\" width=\"13px\" height=\"13px\" align=\"center\" /></a></td>
	</tr>
	";
	
}

?>
</table>
<div style="position:absolute;bottom:0px;right:10px;font-size:70%">* N = New, C = Conversion, O = Off-Net</div>
</div>



</div>

<div id="reserveSlotForm"></div>
</body>
</html> 
