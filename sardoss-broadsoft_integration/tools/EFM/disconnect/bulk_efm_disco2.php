
<HTML>

<BODY>

<a href="bulk_efm_disco.htm">BACK</a><br><br>

<?php 

$hsl_data = explode("\n",$_POST[hsl_data]);
$mlp_data = explode("\n",$_POST[mlp_data]);


foreach($hsl_data as $hsl_line){

	$trimline = trim($hsl_line);

	if(substr($trimline,1,4) == "HSL-"){
		$hslinfo = explode(":",$trimline);
		$hsldata = explode(",",$hslinfo[1]);
		
		$hsl_num = explode("-",$hslinfo[0]);
		$hsl_num = $hsl_num[1];
				
		echo "<b>HSL STATUS: " . $hslinfo[0] . " - " . $hslinfo[2] . " - " . $hsldata[3] . " - " . $hsldata[17] . "</b><br><hr>";
		echo "<br>canc-init-hsl::hsl-$hsl_num<br><br>";
			
		foreach($mlp_data as $mlp_line){
			
			$trimMLP = trim($mlp_line);
			
			if(substr($trimMLP,1,4) == "MLP-"){
				
				$mlpinfo = explode(":",$trimMLP);
				$mlpdata = explode(",",$mlpinfo[1]);
				
				if($mlpdata[0] == "HSL=HSL-$hsl_num"){
					$cleanmlp = str_replace("\"","",$mlpinfo[0]);
					echo "dlt-mlp::$cleanmlp<br><br>";
				}
			}
		}
		
		echo "dlt-hsl::hsl-$hsl_num<br><br><hr>";
	}
}



?>


</BODY>

</HTML>