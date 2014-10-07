
<HTML>

<HEAD>
<TITLE>EFM Bandwidth Drop Tool Results </TITLE>
</HEAD>

<BODY>

<?php 

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

echo "<table border=1>";
//echo "<tr><th>EFMSWITCH</th><th>MLP</th><th>HSL</th><th>INITRATE</TH><TH>CURRATE</TH><TH>BWDROP</TH><TH>CALIBDATE</TH><TH>SNRMREQ</TH><TH>SMODE</TH><TH>EWL</TH>";


foreach($lines as $line){
	
	if(substr($line,0,3)=="EFM"){
	
		preg_match("/EFM[0-9]+[A-Z][A-Z][A-Z]/",$line,$efm_switch);
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
		preg_match("/EWL=[0-9]+/",$line,$ewl);
		$newewl = explode("=",$ewl[0]);	

		if ($hsl_count == 0){
			$HSL = array(
				$newhsl => array(
					"calibdate" => $newcalibdate[1],
					"snrmreq" => $newsnrmreq[1],
					"smode" => $newsmode[1],
					"ewl" => $newewl[1],
					)
				);
		}else{
			
			$temparray = array(
					"calibdate" => $newcalibdate[1],
					"snrmreq" => $newsnrmreq[1],
					"smode" => $newsmode[1],
					"ewl" => $newewl[1],
					);
			
			$HSL[$newhsl] = $temparray;
		}
		
		$hsl_count++;
		
	}
	
	
	
	if(substr($line,4,3)=="MLP"){
		
		
		
		preg_match("/MLP-[0-9]-[0-9]+/",$line,$this_mlp);
		preg_match("/HSL=HSL-[0-9]+/",$line,$hsl_for_mlp);
		$newhsl_for_mlp = explode("=",$hsl_for_mlp[0]);
		$cleanhsl = $newhsl_for_mlp[1];
		preg_match("/CALIBRATE=[0-9]*/",$line,$calibrate);
		$newcalibrate = explode("=",$calibrate[0]);
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
		
		echo "<tr>";
		echo "<td>" . $efm_switch[0] . "</td><td>" . $this_mlp[0] . "</td><td align=right>" . $cleanhsl . "</td><td align=right>" . $newinitcalibrate[1] . "</td><td align=right>" . $newcurrate[1] . "</td><td align=right>" . $bw_drop . "</td><td align=right>" . $HSL[$cleanhsl]["calibdate"] . "</td><td align=right>" . $HSL[$cleanhsl]["snrmreq"] . "</td><td align=right>" . $HSL[$cleanhsl]["smode"] . "</td><td align=right>" . $HSL[$cleanhsl]["ewl"] . "</td>";
		echo "</tr>";
		
		if($newinitcalibrate[1]>0){
			$count++;
		}
	}
}
echo "</table>";


$avg_drop = $total_drop/$count;
$percent_dropped = 100*($num_dropped/$count);

//echo "<br><br><hr>Average drop per calibrated pair: " . round($avg_drop,1) . " kbps";
//echo "<br>Number of calibrated pairs adjusted down: " . $num_dropped . " of " . $count . " (" . round($percent_dropped) . "%)";






?>



</BODY>

</HTML>