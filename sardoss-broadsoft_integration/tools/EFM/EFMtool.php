
<HTML>

<HEAD>

<?php

//include IP check function
include 'checkIP.php';

//DEFINE WINDOW HEADER WITH CUSTOMER NAME
echo "<TITLE>EFM - $_POST[Custname]</TITLE>";

?>

<STYLE TYPE="text/css">

.maintitle { font-family:arial ; font-size:150% ; color:#000066 ; text-align:center ; font-weight:bold }

.detail { font-family:arial ; font-size:75% ; color:#000066 ; text-align:center }

.highlight { font-family:arial ; font-size:100% ; color:#000099 ; background-color:yellow ; font-weight:bold }

.iad { font-family:arial ; color:#347235 ; font-weight:bold }

.tar { font-family:arial ; color:#E41B17 ; font-weight:bold }

.cat { font-family:arial ; color:#C58917 ; font-weight:bold }

.alert { font-family:arial ; color:#E41B17 ; font-weight:bold ;  font-size:300%}

step {font-family:arial;font-size:115%}

h2 {font-family:Helvetica;font-size:85%;text-align:center}

h3 { font-family:arial ; font-size:115% }

h4 {font-family:arial;font-size:100%}

h5 {font-family:arial;font-size:85%}

table.script { border-collapse:collapse }

span.script {color:#000000;font-family:arial;letter-spacing:0.1ex}


</STYLE>
</HEAD>

<?php

//Check IPs
$currserialcheck = checkIP($_POST[curr_ser_ip],$_POST[TARmask]);
$newserialcheck = checkIP($_POST[new_ser_ip],$_POST[TARmask]);
$publiccheck = checkIP($_POST[PublicIP],$_POST[PublicSubnet]);
$privatecheck = checkIP($_POST[PrivateIAD],$_POST[PrivateSubnet]);
$privatesubnetcheck = checkIP($_POST[PrivateSubnet],$_POST[PrivateSubnet]);

switch ($currserialcheck){
	case 0:
		echo "<span class=\"alert\">DO NOT PROCEED!<br>Serial IP is not a valid IP address!</span><br><br>";
		break;
	case 1:
		echo "<span class=\"alert\">DO NOT PROCEED!<br>Serial IP is not a <b>NETWORK</b> IP!</span><br><br>";
		break;
}

// switch ($newserialcheck){
	// case 0:
		// echo "<span class=\"alert\">DO NOT PROCEED!<br>Dummy IP is not a valid IP address!</span><br><br>";
		// break;
	// case 1:
		// echo "<span class=\"alert\">DO NOT PROCEED!<br>Dummy IP is not a <b>NETWORK</b> IP!</span><br><br>";
		// break;
// }

switch ($publiccheck){
	case 0:
		echo "<span class=\"alert\">DO NOT PROCEED!<br>Public IP is not a valid IP address!</span><br><br>";
		break;
	case 1:
		echo "<span class=\"alert\">DO NOT PROCEED!<br>Public IP is not a <b>NETWORK</b> IP!</span><br><br>";
		break;
}

if($_POST[PrivateIAD]!=NULL && $privatecheck == 0){
	echo "<span class=\"alert\">DO NOT PROCEED!<br>Private IP is not a valid IP address!</span><br><br>";
}
if($_POST[PrivateIAD]!=NULL && $privatesubnetcheck == 0){
	echo "<span class=\"alert\">DO NOT PROCEED!<br>Private IP subnet is not a valid IP address!</span><br><br>";
}


// MANIPULATE VARIABLES
$curr_serial_pieces = explode(".",$_POST[curr_ser_ip]);
$new_serial_pieces = explode(".",$_POST[new_ser_ip]);
$public_pieces = explode(".",$_POST[PublicIP]);
$co_id=substr($_POST[EFMbox],-3);


//DEFINE GigabitEthernet ports for each TAR
switch ($co_id) {
	case PPL:
		$TARport = "Port-channel1";
		break;
	case CST:
		$TARport = "Port-channel1";
		break;		
	case EMR:
		$TARport = "GigabitEthernet7/0/0";
		break;
	case ADD:
		$TARport = "Port-channel1";
		break;
	case NRC:
		$TARport = "Port-channel1";
		break;
	case RSN:
		$TARport = "Port-channel1";
		break;
	case SUN:
		$TARport = "Port-channel1";
		break;
	case NAT:
		$TARport = "Port-channel1";
		break;
	}

//SET QOS VARIABLES BASED ON BV PACKAGE
switch ($_POST[PkgType]) {
	case BVI:
		$qos_interface = "service-policy output EFM-2M";
		$qos_iad_policy = "policy-map EFM-2M<br>class class-default<br>shape average 2000000<br>service-policy T1_QOS";
		break;
	case BVII:
		$qos_interface = "service-policy output EFM-4M";
		$qos_iad_policy = "policy-map EFM-4M<br>class class-default<br>shape average 4000000<br>service-policy T1_QOS";
		break;
	case BVIII:
		$qos_interface = "service-policy output EFM-6M";
		$qos_iad_policy = "policy-map EFM-6M<br>class class-default<br>shape average 6000000<br>service-policy T1_QOS";
		break;
	case Unlimited:
		$qos_interface = "service-policy output EFM";
		$qos_iad_policy = "policy-map EFM<br>class class-default<br>shape peak percent 100<br>service-policy T1_QOS";
		break;
	}

//DEFINE SIP QOS
if ($_POST[SvcType] == "SIP"){
	$qos_iad_policy = str_replace("service-policy T1_QOS", "service-policy SIP_T1_QOS", $qos_iad_policy);
	}
	
//DEFINE TAR DOMAIN NAME
if($co_id == "NRC"){
	$tar_domain_name = "CAR02" . $co_id;
}else{
	$tar_domain_name = "TAR00" . $co_id;
}
	
?>


<!--  ------------------BODY OF PAGE BEGINS HERE-----------------             -->

<BODY>

<script type="text/javascript">

function HelpPopup(ref){
	window.open("Help/EFMHelp.htm#"+ref,"Window1","menubar=no,location=no,width=870,height=600,toolbar=no,scrollbars=yes");
	}	
	
</script>



<center>


<?php

//TITLE BAR
echo "<span class=\"maintitle\">CONFIGURATION FOR EFM CONVERSION - $_POST[Custname]</span>";

?>

<table class="script" cellpadding=20>
<tr>
<td width="60%">
<h2><u>** MUST be using MetaASSIST View version 6.8 **</u></h2>
<h2>Based on <a href="./EFM_Architecture_v1.1.doc">"EFM Architecture Specification v1.1"</a> released Jan. 30, 2009</h2>

<h2><a href="http://confluence.cbeyond.net/display/NetEng/802.3ah+Testing+-+Last+Mile+Research">Click here</a> to access the network engineering EFM site.</h2>
<span class="detail"><center>Check boxes provided to help user track progress; they don't really do anything.</center></span>

</td>
<td width="40%" align="center">
<span class="tar">RED TEXT indicates TAR config</span><br>
<span class="cat">GOLD TEXT indicates CAT config</span><br>
<span class="iad">GREEN TEXT indicates IAD config</span><br>
</td>
</tr>
</table>

<?php
// CHECK FOR NULL PRIVATE IP VALUES
echo "<span class=\"tar\">";
if($_POST[PrivateIAD]==NULL){echo "******* ALERT: No PRIVATE IP provided.  IS THIS CORRECT? *******<br>";}
if($_POST[PrivateSubnet]==NULL){echo "******* ALERT: No PRIVATE SUBNET MASK provided.  IS THIS CORRECT? *******<br>";}
echo "</span><br>";
?>

</center>



<!--  INSTALLATION SECTION BEGINS   -->

<hr>
<center><u><H3>INSTALLATION</H3></u> 
<H3>When CPE equipment has been installed, verify ALL pairs and HSL are up.<br>
Calibrate the HSL then proceed with the following steps.</H3>
<input type="button" onClick="HelpPopup(2)" value="Need help with the Actelis equipment?"><br><br>

</center>
<hr>




<!--  CONVERSION SECTION BEGINS  -->


<center><u><H3>CONVERSION</H3></u></center> 

<OL>
<H3><LI><INPUT TYPE=CHECKBOX><u>TAR</u>: Configure 10K subinterface <u>without</u> IP address to reserve subinterface during configuration</LI></H3>

<input type="button" onClick="HelpPopup(1)" value="Help"><br><br>


<?php

echo "<span class=\"tar\">$tar_domain_name - Subinterface config (NO IP ADDRESS)</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "interface $TARport.$_POST[VLAN]<br>";
echo "description $_POST[IADname] - $_POST[Custname] - $_POST[EFMbox] - HSL-$_POST[HSL]<br>";
echo "encapsulation dot1q $_POST[VLAN]<br>";
echo "no snmp trap link-status<br>";
echo "$qos_interface<br>";
echo "!<br>";
echo "</td></tr></table></span>";
?>



<?php

//STEP 2 CONDITIONAL:  Only used when CAT is present in the design (only Emerson at this point)
if($co_id == "EMR"){

	echo "<br><br>";
	echo "<H3><LI><INPUT TYPE=CHECKBOX> <u>CAT</u>: Configure VLAN in CO switch</LI></H3>";

	echo "<span class=\"cat\">CAT00$co_id - VLAN config</span><br>";

	$Name = $_POST[Custname];	
	$AltName = str_replace(" ","_",$Name);

	echo "<table class=\"script\" cellpadding=30 border=2>";
	echo "<tr><td><span class=\"script\">";
	echo "!<br>";
	echo "vlan $_POST[VLAN]<br>";
	echo "name $AltName<br>";
	echo "!<br>";
	echo "int vlan $_POST[VLAN]<br>";
	echo "load-interval 30<br>";
	echo "description $_POST[IADname] - F0/0.$_POST[VLAN]<br>";
	echo "!<br>";
	echo "</td></tr></table></span>";
	//echo "switchport trunk allowed vlan add $_POST[VLAN]<br>";
	//echo "!<br>";
}
?>

<br><br>
<H3><LI><INPUT TYPE=CHECKBOX> <u>ML2300</u>: Configure VLAN(s) in EFM CO gear</LI></H3>
<input type="button" onClick="HelpPopup(2)" value="Help"><br><br>
<H3><LI><INPUT TYPE=CHECKBOX> <u>ML42/62X</u>: Configure VLANs in EFM CPE gear</LI></H3>
<input type="button" onClick="HelpPopup(2)" value="Help"><br><br>
<span class="highlight">** ML42 NOTE: REMEMBER TO APPLY THE CHANGES AND SAVE THE CONFIG!! **</span><br><br>
<H3><LI><INPUT TYPE=CHECKBOX> <u>IAD</u>: Verify IAD connectivity</LI></H3>
<input type="button" onClick="HelpPopup(3)" value="Help"><br><br>


<ul>
<li>"show mgcp" in IAD.  Verify MGCP Admin State is ACTIVE and Oper State is ACTIVE</li>
<li>Verify connectivity to call agent (example: trace to 'atlca.atl0.cbeyond.net')</li>
<li>Verify connectivity to internet (example: trace to google)</li>
</ul>

<br>



<H3><LI><INPUT TYPE=CHECKBOX> <u>TAR Serial/Multilink Interface</u>: Quickly make dummy IP the primary IP and serial IP the secondary IP</LI></H3>
<input type="button" onClick="HelpPopup(4)" value="Help"><br><br>

<span class="highlight">**VERIFY THERE ARE NO ACTIVE CALLS**</span><br><br>



<?php

echo "<span class=\"tar\">$tar_domain_name - Serial IP switchover config</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "int $_POST[TARint]<br>";

$lastoctet_newserialtar = $new_serial_pieces[3] + 1;
$lastoctet_currserialtar = $curr_serial_pieces[3] + 1;

//echo "ip address $new_serial_pieces[0].$new_serial_pieces[1].$new_serial_pieces[2].$lastoctet_newserialtar $_POST[TARmask]<br>";
echo "ip address 169.$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialtar $_POST[TARmask]<br>";
echo "ip address $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialtar $_POST[TARmask] secondary<br>";
echo "!<br>";
echo "</td></tr></table></span>";

?>

<br><br>

<H3><LI><INPUT TYPE=CHECKBOX> <u>IAD Serial/Multilink Interface</u>: Quickly make dummy IP the primary IP and serial IP the secondary IP</LI></H3>
<input type="button" onClick="HelpPopup(5)" value="Help"><br><br>
<span class="highlight">**VERIFY THERE ARE NO ACTIVE CALLS**</span><br><br>



<?php

echo "<span class=\"iad\">$_POST[IADname] - Serial IP switchover config</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "line vty 0 4<br>";
echo "no access-class 99 in<br>";
echo "!<br>";
if(substr_compare($_POST[TARint],"s",0,1,true) == 0){
	echo "int Serial1/0:0<br>";
	}
elseif(substr_compare($_POST[TARint],"m",0,1,true) == 0){
	echo "int Multilink1<br>";
	}

$lastoctet_newserialIAD = $new_serial_pieces[3] + 2;
$lastoctet_currserialIAD = $curr_serial_pieces[3] + 2;

if ($_POST[SvcType] == "SIP"){
	echo "no h323-gateway voip bind srcaddr $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD<br>";
	}
	
//echo "ip address $new_serial_pieces[0].$new_serial_pieces[1].$new_serial_pieces[2].$lastoctet_newserialIAD $_POST[TARmask]<br>";
echo "ip address 169.$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD $_POST[TARmask]<br>";
echo "ip address $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD $_POST[TARmask] secondary<br>";
echo "!<br>";
echo "</td></tr></table></span>";

echo "<br><br>";
//echo "<H3><LI><INPUT TYPE=CHECKBOX> <u>TAR & IAD</u>: Telnet to IAD dummy IP (<span class=\"iad\">$new_serial_pieces[0].$new_serial_pieces[1].$new_serial_pieces[2].$lastoctet_newserialIAD</span>) from TAR to verify backup connection</LI></H3>";
echo "<H3><LI><INPUT TYPE=CHECKBOX> <u>TAR & IAD</u>: Telnet to IAD dummy IP (<span class=\"iad\">169.$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD</span>) from TAR to verify backup connection</LI></H3>";
echo "<input type=\"button\" onClick=\"HelpPopup(6)\" value=\"Help\"><br><br>";

?>


<H3><LI><INPUT TYPE=CHECKBOX> WHEN CUSTOMER IS READY TO GO DOWN, connect EFM CPE gear to IAD and customer network</LI></H3>
<input type="button" onClick="HelpPopup(7)" value="Help"><br><br>

<H3><LI><INPUT TYPE=CHECKBOX> <u>IAD</u>: Configure IAD subinterfaces, QOS Policy, & source-interface for logging and Radius</LI></H3>
<input type="button" onClick="HelpPopup(8)" value="Help"><br><br>

<?php

echo "<span class=\"iad\">$_POST[IADname] - Subinterface configs (Upstream VLAN & Customer Network) & NAT</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "$qos_iad_policy<br>";
echo "!<br>";

if(substr_compare($_POST[TARint],"s",0,1,true) == 0){
	echo "int Serial1/0:0<br>";
	}
elseif(substr_compare($_POST[TARint],"m",0,1,true) == 0){
	echo "int Multilink1<br>";
	}
echo "no ip address $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD $_POST[TARmask] secondary<br>";
echo "!<br>";
echo "interface f0/0.$_POST[VLAN]<br>";
echo "description VLAN $_POST[VLAN] - HSL-$_POST[HSL] - $_POST[EFMbox]<br>";
echo "encapsulation dot1q $_POST[VLAN]<br>";
echo "ip address $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD $_POST[TARmask]<br>";
echo "ip nat outside<br>";
echo "$qos_interface<br>";

if ($_POST[SvcType] == "SIP"){
	echo "h323-gateway voip bind srcaddr $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD<br>";
	}

echo "!<br>";
echo "ip radius source-interface f0/0.$_POST[VLAN]<br>";
echo "logging source-interface f0/0.$_POST[VLAN]<br>";
echo "!<br>";

if(substr_compare($_POST[TARint],"s",0,1,true) == 0){
	echo "no ntp server 64.238.96.13 source serial1/0:0 prefer<br>";
	echo "no ntp server 66.180.96.13 source serial1/0:0<br>";
	echo "!<br>";
	echo "ntp server 64.238.96.13 source f0/0.$_POST[VLAN] prefer<br>";
	echo "ntp server 66.180.96.13 source f0/0.$_POST[VLAN]<br>";
	}
elseif(substr_compare($_POST[TARint],"m",0,1,true) == 0){
	echo "no ntp server 64.238.96.13 source multilink1 prefer<br>";
	echo "no ntp server 66.180.96.13 source multilink1<br>";
	echo "!<br>";
	echo "ntp server 64.238.96.13 source f0/0.$_POST[VLAN] prefer<br>";
	echo "ntp server 66.180.96.13 source f0/0.$_POST[VLAN]<br>";
	}

echo "!<br>";
echo "banner motd ^C<br>";
echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Cbeyond Communications<br>";
echo "<br>";
echo "RESTRICTED NETWORK ACCESS -- ALL CONNECTIONS ARE LOGGED<br>";
echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp UNAUTHORIZED ACCESS NOT PERMITTED<br>";
echo "<br>";
echo "<br>";
echo "*******************************************************<br>";
echo "*&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp*<br>";
echo "*&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp EFM CUSTOMER &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp*<br>";
echo "*&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp*<br>";
echo "*&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp ---> NO ACTIVE T1(s) <--- &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp*<br>";
echo "*&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp*<br>";
echo "*&nbsp&nbsp&nbsp&nbsp Upstream connection via FastEthernet0/0.$_POST[VLAN] &nbsp&nbsp&nbsp&nbsp*<br>";
echo "*&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Customer LAN via FastEthernet0/0.20 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp*<br>";
echo "*&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp*<br>";
echo "*******************************************************<br>";
echo "^C<br>";
echo "!<br>";


$lastoctet_publicIAD = $public_pieces[3] + 1;

echo "!<br>";
echo "interface f0/0<br>";
echo "no ip address<br>";
echo "!<br>";
echo "interface f0/0.20<br>";
echo "description Customer LAN<br>";
echo "encapsulation dot1q 20<br>";

if ($_POST[PublicSubnet] == "255.255.255.255"){
	echo "ip address $_POST[PrivateIAD] $_POST[PrivateSubnet]<br>";
	}
else{
	echo "ip address $public_pieces[0].$public_pieces[1].$public_pieces[2].$lastoctet_publicIAD $_POST[PublicSubnet]<br>";
	if ($_POST[PrivateIAD] != NULL){
		echo "ip address $_POST[PrivateIAD] $_POST[PrivateSubnet] secondary<br>";
		}
	}
	
echo "ip nat inside<br>";

if ($_POST[SvcType] == "SIP"){
	echo "service-policy input SIP_LAN_QOS<br>";
	}

echo "!<br>";
echo "!<br>";

if ($_POST[PublicSubnet] == "255.255.255.255"){
	echo "! Loopback IP; no change to NAT<br>";
	}
else{
	echo "no ip nat inside source list 101 int f0/0 overload<br>";
	echo "ip nat inside source list 101 int f0/0.20 overload<br>";
	}
	
echo "!<br>";
echo "!<br>";
echo "ip route 0.0.0.0 0.0.0.0  FastEthernet0/0.$_POST[VLAN] $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialtar<br>";

if(substr_compare($_POST[TARint],"s",0,1,true) == 0){
	echo "no ip route 0.0.0.0 0.0.0.0 Serial1/0:0<br>";
	}
elseif(substr_compare($_POST[TARint],"m",0,1,true) == 0){
	echo "no ip route 0.0.0.0 0.0.0.0 Multilink1<br>";
	}
echo "!<br>";
echo "</td></tr></table></span>";
?>

<br><br>


<H3><LI><INPUT TYPE=CHECKBOX> <u>TAR</u>: Move current serial IP from old interface to GigE subinterface & update IP route</LI></H3>
<input type="button" onClick="HelpPopup(9)" value="Help"><br><br>



<?php 

echo "<span class=\"tar\">$tar_domain_name - Serial & subinterface change config</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "interface $_POST[TARint]<br>";
echo "no ip address $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialtar $_POST[TARmask] secondary<br>";
echo "!<br>";
echo "!<br>";
echo "interface $TARport.$_POST[VLAN]<br>";
echo "ip address $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialtar $_POST[TARmask]<br>";
echo "!<br>";
echo "!<br>";
echo "ip route $_POST[PublicIP] $_POST[PublicSubnet] $TARport.$_POST[VLAN] $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD<br>";
echo "no ip route $_POST[PublicIP] $_POST[PublicSubnet] $_POST[TARint]<br>";
echo "!<br>";
echo "</td></tr></table></span>";

?>


<!--

<?php

echo "<span class=\"tar\">$tar_domain_name - Change public routing config</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "ip route $_POST[PublicIP] $_POST[PublicSubnet] $TARport.$_POST[VLAN] $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialIAD<br>";
echo "no ip route $_POST[PublicIP] $_POST[PublicSubnet] $_POST[TARint]<br>";
echo "!<br>";
echo "</td></tr></table></span>";
?>
-->



<br><br>

<!--
<H3><LI><INPUT TYPE=CHECKBOX> <u>IAD</u>: Change default route on IAD</LI></H3>
<input type="button" onClick="HelpPopup(10)" value="Help"><br><br>



<?php

echo "<span class=\"iad\">$_POST[IADname] - Change default route config</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "ip route 0.0.0.0 0.0.0.0  FastEthernet0/0.$_POST[VLAN] $curr_serial_pieces[0].$curr_serial_pieces[1].$curr_serial_pieces[2].$lastoctet_currserialtar<br>";

if(substr_compare($_POST[TARint],"s",0,1,true) == 0){
	echo "no ip route 0.0.0.0 0.0.0.0 Serial1/0:0<br>";
	}
elseif(substr_compare($_POST[TARint],"m",0,1,true) == 0){
	echo "no ip route 0.0.0.0 0.0.0.0 Multilink1<br>";
	}
echo "!<br>";
echo "</td></tr></table></span>";
?>
-->

<H4>----------REFER TO THE ARCHITECTURE DOC FOR VPN CONFIG INFORMATION IF NEEDED----------</H4><br>
<H3><LI><INPUT TYPE=CHECKBOX> Verify all services; perform speed test</LI></H3>
<input type="button" onClick="HelpPopup(11)" value="Help"><br><br>

<H3><LI><INPUT TYPE=CHECKBOX> <u>TAR & IAD</u>: Re-add access-class and clean up old IAD and TAR interfaces.</LI></H3>
<input type="button" onClick="HelpPopup(12)" value="Help"><br><br>

<OL>
<li>Re-add access-class 99 to the IAD virtual terminal connection and clear the IAD T1 interface(s)<br><br>
<?php

echo "<span class=\"iad\">$_POST[IADname] - Re-add access-class 99 config and clean up T1 interface(s)</span><br>";
echo "<table class=\"script\" cellpadding=30 border=2>";
echo "<tr><td><span class=\"script\">";
echo "!<br>";
echo "line vty 0 4<br>";
echo "access-class 99 in<br>";
echo "!<br>";
echo "no network-clock-select 1 T1 1/0<br>";
echo "!<br>";
echo "int serial1/0:0<br>";
echo "shut<br>";
echo "no description<br>";
echo "no ip address<br>";
echo "!<br>";
if(substr_compare($_POST[TARint],"m",0,1,true) == 0){
	echo "int serial0/0<br>";
	echo "shut<br>";
	echo "no description<br>";
	echo "!<br>";
	echo "int multilink1<br>";
	echo "shut<br>";
	echo "no description<br>";
	echo "no ip address<br>";
	echo "!<br>";
	echo "no int multilink1<br>";
	echo "!<br>";
	}
echo "controller t1 1/0<br>";
echo "shut<br>";
echo "no description<br>";
echo "no channel-group 0<br>";
echo "!<br>";
echo "</td></tr></table></span>";
?>
</li><br>

<li>Remove configuration from old serial (and multilink, if applicable) interfaces in the TAR</li>
<?php 
echo "<li>Change IAD configuration so that all services are pointing to <b>FastEthernet0/0.$_POST[VLAN]</b> instead of <b>";
if(substr_compare($_POST[TARint],"s",0,1,true) == 0){
	echo "Serial1/0:0";
	}
elseif(substr_compare($_POST[TARint],"m",0,1,true) == 0){
	echo "Multilink1";
	}
echo "</b>.<br></li>";
?>
</OL>
</OL>
<br><br>
<P><a href="/tools/EFMtool.html">Go Back</a></P>

</BODY>

</HTML>