<HTML>

<HEAD>
<TITLE>EFM Rate-Limit Changer</TITLE>
</HEAD>

<BODY>

<h2>EFM Rate-Limit Change Configuration</h2><hr>


<?php


//set variables
$VLAN = $_POST[VLAN];
$curr_rate = $_POST[curr_rate];
$new_rate = $_POST[new_rate];
$sip = $_POST[sip];
$efmswitch = $_POST[efmswitch];
$hsl = $_POST[HSL];
$user = $_POST[user];
$pass = $_POST[pass];

$new_shape = $new_rate * 1048576;
$low_bw = $new_rate * 1024 - 175;


//------------------------BEGIN EFM SWITCH CONFIG SECTION
echo "<table><tr><td width=50%>";
echo "<br><br><b>EFM SWITCH CONFIG</b> - updates Low Bandwidth Alarm in EFM switch<br>";

echo "<textarea style=overflow:hidden;color:red;background-color:#F0F0F0; rows=12 cols=40 readonly>\n
telnet $efmswitch 3083\n\n
act-user::" . $user . ":::" . $pass . ";\n\n
ed-hsl::hsl-" . $hsl . "::::LOWBWTH=" . $low_bw . ";\n\n
rtrv-hsl::hsl-" . $hsl . ";</textarea>";

echo "</td><td>The last command, \"rtrv-hsl\", will show you the configuration of the HSL following the config change.  
Please verify that the LOWBWTH value (highlighted in the example below) is equivalent to <strong>COMMITTED BANDWIDTH * 1024 - 175</strong>.<br><br>

<i> EFM02REN 11-11-18 09:25:52<br>M  0 COMPLD<br> HSL-46:MODE=-O,AUTOCALIB=N,<SPAN style=\"BACKGROUND-COLOR: #ffff00\">LOWBWTH=1873</SPAN>,LNKID=ac.....</i></td></tr>";

//------------------------BEGIN 10K CONFIG SECTION

echo "<tr><td>";
echo "<br><br><br><b>10K CONFIG</b> - updates QOS policy in 10K<br>";

echo "<textarea style=overflow:hidden;color:red;background-color:#F0F0F0; rows=7 cols=40 readonly>\n\n!\ninterface Port-channel1." . $VLAN . "\n";
if ($curr_rate == "unl"){
	echo "no service-policy output EFM-UL\n";
}else{
	echo "no service-policy output EFM-" . $curr_rate . "M\n";
}
if ($new_rate == "unl"){
	echo "service-policy output EFM-UL\n";
}else{
	echo "service-policy output EFM-" . $new_rate . "M\n!\n";
}
echo "</textarea>";

echo "</td></tr>";
//------------------------BEGIN IAD CONFIG SECTION

echo "<tr><td>";
echo "<br><br><br><b>IAD CONFIG</b> - updates QOS policy in IAD<br>";

echo "<textarea style=overflow:hidden;color:green;background-color:#F0F0F0;  rows=12 cols=40 readonly>\n\n!\n";

if ($new_rate == "unl"){
	echo "policy-map EFM-UL\n";
}else{
	echo "policy-map EFM-" . $new_rate . "M\n";
}

echo "class class-default\n";

if ($new_rate == "unl"){
	echo "shape peak percent 100\n";
}else{
	echo "shape average " . $new_shape . "\n";
}

if ($sip == true){
	echo "service-policy SIP_T1_QOS\n";
	echo "service-policy SIP_LAN_QOS_IAD\n";
}else{
	echo "service-policy T1_QOS\n";
	echo "service-policy T1_QOS_IAD\n";
}
echo "!\n";

echo "interface FastEthernet0/0.$VLAN\n";

if ($curr_rate == "unl"){
	echo "no service-policy output EFM-UL\n";
}else{
	echo "no service-policy output EFM-" . $curr_rate . "M\n";
}

if ($new_rate == "unl"){
	echo "service-policy output EFM-UL\n";
}else{
	echo "service-policy output EFM-" . $new_rate . "M\n";
}

echo "!\n\n";

echo "</textarea>";

echo "</td>";

echo "<td>NOTE:  The IAD Config portion contains both the OLD and NEW versions of the IAD policy-map for QOS.<br><br>
Receiving an error message on ONE of the two service-policy statements is an expected result.";

echo "</td></tr></table>";

?>

</BODY>
</HTML>

