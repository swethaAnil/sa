<?php

require("config_func.php");
require("config_constants.php");

//DEFINE VARIABLES
$file_string;
$error = 0;
$username = $_SERVER['PHP_AUTH_USER'];

$order_num = $_POST['order_num'];

$device_key = $_POST['device'];
$device = $device_array[$device_key];

$access_key = $_POST['access'];
$access = $access_array[$access_key];

$circuit_key = "num_circuits_" . $access;
$num_circuits = substr($_POST[$circuit_key],4);

$pca_key = $_POST['pca'];
$pca = $pca_array[$pca_key];

$voice_key = $_POST['voice_type'];
$voice_type = $voice_array[$voice_key];

$analog_key = "num_analog_lines_" . $voice_type;
$num_analogs = substr($_POST[$analog_key],4);

$trunk_key = "num_trunk_groups_" . $voice_type;
$num_trunk_groups = substr($_POST[$trunk_key],4);

//RESET MIXED SERVICE TYPES TO STANDARD VOICE TYPES
if(substr($voice_type,-3) == "MIX"){	$voice_type = substr($voice_type,0,3);	}

$btn = $_POST[btn];
$acct_num = $_POST['acct_num'];
$acct_name = $_POST['acct_name'];
$iad_name = $_POST['iad_name']; 
$iad_number = substr($iad_name,3,-3);
if(strlen($iad_number) > 4){ $iad_number = "9" . $iad_number; }
 
$public_ip_network = $_POST['public_ip'];
$public_ip_parts = explode(".",$public_ip_network);
$public_ip_gateway_lastoctet = $public_ip_parts[3] + 1;
$public_ip_gateway = $public_ip_parts[0] . "." . $public_ip_parts[1] . "." . $public_ip_parts[2] . "." . $public_ip_gateway_lastoctet;

$public_subnet = $_POST['public_subnet'];
$tenk_name = $_POST['tenk_name'];
$tenk_type = substr($tenk_name,0,3);
$bwas = $_POST['BWAS'] . ".voice.cbeyond.net";


$vlan_key = "VLAN_" . $access;
$vlan = $_POST[$vlan_key]; 
$rate_limit_key = "rate_limit_" . $access;
$rate_limit = $_POST[$rate_limit_key];

$fiber_cid = $_POST['CID_FIBER']; 

$efm_switch = $_POST['EFM_SWITCH'];
$hsl = $_POST['HSL_EFM'];


//IDENTIFY DATA CONFIGURATION TYPE 
if($public_subnet == "255.255.255.255"){
	$data_type = "LOOPBACK";
}else{
	$data_type = "PUBLIC";
}


