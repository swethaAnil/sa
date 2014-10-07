<?php

function build_variable_map(){
	$var_map_array = array();
	$variable_map_file = "modules/variable_map.dat";
	$vm_handle = fopen($variable_map_file,"r");
	$vm_contents = fread($vm_handle, filesize($variable_map_file));

	$vm_data = explode("\n",$vm_contents);

	foreach($vm_data as $vm_datum){
		$datum_split = explode("::",$vm_datum);
		$key = $datum_split[0];
		$value =$datum_split[1];
		$var_map_array[$key] = $value;
	}
	fclose($vm_handle);

	return $var_map_array;
}

function print_module($map,$module){

	$filename = "modules/" . $module . ".dat";
	$var_pattern = '/\[VAR\w*\]/';
	
	$handle = fopen($filename,"r");
	if($handle == FALSE){ echo "!!!----------- ALERT: $module NOT FOUND -----------!!!<br />"; }
	$contents = fread($handle, filesize($filename));
	$lines = explode("\n",$contents);
	
	foreach($lines as $line){
		preg_match_all($var_pattern,$line,$matches);
		
		foreach($matches[0] as $match){
				
			$var_value = $match;
			if($map[$var_value]){
				$map[$var_value] = rtrim($map[$var_value]);
				$line = str_replace($var_value,$map[$var_value],$line);
			}
		}
		echo $line;
		echo "<br />";
	}
	fclose($handle);
}

function build_value_map($val_map_file){

	$value_map_array = array();
	$val_map_handle = fopen($val_map_file,"r");
	$val_map_contents = fread($val_map_handle, filesize($val_map_file));

	$val_data = explode("\n",$val_map_contents);
	
	foreach($val_data as $val_map_datum){
		$datum_split = explode("::",$val_map_datum);
		$key = $datum_split[0];
		$value = $datum_split[1];
		$value_map_array[$key] = $value;
	}
	fclose($val_map_handle);

	return $value_map_array;
}

function print_voice_port_module($map,$module,$port_num,$current_line){
	$filename = "modules/" . $module . ".dat";
	$var_pattern = '/\[VAR\w*\]/';
	$peer_num = $port_num + 1;
	$port_var = "[VAR_PORT_NUM]";
	$peer_var = "[VAR_DIAL_PEER_NUM]";
	$second_peer_var = "[VAR_DIAL_PEER_NUM_SECONDARY]";
	$tel_num_var = "[VAR_TEL_NUM]";
	
	if($current_line){
		$tel_num_map_var = "[VAR_TEL_NUM" . $current_line . "]";
	}else{
		$tel_num_map_var = "[VAR_TEL_NUM" . $peer_num . "]";
	}
	$tel_num = $map[$tel_num_map_var];
	$handle = fopen($filename,"r");
	if($handle == FALSE){ echo "!!!----------- ALERT: $module NOT FOUND -----------!!!<br />"; }
	$contents = fread($handle, filesize($filename));
	$lines = explode("\n",$contents);
	
	$port_address_var = "[VAR_PORT_ADDRESS]";
	$model = $map['[VAR_MODEL]'];
		
	foreach($lines as $line){
		
		//SPECIAL HANDLING FOR 8-PORT SPIAD
		if($model == "SPIAD_8" && $port_num > 3){
			$port_address = $map[$port_address_var];
			$port_addy_bits = explode("/",$port_address);
			$new_port_addy_bit = $port_addy_bits[1] + 1;
			$new_port_address = $port_addy_bits[0] . "/" . $new_port_addy_bit . "/";
			$line = str_replace($port_address_var, $new_port_address, $line);
			$alt_port_num = $port_num - 4;
			$line = str_replace($port_var, $alt_port_num, $line);
		}
		
		preg_match_all($var_pattern,$line,$matches);
		foreach($matches[0] as $match){
			$var_value = $match;
			if($map[$var_value]){
				$map[$var_value] = rtrim($map[$var_value]);
				$line = str_replace($var_value,$map[$var_value],$line);
			}
		}
		if($tel_num){$line = str_replace($tel_num_var, $tel_num, $line);}
		$line = str_replace($port_var, $port_num, $line);
		$line = str_replace($peer_var, $peer_num, $line);
		if($peer_num >= 10){
			$line = str_replace($second_peer_var, "", $line);
		}else{
			$line = str_replace($second_peer_var, "0", $line);
		}
		echo $line;
		echo "<br />";
	}
	
	fclose($handle);
	
}

