<?php

class ConfigFunctions{

	protected $connection;
	protected $var_pattern;
	protected $constants;
	
	public function __construct($dbInfo, $constants)
	{
		//CONNECT TO DATABASE
		$this->connection = mysqli_connect($dbInfo['mysqladd'],$dbInfo['mysqluser'],$dbInfo['mysqlpass'],$dbInfo['databasename']) or die(mysqli_connect_error());

		$this->var_pattern = '/\[VAR\w*\]/';

		$this->constants = $constants;
	}


	/*
	*	Pull all module from database
	*
	*	@return array $modules
	*/
	public function allModules(){

		$modules = array();

		$SQL = "SELECT id, name FROM config_modules ORDER BY name";
		$result = mysqli_query($this->connection, $SQL) or die(mysqli_error());	

		while($row = mysqli_fetch_assoc($result)){
			$modules[$row['id']] = $row['name'];
		}
		
		return $modules;
	}

	/*
	*	Pull config from database
	*
	*	@param int $id
	*	@return array $config
	*/
	public function selectModule($id){

		$SQL = "SELECT * FROM config_modules WHERE id = '$id' LIMIT 1";
		$result = mysqli_query($this->connection, $SQL) or die(mysqli_error());	
		$config = mysqli_fetch_assoc($result);

		return $config;
	}

	/*
	*	Update config module record in database
	*
	*	@param int $id
	*	@param string $config
	*	@return boolean
	*/
	public function updateModule($id, $config){

		$config = mysqli_real_escape_string($this->connection, $config);

		$SQL = "UPDATE config_modules SET config = '$config' WHERE id = '$id'";

		if(mysqli_query($this->connection, $SQL)){
			return true;
		}else{
			return false;
		}	
	}

	/*
	*	Pull config from database by name
	*
	*	@param string $moduleName
	*	@return array $module
	*/
	protected function selectModuleByName($moduleName){

		$SQL = "SELECT * FROM config_modules WHERE name = '$moduleName' LIMIT 1";
		$result = mysqli_query($this->connection, $SQL) or die(mysqli_error());	
		$module = mysqli_fetch_assoc($result);

		return $module;
	}	

	public function print_module($map,$moduleName){

		if($module = $this->selectModuleByName($moduleName)){
			$lines = explode("\n", $module['config']);
		
			foreach($lines as $line){
				preg_match_all($this->var_pattern,$line,$matches);
				
				foreach($matches[0] as $match){
						
					$var_value = $match;
					if($map[$var_value]){
						$map[$var_value] = rtrim($map[$var_value]);
						$line = str_replace($var_value,$map[$var_value],$line);
					}
				}
				echo rtrim($line) . '<br>';
			}

		}else{
			echo '!!!----------- ALERT: ' . $moduleName . ' NOT FOUND -----------!!!<br>';
		}
		
	}

	public function build_value_map($val_map_file){

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

	public function print_voice_port_module($map,$moduleName,$port_num,$current_line){
		
		if($module = $this->selectModuleByName($moduleName)){
			$lines = explode("\n", $module['config']);

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
				
				preg_match_all($this->var_pattern,$line,$matches);
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
				echo rtrim($line);
				echo "<br>";
			}
			
		}else{
			echo '!!!----------- ALERT: ' . $moduleName . ' NOT FOUND -----------!!!<br>';
		}
		
	}

	public function print_serial_port_module($map,$moduleName,$interface,$cid,$serial){
		
		if($module = $this->selectModuleByName($moduleName)){
			$lines = explode("\n", $module['config']);
			
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
				
			foreach($lines as $line){
				preg_match_all($this->var_pattern,$line,$matches);
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
				echo rtrim($line);
				echo "<br>";
			}

		}else{
			echo '!!!----------- ALERT: ' . $moduleName . ' NOT FOUND -----------!!!<br>';
		}
		
	}

	public function print_wic_module($T1_interfaces){

		echo "###############################################<br>";
		echo "#   T1 CARD ACTIVATE<br>";
		echo "###############################################<br>";
		echo "<br>";
		foreach($T1_interfaces as $interface){
			$slot = rtrim(str_replace("/"," ",substr($interface,0,-1)));
			if($slot != $old_slot){
				$subslot = substr(rtrim($slot),-1);
				echo "!<br>";
				echo "card type t1 $slot<br>";
				if ($slot != 1){ echo "network-clock-participate wic $subslot<br>"; }
			}
			$old_slot = $slot;
		}
		echo "!<br>";

	}

