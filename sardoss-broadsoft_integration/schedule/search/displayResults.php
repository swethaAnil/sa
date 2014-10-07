<html>

<head>

<style>

#resultList{
	margin:20px;
	font-size:80%;
}



.schedhead th{
white-space:nowrap;
text-align:left;
color:white;
background-color:#4D4D4D;
padding-right:20px;
}

.nowrap td{
white-space:nowrap;
text-align:left;
background-color:#F2F2F2;
}

.nowrap:hover td{
	background-color:gray;
	color:white;
}


</style>

<SCRIPT TYPE="text/javascript">
<!--
function popupA(mylink, windowname)
{
if (! window.focus)return true;

var href;

if (typeof(mylink) == 'string')
	href=mylink;
else
	href=mylink.href;
	myWindow = window.open(href, windowname, 'width=1150,height=800,scrollbars=yes,status=no,toolbar=no,location=no');
	myWindow.focus();
	return false;
}
//-->

</script>

<SCRIPT TYPE="text/javascript">
<!--
function popupB(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=1030,height=800,scrollbars=yes,status=no,toolbar=no,location=no');
return false;
}
//-->

</script>


</head>

<body>


<?php

//GLOBAL OPTIONS
require '../bin/log_func.php';
require '../bin/dbinfo.inc.php';

$username = $_SERVER['PHP_AUTH_USER'];

//PAGE SPECIFIC CONFIGURATION BEGINS

$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

$searched = $_POST['searchEntry'];
$order_nums = array();
$num_lists = 0;

$starttime = microtime(true);


//SEARCH FOR SCHEDULED ORDERS IN GENERAL ORDER TABLE
//$orderSearchSQL = "SELECT * FROM $ordertable WHERE acct_num LIKE '%$searched%'";

$orderSearchSQL = "SELECT * FROM current_orders WHERE (
					acct_num LIKE '%$searched%' OR
					order_num LIKE '%$searched%' OR
					acct_name LIKE '%$searched%') ORDER BY activation_date DESC LIMIT 100";  

$orderSearchResult = @mysql_query($orderSearchSQL,$connection);
$orderResultCount = mysql_num_rows($orderSearchResult);

