<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>
<head>

<title>VPN for 10K Builder - Config</title>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<!--<![endif]-->

</head>

<body>
<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../images/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
 / <span class="location"><a href="/sardoss/config/config_gen_order_form.php">ReDCON</a> / VPN for 10K / <u>Config</u></span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="10k_vpn_build.htm" onClick="history.back();return false;">BACK to VPN for 10K Builder</a>
 | <a href="/">Home</a>
 
<div class="current_user"><?php echo "User: $_SESSION[username]"; ?></div>
</div>

<?php

//DEFINE VARIABLES
$acct_num = $_POST[parent_acct];
$cust_name = $_POST[cust_name];
$site_num = $_POST[site_num];
$tar = strtoupper($_POST[tar]);
$loopback = $_POST[loopback];
$wan_interface = $_POST[iad_wan];
$serial_ip = $_POST[iad_ser_ip];
$vlan = $_POST[vlan];
$vrf = $_POST[vrf];
$vru = $_POST[vru];
$iad_name = strtoupper($_POST[iad_name]);
$error = 0;
$cpe = $_POST[cpe_type];

if($cpe == "IAD"){
	$lan_interface_prefix = "FastEthernet";
	$wan_interface_prefix = "FastEthernet";
}elseif($cpe == "ISR"){
	$lan_interface_prefix = "GigabitEthernet";
	$wan_interface_prefix = "GigabitEthernet";
}

if($_POST[access] == "T1" || $_POST[access] == "FIBER" ){
    $lan_interface = $lan_interface_prefix . "0/0";
}elseif($_POST[access] == "EFM"){
    $lan_interface = $lan_interface_prefix . "0/0.20";
}


//get SERIAL IP FROM IAD NAME
$market = substr($iad_name,-3);
$fqdn = $iad_name . "." . $market . "0.cbeyond.net";
$iad_ip = gethostbyname($fqdn);

if($iad_ip == $fqdn){
        echo "<br /><br /><br /><center><span style=\"font-color:red;font-size:150%;\">CRITICAL ERROR! No IP address found for IAD name ($iad_name).<br />";
        echo "Go back and verify the IAD name.</span></center><br /><br /><br /><br /><br /><br />";
        $error = 1;
}

//Extract IAD number
$iad_num = substr($iad_name,3,-3);
$iad_num_trim = ltrim($iad_num,"0");


//extract WAN interface
if(substr($wan_interface,-1) == "."){
	$wan_interface_new = $wan_interface_prefix . $wan_interface . $vlan;
}elseif($wan_interface == "0/1"){
	$wan_interface_new = $wan_interface_prefix . $wan_interface;
}else{
	$wan_interface_new = $wan_interface;
}

//determine tunnel IP addresses
$tar_tunnel_ip = (($site_num - 1) * 4) + 1;
$iad_tunnel_ip = (($site_num - 1) * 4) + 2;
?>

<br /><br />
<span style="font-size:120%;font-weight:bold;margin-left:25px;">10K Configuration for <span style="color:red";><?php echo $tar; ?></span> - SITE <?php echo $site_num; ?>


<?php