	public function shut_unused_voice_ports($map,$model,$num_ports,$num_analogs){

		if($model == "SPIAD_8"){
		
		}else{
			
			echo "###############################################<br>";
			echo "#   SHUT UNUSED VOICE PORTS<br>";
			echo "###############################################<br>";
			echo "<br>";
			
			$model_bits = explode("_",$model);
			if($model_bits[2] == "secondary"){
				$port_address = $map['[VAR_PORT_ADDRESS_SECONDARY]'];
			}else{
				$port_address = $map['[VAR_PORT_ADDRESS]'];
			}	
			
			for($i=$num_ports-1;$i>=$num_analogs;$i--){
				echo "!<br>";
				echo "voice-port " . $port_address . $i . "<br>";
				echo "shut<br>";
			}
			echo "!<br><br><br>";
		}
	}

	public function print_mlp_module($map,$moduleName,$mlp_num){

		if($module = $this->selectModuleByName($moduleName)){
			$lines = explode("\n", $module['config']);
		
			$mlp_var = "[VAR_MLP]";
			$efm_cid_var = "[VAR_EFM_CID]";
			$cpe_mlp_var = "[VAR_CPE_MLP]";
			
			$curr_mlp_var = "[VAR_MLP" . $mlp_num . "]";
			$curr_mlp = $map[$curr_mlp_var];
			$curr_efm_cid_var = "[VAR_EFM_CID" . $mlp_num . "]";
			$curr_efm_cid = $map[$curr_efm_cid_var];
			
			$curr_cpe_mlp = "1-" . $mlp_num;
			
			foreach($lines as $line){
				preg_match_all($this->var_pattern,$line,$matches);
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
				echo rtrim($line);
				echo "<br>";
			}

		}else{
			echo '!!!----------- ALERT: ' . $moduleName . ' NOT FOUND -----------!!!<br>';
		}
		
	}