echo "Search results for '<i>$searched</i>'...<br><br>";
echo "<u><b>&nbsp;&nbsp;
Scheduled Orders ($orderResultCount found)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></u><br>";
echo "<div id=\"resultList\">";
if($orderResultCount > 0){

	echo "<table><tr class=\"schedhead\"><th>Acct #</th><th>Account Name</th><th>Order #</th><th>Scheduled Activation Date</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>SA Tech</th><th>Status</th></tr>";
	while($row = mysql_fetch_array($orderSearchResult)){
	
		$timeslot = $row['timeslot'];
		if($timeslot == 0){
			$newtimeslot = "ANYTIME";
		}else{
			$hour = substr($timeslot,0,-2);
			$time = mktime($hour,30,0,0,0,0);
			$newtimeslot = date("g:i A",$time);
		}
		
		$instance_id = $row['instance_id'];
				
		$id = $row['tech_id'];
		if($id != "1000"){
			$tech_output = "SELECT * FROM techs WHERE tech_id = '$id'";
			$tech_result = mysql_query($tech_output,$connection);
			while($tech = mysql_fetch_array($tech_result)){
				$tech_name = $tech['first_name'] . " " . $tech['last_name'];
			}
		}else{
			$tech_name = "Overflow";
		}
		if($username != "guest"){
			echo "<tr ondblClick=\"return popupA('../dashboard/order_dashboard.php?order_num=$row[order_num]', $instance_id)\" class=\"nowrap\" style=\"height:20px;\" ><td>" . $row['acct_num'] . "</td><td>" . $row['acct_name'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['activation_date'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td><td>" . $tech_name . "</td><td>" . $row['status'] . "</td></tr>";
		}else{
			echo "<tr class=\"nowrap\" style=\"height:20px;\" ><td>" . $row['acct_num'] . "</td><td>" . $row['acct_name'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['activation_date'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td><td>" . $tech_name . "</td><td>" . $row['status'] . "</td></tr>";
		}
		
		if(in_array($row['order_num'],$order_nums)){
		}else{
			$order_nums[] = $row['order_num'];
		}
	}
}
echo "</table></div>";




//SEARCH FOR 2.0 ORDERS IN 2.0 ORDER TABLE
$v2orderSearchSQL = "SELECT * FROM $v2ordertable WHERE (
					acct_num LIKE '%$searched%' OR
					order_num LIKE '%$searched%' OR
					acct_name LIKE '%$searched%') ORDER BY activation_date DESC LIMIT 100 ";  
$v2orderSearchResult = @mysql_query($v2orderSearchSQL,$connection);
$v2orderResultCount = mysql_num_rows($v2orderSearchResult);

echo "<u><b>&nbsp;&nbsp;
2.0 Orders ($v2orderResultCount found)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></u><br>";
echo "<div id=\"resultList\">";
if($v2orderResultCount > 0){

	echo "<table><tr class=\"schedhead\"><th>Acct #</th><th>Account Name</th><th>Order #</th><th>Scheduled Activation Date</th><th>Order Type</th><th>Access</th><th>Market</th><th>Package</th><th>Time Slot</th><th>2.0 Tech</th><th>2.0 Status</th></tr>";
	while($row = mysql_fetch_array($v2orderSearchResult)){
	
		$timeslot = $row['timeslot'];
		if($timeslot == 0){
			$newtimeslot = "ANYTIME";
		}else{
			$hour = substr($timeslot,0,-2);
			$time = mktime($hour,30,0,0,0,0);
			$newtimeslot = date("g:i A",$time);
		}
		$instance_id = $row['instance_id'];
				
		$id = $row['v2_tech_id'];
		if($id != "1000"){
			$tech_output = "SELECT * FROM techs WHERE tech_id = '$id'";
			$tech_result = mysql_query($tech_output,$connection);
			while($tech = mysql_fetch_array($tech_result)){
				$tech_name = $tech['first_name'] . " " . $tech['last_name'];
			}
		}else{
			$tech_name = "Unassigned";
		}
		if($username != "guest"){
			echo "<tr ondblClick=\"return popupB('../v2/v2_order_dashboard.php?order_num=$row[order_num]', $instance_id)\" class=\"nowrap\" style=\"height:20px;\" ><td>" . $row['acct_num'] . "</td><td>" . $row['acct_name'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['activation_date'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td><td>" . $tech_name . "</td><td>" . $row['status'] . "</td></tr>";
		}else{
			echo "<tr class=\"nowrap\" style=\"height:20px;\" ><td>" . $row['acct_num'] . "</td><td>" . $row['acct_name'] . "</td><td>" . $row['order_num'] . "</td><td>" . $row['activation_date'] . "</td><td>" . $row['order_type'] . "</td><td>" . $row['access_type'] . "</td><td>" . $row['market'] . "</td><td>" . $row['package'] . "</td><td>" . $newtimeslot . "</td><td>" . $tech_name . "</td><td>" . $row['status'] . "</td></tr>";
		}
		
		if(in_array($row['order_num'],$order_nums)){
		}else{
			$order_nums[] = $row['order_num'];
		}
	}
}
echo "</table></div>";





//SEARCH FOR ORDER CHECKLISTS FROM ORDERS PREVIOUSLY FOUND
foreach($order_nums as $order_num){

	$checklistSQL = "SELECT checklist FROM $ordertable WHERE order_num = '$order_num'";
	$checklistResult = @mysql_query($checklistSQL,$connection);
	$checklist = mysql_fetch_row($checklistResult);
		
	if($checklist[0] != NULL){
		$num_lists++;
		$checklistOutput .= "<a style=\"color:blue;text-decoration:underline;\" href=\"../checklist/readChecklist.php?order_num=$order_num\" onClick=\"return popup(this, 'notes')\">Order Number $order_num</a><br>";
	}
}
	
echo "<u><b>&nbsp;&nbsp;
Order Checklists ($num_lists checklists found)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></u><br>";
echo "<div id=\"resultList\">" . $checklistOutput . "</div>";
 
 
 
 
 
//SEARCH FOR 2.0 CHECKLISTS FROM ORDERS PREVIOUSLY FOUND 
foreach($order_nums as $order_num){

	$v2checklistSQL = "SELECT checklist FROM $v2ordertable WHERE order_num = '$order_num'";
	$v2checklistResult = @mysql_query($v2checklistSQL,$connection);
	$v2checklistCount = mysql_num_rows($v2checklistResult);
	$v2checklist = mysql_fetch_row($v2checklistResult);
	
	if($v2checklist[0] != NULL){
		$num_v2lists++;
		$v2checklistOutput .= "<a style=\"color:blue;text-decoration:underline;\" href=\"../v2/v2checklist/v2readChecklist.php?order_num=$order_num\" onClick=\"return popup(this, 'notes')\">Order Number $order_num</a><br>";
	}
}
	
echo "<u><b>&nbsp;&nbsp;
2.0 Pre-Check Checklists ($num_v2lists checklists found)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></u><br>";
echo "<div id=\"resultList\">" . $v2checklistOutput . "</div>";

$endtime = microtime(true);
$duration = $endtime - $starttime; //calculates total time taken
$queryTime = round($duration * 1000,2);

echo "<div style=\"font-size:80%;position:relative;margin-top:40px;left:800px;margin-bottom:60px;\">Query time: $queryTime ms</div>";

?>


</body>
</html>