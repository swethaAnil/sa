<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

$username = $_SERVER['PHP_AUTH_USER'];

include '../bin/dbinfo.inc.php';

//CONNECT TO MYSQL
$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());


$position_array = array(
	7 => 1,
	1 => 2,
	2 => 3,
	3 => 4,
	4 => 5,
	5 => 6,
	6 => 7
);

function checkDayStatus($date,$curr_day){

	global $connection;
	global $database;
	
	$availCount = 0;
	$checkSlotData = NULL;
	$usedSlots = NULL;
	$timeslot = NULL;
	$checkSlotSQL = "SELECT * FROM tcps_time_slots WHERE DATE(date) = DATE('$date')";
	$checkSlotResult = mysql_query($checkSlotSQL, $connection);
	while($row = mysql_fetch_array($checkSlotResult,MYSQL_ASSOC)){
		$checkSlotData = $row;
	}
	
	$slotDataSQL = "SELECT count(timeslot) AS used_slots,timeslot FROM tcps_orders WHERE DATE(activation_date) = DATE('$date') GROUP BY timeslot";
	$slotDataResult = mysql_query($slotDataSQL,$connection);
	while($count = mysql_fetch_array($slotDataResult)){
		$timeslot = $count['timeslot'];
		$usedSlots[$timeslot] = $count['used_slots'];
	}
	if(isset($checkSlotData)){
		foreach($checkSlotData as $key => $value){
			if($key == "dateID" || $key == "date" || $key == "active"){ continue; }
			$slot = substr($key,-4);
			if(substr($slot,0,1) == 0){$slot = substr($slot,-3); }
			if(isset($usedSlots[$slot])){
				$availSlots = $value - $usedSlots[$slot];	
			}else{
				$availSlots = $value;
			}
			if($availSlots > 0){	$availCount += $availSlots; }
		}
	}

	
	$usedSlotSQL = "SELECT count(order_type) AS order_count, order_type FROM tcps_orders WHERE DATE(activation_date) = DATE('$date') GROUP BY order_type";
	$usedSlotResult = mysql_query($usedSlotSQL,$connection);
	while($usedSlotRow = mysql_fetch_array($usedSlotResult)){
		if($usedSlotRow['order_type'] == "New"){
			$usedSlotsNew = $usedSlotRow['order_count'];
		}
		if($usedSlotRow['order_type'] == "Conversion"){
			$usedSlotsConv = $usedSlotRow['order_count'];
		}
	}
	
	if(isset($usedSlotsNew)){}else{	$usedSlotsNew = 0;	}
	if(isset($usedSlotsConv)){}else{	$usedSlotsConv = 0;	}
	
	$this_day = substr($date,-2);
	if(substr($this_day,0,1) == 0){$this_day = substr($this_day,-1); }
	$this_month =  substr($date,5,2);
	$this_year = substr($date,0,4);
	$this_date = mktime(0,0,0,$this_month,$this_day,$this_year);
	
	if($this_date <= $curr_day){
		$class = "inactive";
		$totalUsedString = NULL;
	}elseif($checkSlotData['active'] == "0" || $checkSlotData['active'] == NULL){
		$class = "inactive";
		$totalUsedString = NULL;
	}elseif($availCount <= 0){
		$totalUsedString = "New: $usedSlotsNew<br />Conv: $usedSlotsConv<br />Open: $availCount";
		$class = "full";
	}else{
		$totalUsedString = "New: $usedSlotsNew<br />Conv: $usedSlotsConv<br />Open: $availCount";
		$class = "free";
	}
	
	return array($class,$totalUsedString);

}