//CREATE IAD INTERFACE ARRAY
if($device== "IAD"){
	$T1_interfaces[] = "1/0";
	$cid_key = "CID1_T1";
	$T1_CIDs[] = $_POST[$cid_key];
	$serial_key = "SERIAL1";
	$T1_SERIALs[] = $_POST[$serial_key];
	
	$slot_key = "IAD_slot0";
	if($_POST[$slot_key]){
		for($j=0;$j<$_POST[$slot_key];$j++){
			$T1_interfaces[] = "0/" . $j;
			$cid_var = $j+2;
			$cid_key = "CID" . $cid_var . "_T1";
			$T1_CIDs[] = $_POST[$cid_key];
			$serial_key = "SERIAL" . $cid_var;
			$T1_SERIALs[] = $_POST[$serial_key];
		}
	}

	//CHECK FOR NECESSARY T1 SLOTS ON IAD
	$slot_count = count($T1_interfaces);
	$slots_needed = 0;
	if($access == "T1"){
		$slots_needed = $num_circuits;
	}
	$slots_needed = $slots_needed;

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
		if($_POST[$slot_key]){
			for($j=0;$j<$_POST[$slot_key];$j++){
				$T1_interfaces[] = "0/" . $i . "/" . $j;
				$cid_key = "CID" . $cid_var . "_T1";
				$T1_CIDs[] = $_POST[$cid_key];
				$serial_key = "SERIAL" . $cid_var;
				$T1_SERIALs[] = $_POST[$serial_key];
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
				$upstream_int = "FastEthernet0/1.$vlan";
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
				$upstream_int = "GigabitEthernet0/1.$vlan";
				break;
		}
		break;
}


//CALCULATE SHAPE AVERAGE
$shape_avg = $rate_limit * 1048576;


//CREATE ANALOG LINE ARRAY
for($i=1;$i<=$num_analogs;$i++){
	$line_key = "ANALOG" . $i;	
	$analogs[$i] = $_POST[$line_key];
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
$tenk_last_octet = $ip_parts[3] - 1;
$tenk_ip = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $tenk_last_octet;

//CHECK FOR VALID 10K NAME
$tenk_fqdn = $tenk_name . "." . strtolower($market) . "0.cbeyond.net";
$tenk_ip_private = gethostbyname($tenk_fqdn);
if($tenk_fqdn == $tenk_ip_private){ 
	echo "<h1>ERROR: 10K NAME (\"$tenk_name\") IS INVALID!</h1>"; 
	$error++;
}

//GET SUBNET MASK FOR SERIAL IP
$tenk_ip_last_digit = substr($tenk_last_octet,-1);
if($tenk_ip_last_digit % 2 == 0){
	$serial_subnet = "255.255.255.254";
}else{
	$serial_subnet = "255.255.255.252";
}


//  ************************* BEGIN BUILD MAP FILE **************************************
$filedate = date(DATE_RFC1123);
$map_file_input = array("Built by " . $_SERVER['REMOTE_USER'] . " on " . $filedate);
if($order_num){$map_file_input[] = "[VAR_ORDER_NUM]::$order_num";}
if($device){$map_file_input[] = "[VAR_DEVICE]::$device";}
if($pca){$map_file_input[] = "[VAR_PCA]::$pca";}
if($access){$map_file_input[] = "[VAR_ACCESS]::$access";}
if($voice_type){$map_file_input[] = "[VAR_VOICE_TYPE]::$voice_type";}
if($data_type){$map_file_input[] = "[VAR_DATA_TYPE]::$data_type";}
if($iad_name){$map_file_input[] = "[VAR_IADNAME]::$iad_name";}
if($iad_number){$map_file_input[] = "[VAR_IAD_NUMBER]::$iad_number";}
if($time_zone[$market]){$map_file_input[] = "[VAR_TIMEZONE]::$time_zone[$market]";}
if($daylight_savings[$market]){$map_file_input[] = "[VAR_DAYLIGHT_SAVINGS_TIME]::$daylight_savings[$market]";}
if($domain){$map_file_input[] = "[VAR_DOMAIN]::$domain";}
if($primary_dns[$market]){$map_file_input[] = "[VAR_PRIMARY_DNS]::$primary_dns[$market]";}
if($secondary_dns[$market]){$map_file_input[] = "[VAR_SECONDARY_DNS]::$secondary_dns[$market]";}
if($upstream_int){$map_file_input[] = "[VAR_UPSTREAM_INTERFACE]::$upstream_int";}
if($acct_num){$map_file_input[] = "[VAR_ACCT_NUM]::$acct_num";}
if($rate_limit){$map_file_input[] = "[VAR_BANDWIDTH]::$rate_limit";}
if($tenk_ip){$map_file_input[] = "[VAR_10K_SERIAL_IP]::$tenk_ip";}
if($serial_subnet){$map_file_input[] = "[VAR_SERIAL_SUBNET]::$serial_subnet";}
if($iad_ip){$map_file_input[] = "[VAR_IAD_SERIAL_IP]::$iad_ip";}
if($shape_avg){$map_file_input[] = "[VAR_SHAPE_AVERAGE]::$shape_avg";}
if($iad_fqdn){$map_file_input[] = "[VAR_IAD_FQDN]::$iad_fqdn";}
if($acct_name){$map_file_input[] = "[VAR_ACCT_NAME]::$acct_name";}
if($public_ip_gateway){$map_file_input[] = "[VAR_IAD_PUBLIC_GATEWAY]::$public_ip_gateway";}
if($public_ip_network){$map_file_input[] = "[VAR_IAD_PUBLIC_NETWORK]::$public_ip_network";}
if($public_subnet){$map_file_input[] = "[VAR_PUBLIC_SUBNET_MASK]::$public_subnet";}
if($tenk_name){$map_file_input[] = "[VAR_10K_NAME]::$tenk_name";}
if($bwas){$map_file_input[] = "[VAR_BWAS_FQDN]::$bwas";}
if($data_interface[$device]){$map_file_input[] = "[VAR_DATA_INTERFACE]::$data_interface[$device]";}
if($btn){$map_file_input[] = "[VAR_BTN]::$btn";}
if($voice_port_address[$device]){$map_file_input[] = "[VAR_PORT_ADDRESS]::$voice_port_address[$device]";}
if($num_circuits){$map_file_input[] = "[VAR_NUM_CIRCUITS]::$num_circuits";}
if($num_trunk_groups){$map_file_input[] = "[VAR_NUM_TRUNK_GROUPS]::$num_trunk_groups";}

//DEFINE EFM INTERFACE
if($access == "EFM"){
	if($efm_interface[$device]){$map_file_input[] = "[VAR_EFM_INTERFACE]::$efm_interface[$device]";}
	if($efm_switch){$map_file_input[] = "[VAR_EFM_SWITCH]::$efm_switch";}
	if($hsl){$map_file_input[] = "[VAR_EFM_HSL]::$hsl";}
	if($vlan){$map_file_input[] = "[VAR_EFM_VLAN]::$vlan";}
}

//DEFINE FIBER INTERFACE
if($access == "FIBER"){
	if($fiber_interface[$device]){$map_file_input[] = "[VAR_FIBER_INTERFACE]::$fiber_interface[$device]";}
	if($fiber_cid){$map_file_input[] = "[VAR_FIBER_CID]::$fiber_cid";}
	if($vlan){$map_file_input[] = "[VAR_FIBER_VLAN]::$vlan";}
}

//DEFINE T1 INTERFACES
if($access == "T1"){
	for($i=0;$i<$num_circuits;$i++){
		$var_num = $i+1;
		if($T1_interfaces[$i]){$map_file_input[] = "[VAR_T1_INTERFACE" . $var_num . "]::$T1_interfaces[$i]";}
		if($T1_CIDs[$i]){$map_file_input[] = "[VAR_T1_CID" . $var_num . "]::$T1_CIDs[$i]";}
		if($T1_SERIALs[$i]){$map_file_input[] = "[VAR_T1_SERIAL" . $var_num . "]::$T1_SERIALs[$i]";}
	}
}

	
//DEFINE PRI CONTROLLER INTERFACES
switch($device){
	case IAD:
		if($num_trunk_groups == 1 || $voice-type == "VOPRI"){
			$map_file_input[] = "[VAR_PRI_CONTROLLER1]::1/1";
		}elseif($num_trunk_groups == 2){
			$map_file_input[] = "[VAR_PRI_CONTROLLER1]::1/0";
			$map_file_input[] = "[VAR_PRI_CONTROLLER2]::1/1";
		}
		break;
	case ISR:
		$pri_interface_key1 = $num_circuits;
		$pri_interface_key2 = $num_circuits + 1;
		if($T1_interfaces[$pri_interface_key1]){$map_file_input[] = "[VAR_PRI_CONTROLLER1]::$T1_interfaces[$pri_interface_key1]";}
		if($T1_interfaces[$pri_interface_key2]){$map_file_input[] = "[VAR_PRI_CONTROLLER2]::$T1_interfaces[$pri_interface_key2]";}
		break;
}
		
//DEFINE ANALOG LINES
for($i=1;$i<=$num_analogs;$i++){
	if($analogs[$i]){$map_file_input[] = "[VAR_TEL_NUM$i]::$analogs[$i]";}
}

foreach($map_file_input as $input){
	$file_string = $file_string . $input . "\n";
}

$datetime = date(Y_m_d_His);
$mapfile = "maps/$datetime-$acct_num-$username.txt"; 
$Handle = fopen($mapfile, 'w')or die("can't open file");
fwrite($Handle, $file_string); 
fclose($Handle); 
//  ************************* END BUILD MAP FILE **************************************

//BUILD VALUE MAP ARRAY
$val_map = build_value_map($mapfile);

?>


<!--   BEGIN HTML MAIN PAGE BUILD  -->

<html>
<head>
<title>Configuration for Account <?php echo $acct_num; ?></title>

<!--[if IE 9]>
	<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<![endif]-->

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<!--<![endif]-->


<script language="JavaScript">
function displayConfig(id, step){
	var displayDiv = document.getElementById('displayDiv');
	var elem = document.getElementById(id);

	var displayIframe = document.getElementById("displayIframe");
	var doc = displayIframe.contentDocument;
	
	if (doc == undefined || doc == null)
		doc = displayIframe.contentWindow.document;
		
	doc.open();
	doc.write(elem.innerHTML);
	doc.close();

	var elemCaption = document.getElementById("title");
	elemCaption.innerHTML= "<u>Currently viewing:</u><br />" + step;
} 


</script>

<style>



a{
	font-size:120%;
	font-weight:bold;
}

a:link{
	color:darkblue;
}
a:visited{
	color:darkblue;
}

a:hover{
	color:red;
	text-decoration:none;
}

a:active{
	color:darkred;
	text-decoration:none;
}

#sidebar {
	position: fixed;
	z-index: 30;
	width: 120px;
	padding-left: 10px;
	padding-top: 90px;
	text-align:center;
	
}


#configframe {
	position:fixed;
	z-index: 30;
	margin-top: -430px;
	
}