if($site_num == "1" || $vrf){
	echo "(includes VRF definition, BGP, & RIP configs)</span><br />";

}else{
	echo "(<U>DOES NOT INCLUDE</U> VRF definition, BGP, & RIP configs)</span><br />";
}
if($error != "1"){


if($site_num == "1" || $vrf){
        echo "<blockquote><textarea style=\"margin:20px;\" rows=\"50\" cols=\"80\" readonly>";
        echo "\n\n!\n";
        echo "vrf definition 17184:$acct_num\n";
	echo "description $cust_name\n";
	echo "rd 17184:$acct_num\n";
	echo "route-target export 17184:$acct_num\n";
	echo "route-target import 17184:$acct_num\n";
	echo "!\n";
	echo "address-family ipv4\n";
	echo "exit-address-family\n";
	echo "!\n";
}else{
    echo "<blockquote><textarea style=\"margin:20px;\" rows=\"16\" cols=\"80\" readonly>";
    echo "\n\n!\n";
}

if($site_num == "1" || $vrf){
	echo "!\n";
	echo "router bgp 17184\n";
	echo "!\n";
	echo "address-family ipv4 vrf 17184:$acct_num\n";
	echo "redistribute connected\n";
	echo "redistribute static\n";
	echo "redistribute rip\n";
	echo "no auto-summary\n";
	echo "no synchronization\n";
	echo "exit-address-family\n";
	echo "!\n";
	echo "router rip\n";
	echo "!\n";
	echo "address-family ipv4 vrf 17184:$acct_num\n";
	echo "default-metric 1\n";
	echo "redistribute connected\n";
	echo "redistribute static\n";
	echo "redistribute bgp 17184 metric 1\n";
	echo "network 10.0.0.0\n";
	echo "no auto-summary\n";
	echo "version 2\n";
	echo "exit-address-family\n";
	echo "!\n";
}
echo "!\n";
echo "interface Tunnel$iad_num_trim\n";
 echo "description $cust_name\n";
 echo "vrf forwarding 17184:$acct_num\n";
 echo "ip address 10.255.255.$tar_tunnel_ip 255.255.255.252\n";
 echo "load-interval 30\n";
 echo "tunnel source Loopback10\n";
 echo "tunnel destination $iad_ip\n";
echo "!\n";

echo "exit\n";
echo "!\n\n\n</textarea></blockquote>";
}
?>

<br /><br />


<?php

if($error != "1" && $vru){

	$lower_market = strtolower($market);
	echo "<span style=\"font-size:120%;font-weight:bold;margin-left:25px;margin-top:25px;\">REMOTE USER Configuration for <span style=\"color:red\";>vpn." . $lower_market . "0.cbeyond.net</span></span>";
	
	echo "<br />";

	echo "<blockquote>";
	echo "<textarea style=\"margin:20px;\" rows=\"25\" cols=\"80\" readonly>";
	echo "\n\n!\n";
	echo "ip vrf 17184:$acct_num\n";
	echo "description $cust_name\n";
	echo "rd 17184:$acct_num\n";
	echo "route-target export 17184:$acct_num\n";
	echo "route-target import 17184:$acct_num\n";
	echo "exit\n";
	echo "!\n";
	echo "!\n";
	echo "router bgp 17184\n";
	echo "address-family ipv4 vrf 17184:$acct_num\n";
	echo "redistribute connected\n";
	echo "redistribute static\n";
	echo "no auto-summary\n";
	echo "no synchronization\n";
	echo "exit-address-family\n";
	echo "exit\n";
	echo "!\n";
	echo "!\n";
	echo "ip route vrf 17184:$acct_num 10.255.100.0 255.255.255.0 Null0 250\n";
	echo "! \n";
	echo "exit\n";
	echo "!\n";
}
?>

</textarea></blockquote>


<br /><br />
<span style="font-size:120%;font-weight:bold;margin-left:25px;margin-top:25px;">IAD Configuration for <span style="color:red";><?php echo $iad_name; ?></span></span>
<br />

<blockquote>


<?php
if($error != "1"){
echo "<textarea style=\"margin:20px;\" rows=\"25\" cols=\"80\" readonly>";
echo "\n\n!\n";
echo "route-map LAN_ROUTES_ONLY permit 10\n";
echo "match interface $lan_interface\n";
echo "!\n";
echo "interface Tunnel0\n";
echo "description $tar\n";
echo "ip address 10.255.255.$iad_tunnel_ip 255.255.255.252\n";
echo "ip mtu 1468\n";
echo "ip tcp adjust-mss 1400\n";
echo "load-interval 30\n";
echo "qos pre-classify\n";
echo "tunnel source $iad_ip\n";
echo "tunnel destination $loopback\n";
echo "!\n";
echo "router rip\n";
echo "version 2\n";
echo "redistribute connected route-map LAN_ROUTES_ONLY\n";
echo "passive-interface $wan_interface_new\n";
echo "network 10.0.0.0\n";
echo "no auto-summary\n";
echo "!\n";
echo "exit\n";
echo "!\n";
}
?>

</textarea></blockquote>

</body>
</html>


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
