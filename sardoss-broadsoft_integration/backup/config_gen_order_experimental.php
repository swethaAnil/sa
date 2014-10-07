<?php
if($_POST['order_num']){
	$critical_error = 0;
	$order_num = $_POST['order_num'];
	$url = "http://papa01prd.bay.cbeyond.net:8000/getAccountAndCircuitInfo.json?order=" . $order_num;
	$json = file_get_contents($url);
	$json_output = json_decode($json);
	if($json_output){
		$acct_num = $json_output->account_id;
		$acct_name = $json_output->account_name;
		$access_type = $json_output->access_type;
		$call_agent = $json_output->call_agent;
		$ca_type = $json_output->ca_type;

		if($ca_type == "Broadsoft"){
			$bsm_client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");
			$bsm_result = $bsm_client->findCustomerLocation(array('accountId' => $acct_num));
			$broadsoft = $bsm_result->return;
		}

		$voice_service_type = $json_output->voice_service_type;
		$aggregation_router = $json_output->aggregation_router;
		$agg_router_bits = explode(".",$aggregation_router);
		$tenk_name = $agg_router_bits[0];
		$committed_bw = $json_output->committed_bw;
		$btn = $json_output->btn;
		$efm_switch = $json_output->efm_switch;
		$hsl_bits = explode("-",$json_output->hsl);
		$hsl = ltrim($hsl_bits[1],"0");
		$vlan_bits = explode(".",$json_output->vlan);
		$vlan = $vlan_bits[1];


		$local_line_count = 0;
		foreach ( $json_output->local_lines as $local_line ){
			if($local_line->port > 0){
				$port = $local_line->port;
				$local_line_tn[$port] = $local_line->tn;
				$local_line_count++;
			}
		}

		$network_count = 0;
		foreach ( $json_output->networks as $network ){
			$network_count++;
			$network_type[$network_count] = $network->type;
			$network_description[$network_count] = $network->description;
			$network_addr[$network_count] = $network->addr;
			$network_cidr[$network_count] = $network->cidr;
			$network_iad[$network_count] = $network->iad;
		}

		$circ_count = 0;
		foreach ( $json_output->circs as $circ ){
			$circ_count++;
			$circ_cid[$circ_count] = $circ->cid;
			$circ_cfa[$circ_count] = $circ->cfa;
			$circ_mlp[$circ_count] = $circ->mlp;
			$circ_mlu[$circ_count] = $circ->mlu;
			$circ_slot[$circ_count] = $circ->slot;
			$circ_subslot[$circ_count] = $circ->subslot;
			$circ_port[$circ_count] = $circ->port;
			$circ_bw[$circ_count] = $circ->port-bw;
			
		}
		if($json_output->db_error){echo $json_output->db_error . "<br />";}
		if($json_output->missing){echo $json_output->missing . "<br />";}

		//BUILD SERIAL INTERFACE ARRAY FOR T1 ACCESS and calculate bandwidth
		if($access_type == "T1"){
			for($i=1;$i<=$circ_count;$i++){

				//DEFINE SLOT
				if($circ_slot[$i] == "00"){
					$slot = 0;
				}elseif(strlen($circ_slot[$i]) > 1 && substr($circ_slot[$i],0,1) == 0){
					$slot = ltrim($circ_slot[$i],"0"); 
				}else{
					$slot = $circ_slot[$i];
				}
				
				
				//DEFINE SUBSLOT (IF PRESENT)
				if(preg_match('/-/',$circ_subslot[$i])){
					$bits = explode("-",$circ_subslot[$i]);
					$subslot = $bits[1];
				}elseif(strlen($circ_subslot[$i]) > 0){
					if(strlen($circ_subslot[$i]) > 1 && substr($circ_subslot[$i],0,1) == 0){
						$subslot = ltrim($bit,"0"); 
					}else{
						$subslot = $circ_subslot[$i];
					}
				}
				
				//DEFINE PORT
				$port_bits = explode("/",$circ_port[$i]);
				
				//LOAD INTERFACE NAME INTO SERIAL ARRAY		
				$serial[$i] = "Serial" . $slot;
				if(strlen($subslot) > 0){ $serial[$i] = $serial[$i] . "/" . $subslot;}
				foreach($port_bits as $bit){
					if(strlen($bit) > 1 && substr($bit,0,1) == 0){ $bit = ltrim($bit,"0"); }
					$serial[$i] = $serial[$i] . "/" . $bit;
				}
				$serial[$i] = $serial[$i] . ":0";
				
				$slot = NULL;
				$subslot = NULL;
				$port_bits = NULL;
				
			}
			
			$calc_bw = $circ_count * 1.5;
				
		}

		//EXTRACT IP AND IAD NAME FROM NETWORKS DATA
		$set_public = 0;
		$set_private = 0;
		for($i=1;$i<=$network_count;$i++){
			if($network_type[$i] == "CBEYOND - PRIVATE" && $network_iad[$i] != "" && $set_private == 0){
				$iadname_bits = explode(".",$network_iad[$i]);
				$iadname = STRTOUPPER($iadname_bits[0]);
				$set_private = 1;
			}
			
			if($network_type[$i] == "CBEYOND - PUBLIC" && $set_public == 0){
				$public_ip = $network_addr[$i];
				$public_cidr = $network_cidr[$i];
				$set_public = 1;
			}

			if($network_type[$i] == "CBEYOND - LOOPBACK" && $set_public == 0){
				$public_ip = $network_addr[$i];
				$public_cidr = $network_cidr[$i];
				$set_public = 1;
			}
			
		}	

		//BUILD MLP ARRAY FOR EFM ACCESS
		if($access_type == "EFM"){
			for($i=1;$i<=$circ_count;$i++){
				$mlp[$i] = $circ_mlp[$i];
				$efm_cid[$i] = $circ_cid[$i];
			}
		}

		//DETERMINE DEVICE TYPE
		$device = "IAD";
		if($access_type == "T1" && $circ_count > 3){
			$device = "ISR";
		}
		if($committed_bw > 10){
			$device = "ISR";
		}	
		if($access_type == "Fiber"){
			$device = "ISR";
		}	
	}else{
		$critical_error = 1;
	}
}