#title {
	position: fixed;
	z-index: 30;
	width: 120px;
	padding-left: 10px;
	padding-top: 20px;
	text-align:center;
	
}

#details {
	font-size:80%;
	z-index: 30;
	position:relative;
	padding-top: 60px;
}



</style>

</head>


<body style="font-style:arial;font-size:90%;min-width:800px;">

<div class="titlecolor">

	<div class="title">
	</div>

	<div class="pagetitle">
		<span class="location">
			Configuration for Account <?php echo $acct_num . " - " . $acct_name; ?>
		</span>
	</div>
</div>

<hr class="topline" />

<div id="title">CHOOSE AN OPTION</div>

<div id="sidebar">
	
	<p><a href="#" onclick="displayConfig('tar','<?php echo $tenk_name; ?>')"><?php echo $tenk_name; ?></a>
	<?php 
	if($access == "T1"){
		echo "<p><a href=\"#\" onclick=\"displayConfig('tar-post','$tenk_name<br />(Post-Install)')\">$tenk_name<br />(Post-Install)</a>";
	}
	?>
	<p><a href="#" onclick="displayConfig('ios','IOS')">IOS (flash)</a>
	<p><a href="#" onclick="displayConfig('cpe_device','<?php echo $iad_name; ?>')"><?php echo $iad_name; ?></a>
	
	<div id="details">
		<strong><u>Config Details</u></strong><br />
		Device: <?php echo $device; ?><br />
		Access: <?php echo $access . " (" . $num_circuits . ")"; ?><br />
		PCA: <?php echo $pca; ?><br />
		Voice Type: <?php echo $voice_type; ?>
	</div>
		