function displayMonth($month,$year){

	global $position_array;
	
	$curr_day = mktime(0,0,0,date('m'),date('d'),date('Y'));
	$first_day = mktime(0,0,0,$month,1,$year);
	$dayoftheweek = date('N',$first_day);
	$daysInMonth = date('t',$first_day);
	$dayOnePosition = $position_array[$dayoftheweek];
	$rowsToDisplay = ceil(($daysInMonth + $dayOnePosition)/7);
	$monthDisplay = date('M Y',$first_day);
	if($month == 1){
		$prevMonth = 12;
		$prevYear = $year - 1;
	}else{
		$prevMonth = $month - 1;
		$prevYear = $year;
	}
	
	if($month == 12){
		$nextMonth = 1;
		$nextYear = $year + 1;
	}else{
		$nextMonth = $month + 1;
		$nextYear = $year;
	}
	
	

	echo "<div id=\"calendar\"><div id=\"calendarTitle\">
		<form name=\"prevMonth\" action=\"index.php\" method=\"GET\"><input type=\"hidden\" name=\"month\" value=\"$prevMonth\"><input type=\"hidden\" name=\"year\" value=\"$prevYear\"><input type=\"image\" src=\"../images/leftArrow.png\" width=\"25px\" height=\"20px\"  style=\"margin-right:7px\"/></form>
		" . $monthDisplay . "
		<form name=\"nextMonth\" action=\"index.php\" method=\"GET\"><input type=\"hidden\" name=\"month\" value=\"$nextMonth\"><input type=\"hidden\" name=\"year\" value=\"$nextYear\"><input type=\"image\" src=\"../images/rightArrow.png\" width=\"25px\" height=\"20px\"/></form>
		</div><br /><span style=\"font-size:70%;\">Mouse over active days to see available slots.  Click to reserve a slot.  (All times Eastern)</span><br /><table class=\"calendar\"><tr><th>Sun</th><th>Mon</th><th>Tues</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>
	";

	$position=1;

	//build first week of calendar

	echo "<tr>";

	for($i=0;$i<7;$i++){
		
		if(isset($day)){
			$date = "$year-$month-$day";
			$dayInfo = checkDayStatus($date, $curr_day);
			echo "<td class=\"$dayInfo[0]\" id=\"calendar$day\" >$day<span style=\"display:none\">|$date|</span>
			<div class=\"usedSlotsInCalendar\">$dayInfo[1]</div></td>
			";
			$day++;
		}elseif($position == $dayOnePosition){
			$day = 1;
			$date = "$year-$month-$day";
			$dayInfo = checkDayStatus($date, $curr_day);
			echo "<td class=\"$dayInfo[0]\" id=\"calendar$day\" >$day<span style=\"display:none\">|$date|</span>
			<div class=\"usedSlotsInCalendar\">$dayInfo[1]</div></td>
			";
			$day++;
		}else{
			echo "<td style=\"background-color:lightgray;\"></td>";
		}
		$position++;
	}

	echo "</tr>";

	//BUILD REMAINING WEEKS

	for($i=1;$i<=$rowsToDisplay-1;$i++){
		
		echo "<tr>";
		
		for($j=1;$j<=7;$j++){
			if($day > $daysInMonth){
				echo "<td style=\"background-color:lightgray;\"></td>";
			}else{
				$date = "$year-$month-$day";
				$dayInfo = checkDayStatus($date, $curr_day);
				echo "<td class=\"$dayInfo[0]\" id=\"calendar$day\" >$day<span style=\"display:none\">|$date|</span>
				<div class=\"usedSlotsInCalendar\">$dayInfo[1]</div></td>
				";
			}
			$day++;
		}
		echo "</tr>";
	}


	echo "</table>
	<div id=\"keyTableDiv\">
	<table id=\"keyTable\"><tr><td id=\"free\">Slots Available</td><td id=\"full\">No Slots Available</td><td id=\"inactive\">Inactive</td></tr></table>
	</div></div>";

}

?>
<html>


<head>
<title>TCPS Calendar - Service Activations</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
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

table,tr,td,th{
border-collapse:collapse;
}


table.calendar{
	position:fixed;
	
	
}

table.calendar tr,th,td{
border:1px solid black;
border-collapse:collapse;
vertical-align:top;


}


table.calendar th{

	
	
}

table.calendar td{
	height:70px;
	width:70px;
	font-family:'Palatino Linotype','Book Antiqua',Palatino,serif;
	
}

table.availSlots{
	position:relative;
	margin-left:auto;
	margin-right:auto;
	top: 7px;
	
}

