<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>12th Hour Retention - IAD/BTS Assistant</title>

<style>

h3{
	color:navy;
	font-family:"Arial";
}

body{
	font-family:"Arial";
}

</style>

</head>


<?php

$bts_lines = explode("\n",$_POST['bts_data']);
$sub_profile = $_POST['sub_profile'];

foreach($bts_lines as $line){
	
	$line_parts = explode("=", $line);
	$key = $line_parts[0];
	$value = $line_parts[1];
	
	switch($key){	
		case "ID":
			$sub_id_parts = explode("-",$value);
			$sub_id_num = trim($sub_id_parts[1]);
			$sub_id[$sub_id_num] = $value;
			break;
		case "DN1":
			$dn1[$sub_id_num] = $value;
			$i++;
			break;
		default:
			break;
	}
}
?>

<h3>Use this list to check the status of numbers in Port PS</h3>

<textarea rows=2 cols=80 readonly>
<?php
for($i=1; $i<=48; $i++){
	if($sub_id[$i]){
		$this_number = trim($dn1[$i]);
		echo $this_number . ",";
	}
}	
?>
</textarea>

<br />
<br />
<h3>BTS config</h3>
<textarea rows=20 cols=80 readonly>
<?php

for($i=1; $i<=48; $i++){
	if($sub_id[$i]){
		$this_sub = trim($sub_id[$i]);
		echo "\n\nchange dn2subscriber status=ASSIGNED;sub-id=" . $this_sub . ";fdn=" . $dn1[$i];
		echo "\nchange sub id=" . $this_sub . ";sub_profile_id=" . $sub_profile;
	}
}	
?>
</textarea>

<br />
<br />
<h3>IAD config</h3>
<textarea rows=12 cols=80 readonly>


!
int f0/0
no ip policy route-map LissomControl
!
no access-list 128
!
no route-map LissomControl permit 10
!

</textarea>







