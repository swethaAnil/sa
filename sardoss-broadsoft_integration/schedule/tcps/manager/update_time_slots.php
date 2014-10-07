<html>

<title>SA Schedule - TCPS Time Slot Update</title>
<head>
     <link rel="stylesheet" href="../../style/schedule_style.css" type="text/css">
</head>
<body>

<h1>TCPS Schedule - Time Slot Update</h1>

<form action="update_time_slots.php" method="POST">
Select a month: 
<select name="month">
	<option value=""></option>
	<option value="1">Jan</option>
	<option value="2">Feb</option>
	<option value="3">Mar</option>
	<option value="4">Apr</option>
	<option value="5">May</option>
	<option value="6">Jun</option>
	<option value="7">Jul</option>
	<option value="8">Aug</option>
	<option value="9">Sep</option>
	<option value="10">Oct</option>
	<option value="11">Nov</option>
	<option value="12">Dec</option>
</select> 

<select name="year">
	<option value="2013">2013</option>
	<option value="2014">2014</option>
	<option value="2015">2015</option>
	<option value="2016">2016</option>
</select> 

<input type="submit" value="Submit" />

</form>


<?php

if($_POST['month'] && $_POST['year']){

	
	
	include '../../bin/dbinfo.inc.php';

	//CONNECT TO MYSQL
	$connection = @mysql_connect($mysqladd,$mysqluser,$mysqlpass) or die(mysql_error());
	$database = @mysql_select_db($databasename,$connection) or die(mysql_error());

	$dateDisplay = date("F Y", mktime(0, 0, 0, $_POST['month'], 1, $_POST['year']));
	
	echo "<h2>Currently viewing: $dateDisplay</h2>";
	echo "<FORM METHOD=\"POST\" ACTION=\"submit_time_slot_update.php\">";
	echo "<table><tr>
		<th class=\"coverage\">Date</th>
		<th class=\"personnel\">Active?</th>
		<th class=\"personnel\">7:30A</th>
		<th class=\"personnel\">8:30A</th>
		<th class=\"personnel\">9:30A</th>
		<th class=\"personnel\">10:30A</th>
		<th class=\"personnel\">11:30A</th>
		<th class=\"personnel\">12:30A</th>
		<th class=\"personnel\">1:30P</th>
		<th class=\"personnel\">2:30P</th>
		<th class=\"personnel\">3:30P</th>
		<th class=\"personnel\">4:30P</th>
		<th class=\"personnel\">5:30P</th>
		<th class=\"personnel\">6:30P</th>
		<th class=\"personnel\">7:30P</th>
		<th class=\"personnel\">8:30P</th></tr>";
	
	$slot_output = "SELECT * FROM tcps_time_slots WHERE MONTH(date) = '$_POST[month]' AND YEAR(date) = '$_POST[year]' ORDER BY date";
	$slot_result = mysql_query($slot_output,$connection);
	$row_count = 0;
	while($date = mysql_fetch_array($slot_result)){
		$slot_data = array();
		$slot_data[] = $date['dateID'] . "_active";
		$slot_data[] = $date['dateID'] . "_timeslot0730";
		$slot_data[] = $date['dateID'] . "_timeslot0830";
		$slot_data[] = $date['dateID'] . "_timeslot0930";
		$slot_data[] = $date['dateID'] . "_timeslot1030";
		$slot_data[] = $date['dateID'] . "_timeslot1130";
		$slot_data[] = $date['dateID'] . "_timeslot1230";
		$slot_data[] = $date['dateID'] . "_timeslot1330";
		$slot_data[] = $date['dateID'] . "_timeslot1430";
		$slot_data[] = $date['dateID'] . "_timeslot1530";
		$slot_data[] = $date['dateID'] . "_timeslot1630";
		$slot_data[] = $date['dateID'] . "_timeslot1730";
		$slot_data[] = $date['dateID'] . "_timeslot1830";
		$slot_data[] = $date['dateID'] . "_timeslot1930";
		$slot_data[] = $date['dateID'] . "_timeslot2030";
		
		
			if($row_count == '0'){
				$cell_def = "class=\"personnel1\"";
				$row_count = 1;
			}elseif($row_count == '1'){
				$cell_def = "class=\"personnel2\"";
				$row_count = 0;
			}
		
		$this_month =  substr($date['date'],5,2);
		$this_year = substr($date['date'],0,4);
		$this_day = substr($date['date'],-2);
		$fullDate = mktime(0,0,0,$this_month,$this_day,$this_year);
		$date_output = date('n/j/Y - l',$fullDate);
		
		echo "<tr><td $cell_def>" . $date_output . "</td>";
		
		
		echo "<td $cell_def align=\"center\"><input type=\"hidden\" name=$slot_data[0] value=\"off\"><input type=\"checkbox\" name=$slot_data[0]";
		if($date['active'] == '1'){echo " checked";}
		echo "></td>
		";

		$counter = 1;
		
		for($i=3;$i<=16;$i++){
			echo "<td $cell_def align=\"center\"><input type=\"text\" value=$date[$i] name=$slot_data[$counter] size=\"1\"></td>
			";
			$counter++;
		}
		
		
	}
	echo "</table><br>";

	echo "<INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Submit Update\"></FoRM>";
}
?>
<FORM><INPUT TYPE="BUTTON" VALUE="Cancel" ONCLICK="window.location.href='./'"></form>

</body>
</html> 