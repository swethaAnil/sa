<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php

$num_t1s = $_POST['num_t1s'];
$device = $_POST['device'];

switch($device){
	case IAD:
		$serial_interface = "1/0";
		$card_activate = "card type t1 1";
		break;
	case ISR:
		$serial_interface = "0/0/0";
		$card_activate = "card type t1 0 0\n!\nnetwork-clock-participate wic 0\nnetwork-clock-select 1 T1 0/0/0";
		break;
}


switch($num_t1s){
	case Single:
		$default_route = "ip route 0.0.0.0 0.0.0.0 Serial" . $serial_interface . ":0";
		break;
	case Multi:
		$default_route = "ip route 0.0.0.0 0.0.0.0 Multilink1";
		break;
}



?>

<html>

<head>
<title>RedCON - T1 Base Config</title>
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
hostname <?php echo $num_t1s . "_T1_" . $device . "_Base\n"; ?>
!
config-register 0x2102
!
<?php echo $card_activate . "\n"; ?>
!
controller T1 <?php echo $serial_interface . "\n"; ?>
fdl ansi
channel-group 0 timeslots 1-24
description Upstream T1
!
!
<?php if($num_t1s == "Multi"){
echo "<pre>
interface Multilink1
ppp multilink
ip address negotiated
no cdp enable
ppp multilink interleave
ppp multilink fragment delay 20
ppp multilink group 1
no shut
!
int Serial" . $serial_interface . ":0
no ip address
encapsulation ppp
ppp multilink
ppp multilink group 1
no shut
</pre>"; 
}elseif($num_t1s == "Single"){
echo "<pre>int Serial" . $serial_interface . ":0
ip address negotiated
encapsulation ppp
no shut</pre>";}?>
!
ip classless
ip route 0.0.0.0 0.0.0.0 <?php if($num_t1s == "Multi"){ echo "Multilink1\n"; }else{ echo "Serial" . $serial_interface . ":0\n"; } ?>
no ip http server
!
gatekeeper
shutdown
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



