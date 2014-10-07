<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title> Service Activations - Device Config Generator </title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="style/tracker_style.css" />
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
	
	
	
	
	
	var serial1 = document.forms[0].SERIAL1.value.toUpperCase();
	var serial2 = document.forms[0].SERIAL2.value.toUpperCase();
	var serial3 = document.forms[0].SERIAL3.value.toUpperCase();
	var serial4 = document.forms[0].SERIAL4.value.toUpperCase();
	var serial5 = document.forms[0].SERIAL5.value.toUpperCase();
	var serial6 = document.forms[0].SERIAL6.value.toUpperCase();
	var serial7 = document.forms[0].SERIAL7.value.toUpperCase();
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


</style>

<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="style/SA_title_words_small.png">
</div>

<div class="pagetitle">Device Config Generator - BETA<span class="location"></span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href=""></a>
<div class="current_user">User: <?php echo $_SERVER['PHP_AUTH_USER']; ?></div>
</div>
</div>


<div id="nojs" style="text-align:center;position:relative;top:75px;"><br /><br />
JavaScript must be enabled in your browser in order to use this tool.<br />
JavaScript is either disabled or not supported by your browser.<br /><br />
Enable Javascript in your browser options and try again.</div>

<div id="fullpage" style="display:none">

<div style="min-width:600px;position:relative;top:75px;margin-left:10px;width:100%;">

<div style="font-size:80%;text-align:left;">NOTE: Use hard refresh (CTRL-F5) to reset the form when changing from one access or device type to another.</div>
<br />


<form action="config_output.php" method="POST" target="new_window" onsubmit="return show_confirm()">

<table width="100%"><tr>

<td valign="top">
Device Type:
<select onchange="show(this)" name="device">
<option value="DEVC0"></option>
<option value="DEVC1">IAD</option>
<!--<option value="DEVC2">SPIAD</option>-->
<option value="DEVC3">ISR</option>
</select>
</td>

<td valign="top">
Access Type: 
<select onchange="show(this)" name="access" >
<option value="ACCE0"></option>
<option value="ACCE1">T1</option>
<option value="ACCE2">EFM</option>
<option value="ACCE3">FIBER</option>
</select>

<div id="ACCE1" style="display:none">
<br />
Number of T1s?
<select onchange="multi_show(this)" name="num_circuits_T1">
<option value="TONE0"></option>
<option value="TONE1">1</option>
<option value="TONE2">2</option>
<option value="TONE3">3</option>
<option value="TONE4">4</option>
<option value="TONE5">5</option>
<option value="TONE6">6</option>
<option value="TONE7">7</option>
</select>
</div>


<div id="ACCE2" style="display:none">
<br />
Number of EFM pairs?
<select onchange="multi_show(this)" name="num_circuits_EFM">
<option value="EFMP0"></option>
<option value="EFMP2">2</option>
<option value="EFMP3">3</option>
<option value="EFMP4">4</option>
<option value="EFMP5">5</option>
<option value="EFMP6">6</option>
<option value="EFMP7">7</option>
<option value="EFMP8">8</option>
</select>
</div>