</div>




<div id="configframe">

<div id="tar"  style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	echo "<span style=\"color:#CC0000;font-family:courier;font-size:80%;\">";
	//PRINT 10K config
	if($access == "T1" && $num_circuits == 1){
		$module = $access . "_SINGLE_TAR_module";
		$i = 0;
		print_serial_port_module($val_map,$module,$i,$T1_interfaces[$i],$T1_CIDs[$i],$T1_SERIALs[$i]);
	}elseif($access == "T1" && $num_circuits > 1){
		$module = $access . "_MULTI_TAR_module";
		print_module($val_map,$module);
	}else{
		$module = $access . "_TAR_module";
		print_module($val_map,$module);
	}
	
	
	//PRINT SERIAL INTERFACE ACCESS CONFIGS
	if($access == "T1" && $num_circuits > 1){
		$module = $access . "_MULTI_TAR_serial_port_module";
		for($i=0;$i<$num_circuits;$i++){
			print_serial_port_module($val_map,$module,$i,$T1_interfaces[$i],$T1_CIDs[$i],$T1_SERIALs[$i]);
		}
	}
	echo "</span>";
	
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END 10K CONFIG  -->

<div id="tar-post"  style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	
	echo "<span style=\"color:#CC0000;font-family:courier;font-size:80%;\">";
	
	//PRINT 10K POST-INSTALL config
	if($access == "T1" && $num_circuits == 1){
		$module = $access . "_SINGLE_TAR_POST_module";
		$i = 0;
		print_serial_port_module($val_map,$module,$i,$T1_interfaces[$i],$T1_CIDs[$i],$T1_SERIALs[$i]);
	}elseif($access == "T1" && $num_circuits > 1){
		$module = $access . "_MULTI_TAR_POST_module";
		print_module($val_map,$module);
	}
	
	//PRINT SERIAL INTERFACE POST-INSTALL CONFIGS
	if($access == "T1" && $num_circuits > 1){
		$module = $access . "_MULTI_TAR_POST_serial_port_module";
		for($i=0;$i<$num_circuits;$i++){
			print_serial_port_module($val_map,$module,$i,$T1_interfaces[$i],$T1_CIDs[$i],$T1_SERIALs[$i]);
		}
	}
	
	echo "</span>";
		
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END 10K POST CONFIG  -->


