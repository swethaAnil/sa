<?php

session_start();

if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  echo "Type: " . $_FILES["file"]["type"] . "<br />";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];
}


move_uploaded_file($_FILES["file"]["tmp_name"],"/tmp/" . $_FILES["file"]["name"]);
echo "Stored in: " . "/tmp/" . $_FILES["file"]["name"];
      
$new_file = "/tmp/" . $_FILES["file"]["name"];




echo "<br><br>";

 
$data = array();

function split_time($time){

$split_date = split("T",$time);
$split_time = split(":",$split_date[1]);
$new_time = $split_time[0] . $split_time[1];
return $new_time;

}

function add_ticket( $acct_name, $acct_num, $access_type, $order_num, $order_type, $market, $package, $timeslot){
	
	global $data;
	
	$new_timeslot = split_time($timeslot);
	
	$data[]= array(
	'acct_name' => $acct_name,
	'acct_num' => $acct_num,
	'access_type' => $access_type,
	'order_num' => $order_num,
	'order_type' => $order_type,
	'market' => $market,
	'package' => $package,
	'timeslot' => $new_timeslot, 
	);
}

if ( $new_file ){
	
	$dom = DOMDocument::load( $new_file );
	$rows = $dom->getElementsByTagName( 'Row' );
	$first_row = true;
	
	foreach ($rows as $row){
		if ( !$first_row ){
			$acct_name = "";
			$acct_num = "";
			$access_type = "";
			$order_num = "";
			$order_type = "";
			$market = "";
			$package = "";
			$timeslot = "";

			$index = 1;
			$cells = $row->getElementsByTagName( 'Cell' );
			
			foreach( $cells as $cell ){ 
				$ind = $cell->getAttribute( 'Index' );
				if ( $ind != null ) $index = $ind;

				if ( $index == 1 ) $acct_name = $cell->nodeValue;
				if ( $index == 2 ) $acct_num = $cell->nodeValue;
				if ( $index == 3 ) $access_type = $cell->nodeValue;
				if ( $index == 4 ) $order_num = $cell->nodeValue;
				if ( $index == 5 ) $order_type = $cell->nodeValue;
				if ( $index == 6 ) $market = $cell->nodeValue;
				if ( $index == 7 ) $package = $cell->nodeValue;
				if ( $index == 8 ) $timeslot = $cell->nodeValue;

				$index += 1;
			}
		
		add_ticket( $acct_name, $acct_num, $access_type ,$order_num, $order_type, $market, $package, $timeslot);
		}
		
	$first_row = false;
	}
}

$_SESSION['data']=$data;

?> 

<html>

<style type="text/css">

body{
font-size:80%;
font-family:"arial";
background-color:#EEEEEE;
color:black;
}

#customers
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:100%;
border-collapse:collapse;
}
#customers td, #customers th 
{
font-size:0.75em;
white-space: nowrap;
border:1px solid #191970;
padding:3px 7px 2px 7px;
}
#customers th 
{
font-size:1em;
white-space: nowrap;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#191970;
color:#ffffff;
}
#customers tr.alt td 
{
color:#000000;
background-color:#EAF2D3;
}
</style>



<body>
<table id="customers">
<tr>
<th>Account Name</th>
<th>Account Number</th>
<th>Access Type</th>
<th>Order Number</th>
<th>Order Type</th>
<th>Market</th>
<th>Package</th>
<th>Timeslot</th>
</tr>
<?php foreach( $data as $row ) { ?>
<tr>
<td><?php echo( $row['acct_name'] ); ?></td>
<td><?php echo( $row['acct_num'] ); ?></td>
<td><?php echo( $row['access_type'] ); ?></td>
<td><?php echo( $row['order_num'] ); ?></td>
<td><?php echo( $row['order_type'] ); ?></td>
<td><?php echo( $row['market'] ); ?></td>
<td><?php echo( $row['package'] ); ?></td>
<td><?php echo( $row['timeslot'] ); ?></td>
</tr>
<?php } ?>
</table>

<br><br>
<form action="sched_database.php" method="post">
<b>Does this look OK? Are all the elements correct and in the proper column?</b>
<input type="Submit" value="YES, put this info in the database.">
</form>
<FORM><INPUT TYPE="BUTTON" VALUE="Cancel" ONCLICK="window.location.href='schedule_settings.php'"></form>




</body>
</html>

  

  

