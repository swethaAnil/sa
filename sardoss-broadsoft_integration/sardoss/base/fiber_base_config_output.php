<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

$vlan = $_POST['vlan'];
$device = $_POST['device'];
$vendor = $_POST['vendor'];

//get SERIAL IP FROM IAD NAME
$iad_name = trim($_POST[iad_name]);
$mkt = substr($iad_name,-3);
$fqdn = $iad_name . "." . $mkt . "0.cbeyond.net";
$iad_ip = gethostbyname($fqdn);
$ip_parts = explode(".",$iad_ip);

if($ip_parts[0] == 10){
	$tenk_last_octet = $ip_parts[3] - 4;
	$subnet_mask = "255.255.255.248";
}else{
	$tenk_last_octet = $ip_parts[3] - 1;
	
//get subnet mask for 10K IP
	$tenk_ip_last_digit = substr($tenk_last_octet,-1);
	
	if($tenk_ip_last_digit % 2 == 0){
		$subnet_mask = "255.255.255.254";
	}else{
		$subnet_mask = "255.255.255.252";
	}
}

$tenk_ip = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $tenk_last_octet;


//Error if no IP
if($iad_ip == $fqdn){
        echo "<br /><br /><br /><center><span style=\"font-color:red;font-size:150%;\">CRITICAL ERROR! No IP address found for IAD name ($iad_name).<br />";
        echo "Go back and verify the IAD name.</span></center><br /><br /><br /><br /><br /><br />";
}

switch($device){
	case IAD:
		$fiber_interface = "FastEthernet0/1";
		break;
	case ISR:
		$fiber_interface = "GigabitEthernet0/1";
		break;
}

if($vendor == "ZAYO" || $vendor == "FTTB"){
	$interface_suffix = "." . $vlan;
}else{
	$interface_suffix = "";
}



?>

<html>

<head>
<title>Fiber Base Config Generator</title>
</head>

<body>

<br />

<center>
Paste the config below into the IAD
</center>

<br /><br /><br />

<pre>
en
!
conf t
!
enable password t3st
!
ip subnet-zero
ip cef
!
config-register 0x2102
!
hostname <?php echo $device . "_Fiber_Base\n"; ?>
!
!
interface <?php echo $fiber_interface . "\n"; ?>
no ip mroute-cache
no snmp trap link-status
no cdp enable
speed 100
duplex full
no logging event link-status
load-interval 30
no cdp enable
no shut
!
!
interface <?php echo $fiber_interface . $interface_suffix . "\n"; ?>
<?php if($vendor == "ZAYO" || $vendor == "FTTB"){ echo "encapsulation dot1q " . $vlan . "\n"; } ?>
ip address <?php echo $iad_ip . " " . $subnet_mask . "\n";?>
ip nat outside
no shut
!
!
ip radius source-interface <?php echo $fiber_interface . $interface_suffix . "\n"; ?>
logging source-interface <?php echo $fiber_interface . $interface_suffix . "\n"; ?>
!
!
ip classless
ip route 0.0.0.0 0.0.0.0 <?php echo $fiber_interface . $interface_suffix . " " . $tenk_ip . "\n"; ?>
no ip http server
!
!
line vty 0 4
exec-timeout 30 0
password t3st
login
!
end
wr mem

</pre>

</body>

</html>