</td>
<td valign="top">
Primary Call Agent:  
<select onchange="show(this)" name="pca">
<option value="PRCA0"></option>
<!--<option value="PRCA1">BTS</option>-->
<option value="PRCA2">Broadsoft</option>
</select>
</td>
<td valign="top">
Voice Service Type: 
<select onchange="show(this)" name="voice_type">
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
<option value="ANLG1">1</option>
<option value="ANLG2">2</option>
<option value="ANLG3">3</option>
<option value="ANLG4">4</option>
<option value="ANLG5">5</option>
<option value="ANLG6">6</option>
<option value="ANLG7">7</option>
<option value="ANLG8">8</option>
<option value="ANLG9">9</option>
<option value="ANLG10">10</option>
<option value="ANLG11">11</option>
<option value="ANLG12">12</option>
<option value="ANLG13">13</option>
<option value="ANLG14">14</option>
<option value="ANLG15">15</option>
<option value="ANLG16">16</option>
<option value="ANLG17">17</option>
<option value="ANLG18">18</option>
<option value="ANLG19">19</option>
<option value="ANLG20">20</option>
<option value="ANLG21">21</option>
<option value="ANLG22">22</option>
<option value="ANLG23">23</option>
<option value="ANLG24">24</option>
<option value="ANLG25">25</option>
<option value="ANLG26">26</option>
<option value="ANLG27">27</option>
<option value="ANLG28">28</option>
<option value="ANLG29">29</option>
<option value="ANLG30">30</option>
<option value="ANLG31">31</option>
<option value="ANLG32">32</option>
<option value="ANLG33">33</option>
<option value="ANLG34">34</option>
<option value="ANLG35">35</option>
<option value="ANLG36">36</option>
<option value="ANLG37">37</option>
<option value="ANLG38">38</option>
<option value="ANLG39">39</option>
<option value="ANLG40">40</option>
<option value="ANLG41">41</option>
<option value="ANLG42">42</option>
<option value="ANLG43">43</option>
<option value="ANLG44">44</option>
<option value="ANLG45">45</option>
<option value="ANLG46">46</option>
<option value="ANLG47">47</option>
<option value="ANLG48">48</option>
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
<h4 style="text-decoration:underline;">GENERAL INFORMATION</h4>
<table style="border-collapse:collapse;">
<tr><td>Account Number</td><td>Account Name</td><td>IAD Name</td><td>10K Name</td></tr>
<tr><td class="form"><input type="text" name="acct_num" /></td><td class="form"><input type="text" name="acct_name" /></td>
<td class="form"><input type="text" name="iad_name" /></td><td class="form"><input type="text" name="tenk_name" /></td></tr>

<tr><td>Public IP (Network)</td><td>Subnet Mask</td></tr>
<tr><td class="form"><input type="text" name="public_ip" /></td>
<td class="form"><select name="public_subnet">
		<option value=""></option>
		<option value="255.255.255.255">255.255.255.255</option>
		<option value="255.255.255.252">255.255.255.252</option>
		<option value="255.255.255.248">255.255.255.248</option>
		<option value="255.255.255.240">255.255.255.240</option>
		<option value="255.255.255.224">255.255.255.224</option>
		<option value="255.255.255.192">255.255.255.192</option>
		<option value="255.255.255.128">255.255.255.128</option>
</select></td></tr>
<tr><td>BTN</td></tr>	
<tr><td class="form"><input type="text" name="btn" /></td></tr>	
</table>


<!-- PCA INFO DIV -->
<div id="PRCA1" style="display:none">
</div>
<div id="PRCA2" style="display:none">
<table><tr><td>BWAS</td></tr>
<tr><td class="form"><select name="BWAS">
		<option value=""></option>
		<option value="bwas00">BWAS00</option>
		<option value="bwas01">BWAS01</option>
		<option value="bwas04">BWAS04</option>
		<option value="bwas05">BWAS05</option>
		<option value="bwas10">BWAS10</option>
		<option value="bwas11">BWAS11</option>
		<option value="bwas12">BWAS12</option>
		<option value="bwas13">BWAS13</option>
		<option value="bwas14">BWAS14</option>
		<option value="bwas15">BWAS15</option>
</select></td></tr></table>
</div>

<!--DEVICE DIV -->
<div id="DEVC1" style="display:none">
<h5 style="text-decoration:underline;">IAD SLOT INFORMATION</h5>
<table><tr><td>SLOT 0</td></tr>
<tr>
<td class="form"><select name="IAD_slot0">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
</select></td>
</tr></table>
</select>
</div>
<div id="DEVC2" style="display:none">
</div>
<div id="DEVC3" style="display:none">
<h5 style="text-decoration:underline;">ISR SLOT INFORMATION</h5>
<table><tr><td>SLOT 0/0</td><td>SLOT 0/1</td><td>SLOT 0/2</td><td>SLOT 0/3</td></tr>
<tr>
<td class="form"><select name="ISR_slot0">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
<td class="form"><select name="ISR_slot1">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
<td class="form"><select name="ISR_slot2">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
<td class="form"><select name="ISR_slot3">
		<option value="">NONE</option>
		<option value="1">Single WIC</option>
		<option value="2">Dual WIC</option>
		<option value="4">Quad WIC</option>