function print_serial_port_module($map,$module,$port_num,$interface,$cid,$serial){
	
	$filename = "modules/" . $module . ".dat";
	$var_pattern = '/\[VAR\w*\]/';
	
	$j = $i+1;
	$serial_pattern = '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}/';
	preg_match($serial_pattern,$serial,$serial_match);
	$serial_numbers = explode("/",$serial_match[0]);
	$t3_interface = $serial_numbers[0] . "/" . $serial_numbers[1] . "/" . $serial_numbers[2];
	$tenk_t1 = $serial_numbers[3];
	
	$interface_var = "[VAR_T1_INTERFACE]";
	$cid_var = "[VAR_T1_CID]";
	$serial_var = "[VAR_T1_SERIAL]";
	$slot_var = "[VAR_T1_SLOT]";
	$subslot_var = "[VAR_T1_SUBSLOT]";
	$t3_interface_var = "[VAR_10K_T3_INTERFACE]";
	$tenk_t1_var = "[VAR_10K_T1]";
	
	$slot = rtrim(str_replace("/"," ",substr($interface,0,-1)));
	$subslot = substr(rtrim($slot),-1);
		
	$handle = fopen($filename,"r");
	if($handle == FALSE){ echo "!!!----------- ALERT: $module NOT FOUND -----------!!!<br />"; }
	$contents = fread($handle, filesize($filename));
	$lines = explode("\n",$contents);
	
	foreach($lines as $line){
		preg_match_all($var_pattern,$line,$matches);
		foreach($matches[0] as $match){
			$var_value = $match;
			if($map[$var_value]){
				$map[$var_value] = rtrim($map[$var_value]);
				$line = str_replace($var_value,$map[$var_value],$line);
			}
		}
		if($tel_num){$line = str_replace($tel_num_var, $tel_num, $line);}
		$line = str_replace($interface_var, $interface, $line);
		$line = str_replace($cid_var, $cid, $line);
		$line = str_replace($serial_var, $serial, $line);
		$line = str_replace($slot_var, $slot, $line);
		$line = str_replace($subslot_var, $subslot, $line);
		$line = str_replace($t3_interface_var, $t3_interface, $line);
		$line = str_replace($tenk_t1_var, $tenk_t1, $line);
		echo $line;
		echo "<br />";
	}
	
	fclose($handle);
	
}


function print_ios_module(){

	echo "<br /><br /><br />";
	echo "<strong><u>2430 IAD IOS</u></strong>";
	echo "<br />";
	echo "copy ftp://ios:i0sb00t@10.128.8.16/c2430-is-mz.124-24.T10.bin flash:";
	echo "<br /><br />";
	echo "<strong><u>2435 IAD IOS</u></strong>";
	echo "<br />";
	echo "copy ftp://ios:i0sb00t@10.128.8.16/c2435-ipvoice-mz.124-24.T10.bin flash:";
	echo "<br /><br />";
	echo "<strong><u>2900 ISR/SPIAD IOS</u></strong>";
	echo "<br />";
	echo "copy ftp://ios:i0sb00t@10.128.8.16//nfs/tftpboot/IOS/c2900-universalk9-mz.SPA.151-4.M7.bin flash:";
	echo "<br /><br />";
	echo "<strong><u>2950 ISR IOS</u></strong>";
	echo "<br />";
	echo "copy ftp://ios:i0sb00t@10.128.8.16//nfs/tftpboot/IOS/c2951-universalk9-mz.SPA.150-1.M8.bin flash:";
}


function print_wic_module($T1_interfaces){

	echo "###############################################<br />";
	echo "#   T1 CARD ACTIVATE<br />";
	echo "###############################################<br />";
	echo "<br />";
	foreach($T1_interfaces as $interface){
		$slot = rtrim(str_replace("/"," ",substr($interface,0,-1)));
		if($slot != $old_slot){
			$subslot = substr(rtrim($slot),-1);
			echo "!<br />";
			echo "card type t1 $slot<br />";
			if ($slot != 1){ echo "network-clock-participate wic $subslot<br />"; }
		}
		$old_slot = $slot;
	}
	echo "!<br />";

}