<div id="ios"  style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	echo "<span style=\"font-family:arial;font-size:80%;\">";
	print_ios_module();
	echo "</span>";
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END DEVICE IOS CONFIG  -->


<div id="cpe_device"  style="visibility:hidden;display:none;">
<?php

if($error == 0){
	
	echo "<span style=\"color:darkgreen;font-family:courier;font-size:80%;\">";
	
	//PRINT UNIVERSAL DEVICE CONFIG
	$module = $device . "_universal_module";
	print_module($val_map,$module);
	
	//PRINT WIC ACTIVATE CONFIG
	echo "###############################################<br />";
	echo "#   T1_CARD_ACTIVATE_module<br />";
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

	//PRINT DEVICE ACCESS CONFIG
	if($access == "T1" && $num_circuits == 1){
		$module = $access . "_SINGLE_access_module";
	}elseif($access == "T1" && $num_circuits > 1){
		$module = $access . "_MULTI_access_module";
	}else{
		$module = $access . "_access_module";
	}
	print_module($val_map,$module);

	//PRINT SERIAL INTERFACE ACCESS CONFIGS
	if($access == "T1" && $num_circuits > 1){
		$module = $access . "_MULTI_serial_port_module";
		for($i=0;$i<$num_circuits;$i++){
			print_serial_port_module($val_map,$module,$i,$T1_interfaces[$i],$T1_CIDs[$i],$T1_SERIALs[$i]);
		}
	}

	//PRINT DATA CONFIG
	if($access == "EFM"){
		$module = "EFM_" . $data_type . "_data_module";
	}else{
		$module = "STD_" . $data_type . "_data_module";
	}
	print_module($val_map,$module);

	if($_POST['submit'] == "Activation Config"){

		//PRINT UNIVERSAL VOICE CONFIG
		$module = $pca . "_" . $voice_type . "_universal_voice_module";
		print_module($val_map,$module);

		//PRINT VOICE TRUNK CONFIGS
		if($num_trunk_groups){
			$module = $pca . "_" . $voice_type . "_" . $num_trunk_groups . "_trunk_module";
			print_module($val_map,$module);
		}


		//PRINT ANALOG VOICE-PORT CONFIGS
		if($num_analogs){
			if($voice_type == "SIP"){
				$module = $pca . "_" . $voice_type . "_voice_port_module";
				for($i=0;$i<$num_analogs;$i++){
					print_voice_port_module($val_map,$module,$i);
				}
			}elseif($voice_type == "PRI" || $voice_type == "VOPRI"){
				$module = $pca . "_MIXED_ANALOG_voice_port_module";
				for($i=0;$i<$num_analogs;$i++){
					print_voice_port_module($val_map,$module,$i);
				}
			}else{
				$module = $pca . "_ANALOG_voice_port_module";
				for($i=0;$i<$num_analogs;$i++){
					print_voice_port_module($val_map,$module,$i);
				}
			}
		}
	}
	echo "</span>";
}else{
	echo "NO CONFIGS FOR YOU!";
}
 ?>
 </div>  <!--  END DEVICE CONFIG DIV  -->
 </div>
 
 <iframe id='displayIframe' width="650px" height="95%" align="right" frameborder="0" marginwidth="20px" >&nbsp;</iframe>
 
</body>
 </html>
 