</select></td>
</tr></table>
</select>
</div>



<!--FIBER CIRCUIT DIV -->
<div id="ACCE3" style="display:none">
<h5 style="text-decoration:underline;">FIBER CIRCUIT INFORMATION</h5>
<table><tr><td>Fiber Circuit ID</td><td>Fiber VLAN ID</td><td>Fiber Rate Limit</td></tr>
<tr><td class="form"><input type="text" name="CID_FIBER" /></td>
<td class="form"><input type="text" name="VLAN_FIBER" /></td>
<td class="form"><select name="rate_limit_FIBER">
		<option value=""></option>
		<option value="2">2</option>
		<option value="4">4</option>
		<option value="6">6</option>
		<option value="8">8</option>
		<option value="10">10</option>
		<option value="15">15</option>
		<option value="20">20</option>
		<option value="25">25</option>
		<option value="30">30</option>
		<option value="35">35</option>
		<option value="40">40</option>
		<option value="45">45</option>
		<option value="50">50</option>
		<option value="100">100</option>
</select></td></tr></table>
</select>
</div>

<!--T-1 CIRCUIT ID DIVs -->
<div id="TONE1" style="display:none">
<h5 style="text-decoration:underline;">T1 CIRCUIT INFORMATION</h5>
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 1: <input type="text" name="SERIAL1" /></td><td>Circuit ID: <input type="text" name="CID1_T1" /></td></tr>
</table>
</div>
<div id="TONE2" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 2: <input type="text" name="SERIAL2" /></td><td>Circuit ID: <input type="text" name="CID2_T1" /></td></tr>
</table>
</div>
<div id="TONE3" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 3: <input type="text" name="SERIAL3" /></td><td>Circuit ID: <input type="text" name="CID3_T1" /></td></tr>
</table>
</div>
<div id="TONE4" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 4: <input type="text" name="SERIAL4" /></td><td>Circuit ID: <input type="text" name="CID4_T1" /></td></tr>
</table>
</div>
<div id="TONE5" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 5: <input type="text" name="SERIAL5" /></td><td>Circuit ID: <input type="text" name="CID5_T1" /></td></tr>
</table>
</div>
<div id="TONE6" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 6: <input type="text" name="SERIAL6" /></td><td>Circuit ID: <input type="text" name="CID6_T1" /></td></tr>
</table>
</div>
<div id="TONE7" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>Serial Interface 7: <input type="text" name="SERIAL7" /></td><td>Circuit ID: <input type="text" name="CID7_T1" /></td></tr>
</table>
</div>



<!--EFM CIRCUIT ID DIVs -->
<div id="EFMP1" style="display:none">
<h5 style="text-decoration:underline;">EFM CIRCUIT INFORMATION</h5>
<table><tr><td>EFM Switch</td><td>EFM HSL #</td><td>EFM VLAN ID</td><td>EFM Rate Limit</td></tr>
<tr><td class="form"><input type="text" name="EFM_SWITCH" /></td>
<td class="form"><input type="text" name="HSL_EFM" /></td>
<td class="form"><input type="text" name="VLAN_EFM" /></td>
<td class="form"><select name="rate_limit_EFM">
		<option value=""></option>
		<option value="2">2</option>
		<option value="4">4</option>
		<option value="6">6</option>
		<option value="8">8</option>
		<option value="10">10</option>
		<option value="15">15</option>
		<option value="20">20</option>
		<option value="25">25</option>
		<option value="30">30</option>
		<option value="35">35</option>
		<option value="40">40</option>
		<option value="45">45</option>
		<option value="50">50</option>
		<option value="100">100</option>