function highlight_select($field){
	if($_POST['order_num']){
		if($field == NULL){
			echo "style=\"background-color:#B20000;color:white;\"";
		}
	}
}

function highlight_input($field){
	if($_POST['order_num']){
		if($field){
			echo "value=\"$field\"";
		}else{
			echo "style=\"background-color:#B20000;color:white;\"";
		}		
	}
}


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title> Service Activations - ReDCON</title>

<!--[if IE 9]>
	<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<![endif]-->

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<!--<![endif]-->



</head>

<script type="text/javascript">

function show(obj) {
	fullID = obj.options[obj.selectedIndex].value;
	no = fullID.substr(4);
	ident = fullID.substr(0,4);
	count = obj.options.length;
	
	for(i=1;i<count;i++){
		document.getElementById(ident+i).style.display = 'none';
	}
	if(no>0){
		document.getElementById(ident+no).style.display = 'block';
	}
}


function multi_show(obj) {
	fullID = obj.options[obj.selectedIndex].value;
	no = fullID.substr(4);
	ident = fullID.substr(0,4);
	count = obj.options.length;
	current = 1;
	
	for(i=1;i<=no;i++){
		document.getElementById(ident+i).style.display = 'block';
		current++;
	}
	
	for(i=current;i<count;i++){
		document.getElementById(ident+i).style.display = 'none';
	}
	
}

function show_confirm(){

	var Missing = "";
	var Msg = "";

	//check for NULL values
	if(document.forms[0].device.value.substring(4)=="0"){
		Missing = Missing + "Device Type\n";
		}
	if(document.forms[0].access.value.substring(4)=="0"){
		Missing = Missing + "Access Type\n";
		}
	if(document.forms[0].pca.value.substring(4)=="0"){
		Missing = Missing + "Primary Call Agent\n";
		}
	if(document.forms[0].voice_type.value.substring(4)=="0"){
		Missing = Missing + "Voice Service Type\n";
		}
	if(document.forms[0].acct_num.value==""){
		Missing = Missing + "Account Number\n";
		}
	if(document.forms[0].acct_name.value==""){
		Missing = Missing + "Account Name\n";
		}
	if(document.forms[0].iad_name.value==""){
		Missing = Missing + "IAD Name\n";
		}	
	if(document.forms[0].tenk_name.value==""){
		Missing = Missing + "10K Name\n";
		}
	if(document.forms[0].public_ip.value==""){
		Missing = Missing + "Public IP\n";
		}
	if(document.forms[0].public_subnet.value==""){
		Missing = Missing + "Subnet Mask\n";
		}
	if(document.forms[0].btn.value==""){
		Missing = Missing + "BTN\n";
		}
		
	//MISSING CRITICAL VALUES ALERT
	if(Missing != ""){	
		Missing = "No values provided for the following field(s):\n\n" + Missing;
		alert(Missing)
		return false;
	}
	
	//check account number format
	var acct_num = document.forms[0].acct_num.value;
	var acct_num_pattern = /^[0-9]{4,6}$/;
	if(acct_num.match(acct_num_pattern) == null){
		alert("ERROR! - Account number is invalid. \n\n Please double-check the account number.")
		return false;
	}
	
	//check ip address format
	var public_ip = document.forms[0].public_ip.value;
	var public_ip_pattern = /^[1-9][0-9]{0,2}\.[1-9][0-9]{0,2}\.[1-9][0-9]{0,2}\.[1-9][0-9]{0,2}$/;
	if(public_ip.match(public_ip_pattern) == null){
		alert("ERROR! - Public IP is invalid. \n\n Please double-check the public IP.")
		return false;
	}
	
	if(document.forms[0].access.value.substring(4)=="1"){
	
		var serial1 = document.forms[0].SERIAL1.value.toUpperCase();
		var serial2 = document.forms[0].SERIAL2.value.toUpperCase();
		var serial3 = document.forms[0].SERIAL3.value.toUpperCase();
		var serial4 = document.forms[0].SERIAL4.value.toUpperCase();
		var serial5 = document.forms[0].SERIAL5.value.toUpperCase();
		var serial6 = document.forms[0].SERIAL6.value.toUpperCase();
		var serial7 = document.forms[0].SERIAL7.value.toUpperCase();
		var serial8 = document.forms[0].SERIAL8.value.toUpperCase();
		var serial_int_pattern = /S(E|ER|ERI|ERIA|ERIAL)*[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}:0/i;

		//check serial interface format
		if(serial1 != "" && serial1.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}
		if(serial2 != "" && serial2.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}
		if(serial3 != "" && serial3.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}
		if(serial4 != "" && serial4.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}	
		if(serial5 != "" && serial5.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}
		if(serial6 != "" && serial6.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}
		if(serial7 != "" && serial7.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}
		if(serial8 != "" && serial8.match(serial_int_pattern) == null){
			alert("ERROR! - One or more serial interfaces don't look right. \n\nPlease double-check the format of the serial interface data.")
			return false;
		}
	}
	
	window.open('','new_window','width=800,height=800,location=0,toolbar=0,status=0,menubar=0,resizable=1,scrollbars=1').focus();
		
	
	
}

