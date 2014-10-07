<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

$vlan = $_POST[vlan];

//get SERIAL IP FROM IAD NAME
$iad_name = trim($_POST[iad_name]);
$mkt = substr($iad_name,-3);
$fqdn = $iad_name . "." . $mkt . "0.cbeyond.net";
$iad_ip = gethostbyname($fqdn);
$ip_parts = explode(".",$iad_ip);
$tenk_last_octet = $ip_parts[3] - 1;
$tenk_ip = $ip_parts[0] . "." . $ip_parts[1] . "." . $ip_parts[2] . "." . $tenk_last_octet;

//get subnet mask for 10K IP
$tenk_ip_last_digit = substr($tenk_last_octet,-1);
if($tenk_ip_last_digit % 2 == 0){
	$subnet_mask = "255.255.255.254";
}else{
	$subnet_mask = "255.255.255.252";
}

//Error if no IP
if($iad_ip == $fqdn){
        echo "<br /><br /><br /><center><span style=\"font-color:red;font-size:150%;\">CRITICAL ERROR! No IP address found for IAD name ($iad_name).<br />";
        echo "Go back and verify the IAD name.</span></center><br /><br /><br /><br /><br /><br />";
}

switch($_POST['device']){
	case iad:
		$fiber_interface = "FastEthernet0/1";
		break;
	case isr:
		$fiber_interface = "GigabitEthernet0/1";
		break;
}

?>

<html>

<head>
<title>Fiber (lit) Base Config Generator</title>
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
hostname SwellFiberBase
!
!
interface <?php echo $fiber_interface . "\n"; ?>
ip nat inside
no ip mroute-cache
no snmp trap link-status
no cdp enable
speed auto
duplex auto
no logging event link-status
load-interval 30
no cdp enable
no shut
!
!
interface <?php echo $fiber_interface . "." . $vlan . "\n"; ?>
encapsulation dot1q <?php echo $vlan . "\n"; ?>
ip address <?php echo $iad_ip . " " . $subnet_mask . "\n";?>
ip nat outside
!
!
ip radius source-interface <?php echo $fiber_interface . "." . $vlan . "\n"; ?>
logging source-interface <?php echo $fiber_interface . "." . $vlan . "\n"; ?>
!
!
ip classless
ip route 0.0.0.0 0.0.0.0 <?php echo $fiber_interface . "." . $vlan . " " . $tenk_ip . "\n"; ?>
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


