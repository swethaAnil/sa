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
	$var_pattern = '/\[VAR\S*\]/';
	
	$handle = fopen($filename,"r");
	$contents = fread($handle, filesize($filename));
	$lines = explode("\n",$contents);
	//echo "<pre>";
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
	//echo "</pre>";
}

function build_value_map($acct_num){

	$value_map_array = array();
	$val_map_file = "modules/value_map_acct_" . $acct_num . ".dat";
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

function print_voice_port_module($map,$module,$port_num){
	$filename = "modules/" . $module . ".dat";
	$var_pattern = '/\[VAR\S*\]/';
	$port_var = "[VAR_PORT_NUM]";
	$peer_var = "[VAR_DIAL_PEER_NUM]";
	$second_peer_var = "[VAR_DIAL_PEER_NUM_SECONDARY]";

	$handle = fopen($filename,"r");
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
		
		$line = str_replace($port_var, $port_num, $line);
		$line = str_replace($peer_var, $port_num+1, $line);
		if($port_num >= 9){
			$line = str_replace($second_peer_var, "", $line);
		}else{
			$line = str_replace($second_peer_var, "0", $line);
		}
		echo $line;
		echo "<br />";
	}
	
	fclose($handle);
	
}



//define variables
$voice_array = array(NONE,ANALOG,PRI,CAS,SIP,VOPRI,PRIM,CASM);
$voice_id = $_POST['voice_type'];
$voice_type = $voice_array[$voice_id];

$analog_var = "num_analog_lines_" . $voice_type;
$num_analogs = $_POST[$analog_var];

$acct = $_POST['acct_num'];
$device = $_POST['device'];
$pca = $_POST['pca'];
$access = $_POST['access'];

$val_map = build_value_map($acct);

switch($device){
	case IAD:
	
		$module = "IAD_universal_module";
		print_module($val_map,$module);
		
		$module = "IAD_PUBLIC_data_module";
		print_module($val_map,$module);
		
		switch($access){
			case T1:
				$module = "IAD_T1_access_module";
				print_module($val_map,$module);
				break;
			case EFM:
				$module = "IAD_EFM_access_module";
				print_module($val_map,$module);
				break;
			case FIBER:
				$module = "IAD_FIBER_access_module";
				print_module($val_map,$module);
				break;
		}
		
		switch($pca){
		
			case BTS:
				switch($voice_type){
					case ANALOG:
						$module = "IAD_BTS_ANALOG_universal_voice_module";
						print_module($val_map,$module);
						break;
					case PRI:
						$module = "IAD_BTS_PRI_universal_voice_module";
						print_module($val_map,$module);
						break;
					case CAS:
						$module = "IAD_BTS_CAS_universal_voice_module";
						print_module($val_map,$module);
						break;
					case SIP:
						$module = "IAD_BTS_SIP_universal_voice_module";
						print_module($val_map,$module);
						break;
				}
				break;
				
			case BROADSOFT:
				switch($voice_type){
					case ANALOG:
						$module = "IAD_BSOFT_ANALOG_universal_voice_module";
						print_module($val_map,$module);
						$module = "IAD_BSOFT_ANALOG_voice_port_module";

						for($i=0;$i<$num_analogs;$i++){
							print_voice_port_module($val_map,$module,$i);
						}
						break;
					case PRI:
						$module = "IAD_BSOFT_PRI_universal_voice_module";
						print_module($val_map,$module);
						break;
					case CAS:
						$module = "IAD_BSOFT_CAS_universal_voice_module";
						print_module($val_map,$module);
						break;
					case SIP:
						$module = "IAD_BSOFT_SIP_universal_voice_module";
						print_module($val_map,$module);
						break;
				}
				break;
		}
	break;
		
	case ISR:
	
		$module = "ISR_universal_module";
		print_module($val_map,$module);
		
		switch($access){
			case T1:
				$module = "ISR_T1_access_module";
				print_module($val_map,$module);
				break;
			case EFM:
				$module = "ISR_EFM_access_module";
				print_module($val_map,$module);
				break;
			case FIBER:
				$module = "ISR_FIBER_access_module";
				print_module($val_map,$module);
				break;
		}
		
		switch($pca){
			case BTS:
				switch($voice_type){
					case analog:
						$module = "ISR_BTS_ANALOG_universal_voice_module";
						print_module($val_map,$module);
						
						break;
					case PRI:
						$module = "ISR_BTS_PRI_universal_voice_module";
						print_module($val_map,$module);
						break;
					case CAS:
						$module = "ISR_BTS_CAS_universal_voice_module";
						print_module($val_map,$module);
						break;
					case SIP:
						$module = "ISR_BTS_SIP_universal_voice_module";
						print_module($val_map,$module);
						break;
				}
				break;
			case Broadsoft:
				switch($voice_type){
					case ANALOG:
						$module = "ISR_BSOFT_ANALOG_universal_voice_module";
						print_module($val_map,$module);
						break;
					case PRI:
						$module = "ISR_BSOFT_PRI_universal_voice_module";
						print_module($val_map,$module);
						break;
					case CAS:
						$module = "ISR_BSOFT_CAS_universal_voice_module";
						print_module($val_map,$module);
						break;
					case SIP:
						$module = "ISR_BSOFT_SIP_universal_voice_module";
						print_module($val_map,$module);
						break;
				}
				break;
		}
	break;
}


?>
