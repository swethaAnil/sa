



<HTML>

<BODY>

<?php 


$input = $_POST[text1];

$lines = explode("\n",$input);
$ALL_CPE = array();
echo "<table border=1><tr><th>HSL</th><th>IP ADDRESS</th><th>Software State</th><th>Serial #</th><th>Model</th><th>Software Version</th></tr>";

foreach($lines as $line){
	
	$line = trim($line);
	
	
	if(substr($line,1,3) == "HSL"){
		if(substr($line,6,1) == ":"){
			$HSL = substr($line,1,5);
		}elseif(substr($line,7,1)== ":"){
			$HSL = substr($line,1,6);
		}else{
			$HSL = substr($line,1,6);
		}
		$values = explode(",",$line);
		$sw_ver = str_replace("\"","",$values[14]);
				
		$CPE = array(array(
				"HSL" => $HSL,
				"IP" => $values[2],
				"STATE" => $values[8],
				"SERIAL" => $values[10],
				"MODEL" => $values[12],
				"VER" => $sw_ver));
		
		foreach($CPE as $CPE_tempone){
				echo "<td>$CPE_tempone[HSL]</td><td>$CPE_tempone[IP]</td><td>$CPE_tempone[STATE]</td><td>$CPE_tempone[SERIAL]</td><td>$CPE_tempone[MODEL]</td><td>$CPE_tempone[VER]</td></tr>";
		}
	$ALL_CPE = array_merge($ALL_CPE, $CPE);
	}
}
	





echo "</table><br><br>";

echo "<hr>CPE LOGIN<HR>";

foreach($ALL_CPE as $tempone){
	foreach ($tempone as $key=>$temptwo) {
		if($key == "SERIAL"){
			echo "act-user:" . $temptwo . ":admin:::admin;<br>";
		}
	}
}

echo "<hr>Retrieve Software<HR>";

foreach($ALL_CPE as $tempone){
	foreach ($tempone as $key=>$temptwo) {
		if($key == "SERIAL"){
			echo "rtrv-sw:" . $temptwo . ";<br>";
		}
	}
}

echo "<hr>Load Software<HR>";

foreach($ALL_CPE as $tempone){
	foreach ($tempone as $key=>$temptwo) {
		if($key == "SERIAL"){
			echo "load-sw:" . $temptwo . ":::::ipaddr=10.128.8.16,filename=/nfs/tftpboot/ml600-R710-36-ssh-cby.mft,uid=[USER],pid=[PASS];<br>";
		}
	}
}

echo "<hr>Activate Software<HR>";

foreach($ALL_CPE as $tempone){
	foreach ($tempone as $key=>$temptwo) {
		if($key == "SERIAL"){
			echo "invk-sw:" . $temptwo . ";<br>";
		}
	}
}
echo "<hr>Commit Software<HR>";

foreach($ALL_CPE as $tempone){
	foreach ($tempone as $key=>$temptwo) {
		if($key == "SERIAL"){
			echo "cmmt-sw:" . $temptwo . ";<br>";
		}
	}
}


?>



</BODY>

</HTML>