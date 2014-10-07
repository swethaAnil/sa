<?php

function checkIP($host,$subnet)
{

	$ip_pattern = "/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/";
	$filler = "00000000";

	if (preg_match($ip_pattern, $host)) {
		
		$hostchunks = explode(".",$host);
		$hostbin = decbin($hostchunks[3]);
		
		$subnetchunks = explode(".",$subnet);
		$subnetbin = decbin($subnetchunks[3]);
		
		$length = strlen($hostbin);
		$alt_len = 8 - $length;
		
		$hostfiller = substr($filler,0,$alt_len);
		$hostbin = substr_replace($hostbin,$hostfiller,0,0);
		$original_hostbin = $hostbin;
				
	} else {
		return 0;
	}

	if($subnetbin == "11111111" && $original_hostbin == "00000000"){
		return 2;
	}else{
		$t_subnet = preg_replace('/10/','1t0',$subnetbin);
		$split_octet = split("t",$t_subnet);
		$zero_count = strlen($split_octet[1]);
		$hostbin = substr_replace($hostbin,$split_octet[1],-$zero_count);

		if ($hostbin == $original_hostbin){
			return 2;
		}else{
			return 1;
		}
	}	
}


?>