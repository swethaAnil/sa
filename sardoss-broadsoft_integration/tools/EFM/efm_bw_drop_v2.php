
<HTML>

<HEAD>
<TITLE>EFM Bandwidth Drop Tool Results </TITLE>
</HEAD>

<BODY>

<?php 

ini_set('memory_limit', '1024M');

$rawdata = $_POST[text1];
$lines = explode("\n",$rawdata);
$count = 0;
$total_drop = 0;
$num_dropped = 0;

$hsl_count = 0;
$total_hsl_drop = 0;
$num_hsl_dropped = 0;

$HSL = array();

$hsl_count = 0;
$mlp_count = 0;
$ewl_count = 0;

echo "<table border=1>";
//echo "<tr><th>EFMSWITCH</th><th>MLP</th><th>HSL</th><th>INITRATE</TH><TH>CURRATE</TH><TH>BWDROP</TH><TH>CALIBDATE</TH><TH>SNRMREQ</TH><TH>SMODE</TH><TH>EWL</TH>";


foreach($lines as $line){
	
	$enil = strrev($line);
		
	if(substr($line,0,3)=="EFM"){
	
		preg_match("/EFM[0-9]+[A-Z][A-Z][A-Z]/",$line,$efm_switch);
		$switch = $efm_switch[0];
	}
	
	if(substr($line,4,3)=="HSL"){	
		
		preg_match("/HSL-[0-9]+/",$line,$this_hsl);	
		$newhsl = $this_hsl[0];
		preg_match("/CALIBDATE=[0-9]+-[0-9]+/",$line,$calibdate);
		$newcalibdate = explode("=",$calibdate[0]);
		preg_match("/SNRMREQ=[0-9]+/",$line,$snrmreq);
		$newsnrmreq = explode("=",$snrmreq[0]);		
		preg_match("/SMODE=[A-Z][A-Z][0-9]+/",$line,$smode);
		$newsmode = explode("=",$smode[0]);		
		//preg_match("/EWL=[0-9]+/",$line,$ewl);
		//$newewl = explode("=",$ewl[0]);	
		
		$newhsl = $switch.$newhsl;
		
		if ($hsl_count == 0){
			$HSL = array(
				$newhsl => array(
					"calibdate" => $newcalibdate[1],
					"snrmreq" => $newsnrmreq[1],
					"smode" => $newsmode[1],
					)
				);
		}else{
			
			$temparray = array(
					"calibdate" => $newcalibdate[1],
					"snrmreq" => $newsnrmreq[1],
					"smode" => $newsmode[1],
					);
			
			$HSL[$newhsl] = $temparray;
		}
		
		$hsl_count++;
		
	}
	
	
	
	if(substr($line,4,3)=="MLP" && substr($enil,2,1) != ","){
		
		preg_match("/MLP-[0-9]-[0-9]+/",$line,$this_mlp);
		$currMLP = $this_mlp[0];
		//$MLPs = array("MLP" => $currMLP);
		//echo $currMLP . " ";
		//echo "<br><br>";
		//echo $MLPs["MLP"] . " ";
		preg_match("/HSL=HSL-[0-9]+/",$line,$hsl_for_mlp);
		$newhsl_for_mlp = explode("=",$hsl_for_mlp[0]);
		
		//preg_match("/CALIBRATE=[0-9]*/",$line,$calibrate);
		//$newcalibrate = explode("=",$calibrate[0]);
		preg_match("/INITCALIBRATE=[0-9]*/",$line,$initcalibrate);
		$newinitcalibrate = explode("=",$initcalibrate[0]);

		
		preg_match("/CURRATE=[0-9]*/",$line,$currate);
		$newcurrate = explode("=",$currate[0]);
		
		if($newinitcalibrate[1] > $newcurrate[1]){
			$bw_drop = $newinitcalibrate[1] - $newcurrate[1];
			$total_drop = $total_drop + $bw_drop;
			$num_dropped++;
		}else{
			$bw_drop = 0;
		}
		$currMLP = $switch.$currMLP;
		
		if($mlp_count == 0){
				
			$MLPs = array(
				$currMLP => array(
					"HSL" => $newhsl_for_mlp[1],
					"initrate" => $newinitcalibrate[1],
					"currate" => $newcurrate[1],
					"bw_drop" => $bw_drop,
					"switch" => $efm_switch[0],
					)
				);
			
		}else{
		
			$temparraytwo = array(
				"HSL" => $newhsl_for_mlp[1],
				"initrate" => $newinitcalibrate[1],
				"currate" => $newcurrate[1],
				"bw_drop" => $bw_drop,
				"switch" => $efm_switch[0],
				);
				
				
			$MLPs[$currMLP] = $temparraytwo;
		}
		
		$mlp_count++;		
	}
		
	
	if((substr($line,4,3)=="MLP") && (substr($enil,2,1) == ",")){
		
		preg_match("/MLP-[0-9]-[0-9]+/",$line,$this_mlp);
		$currMLP = $this_mlp[0];
		preg_match("/EWL=[0-9]+/",$line,$ewl);
		$newewl = explode("=",$ewl[0]);
		$cleanewl = $newewl[1];
		
		$cleanewl = $switch.$cleanewl;
		$currMLP = $switch.$this_mlp[0];	
		$EWLs[$currMLP] = $cleanewl;	
			
		$ewl_count++;
	}

		
}

foreach($MLPs as $MLP => $port){

	$choppedMLP = substr($MLP,8);
	
	echo "<tr><td>$choppedMLP</td>";
	
	foreach($port as $key => $value){
			
		if($key == "HSL"){
			$thisHSL = $value;
		}
		if($key = "switch"){
			$thisswitch = $value;
		}
				
		echo "<td>" . $value . "</td>";
//		echo "<td>" .  . "</td><td>" . $MLP . "</td><td align=right>" . $cleanhsl . "</td><td align=right>" . $MLP["initrate"] . "</td><td align=right>" . $MLP["currate"] . "</td><td align=right>" . $MLP["bw_drop"] . "</td><td align=right>" . $HSL[$cleanhsl]["calibdate"] . "</td><td align=right>" . $HSL[$cleanhsl]["snrmreq"] . "</td><td align=right>" . $HSL[$cleanhsl]["smode"] . "</td><td align=right>" . $MLP["ewl"] . "</td>";
		// echo "</tr>";
		//echo $key[$value] . "<br><br>";
//		foreach($key as 
		}
	
	$uniqueHSL = $thisswitch.$thisHSL;
	$uniqueEWL = substr($EWLs[$MLP],8);
	
	echo "<td>" . $HSL[$uniqueHSL]["calibdate"] . "</td>";
	echo "<td>" . $HSL[$uniqueHSL]["snrmreq"] . "</td>";
	echo "<td>" . $HSL[$uniqueHSL]["smode"] . "</td>";
	echo "<td>" . $uniqueEWL . "</td></tr>";

	
	}

echo "</table>";











?>



</BODY>

</HTML>