table.availSlots th{

	text-align:center;
	padding: 2px;
	color:black;
	background-color:lightgray;
	border:none;
	border-bottom:2px solid #333333;
	font-weight:normal;

}

table.availSlots td{

	text-align:left;
	padding: 5px;
	border:none;
	border-bottom:1px solid gray;

}


.events{

	position:fixed;
	left:600px;
	top:130px;
	display:none;
	border:2px solid #333333;
	height:450px;
	width:250px;
	background-color:lightgray;
	font-size:90%;
	

}

#dateDetail{
	position:fixed;
	background-color:lightgray;
	display:none;
	border:2px solid #333333;
	height:450px;
	width:250px;
	top:130px;
	left:600px;
	
}

.free{
	background-color:lightgreen;
}


.full{

	background-color:red;

}

.inactive{

	background-color:lightgray;

}

.dayInfo{
	position:relative;
	top:10px;
	text-align:center;

}

.eventTitle{

	font-size: 100%;
	padding:6px;
	
	text-align:right;
	color:white;
	background-color:#333333;
	
}




.slotItem{
	
	font-size: 120%;
	text-align: center;


}


#calendarTitle{
	font-size:170%;
	
}



form{
	display:inline;
	

}

#keyTableDiv{
	position:fixed;
	top:125px;
	left:310px;
}

#keyTable td{
	padding:3px;
	padding-left:4px;
	padding-right:4px;
	font-size:75%;
}

#free{
	background-color:lightgreen;
	
}


#full{

	background-color:red;

}

#inactive{

	background-color:lightgray;

}

.usedSlotsInCalendar{
	position:relative;
	top:5px;
	left:18px;
	font-family:'Arial';
	font-size:70%;
	
}






</style>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>
<script>
$(document).ready(function(){

	$.ajaxSetup({ cache:false });

/* 	$(".day").mouseover(function(){
		$(this).css("background-color","blue");
		var ACdate = $(this).text();
		$("#event" + ACdate).show();
		
	  
	});
	
	$(".day").mouseout(function(){
		$(this).css("background-color","white");
		var ACdate = $(this).text();
		$("#event" + ACdate).hide();
	  
	}); */
	
	$(".free").mouseover(function(){
		$(this).css("background-color","darkblue");
		$(this).css("color","white");
		var text = $(this).text();
		var splitText = text.split("|");
		var ACdate = splitText[0];
		$("#event" + ACdate).show();
		
	  
	});
	
	$(".free").mouseout(function(){
		$(this).css("background-color","lightgreen");
		$(this).css("color","black");
		var text = $(this).text();
		var splitText = text.split("|");
		var ACdate = splitText[0];
		$("#event" + ACdate).hide();
	  
	});
	
	$(".full").mouseover(function(){
		$(this).css("background-color","darkblue");
		$(this).css("color","white");
		var text = $(this).text();
		var splitText = text.split("|");
		var ACdate = splitText[0];
		$("#event" + ACdate).show();
		
	  
	});
	
	$(".full").mouseout(function(){
		$(this).css("background-color","red");
		$(this).css("color","black");
		var text = $(this).text();
		var splitText = text.split("|");
		var ACdate = splitText[0];
		$("#event" + ACdate).hide();
	  
	});
	
	$(".free").click(function(){
		var text = $(this).text();
		var splitText = text.split("|");
		$("#calendar").animate({opacity:'0.2'});
		$("#keyTable").animate({opacity:'0.2'});
		$("#dateDetail").css("display","block");
		$("#dateDetail").animate({
			left:'50px',
			width:'900px',
			height:'500px',
			delay:'10000'
		});
		//alert(splitText[1]);
		setTimeout(function(){
			$("#dateDetail").load("dateDetail.php?date=" + splitText[1]);
		},500);
		
	});
	
	
	$(".full").click(function(){
		var text = $(this).text();
		var splitText = text.split("|");
		$("#calendar").animate({opacity:'0.2'});
		$("#keyTable").animate({opacity:'0.2'});
		$("#dateDetail").css("display","block");
		$("#dateDetail").animate({
			left:'50px',
			width:'900px',
			height:'500px',
			delay:'10000'
		});
		setTimeout(function(){
			$("#dateDetail").load("dateDetail.php?date=" + splitText[1]);
		},500);
		
	});
	
});
</script>