</select></td></tr></table>
<!--<table style="border-collapse:collapse;">
<tr><td>EFM MLP 1: <input type="text" name="MLP1" /></td><td>Circuit ID: <input type="text" name="CID1_EFM" /></td></tr>
</table>-->
</div>
<!--
<div id="EFMP2" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 2: <input type="text" name="MLP2" /></td><td>Circuit ID: <input type="text" name="CID2_EFM" /></td></tr>
</table>
</div>
<div id="EFMP3" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 3: <input type="text" name="MLP3" /></td><td>Circuit ID: <input type="text" name="CID3_EFM" /></td></tr>
</table>
</div>
<div id="EFMP4" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 4: <input type="text" name="MLP4" /></td><td>Circuit ID: <input type="text" name="CID4_EFM" /></td></tr>
</table>
</div>
<div id="EFMP5" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 5: <input type="text" name="MLP5" /></td><td>Circuit ID: <input type="text" name="CID5_EFM" /></td></tr>
</table>
</div>
<div id="EFMP6" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 6: <input type="text" name="MLP6" /></td><td>Circuit ID: <input type="text" name="CID6_EFM" /></td></tr>
</table>
</div>
<div id="EFMP7" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 7: <input type="text" name="MLP7" /></td><td>Circuit ID: <input type="text" name="CID7_EFM" /></td></tr>
</table>
</div>
<div id="EFMP8" style="display:none">
<table style="border-collapse:collapse;">
<tr><td>EFM MLP 8: <input type="text" name="MLP8" /></td><td>Circuit ID: <input type="text" name="CID8_EFM" /></td></tr>
</table>
</div>
-->


