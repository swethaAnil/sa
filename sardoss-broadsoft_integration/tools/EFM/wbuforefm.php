<HTML>

<HEAD>
<TITLE>WBU for EFM Re-Homes</TITLE>
</HEAD>

<BODY>

<?php


//set variables
$vlan = $_POST[vlan];
$publicIP = $_POST[publicIP];
$publicsubnet = $_POST[publicsubnet];
$track = $_POST[track];
$serialsubnet = $_POST[serialsubnet];

$serialIP = $_POST[serialIP];

$ip_pieces = explode(".",$serialIP);

if (is_numeric($ip_pieces[3])){
	
	if($serialsubnet == "30"){
		$iad_last_octet = $ip_pieces[3] + 2;
		$tar_last_octet = $ip_pieces[3] + 1;
	}elseif($serialsubnet == "31"){
		$iad_last_octet = $ip_pieces[3] + 1;
		$tar_last_octet = $ip_pieces[3];
	}elseif($serialsubnet == ""){
		echo "<H1>**************  STOP!!!!  NO SERIAL SUBNET SELECTED  **********************</H1>";
	}
	
	$iadIP = $ip_pieces[0] . "." . $ip_pieces[1] . "." . $ip_pieces[2] . "." . $iad_last_octet; 
	$tarIP = $ip_pieces[0] . "." . $ip_pieces[1] . "." . $ip_pieces[2] . "." . $tar_last_octet;
}else{
	echo "<H1>**************  STOP!!!!  BAD SERIAL IP  **********************</H1>";
}



?>

<h3><u>IAD CONFIG</u></h3>


!<br>
ip sla 1<br>
icmp-echo <?php echo $tarIP; ?><br>
timeout 5000<br>
frequency 5<br>
!<br>
ip sla schedule 1 life forever start-time now<br>
!<br>
track 1 rtr 1 reachability<br>
!<br>
ip route 0.0.0.0 0.0.0.0 FastEthernet0/0.<?php echo $vlan; ?> <?php echo $tarIP; ?> track 1<br>
no ip route 0.0.0.0 0.0.0.0 FastEthernet0/0.<?php echo $vlan; ?> <?php echo $tarIP; ?><br>
!<br>
ip route 0.0.0.0 0.0.0.0 Tunnel1 100<br>
!<br><br>

<h3><u>10K CONFIG</u></h3>

!<br>
ip sla <?php echo $track; ?><br>
icmp-echo <?php echo $iadIP; ?><br>
timeout 5000<br>
frequency 5<br>
!<br>
ip sla schedule <?php echo $track; ?> life forever start-time now<br>
!<br>
track <?php echo $track; ?> rtr <?php echo $track; ?> reachability<br>
!<br>
ip route <?php echo $publicIP; ?> <?php echo $publicsubnet; ?> Port-channel1.<?php echo $vlan; ?> <?php echo $iadIP; ?> track <?php echo $track; ?><br>
no ip route <?php echo $publicIP; ?> <?php echo $publicsubnet; ?> Port-channel1.<?php echo $vlan; ?> <?php echo $iadIP; ?><br>
!<br><br>



</BODY>
</HTML>