</script>

<style type="text/css">

td{
	font-size:80%;
	font-weight:bold;
	padding-right:10px;
}

td.form{
	padding-bottom:10px;
}

td.line{
	text-align:right;
	width:50px;
	padding-right:1px;
	vertical-align:0px;
}

input, select{
	background-color:#F2F2F2;
}


</style>

<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../images/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
<span class="location">
 / <a href="../config/config_gen_order_form.php">ReDCON</a>
 / <u>Order Data</u></span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="../config/config_archive.php">Config Archive</a>
<a style="padding-left:10px;" href="../config/config_modules.php">Config Modules</a>
<a style="padding-left:10px;" href="../base/efm_base_config.php">EFM Base Config</a>
<a style="padding-left:10px;" href="../base/fiber_base_config.php">FIBER Base Config</a>
<a style="padding-left:10px;" href="../vpn/10K_VPN_build.htm">VPN for 10K</a>
<a style="padding-left:10px;" href="/schedule">Online Schedule</a>
<div class="current_user">User: <?php echo $_SERVER['PHP_AUTH_USER']; ?></div>
</div>
</div>


<div id="nojs" style="text-align:center;position:relative;top:75px;"><br /><br />
JavaScript must be enabled in your browser in order to use this tool.<br />
JavaScript is either disabled or not supported by your browser.<br /><br />
Enable Javascript in your browser options and try again.</div>

<div id="fullpage" style="display:none">

<div style="min-width:800px;position:relative;top:75px;margin-left:10px;width:100%;">

<?php

if($access_type == "T1" && $calc_bw < $committed_bw){
	echo "<span style=\"color:red;\"><strong>ALERT</strong>: Siebel indicates a committed bandwidth of $committed_bw, but the system only pulled enough T1s to provide $calc_bw. Please double-check the number of T1s on the account and make the necessary adjustments below.</span><br /><br />";
}

if($json_output->not_found){
	echo "<span style=\"color:red;\"><strong>ALERT</strong>: Data for service order <strong>" . $json_output->not_found . "</strong> could not be found.  You're on your own...</span><br /><br />";
	$critical_error = 1;
}
if($json_output->no_accepted_circuits){
	echo "<span style=\"color:red;\"><strong>ALERT</strong>: There are no accepted circuits for service order <strong>" . $json_output->no_accepted_circuits . "</strong>.  You're on your own...</span><br /><br />";
	$critical_error = 1;
}
if($json_output->no_service_address){
	echo "<span style=\"color:red;\"><strong>ALERT</strong>: There is no service address for service order <strong>" . $json_output->no_service_address . "</strong>.  You're on your own...</span><br /><br />";
	$critical_error = 1;
}
if($ca_type == "BTS"){
	echo "<span style=\"color:red;\"><strong>ALERT</strong>: This appears to be a BTS account, which is not currently supported by this application.</span><br /><br />";
	$critical_error = 1;
}

?>

<div style="min-width:800px;font-size:80%;text-align:left;">NOTE: Use hard refresh (CTRL-F5) to reset the form when changing from one access or device type to another.</div>
<br />


<form action="config_output_experimental.php" method="POST" target="new_window" onsubmit="return show_confirm()">

<table width="100%"><tr>