<!--ANALOG LINE DIVs -->
<div id="ANLG1" style="display:none;">
<h5 style="text-decoration:underline;">ANALOG LINE INFORMATION</h5>
<table style="border-collapse:collapse;"><tr><td class="line">Line 1:</td><td class="form"><input type="text" name="ANALOG1" /></td></tr></table>
</div>
<div id="ANLG2" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 2:</td><td class="form"><input type="text" name="ANALOG2" /></td></tr></table>
</div>
<div id="ANLG3" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 3:</td><td class="form"><input type="text" name="ANALOG3" /></td></tr></table>
</div>
<div id="ANLG4" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 4:</td><td class="form"><input type="text" name="ANALOG4" /></td></tr></table>
</div>
<div id="ANLG5" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 5:</td><td class="form"><input type="text" name="ANALOG5" /></td></tr></table>
</div>
<div id="ANLG6" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 6:</td><td class="form"><input type="text" name="ANALOG6" /></td></tr></table>
</div>
<div id="ANLG7" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 7:</td><td class="form"><input type="text" name="ANALOG7" /></td></tr></table>
</div>
<div id="ANLG8" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 8:</td><td class="form"><input type="text" name="ANALOG8" /></td></tr></table>
</div>
<div id="ANLG9" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 9:</td><td class="form"><input type="text" name="ANALOG9" /></td></tr></table>
</div>
<div id="ANLG10" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 10:</td><td class="form"><input type="text" name="ANALOG10" /></td></tr></table>
</div>
<div id="ANLG11" style="display:none;">
<table style="border-collapse:collapse;"><tr><td class="line">Line 11:</td><td class="form"><input type="text" name="ANALOG11" /></td></tr></table>
</div>
<div id="ANLG12" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 12:</td><td class="form"><input type="text" name="ANALOG12" /></td></tr></table>
</div>
<div id="ANLG13" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 13:</td><td class="form"><input type="text" name="ANALOG13" /></td></tr></table>
</div>
<div id="ANLG14" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 14:</td><td class="form"><input type="text" name="ANALOG14" /></td></tr></table>
</div>
<div id="ANLG15" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 15:</td><td class="form"><input type="text" name="ANALOG15" /></td></tr></table>
</div>
<div id="ANLG16" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 16:</td><td class="form"><input type="text" name="ANALOG16" /></td></tr></table>
</div>
<div id="ANLG17" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 17:</td><td class="form"><input type="text" name="ANALOG17" /></td></tr></table>
</div>
<div id="ANLG18" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 18:</td><td class="form"><input type="text" name="ANALOG18" /></td></tr></table>
</div>
<div id="ANLG19" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 19:</td><td class="form"><input type="text" name="ANALOG19" /></td></tr></table>
</div>
<div id="ANLG20" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 20:</td><td class="form"><input type="text" name="ANALOG20" /></td></tr></table>
</div>
<div id="ANLG21" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 21:</td><td class="form"><input type="text" name="ANALOG21" /></td></tr></table>
</div>
<div id="ANLG22" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 22:</td><td class="form"><input type="text" name="ANALOG22" /></td></tr></table>
</div>
<div id="ANLG23" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 23:</td><td class="form"><input type="text" name="ANALOG23" /></td></tr></table>
</div>
<div id="ANLG24" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 24:</td><td class="form"><input type="text" name="ANALOG24" /></td></tr></table>
</div>
<div id="ANLG25" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 25:</td><td class="form"><input type="text" name="ANALOG25" /></td></tr></table>
</div>
<div id="ANLG26" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 26:</td><td class="form"><input type="text" name="ANALOG26" /></td></tr></table>
</div>
<div id="ANLG27" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 27:</td><td class="form"><input type="text" name="ANALOG27" /></td></tr></table>
</div>
<div id="ANLG28" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 28:</td><td class="form"><input type="text" name="ANALOG28" /></td></tr></table>
</div>
<div id="ANLG29" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 29:</td><td class="form"><input type="text" name="ANALOG29" /></td></tr></table>
</div>
<div id="ANLG30" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 30:</td><td class="form"><input type="text" name="ANALOG30" /></td></tr></table>
</div>
<div id="ANLG31" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 31:</td><td class="form"><input type="text" name="ANALOG31" /></td></tr></table>
</div>
<div id="ANLG32" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 32:</td><td class="form"><input type="text" name="ANALOG32" /></td></tr></table>
</div>
<div id="ANLG33" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 33:</td><td class="form"><input type="text" name="ANALOG33" /></td></tr></table>
</div>
<div id="ANLG34" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 34:</td><td class="form"><input type="text" name="ANALOG34" /></td></tr></table>
</div>
<div id="ANLG35" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 35:</td><td class="form"><input type="text" name="ANALOG35" /></td></tr></table>
</div>
<div id="ANLG36" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 36:</td><td class="form"><input type="text" name="ANALOG36" /></td></tr></table>
</div>
<div id="ANLG37" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 37:</td><td class="form"><input type="text" name="ANALOG37" /></td></tr></table>
</div>
<div id="ANLG38" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 38:</td><td class="form"><input type="text" name="ANALOG38" /></td></tr></table>
</div>
<div id="ANLG39" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 39:</td><td class="form"><input type="text" name="ANALOG39" /></td></tr></table>
</div>
<div id="ANLG40" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 40:</td><td class="form"><input type="text" name="ANALOG40" /></td></tr></table>
</div>
<div id="ANLG41" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 41:</td><td class="form"><input type="text" name="ANALOG41" /></td></tr></table>
</div>
<div id="ANLG42" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 42:</td><td class="form"><input type="text" name="ANALOG42" /></td></tr></table>
</div>
<div id="ANLG43" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 43:</td><td class="form"><input type="text" name="ANALOG43" /></td></tr></table>
</div>
<div id="ANLG44" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 44:</td><td class="form"><input type="text" name="ANALOG44" /></td></tr></table>
</div>
<div id="ANLG45" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 45:</td><td class="form"><input type="text" name="ANALOG45" /></td></tr></table>
</div>
<div id="ANLG46" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 46:</td><td class="form"><input type="text" name="ANALOG46" /></td></tr></table>
</div>
<div id="ANLG47" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 47:</td><td class="form"><input type="text" name="ANALOG47" /></td></tr></table>
</div>
<div id="ANLG48" style="display:none">
<table style="border-collapse:collapse;"><tr><td class="line">Line 48:</td><td class="form"><input type="text" name="ANALOG48" /></td></tr></table>
</div>



<br />
<hr />
<br />
 <input class="button" type="submit" value="Pre-install Config" name="submit"> 
 <input class="button" type="submit" value="Activation Config" name="submit"> 
 <input class="button" type="reset" value="Clear Form">

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