	public function processInput($input)
	{
		//DEFINE VARIABLES
		$file_string = '';
		$error = 0;
		$username = $_SERVER['PHP_AUTH_USER'];

		$order_num = $input['order_num'];

		$device_key = $input['device'];
		$device = $this->constants->device_array[$device_key];
		$model_key = $device . "_model";
		$model = $input[$model_key];

		$model_bits = explode("_",$model);
		if(is_numeric($model_bits[1])){
			$num_fxs_ports = $model_bits[1];
		}else{
			$num_fxs_ports = 0;
		}

		$num_devices = substr($input['num_devices'],-1);

		if( $num_devices == 2){
			
			
			$second_device_key = $input['second_device'];
			$second_device = $this->constants->device_array[$second_device_key];
				
			$second_model_key = $second_device . "_model_secondary";
			$second_model = $input[$second_model_key];
			

			$second_model_bits = explode("_",$second_model);

			if(is_numeric($second_model_bits[1])){
				$num_fxs_ports_second = $second_model_bits[1];
			}else{
				$num_fxs_ports_second = 0;
			}
			
			$second_iad_name_key = $second_device . "_name_secondary";
			$second_iad_name = $input[$second_iad_name_key];
				
		}

		$available_fxs_ports = $num_fxs_ports + $num_fxs_ports_second;

		$access_key = $input['access'];
		$access = $this->constants->access_array[$access_key];

		$circuit_key = "num_circuits_" . $access;
		$num_circuits = substr($input[$circuit_key],4);

		$pca_key = $input['pca'];
		$pca = $this->constants->pca_array[$pca_key];

		$voice_key = $input['voice_type'];
		$voice_type = $this->constants->voice_array[$voice_key];

		$analog_key = "num_analog_lines_" . $voice_type;
		$num_analogs = substr($input[$analog_key],4);

		if($num_analogs > $available_fxs_ports){
			echo "<h1>ERROR: THERE ARE NOT ENOUGH FXS PORTS FOR THIS CONFIGURATION!</h1>";
			echo "<h3>You need $num_analogs, but you have $available_fxs_ports.</h3>";
			$error++;
		}

		//DEFINE NUMBER OF TRUNK GROUPS
		$trunk_key = "num_trunk_groups_" . $voice_type;
		if($input[$trunk_key]){
			$num_trunk_groups = substr($input[$trunk_key],4);
		}elseif($voice_type == "VOPRI"){
			$num_trunk_groups = 1;
		}

		//RESET MIXED SERVICE TYPES TO STANDARD VOICE TYPES
		if(substr($voice_type,-3) == "MIX"){	$voice_type = substr($voice_type,0,3);	}

		$btn = $input[btn];
		$acct_num = $input['acct_num'];
		$acct_name = $input['acct_name'];
		$iad_name = $input['iad_name']; 
		$iad_number = substr($iad_name,3,-3);
		if(strlen($iad_number) > 4){ $iad_number = "9" . $iad_number; }
		 
		$public_ip_network = $input['public_ip'];
		$public_ip_parts = explode(".",$public_ip_network);
		$public_ip_gateway_lastoctet = $public_ip_parts[3] + 1;
		$public_subnet = $input['public_subnet'];

		//IDENTIFY DATA CONFIGURATION TYPE 
		if($public_subnet == "255.255.255.255"){
			$data_type = "LOOPBACK";
			$public_ip_gateway = $public_ip_network;
		}else{
			$data_type = "PUBLIC";
			$public_ip_gateway = $public_ip_parts[0] . "." . $public_ip_parts[1] . "." . $public_ip_parts[2] . "." . $public_ip_gateway_lastoctet;
		}

		$tenk_name = $input['tenk_name'];
		$tenk_type = substr($tenk_name,0,3);
		$bwas = $input['BWAS'] . ".voice.cbeyond.net";

		if($access == "FIBER"){

			$fiber_provider = $input['fiber_provider'];
			$fiber_cid_key = "CID_" . $fiber_provider;
			$fiber_cid = $input[$fiber_cid_key];
			$cid_parts = explode("/",$fiber_cid);
			$siteID = $cid_parts[2];
			$basPort = $input['BAS_PORT'];
			$fiber_vlan_key = "VLAN_" . $fiber_provider;
			$fiber_vlan = $input[$fiber_vlan_key];
			$vlan = $fiber_vlan;
			$fiber_site_vlan_key = "SITE_VLAN_" . $fiber_provider;
			$fiber_site_vlan = $input[$fiber_site_vlan_key];
			$fiber_rate_limit_key = "rate_limit_" . $fiber_provider;
			$fiber_rate_limit = $input[$fiber_rate_limit_key]; 
			$fiber_secondary_10k_key = "secondary_10k_name_" . $fiber_provider;
			$fiber_secondary_10k_name = $input[$fiber_secondary_10k_key]; 
			$fiber_10k_interface_key = "TENK_INTERFACE_" . $fiber_provider;
			$fiber_10k_interface = $input[$fiber_10k_interface_key] . "." . $fiber_vlan; 
		//CALCULATE SHAPE AVERAGE
			$shape_avg = $fiber_rate_limit * 1048576;			
		}

		if($access == "EFM"){
			
			$efm_vlan = $input['VLAN_EFM'];
			$vlan = $efm_vlan;
			$efm_rate_limit = $input['rate_limit_EFM'];
		//CALCULATE SHAPE AVERAGE
			$shape_avg = $efm_rate_limit * 1048576;

			$efm_switch = $input['EFM_SWITCH'];
			$hsl = $input['HSL_EFM'];
			$efm_ip = $input['EFM_IP'];
			$efm_lowbw_value = ($rate_limit * 1024) + 175;
			
			$efm_ip_octets = explode(".",$efm_ip);
			$efm_gateway = $efm_ip_octets[0] . "." . $efm_ip_octets[1] . "." . $efm_ip_octets[2] . ".1";
			$efm_subnet = "255.255.254.0";
			
			for($i=1;$i<=$num_circuits;$i++){
				$mlp_var = "MLP" . $i;
				$efm_cid_var = "CID" . $i . "_EFM";
				$mlp_array[$i] = $input[$mlp_var];
				$efm_cid_array[$i] = $input[$efm_cid_var];
			}

		}


		//CREATE IAD INTERFACE ARRAY
		if($device== "IAD"){
			$T1_interfaces[] = "1/0";
			$cid_key = "CID1_T1";
			$T1_CIDs[] = $input[$cid_key];
			$serial_key = "SERIAL1";
			$T1_SERIALs[] = $input[$serial_key];
			
			$slot_key = "IAD_slot0";
			if($input[$slot_key]){
				for($j=0;$j<$input[$slot_key];$j++){
					$T1_interfaces[] = "0/" . $j;
					$cid_var = $j+2;
					$cid_key = "CID" . $cid_var . "_T1";
					$T1_CIDs[] = $input[$cid_key];
					$serial_key = "SERIAL" . $cid_var;
					$T1_SERIALs[] = $input[$serial_key];
				}
			}
			
			if($model == "IAD_24" || $model == "IAD_T1"){
				$T1_interfaces[] = "1/1";
			}
			
			//CHECK FOR NECESSARY T1 SLOTS ON IAD
			$slot_count = count($T1_interfaces);
			$slots_needed = 0;
			if($access == "T1"){
				$slots_needed = $num_circuits;
			}
			$slots_needed = $num_trunk_groups + $slots_needed;

			if($slot_count < $slots_needed){
				echo "<h1>ERROR: THERE ARE NOT ENOUGH T1 SLOTS FOR THIS CONFIGURATION!</h1>";
				echo "<h3>You need $slots_needed, but you have $slot_count.</h3>";
				$error++;
			}

		}

		//CREATE SPIAD INTERFACE ARRAY
		if($device== "SPIAD"){
			$cid_var = 1;
			for($i=0;$i<=3;$i++){
				$slot_key = "SPIAD_slot" . $i;
				if($input[$slot_key]){
					for($j=0;$j<$input[$slot_key];$j++){
						$T1_interfaces[] = "0/" . $i . "/" . $j;
						$cid_key = "CID" . $cid_var . "_T1";
						$T1_CIDs[] = $input[$cid_key];
						$serial_key = "SERIAL" . $cid_var;
						$T1_SERIALs[] = $input[$serial_key];
						$cid_var++;
					}
				}
			}
			
			//CHECK FOR NECESSARY T1 SLOTS ON ISR
			$slot_count = count($T1_interfaces);
			$slots_needed = 0;
			if($access == "T1"){
				$slots_needed = $num_circuits;
			}
			$slots_needed = $num_trunk_groups + $slots_needed;

			if($slot_count < $slots_needed){
				echo "<h1>ERROR: THERE ARE NOT ENOUGH T1 SLOTS FOR THIS CONFIGURATION!</h1>";
				echo "<h3>You need $slots_needed, but you have $slot_count.</h3>";
				$error++;
			}
		}

		//CREATE ISR INTERFACE ARRAY
		if($device== "ISR"){
			$cid_var = 1;
			for($i=0;$i<=3;$i++){
				$slot_key = "ISR_slot" . $i;
				if($input[$slot_key]){
					for($j=0;$j<$input[$slot_key];$j++){
						$T1_interfaces[] = "0/" . $i . "/" . $j;
						$cid_key = "CID" . $cid_var . "_T1";
						$T1_CIDs[] = $input[$cid_key];
						$serial_key = "SERIAL" . $cid_var;
						$T1_SERIALs[] = $input[$serial_key];
						$cid_var++;
					}
				}
			}
			
			//CHECK FOR NECESSARY T1 SLOTS ON ISR
			$slot_count = count($T1_interfaces);
			$slots_needed = 0;
			if($access == "T1"){
				$slots_needed = $num_circuits;
			}
			$slots_needed = $num_trunk_groups + $slots_needed;

			if($slot_count < $slots_needed){
				echo "<h1>ERROR: THERE ARE NOT ENOUGH T1 SLOTS FOR THIS CONFIGURATION!</h1>";
				echo "<h3>You need $slots_needed, but you have $slot_count.</h3>";
				$error++;
			}
		}



		//UPSTREAM INTERFACE DECISION ENGINE
		switch($device){
			case IAD:
				switch($access){
					case T1:
						switch($num_circuits){
							case 1:
								$upstream_int = "Serial1/0:0";
								break;
							case ($num_circuits > 1):
								$upstream_int = "Multilink1";
								break;
						}
						break;
					
					case EFM:
						$upstream_int = "FastEthernet0/0.$vlan";
						break;
					
					case FIBER:
						$upstream_int = "FastEthernet0/1";
						break;
				}
				break;

			case SPIAD:
				switch($access){
					case T1:
						switch($num_circuits){
							case 1:
								$upstream_int = "Serial" . $T1_interfaces[0] . ":0";
								break;
							case ($num_circuits > 1):
								$upstream_int = "Multilink1";
								break;
						}
						break;
					case EFM:
						$upstream_int = "GigabitEthernet0/0.$vlan";
						break;
					case FIBER:
						$upstream_int = "GigabitEthernet0/1";
						break;
				}
				break;

				
			case ISR:
				switch($access){
					case T1:
						switch($num_circuits){
							case 1:
								$upstream_int = "Serial" . $T1_interfaces[0] . ":0";
								break;
							case ($num_circuits > 1):
								$upstream_int = "Multilink1";
								break;
						}
						break;
					case EFM:
						$upstream_int = "GigabitEthernet0/0.$vlan";
						break;
					case FIBER:
						$upstream_int = "GigabitEthernet0/1";
						break;
				}
				break;
		}





		//CREATE ANALOG LINE ARRAY
		for($i=1;$i<=$num_analogs;$i++){
			$line_key = "ANALOG" . $i;	
			$analogs[$i] = $input[$line_key];
		}


		//GET IAD SERIAL IP FROM IAD NAME
		$market = strtoupper(substr($iad_name,-3));
		$domain = strtolower($market) . "0.cbeyond.net";
		$iad_fqdn = $iad_name . "." . strtolower($market) . "0.cbeyond.net";
		$iad_ip = gethostbyname($iad_fqdn);


		//CHECK FOR VALID IAD NAME
		if($iad_fqdn == $iad_ip){ 
			echo "<h1>ERROR: IAD NAME (\"$iad_name\") IS INVALID!</h1>"; 
			$error++;
		}

		//GET 10K SERIAL IP FROM IAD SERIAL IP
		$ip_parts = explode(".",$iad_ip);
		if($ip_parts[0] == 10){
			$tenk_last_octet = $ip_parts[3] - 4;
			$tenk_primary_last_octet = $ip_parts[3] - 3;
			$tenk_secondary_last_octet = $ip_parts[3] - 2;
			$serial_subnet = "255.255.255.248";
		}else{
			$tenk_last_octet = $ip_parts[3] - 1;
			//GET SUBNET MASK FOR SERIAL IP
			$tenk_ip_last_digit = substr($tenk_last_octet,-1);
			if($tenk_ip_last_digit % 2 == 0){
				$serial_subnet = "255.255.255.254";
			}else{
				$serial_subnet = "255.255.255.252";
			}
		}
		$tenk_ip = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $tenk_last_octet;
		$tenk_primary_ip = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $tenk_primary_last_octet;
		$tenk_secondary_ip = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $tenk_secondary_last_octet;





		//GET SECOND IAD SERIAL IP FROM IAD NAME
		if($second_iad_name){
			$second_market = strtoupper(substr($second_iad_name,-3));
			$second_domain = strtolower($second_market) . "0.cbeyond.net";
			$second_iad_fqdn = $second_iad_name . "." . strtolower($second_market) . "0.cbeyond.net";
			$second_iad_ip = gethostbyname($second_iad_fqdn);

		//CHECK FOR VALID IAD NAME
			if($second_iad_fqdn == $second_iad_ip){ 
				echo "<h1>ERROR: SECONDARY IAD NAME (\"$second_iad_name\") IS INVALID!</h1>"; 
				$error++;
			}
			
			
		//GET 10K SERIAL IP FROM IAD SERIAL IP
			$ip_parts = explode(".",$second_iad_ip);
			if($ip_parts[0] == 10){
				$network_address_last_octet = $ip_parts[3] - 6;
				$upstream_int_last_octet = $ip_parts[3] - 4;
				$second_serial_subnet = "255.255.255.248";
			}else{
				$upstream_int_last_octet = $ip_parts[3] - 1;
				//GET SUBNET MASK FOR SERIAL IP
				$tenk_ip_last_digit = substr($tenk_last_octet,-1);
				if($tenk_ip_last_digit % 2 == 0){
					$second_serial_subnet = "255.255.255.254";
					$network_address_last_octet = $ip_parts[3] - 1;
				}else{
					$second_serial_subnet = "255.255.255.252";
					$network_address_last_octet = $ip_parts[3] - 2;
				}
			}
			$network_address = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $network_address_last_octet;
			$upstream_int_ip = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $upstream_int_last_octet;
		}



		//CHECK FOR VALID 10K NAME
		$tenk_fqdn = $tenk_name . "." . strtolower($market) . "0.cbeyond.net";
		$tenk_ip_private = gethostbyname($tenk_fqdn);
		if($tenk_fqdn == $tenk_ip_private){ 
			echo "<h1>ERROR: 10K NAME (\"$tenk_name\") IS INVALID!</h1>"; 
			$error++;
		}

		//GET SUBNET MASK FOR SERIAL IP
		// $tenk_ip_last_digit = substr($tenk_last_octet,-1);
		// if($tenk_ip_last_digit % 2 == 0){
			// $serial_subnet = "255.255.255.254";
		// }else{
			// $serial_subnet = "255.255.255.252";
		// }


		//  ************************* BEGIN BUILD MAP FILE **************************************
		$filedate = date(DATE_RFC1123);
		$map_file_input = array("Built by " . $_SERVER['REMOTE_USER'] . " on " . $filedate);
		if($order_num){$map_file_input[] = "[VAR_ORDER_NUM]::" . $order_num;}
		if($device){$map_file_input[] = "[VAR_DEVICE]::" . $device;}
		if($model){$map_file_input[] = "[VAR_MODEL]::" . $model;}
		if($pca){$map_file_input[] = "[VAR_PCA]::" . $pca;}
		if($access){$map_file_input[] = "[VAR_ACCESS]::" . $access;}
		if($voice_type){$map_file_input[] = "[VAR_VOICE_TYPE]::" . $voice_type;}
		if($data_type){$map_file_input[] = "[VAR_DATA_TYPE]::" . $data_type;}
		if($iad_name){$map_file_input[] = "[VAR_IADNAME]::" . $iad_name;}
		if($iad_number){$map_file_input[] = "[VAR_IAD_NUMBER]::" . $iad_number;}
		if($this->constants->time_zone[$market]){$map_file_input[] = "[VAR_TIMEZONE]::" . $this->constants->time_zone[$market];}
		if($this->constants->daylight_savings[$market]){$map_file_input[] = "[VAR_DAYLIGHT_SAVINGS_TIME]::" . $this->constants->daylight_savings[$market];}
		if($domain){$map_file_input[] = "[VAR_DOMAIN]::" . $domain;}
		if($this->constants->primary_dns[$market]){$map_file_input[] = "[VAR_PRIMARY_DNS]::" . $this->constants->primary_dns[$market];}
		if($this->constants->secondary_dns[$market]){$map_file_input[] = "[VAR_SECONDARY_DNS]::" . $this->constants->secondary_dns[$market];}
		if($upstream_int){$map_file_input[] = "[VAR_UPSTREAM_INTERFACE]::" . $upstream_int;}
		if($acct_num){$map_file_input[] = "[VAR_ACCT_NUM]::" . $acct_num;}
		if($tenk_ip){$map_file_input[] = "[VAR_10K_SERIAL_IP]::" . $tenk_ip;}
		if($serial_subnet){$map_file_input[] = "[VAR_SERIAL_SUBNET]::" . $serial_subnet;}
		if($iad_ip){$map_file_input[] = "[VAR_IAD_SERIAL_IP]::" . $iad_ip;}
		if($shape_avg){$map_file_input[] = "[VAR_SHAPE_AVERAGE]::" . $shape_avg;}
		if($iad_fqdn){$map_file_input[] = "[VAR_IAD_FQDN]::" . $iad_fqdn;}
		if($acct_name){$map_file_input[] = "[VAR_ACCT_NAME]::" . $acct_name;}
		if($public_ip_gateway){$map_file_input[] = "[VAR_IAD_PUBLIC_GATEWAY]::" . $public_ip_gateway;}
		if($public_ip_network){$map_file_input[] = "[VAR_IAD_PUBLIC_NETWORK]::" . $public_ip_network;}
		if($public_subnet){$map_file_input[] = "[VAR_PUBLIC_SUBNET_MASK]::" . $public_subnet;}
		if($tenk_name){$map_file_input[] = "[VAR_10K_NAME]::" . $tenk_name;}
		if($bwas){$map_file_input[] = "[VAR_BWAS_FQDN]::" . $bwas;}
		if($btn){$map_file_input[] = "[VAR_BTN]::" . $btn;}
		if($num_circuits){$map_file_input[] = "[VAR_NUM_CIRCUITS]::" . $num_circuits;}
		if($num_trunk_groups){$map_file_input[] = "[VAR_NUM_TRUNK_GROUPS]::" . $num_trunk_groups;}
		if($market){$map_file_input[] = "[VAR_MARKET]::" . $market;}
		if($num_devices){$map_file_input[] = "[VAR_NUM_DEVICES]::" . $num_devices;}
		if($num_analogs){$map_file_input[] = "[VAR_NUM_ANALOGS]::" . $num_analogs;}
		if($num_fxs_ports){$map_file_input[] = "[VAR_NUM_FXS_PORTS]::" . $num_fxs_ports;}
		if($num_fxs_ports_second){$map_file_input[] = "[VAR_NUM_FXS_PORTS]::" . $num_fxs_ports_second;}
		if($input['mpls']){ $map_file_input[] = "[VAR_MPLS]::1"; }

		//DEFINE VOICE PORT ADDRESS
		if($model == "SPIAD_8"){
			$key = "SPIAD_ALT";
			if($this->constants->voice_port_address[$key]){$map_file_input[] = "[VAR_PORT_ADDRESS]::" . $this->constants->voice_port_address[$key];}
		}else{
			if($this->constants->voice_port_address[$device]){$map_file_input[] = "[VAR_PORT_ADDRESS]::" . $this->constants->voice_port_address[$device];}
		}

		//DEFINE VARIABLES FOR SECONDARY IAD
		if($num_devices == 2){
			if($second_device){$map_file_input[] = "[VAR_DEVICE_SECONDARY]::" . $second_device;}
			if($second_model){$map_file_input[] = "[VAR_MODEL_SECONDARY]::" . $second_model;}
			if($second_iad_name){$map_file_input[] = "[VAR_IADNAME_SECONDARY]::" . $second_iad_name;}
			if($second_device){$map_file_input[] = "[VAR_UPSTREAM_INTERFACE_SECONDARY]::" . $this->constants->data_interface[$second_device];}
			if($device){$map_file_input[] = "[VAR_CROSSOVER_INTERFACE]::" . $this->constants->fiber_interface[$device];}
			if($second_iad_ip){$map_file_input[] = "[VAR_SECOND_IAD_SERIAL_IP]::" . $second_iad_ip;}
			if($second_serial_subnet){$map_file_input[] = "[VAR_SECOND_SERIAL_SUBNET]::" . $second_serial_subnet;}
			if($network_address){$map_file_input[] = "[VAR_SECOND_SERIAL_NETWORK_ADDRESS]::" . $network_address;}
			if($upstream_int_ip){$map_file_input[] = "[VAR_SECOND_SERIAL_UPSTREAM_IP]::" . $upstream_int_ip;}

			if($second_model == "SPIAD_8"){
				$key = "SPIAD_ALT";
				if($this->constants->voice_port_address[$key]){$map_file_input[] = "[VAR_PORT_ADDRESS_SECONDARY]::" . $this->constants->voice_port_address[$key];}
			}else{
				if($this->constants->voice_port_address[$device]){$map_file_input[] = "[VAR_PORT_ADDRESS_SECONDARY]::" . $this->constants->voice_port_address[$second_device];}
			}	
		}


		//DEFINE SIP ACL IPs
		if($voice_type == "SIP"){
			if($this->constants->sip_acl_ip_outbound[$market]){$map_file_input[] = "[VAR_VAR_SIP_PROTECTED_IP_OUTBOUND]::" . $this->constants->sip_acl_ip_outbound[$market];}
			if($this->constants->sip_acl_ip_inbound[$market]){$map_file_input[] = "[VAR_VAR_SIP_PROTECTED_IP_INBOUND]::" . $this->constants->sip_acl_ip_inbound[$market];}
		}

		//DEFINE EFM INTERFACES
		if($access == "EFM"){
			if($this->constants->efm_interface[$device]){$map_file_input[] = "[VAR_EFM_INTERFACE]::" . $this->constants->efm_interface[$device];}
			if($efm_switch){$map_file_input[] = "[VAR_EFM_SWITCH]::" . $efm_switch;}
			if($hsl){$map_file_input[] = "[VAR_EFM_HSL]::" . $hsl;}
			if($efm_vlan){$map_file_input[] = "[VAR_EFM_VLAN]::" . $efm_vlan;}
			if($this->constants->data_interface[$device]){$map_file_input[] = "[VAR_DATA_INTERFACE]::" . $this->constants->data_interface[$device] . ".20";}
			if($efm_ip){$map_file_input[] = "[VAR_EFM_CPE_IP]::" . $efm_ip;}
			if($efm_gateway){$map_file_input[] = "[VAR_EFM_CPE_GATEWAY]::" . $efm_gateway;}
			if($efm_subnet){$map_file_input[] = "[VAR_EFM_CPE_SUBNET]::" . $efm_subnet;}
			if($efm_lowbw_value){$map_file_input[] = "[VAR_EFM_LOWBW_VALUE]::" . $efm_lowbw_value;}
			if($efm_rate_limit){$map_file_input[] = "[VAR_BANDWIDTH]::" . $efm_rate_limit;}
			if($efm_vlan){$map_file_input[] = "[VAR_10K_INTERFACE]::Port-channel1." . $efm_vlan;}
			
			for($i=1;$i<=$num_circuits;$i++){
				if($mlp_array[$i]){$map_file_input[] = "[VAR_MLP" . $i . "]::" . $mlp_array[$i];}
				if($efm_cid_array[$i]){$map_file_input[] = "[VAR_EFM_CID" . $i . "]::" . $efm_cid_array[$i];}
			}
		}

		//DEFINE FIBER INTERFACES
		if($access == "FIBER"){
			if($this->constants->data_interface[$device]){$map_file_input[] = "[VAR_DATA_INTERFACE]::" . $this->constants->data_interface[$device];}
			if($this->constants->fiber_interface[$device]){$map_file_input[] = "[VAR_FIBER_INTERFACE]::" . $this->constants->fiber_interface[$device];}
			if($fiber_cid){$map_file_input[] = "[VAR_FIBER_CID]::" . $fiber_cid;}
			if($fiber_vlan){$map_file_input[] = "[VAR_FIBER_VLAN]::" . $fiber_vlan;}
			if($fiber_site_vlan){$map_file_input[] = "[VAR_FIBER_SITE_VLAN]::" . $fiber_site_vlan;}
			if($fiber_rate_limit){$map_file_input[] = "[VAR_BANDWIDTH]::" . $fiber_rate_limit;}
			if($fiber_secondary_10k_name){$map_file_input[] = "[VAR_10K_NAME_SECONDARY]::" . $fiber_secondary_10k_name;}
			if($fiber_10k_interface){$map_file_input[] = "[VAR_10K_INTERFACE]::" . $fiber_10k_interface;}
			if($this->constants->dark_fiber_10K_interfaces[$tenk_name]){$map_file_input[] = "[VAR_10K_INTERFACE_PRIMARY]::" . $this->constants->dark_fiber_10K_interfaces[$tenk_name] . "." . $fiber_vlan;}
			if($this->constants->dark_fiber_10K_interfaces[$fiber_secondary_10k_name]){$map_file_input[] = "[VAR_10K_INTERFACE_SECONDARY]::" . $this->constants->dark_fiber_10K_interfaces[$fiber_secondary_10k_name] . "." . $fiber_vlan;}
			if($tenk_primary_ip){$map_file_input[] = "[VAR_10K_PRIMARY_SERIAL_IP]::" . $tenk_primary_ip;}
			if($tenk_secondary_ip){$map_file_input[] = "[VAR_10K_SECONDARY_SERIAL_IP]::" . $tenk_secondary_ip;}
			if($siteID){$map_file_input[] = "[VAR_SITE_NAME]::" . $siteID;}
			if($basPort){$map_file_input[] = "[VAR_BAS_PORT]::" . $basPort;}
			if($input['fiber_provider']){$map_file_input[] = "[VAR_FIBER_PROVIDER]::" . $input['fiber_provider'];}
		}

		//DEFINE T1 INTERFACES
		if($access == "T1"){
			if($this->constants->data_interface[$device]){$map_file_input[] = "[VAR_DATA_INTERFACE]::" . $this->constants->data_interface[$device];}
			for($i=0;$i<$num_circuits;$i++){
				$var_num = $i+1;
				if($T1_interfaces[$i]){$map_file_input[] = "[VAR_T1_INTERFACE" . $var_num . "]::" . $T1_interfaces[$i];}
				if($T1_CIDs[$i]){$map_file_input[] = "[VAR_T1_CID" . $var_num . "]::" . $T1_CIDs[$i];}
				if($T1_SERIALs[$i]){$map_file_input[] = "[VAR_T1_SERIAL" . $var_num . "]::" . $T1_SERIALs[$i];}
			}
		}
			
		//DEFINE PRI CONTROLLER INTERFACES
		switch($device){
			case IAD:
				if($num_trunk_groups == 1){
					$map_file_input[] = "[VAR_PRI_CONTROLLER1]::1/1";
					
				}elseif($num_trunk_groups == 2){
					$map_file_input[] = "[VAR_PRI_CONTROLLER1]::1/0";
					$map_file_input[] = "[VAR_PRI_CONTROLLER2]::1/1";
				}
				break;

			case SPIAD:
				$num_T1s = 0;
				if ($access == "T1"){ $num_T1s = $num_circuits; }
				$pri_interface_key1 = $num_T1s;
				$pri_interface_key2 = $num_T1s + 1;
				if($T1_interfaces[$pri_interface_key1]){$map_file_input[] = "[VAR_PRI_CONTROLLER1]::" . $T1_interfaces[$pri_interface_key1];}
				if($T1_interfaces[$pri_interface_key2]){$map_file_input[] = "[VAR_PRI_CONTROLLER2]::" . $T1_interfaces[$pri_interface_key2];}
				break;
			
			case ISR:
				$num_T1s = 0;
				if ($access == "T1"){ $num_T1s = $num_circuits; }
				$pri_interface_key1 = $num_T1s;
				$pri_interface_key2 = $num_T1s + 1;
				if($T1_interfaces[$pri_interface_key1]){$map_file_input[] = "[VAR_PRI_CONTROLLER1]::" . $T1_interfaces[$pri_interface_key1];}
				if($T1_interfaces[$pri_interface_key2]){$map_file_input[] = "[VAR_PRI_CONTROLLER2]::" . $T1_interfaces[$pri_interface_key2];}
				break;
		}
				
				
		//DEFINE ANALOG LINES
		for($i=1;$i<=$num_analogs;$i++){
			if($analogs[$i]){$map_file_input[] = "[VAR_TEL_NUM" . $i . "]::" . $analogs[$i];}
		}

		foreach($map_file_input as $input){
			$file_string = $file_string . $input . "\n";
		}

		$datetime = date(Y_m_d_His);
		$mapfile = "maps/$datetime-$acct_num-$username.txt"; 
		$Handle = fopen($mapfile, 'w') or die("Error: Unable to create map file.");
		fwrite($Handle, $file_string); 
		fclose($Handle); 

		return array('mapfile' => $mapfile, 'errors' => $errors);
	}

}