<td valign="top">
Device Type:
<select <?php highlight_select($device); ?> onchange="show(this)" name="device">
<option value="DEVC0"></option>
<option value="DEVC1" <?php if($device == "IAD"){ echo "selected"; } ?> >IAD</option>
<option value="DEVC2"></option>
<option value="DEVC3" <?php if($device == "ISR"){ echo "selected"; } ?> >ISR</option>
</select>
</td>

<td valign="top">
Access Type: 
<select <?php highlight_select($access_type); ?> onchange="show(this)" name="access" >
<option value="ACCE0"></option>
<option value="ACCE1" <?php if($access_type == "T1"){ echo "selected"; } ?> >T1</option>
<option value="ACCE2" <?php if($access_type == "EFM"){ echo "selected"; } ?> >EFM</option>
<option value="ACCE3" <?php if($access_type == "Fiber"){ echo "selected"; } ?> >FIBER</option>
</select>

<div id="ACCE1" <?php if($access_type == "T1"){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<br />
Number of T1s?
<select onchange="multi_show(this)" name="num_circuits_T1">
<option value="TONE0"></option>
<option value="TONE1" <?php if($circ_count == 1 && $access_type == "T1"){ echo "selected"; } ?> >1</option>
<option value="TONE2" <?php if($circ_count == 2 && $access_type == "T1"){ echo "selected"; } ?> >2</option>
<option value="TONE3" <?php if($circ_count == 3 && $access_type == "T1"){ echo "selected"; } ?> >3</option>
<option value="TONE4" <?php if($circ_count == 4 && $access_type == "T1"){ echo "selected"; } ?> >4</option>
<option value="TONE5" <?php if($circ_count == 5 && $access_type == "T1"){ echo "selected"; } ?> >5</option>
<option value="TONE6" <?php if($circ_count == 6 && $access_type == "T1"){ echo "selected"; } ?> >6</option>
<option value="TONE7" <?php if($circ_count == 7 && $access_type == "T1"){ echo "selected"; } ?> >7</option>
<option value="TONE7" <?php if($circ_count == 8 && $access_type == "T1"){ echo "selected"; } ?> >8</option>
</select>
</div>


<div id="ACCE2" <?php if($access_type == "EFM"){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<br />
Number of EFM pairs?
<select onchange="multi_show(this)" name="num_circuits_EFM">
<option value="EFMP0"></option>
<option value="EFMP2" <?php if($circ_count == 2 && $access_type == "EFM"){ echo "selected"; } ?> >2</option>
<option value="EFMP3" <?php if($circ_count == 3 && $access_type == "EFM"){ echo "selected"; } ?> >3</option>
<option value="EFMP4" <?php if($circ_count == 4 && $access_type == "EFM"){ echo "selected"; } ?> >4</option>
<option value="EFMP5" <?php if($circ_count == 5 && $access_type == "EFM"){ echo "selected"; } ?> >5</option>
<option value="EFMP6" <?php if($circ_count == 6 && $access_type == "EFM"){ echo "selected"; } ?> >6</option>
<option value="EFMP7" <?php if($circ_count == 7 && $access_type == "EFM"){ echo "selected"; } ?> >7</option>
<option value="EFMP8" <?php if($circ_count == 8 && $access_type == "EFM"){ echo "selected"; } ?> >8</option>
</select>
</div>

</td>
<td valign="top">
Primary Call Agent:  
<select <?php highlight_select($ca_type); ?> onchange="show(this)" name="pca">
<option value="PRCA0"></option>
<!--<option value="PRCA1">BTS</option>-->
<option value="PRCA2" <?php if($ca_type == "Broadsoft"){ echo "selected"; } ?> >Broadsoft</option>
</select>
</td>
<td valign="top">
Voice Service Type: 
<select <?php highlight_select($voice_service_type); ?> onchange="show(this)" name="voice_type">
<option value="VOIC0"></option>
<option value="VOIC1">Analog</option>
<option value="VOIC2">PRI</option>
<!--<option value="VOIC3">CAS</option>-->
<!--<option value="VOIC4">SIP</option>-->
<option value="VOIC5">Voice-only PRI</option>
<option value="VOIC6">PRI-Mixed</option>
<!--<option value="VOIC7">CAS-Mixed</option>-->
</select>

<div id="VOIC1" style="display:none">
<br />
Number of analog lines?
<select onchange="multi_show(this)" name="num_analog_lines_ANALOG">
<option value="ANLG0">0</option>

<?php
for($i=1;$i<=48;$i++){
	echo "<option value=\"ANLG" . $i . "\"";
	if($local_line_count == $i){ echo " selected "; }
	echo ">" . $i . "</option>";
}
?>

</select>
</div>

<div id="VOIC2" style="display:none">
<br />
Number of PRI trunk groups?
<select name="num_trunk_groups_PRI">
<option value="TRGP0"></option>
<option value="TRGP1">1</option>
<option value="TRGP2">2</option>
</select>
</div>

<div id="VOIC3" style="display:none">
<br />
Number of CAS trunk groups?
<select name="num_trunk_groups_CAS">
<option value="TRGP0"></option>
<option value="TRGP1">1</option>
<option value="TRGP2">2</option>
</select>
</div>

<div id="VOIC4" style="display:none">
<br />
Number of analog lines?
<select onchange="multi_show(this)" name="num_analog_lines_SIP">
<option value="ANLG0">0</option>
<option value="ANLG1">1</option>
<option value="ANLG2">2</option>
<option value="ANLG3">3</option>
<option value="ANLG4">4</option>
<option value="ANLG5">5</option>
<option value="ANLG6">6</option>
<option value="ANLG7">7</option>
<option value="ANLG8">8</option>
</select>
</div>

<div id="VOIC5" style="display:none">
<br />
Number of analog lines?
<select onchange="multi_show(this)" name="num_analog_lines_VOPRI">
<option value="ANLG0">0</option>
<option value="ANLG1">1</option>
<option value="ANLG2">2</option>
<option value="ANLG3">3</option>
<option value="ANLG4">4</option>
<option value="ANLG5">5</option>
<option value="ANLG6">6</option>
<option value="ANLG7">7</option>
<option value="ANLG8">8</option>
</select>
</div>

<div id="VOIC6" style="display:none">
<br />
Number of PRI trunk groups?
<select name="num_trunk_groups_PRIMIX">
<option value="TRGP0"></option>
<option value="TRGP1">1</option>
<option value="TRGP2">2</option>
</select>
<br />
Number of analog lines?
<select onchange="multi_show(this)" name="num_analog_lines_PRIMIX">
<option value="ANLG0">0</option>
<option value="ANLG1">1</option>
<option value="ANLG2">2</option>
<option value="ANLG3">3</option>
<option value="ANLG4">4</option>
<option value="ANLG5">5</option>
<option value="ANLG6">6</option>
<option value="ANLG7">7</option>
<option value="ANLG8">8</option>
</select>
</div>

<div id="VOIC7" style="display:none">
<br />
Number of CAS trunk groups?
<select name="num_trunk_groups_CASMIX">
<option value="TRGP0"></option>
<option value="TRGP1">1</option>
<option value="TRGP2">2</option>
</select>
<br />
Number of analog lines?
<select onchange="multi_show(this)" name="num_analog_lines_CASMIX">
<option value="ANLG0">0</option>
<option value="ANLG1">1</option>
<option value="ANLG2">2</option>
<option value="ANLG3">3</option>
<option value="ANLG4">4</option>
<option value="ANLG5">5</option>
<option value="ANLG6">6</option>
<option value="ANLG7">7</option>
<option value="ANLG8">8</option>
</select>
</div>

</td>
</tr></table>

<hr />

<!--           GENERAL INFO FORM SECTION              -->

<table width="100%"><tr><td width="75%" style="vertical-align:top;">

<h4 style="text-decoration:underline;">GENERAL INFORMATION</h4>
<table style="border-collapse:collapse;">
<tr><td>Account Number</td><td>Account Name</td><td>IAD Name</td><td>10K Name</td></tr>
<tr><td class="form"><input type="text" name="acct_num" size="14" maxlength="6" <?php highlight_input($acct_num); ?> /></td><td class="form"><input type="text" name="acct_name" size="40" <?php highlight_input($acct_name); ?> /></td>
<td class="form"><input type="text" name="iad_name" size="14" <?php highlight_input($iadname); ?> /></td><td class="form"><input type="text" name="tenk_name" size="14" <?php highlight_input($tenk_name); ?> /></td></tr>

<tr><td>Public IP <span style="font-size:80%">(Network)</span></td><td>Subnet Mask</td></tr>
<tr><td class="form"><input type="text" name="public_ip" size="14" <?php highlight_input($public_ip); ?> /></td>
<td class="form"><select name="public_subnet" <?php highlight_select($public_cidr); ?>>
		<option value=""></option>
		<option value="255.255.255.255" <?php if($public_cidr == 32){ echo "selected"; } ?> >255.255.255.255</option>
		<option value="255.255.255.252" <?php if($public_cidr == 30){ echo "selected"; } ?> >255.255.255.252</option>
		<option value="255.255.255.248" <?php if($public_cidr == 29){ echo "selected"; } ?> >255.255.255.248</option>
		<option value="255.255.255.240" <?php if($public_cidr == 28){ echo "selected"; } ?> >255.255.255.240</option>
		<option value="255.255.255.224" <?php if($public_cidr == 27){ echo "selected"; } ?> >255.255.255.224</option>
		<option value="255.255.255.192" <?php if($public_cidr == 26){ echo "selected"; } ?> >255.255.255.192</option>
		<option value="255.255.255.128" <?php if($public_cidr == 25){ echo "selected"; } ?> >255.255.255.128</option>
</select></td></tr>
<tr><td>BTN</td><td>BWAS</td></tr>	
<tr><td class="form"><input type="text" name="btn" size="14" <?php highlight_input($btn); ?> /></td>
<td class="form"><select name="BWAS" <?php highlight_select($broadsoft); ?>>
		<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		<option value="bwas00" <?php if($broadsoft == "bwas00"){ echo "selected"; } ?> >BWAS00</option>
		<option value="bwas01" <?php if($broadsoft == "bwas01"){ echo "selected"; } ?> >BWAS01</option>
		<option value="bwas04" <?php if($broadsoft == "bwas04"){ echo "selected"; } ?> >BWAS04</option>
		<option value="bwas05" <?php if($broadsoft == "bwas05"){ echo "selected"; } ?> >BWAS05</option>
		<option value="bwas10" <?php if($broadsoft == "bwas10"){ echo "selected"; } ?> >BWAS10</option>
		<option value="bwas11" <?php if($broadsoft == "bwas11"){ echo "selected"; } ?> >BWAS11</option>
		<option value="bwas12" <?php if($broadsoft == "bwas12"){ echo "selected"; } ?> >BWAS12</option>
		<option value="bwas13" <?php if($broadsoft == "bwas13"){ echo "selected"; } ?> >BWAS13</option>
		<option value="bwas14" <?php if($broadsoft == "bwas14"){ echo "selected"; } ?> >BWAS14</option>
		<option value="bwas15" <?php if($broadsoft == "bwas15"){ echo "selected"; } ?> >BWAS15</option>
</select></td></tr>	
</table>

<!--DEVICE DIV -->
<div id="DEVC1" <?php if($device == "IAD"){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<h5 style="text-decoration:underline;">IAD SLOT INFORMATION</h5>
<table><tr><td>SLOT 0</td></tr>
<tr>
<td class="form"><select <?php highlight_select(); ?> name="IAD_slot0">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
</select></td>
</tr></table>
</select>
</div>
<div id="DEVC2" style="display:none">
</div>
<div id="DEVC3" <?php if($device == "ISR"){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<h5 style="text-decoration:underline;">ISR SLOT INFORMATION</h5>
<table><tr><td>SLOT 0/0</td><td>SLOT 0/1</td><td>SLOT 0/2</td><td>SLOT 0/3</td></tr>
<tr>
<td class="form"><select <?php highlight_select(); ?> name="ISR_slot0">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
<td class="form"><select <?php highlight_select(); ?> name="ISR_slot1">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
<td class="form"><select <?php highlight_select(); ?> name="ISR_slot2">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
<td class="form"><select <?php highlight_select(); ?> name="ISR_slot3">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
</tr></table>
</select>
</div>



<!--FIBER CIRCUIT DIV -->
<div id="ACCE3" <?php if($access_type == "Fiber"){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<h5 style="text-decoration:underline;">FIBER CIRCUIT INFORMATION</h5>
<table><tr><td>Circuit ID</td><td>VLAN ID</td><td>Committed Bandwidth</td></tr>
<tr><td class="form"><input type="text" name="CID_FIBER" <?php if($access_type == "Fiber"){ highlight_input($circ_cid[1]); }?> /></td>
<td class="form"><input type="text" name="VLAN_FIBER" <?php if($access_type == "Fiber"){ highlight_input($vlan); }?> /></td>
<td class="form"><select name="rate_limit_FIBER">
		<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		<option value="2" <?php if($committed_bw == 2){ echo "selected"; } ?> >2</option>
		<option value="4" <?php if($committed_bw == 4){ echo "selected"; } ?> >4</option>
		<option value="6" <?php if($committed_bw == 6){ echo "selected"; } ?> >6</option>
		<option value="8" <?php if($committed_bw == 8){ echo "selected"; } ?> >8</option>
		<option value="10" <?php if($committed_bw == 10){ echo "selected"; } ?> >10</option>
		<option value="15" <?php if($committed_bw == 15){ echo "selected"; } ?> >15</option>
		<option value="20" <?php if($committed_bw == 20){ echo "selected"; } ?> >20</option>
		<option value="25" <?php if($committed_bw == 25){ echo "selected"; } ?> >25</option>
		<option value="30" <?php if($committed_bw == 30){ echo "selected"; } ?> >30</option>
		<option value="35" <?php if($committed_bw == 35){ echo "selected"; } ?> >35</option>
		<option value="40" <?php if($committed_bw == 40){ echo "selected"; } ?> >40</option>
		<option value="45" <?php if($committed_bw == 45){ echo "selected"; } ?> >45</option>
		<option value="50" <?php if($committed_bw == 50){ echo "selected"; } ?> >50</option>
		<option value="100" <?php if($committed_bw == 100){ echo "selected"; } ?> >100</option>
</select></td></tr></table>
</select>
</div>

<!--T-1 CIRCUIT ID DIVs -->
<div id="TONE1" <?php if($access_type == "T1" && $circ_count >= 1){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<h5 style="text-decoration:underline;">T1 CIRCUIT INFORMATION</h5>
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 1: <input type="text" name="SERIAL1" value="<?php if($access_type == "T1"){ echo $serial[1]; }?>"/></td><td>Circuit ID: <input type="text" name="CID1_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[1]; }?>"/></td></tr>
</table>
</div>
<div id="TONE2" <?php if($access_type == "T1" && $circ_count >= 2){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 2: <input type="text" name="SERIAL2" value="<?php if($access_type == "T1"){ echo $serial[2]; }?>"/></td><td>Circuit ID: <input type="text" name="CID2_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[2]; }?>"/></td></tr>
</table>
</div>
<div id="TONE3" <?php if($access_type == "T1" && $circ_count >= 3){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 3: <input type="text" name="SERIAL3" value="<?php if($access_type == "T1"){ echo $serial[3]; }?>"/></td><td>Circuit ID: <input type="text" name="CID3_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[3]; }?>"/></td></tr>
</table>
</div>
<div id="TONE4" <?php if($access_type == "T1" && $circ_count >= 4){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 4: <input type="text" name="SERIAL4" value="<?php if($access_type == "T1"){ echo $serial[4]; }?>"/></td><td>Circuit ID: <input type="text" name="CID4_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[4]; }?>"/></td></tr>
</table>
</div>
<div id="TONE5" <?php if($access_type == "T1" && $circ_count >= 5){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 5: <input type="text" name="SERIAL5" value="<?php if($access_type == "T1"){ echo $serial[5]; }?>"/></td><td>Circuit ID: <input type="text" name="CID5_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[5]; }?>"/></td></tr>
</table>
</div>
<div id="TONE6" <?php if($access_type == "T1" && $circ_count >= 6){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 6: <input type="text" name="SERIAL6" value="<?php if($access_type == "T1"){ echo $serial[6]; }?>"/></td><td>Circuit ID: <input type="text" name="CID6_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[6]; }?>"/></td></tr>
</table>
</div>
<div id="TONE7" <?php if($access_type == "T1" && $circ_count >= 7){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 7: <input type="text" name="SERIAL7" value="<?php if($access_type == "T1"){ echo $serial[7]; }?>"/></td><td>Circuit ID: <input type="text" name="CID7_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[7]; }?>"/></td></tr>
</table>
</div>
<div id="TONE8" <?php if($access_type == "T1" && $circ_count >= 8){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 8: <input type="text" name="SERIAL8" value="<?php if($access_type == "T1"){ echo $serial[8]; }?>"/></td><td>Circuit ID: <input type="text" name="CID7_T1" value="<?php if($access_type == "T1"){ echo $circ_cid[8]; }?>"/></td></tr>
</table>
</div>


<!--EFM CIRCUIT ID DIVs -->
<div id="EFMP1" <?php if($access_type == "EFM" && $circ_count >= 1){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<h5 style="text-decoration:underline;">EFM CIRCUIT INFORMATION</h5>
<table><tr><td>EFM Switch</td><td>HSL #</td><td>VLAN ID</td><td>Committed Bandwidth</td></tr>
<tr><td class="form"><input type="text" name="EFM_SWITCH" <?php if($access_type == "EFM"){ highlight_input($efm_switch); }?> /></td>
<td class="form"><input type="text" name="HSL_EFM" size="10" <?php if($access_type == "EFM"){ highlight_input($hsl); }?> /></td>
<td class="form"><input type="text" name="VLAN_EFM" size="10" <?php if($access_type == "EFM"){ highlight_input($vlan); }?> /></td>
<td class="form"><select name="rate_limit_EFM">
		<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
		<option value="2" <?php if($committed_bw == 2){ echo "selected"; } ?> >2</option>
		<option value="4" <?php if($committed_bw == 4){ echo "selected"; } ?> >4</option>
		<option value="6" <?php if($committed_bw == 6){ echo "selected"; } ?> >6</option>
		<option value="8" <?php if($committed_bw == 8){ echo "selected"; } ?> >8</option>
		<option value="10" <?php if($committed_bw == 10){ echo "selected"; } ?> >10</option>
		<option value="15" <?php if($committed_bw == 15){ echo "selected"; } ?> >15</option>
		<option value="20" <?php if($committed_bw == 20){ echo "selected"; } ?> >20</option>
		<option value="25" <?php if($committed_bw == 25){ echo "selected"; } ?> >25</option>
		<option value="30" <?php if($committed_bw == 30){ echo "selected"; } ?> >30</option>
		<option value="35" <?php if($committed_bw == 35){ echo "selected"; } ?> >35</option>
		<option value="40" <?php if($committed_bw == 40){ echo "selected"; } ?> >40</option>
		<option value="45" <?php if($committed_bw == 45){ echo "selected"; } ?> >45</option>
		<option value="50" <?php if($committed_bw == 50){ echo "selected"; } ?> >50</option>
		<option value="100" <?php if($committed_bw == 100){ echo "selected"; } ?> >100</option>
</select></td></tr></table>
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 1: <input type="text" name="MLP1" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[1]; }?>"/></td><td>Circuit ID: <input type="text" name="CID1_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[1]; }?>"/></td></tr>
</table>
</div>
<div id="EFMP2" <?php if($access_type == "EFM" && $circ_count >= 2){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 2: <input type="text" name="MLP2" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[2]; }?>"/></td><td>Circuit ID: <input type="text" name="CID2_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[2]; }?>"/></td></tr>
</table>
</div>
<div id="EFMP3" <?php if($access_type == "EFM" && $circ_count >= 3){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 3: <input type="text" name="MLP3" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[3]; }?>"/></td><td>Circuit ID: <input type="text" name="CID3_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[3]; }?>"/></td></tr>
</table>
</div>
<div id="EFMP4" <?php if($access_type == "EFM" && $circ_count >= 4){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 4: <input type="text" name="MLP4" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[4]; }?>"/></td><td>Circuit ID: <input type="text" name="CID4_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[4]; }?>"/></td></tr>
</table>
</div>
<div id="EFMP5" <?php if($access_type == "EFM" && $circ_count >= 5){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 5: <input type="text" name="MLP5" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[5]; }?>"/></td><td>Circuit ID: <input type="text" name="CID5_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[5]; }?>"/></td></tr>
</table>
</div>
<div id="EFMP6" <?php if($access_type == "EFM" && $circ_count >= 6){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 6: <input type="text" name="MLP6" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[6]; }?>"/></td><td>Circuit ID: <input type="text" name="CID6_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[6]; }?>"/></td></tr>
</table>
</div>
<div id="EFMP7" <?php if($access_type == "EFM" && $circ_count >= 7){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 7: <input type="text" name="MLP7" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[7]; }?>"/></td><td>Circuit ID: <input type="text" name="CID7_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[7]; }?>"/></td></tr>
</table>
</div>
<div id="EFMP8" <?php if($access_type == "EFM" && $circ_count >= 8){ echo "style=\"display:block\""; }else{ echo "style=\"display:none\""; } ?> >
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 8: <input type="text" name="MLP8" size="10" value="<?php if($access_type == "EFM"){ echo $mlp[8]; }?>"/></td><td>Circuit ID: <input type="text" name="CID8_EFM" value="<?php if($access_type == "EFM"){ echo $efm_cid[8]; }?>"/></td></tr>
</table>
</div>



<!--ANALOG LINE DIVs -->
<?php
for($i=1;$i<=48;$i++){
	echo "<div id=\"ANLG" . $i . "\" ";
	if($local_line_count >= $i){
		echo "style=\"display:block\""; 
	}else{
		echo "style=\"display:none\""; 
	}
	echo " >";

	if($i == 1){ echo "<h5 style=\"text-decoration:underline;\">ANALOG LINE INFORMATION</h5>"; }
	
	echo "<table style=\"border-collapse:collapse;\"><tr><td class=\"line\">Line " . $i . ":</td><td class=\"form\"><input type=\"text\" name=\"ANALOG" . $i . "\"  value=\"" . $local_line_tn[$i] . "\"/></td></tr></table></div>";
}
?>

</td><td  style="vertical-align:top;padding-top:20px;">

<?php
if($order_num){  //only display synopsis if an order number was entered
	echo "<div>";
	echo "<strong>ORDER SYNOPSIS</strong><br />";
	
	if($critical_error == 1){
		echo "<textarea rows=\"8\" cols=\"35\" style=\"background-color:#F0F0F0;font-family:arial;font-size:0.875em;\" readonly>";
		echo "The system couldn't find enough information to provide a synopsis.\n\n";
		echo "You will need to provide any missing values manually in order to proceed.";
	}else{
		echo "<textarea rows=\"22\" cols=\"35\" style=\"background-color:#F0F0F0;font-family:arial;font-size:0.875em;\" readonly>";
		echo "The system found a $access_type account with $circ_count circuit(s).  The customer is expecting $committed_bw MB of bandwidth.  Based on this information, the system recommends an $device for this order.\n\n";
		echo "Please indicate which WIC cards, if any, are installed in the $device SLOT INFORMATION section to the left.\n\n";
		echo "The system found $local_line_count analog line(s) on the order, but doesn't know what type of voice service the customer needs.  Please indicate the Voice Service Type in the field above.";
	}
	echo "</textarea></div>";
}
?>

</td></tr></table>

<hr />
<br />
 <input class="button" type="submit" value="Pre-install Config" name="submit"> 
 <input class="button" type="submit" value="Activation Config" name="submit"> 
 <input class="button" type="reset" value="Reset Form">

</form>

<br /><br /><br />


</div>
</div>
</body>

<script>
//if script enabled warning message hidden.
document.getElementById('nojs').style.display="none";
document.getElementById('fullpage').style.display="inline";
</script>

</html>