</head>

<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:0px;" src="../images/cbey_logo_small.png">
</div>


<div class="pagetitle">
<a class="pagetitle" href="../../">Service Activations</a>
 / <a class="pagetitle" href="../current_schedule.php">Online Schedule</a>
 / 
<span class="location">
<u>TCPS</u>
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<div class="optionbarItems">
<a style="padding-left:10px;" href="current_tcps_orders.php">All TCPS Orders</a>
<a style="padding-left:10px;" href="./manager/">TCPS Manager</a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>
</div>

<div style="min-width:600px;position:relative;top:120px;margin-left:50px;width:100%;">

<?php

$curr_month = date('m');
$curr_year = date('Y');

if($_GET){
	$month = $_GET['month'];
	$year = $_GET['year'];
}else{
	$month = $curr_month;
	$year = $curr_year;
}

displayMonth($month,$year);


$curr_day = 0;
$curr_slot = 0;

$slotLimitsSQL = "SELECT * FROM tcps_time_slots WHERE MONTH(date) = '$month' AND YEAR(date) = '$year' ORDER BY date";
$slotLimitsResult = mysql_query($slotLimitsSQL,$connection);
while($row = mysql_fetch_array($slotLimitsResult,MYSQL_ASSOC)){
	
	$date = $row['date'];
	$slotLimitData[$date] = $row;

}


$slotDataSQL = "SELECT count(timeslot) AS used_slots,activation_date,timeslot FROM tcps_orders WHERE MONTH(activation_date) = '$month' AND YEAR(activation_date) = '$year' GROUP BY activation_date, timeslot";
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
	
	echo "<div class=\"events\" id=\"event$this_day\"><div class=\"eventTitle\">$date_output&nbsp;</div>
	";
	echo "<table class=\"availSlots\"><tr><th colspan=\"2\">Available Time Slots</th></tr>";
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
			$time = mktime($hour,30,0,0,0,0);
			$timeDisplay = date("g:i A", $time);
			//echo "<div class=\"slotItem\">" . $timeDisplay . " - " . $availSlots . "</div>
			//";
			echo "<tr><td>" . $timeDisplay . "</td><td>" . $availSlots . "</td></tr>
			";
		}
	}
	echo "</table></div>";
}
	
	/* 
while($row = mysql_fetch_array($slotDataResult)){
	
	$this_day = substr($row['activation_date'],-2);
	if(substr($this_day,0,1) == 0){$this_day = substr($this_day,-1); }
	$this_month =  substr($row['activation_date'],5,2);
	$this_year = substr($row['activation_date'],0,4);
	$this_date = mktime(0,0,0,$this_month,$this_day,$this_year);
	$date_output = date('l, F jS, Y',$this_date);
		
	if($curr_day == 0){
		
		echo "<div class=\"events\" id=\"event$this_day\"><br />$date_output<br /><br />
		";	
		$curr_day = $this_day;
		//echo "curr day is 0<br />";
	}
	
	if($this_day != $curr_day){
		//echo "this day ($this_day) does not equal curr day ($curr_day)<br />";
		echo "</div><div class=\"events\" id=\"event$this_day\"><br />$date_output<br /><br />
		";
		$curr_day = $this_day;
	
	}
	
	echo "<div style=\"position:relative;margin:10px;\" >
	";
	
	if($row['timeslot'] != $curr_slot){
		$hour = substr($row['timeslot'],0,-2);
		echo $hour . ":30 AM Eastern<hr />
		";
		$curr_slot = $row['timeslot'];
	
	}
	
	//echo "slot div here<br />";
	echo "$row[acct_num] - $row[acct_name]<br /> Order: $row[order_num]<br /><br /></div>
	";
	
	
}

echo "</div>";

 */
?>



</div>

<div id="dateDetail" ></div>
</body>
</html> 