function shut_unused_voice_ports($map,$model,$num_ports,$num_analogs){


	if($model == "SPIAD_8"){
	
	}else{
		
		echo "###############################################<br />";
		echo "#   SHUT UNUSED VOICE PORTS<br />";
		echo "###############################################<br />";
		echo "<br />";
		
		$model_bits = explode("_",$model);
		if($model_bits[2] == "secondary"){
			$port_address = $map['[VAR_PORT_ADDRESS_SECONDARY]'];
		}else{
			$port_address = $map['[VAR_PORT_ADDRESS]'];
		}	
		
		for($i=$num_ports-1;$i>=$num_analogs;$i--){
			echo "!<br />";
			echo "voice-port " . $port_address . $i . "<br />";
			echo "shut<br />";
		}
		echo "!<br /><br /><br />";
	}
}

/* function print_mlp_module($map,$module,$mlp_num){

	$filename = "modules/" . $module . ".dat";
	$var_pattern = '/\[VAR\w*\]/';
	
	$mlp_var = "[VAR_MLP]";
	$efm_cid_var = "[VAR_EFM_CID]";
	
	$curr_mlp_var = "[VAR_MLP" . $mlp_num . "]";
	$curr_mlp = $map[$curr_mlp_var];
	$curr_efm_cid_var = "[VAR_EFM_CID" . $mlp_num . "]";
	$curr_efm_cid = $map[$curr_efm_cid_var];
	
	$handle = fopen($filename,"r");
	if($handle == FALSE){ echo "!!!----------- ALERT: $module NOT FOUND -----------!!!<br />"; }
	$contents = fread($handle, filesize($filename));
	$lines = explode("\n",$contents);
	
	foreach($lines as $line){
		preg_match_all($var_pattern,$line,$matches);
		foreach($matches[0] as $match){
			$var_value = $match;
			if($map[$var_value]){
				$map[$var_value] = rtrim($map[$var_value]);
				$line = str_replace($var_value,$map[$var_value],$line);
				
			}
		}
			
		$line = str_replace($mlp_var, $curr_mlp, $line);
		$line = str_replace($efm_cid_var, $curr_efm_cid, $line);
		echo $line;
		echo "<br />";
	}
	
	fclose($handle);
} */

function print_mlp_module($map,$module,$mlp_num){

	$filename = "modules/" . $module . ".dat";
	$var_pattern = '/\[VAR\w*\]/';
	
	$mlp_var = "[VAR_MLP]";
	$efm_cid_var = "[VAR_EFM_CID]";
	$cpe_mlp_var = "[VAR_CPE_MLP]";
	
	$curr_mlp_var = "[VAR_MLP" . $mlp_num . "]";
	$curr_mlp = $map[$curr_mlp_var];
	$curr_efm_cid_var = "[VAR_EFM_CID" . $mlp_num . "]";
	$curr_efm_cid = $map[$curr_efm_cid_var];
	
	$curr_cpe_mlp = "1-" . $mlp_num;
	
	$handle = fopen($filename,"r");
	if($handle == FALSE){ echo "!!!----------- ALERT: $module NOT FOUND -----------!!!<br />"; }
	$contents = fread($handle, filesize($filename));
	$lines = explode("\n",$contents);
	
	foreach($lines as $line){
		preg_match_all($var_pattern,$line,$matches);
		foreach($matches[0] as $match){
			$var_value = $match;
			if($map[$var_value]){
				$map[$var_value] = rtrim($map[$var_value]);
				$line = str_replace($var_value,$map[$var_value],$line);
				
			}
		}
		
		$line = str_replace($mlp_var, $curr_mlp, $line);
		$line = str_replace($cpe_mlp_var, $curr_cpe_mlp, $line);
		$line = str_replace($efm_cid_var, $curr_efm_cid, $line);
		echo $line;
		echo "<br />";
	}
	
	fclose($handle);
	
}


?>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39762922-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


