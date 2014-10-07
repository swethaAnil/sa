<HTML>

<HEAD>
<TITLE>EFM Cutover Expedite Tool</TITLE>
</HEAD>

<BODY>

<?php

$acctnum = $_POST[acctnum];
$pid = $_POST[pid];
$vpn = $_POST[vpn];
$ip_choice = $_POST[ip];
$ftp = $_POST[ftp];


if($_POST[alt_private]){ $private_ip = $_POST[alt_private];}

$url = "http://raptor.cbeyond.net/workspace//webRoot/Schema-415478435-601800053/192/webResources/jsp/config-list.jsp?accountNo=" . $acctnum . "&pId=" . $pid . "&takeSnapshot=false";
//http://raptor.cbeyond.net/workspace//webRoot/Schema-415478435-601800053/136/webResources/jsp/config-list.jsp?accountNo=
//http://raptor.cbeyond.net/workspace//webRoot/Schema-415478435-601800053/111/webResources/jsp/config-list.jsp?accountNo=
//http://raptor.cbeyond.net/workspace//webRoot/Schema-415478435-601800053/134/webResources/jsp/config-list.jsp?accountNo=125623&pId=1275867&takeSnapshot=false
$file = file_get_contents($url);

preg_match("/description.*/",$file,$DESCRIPTION);
$ACCT_INFO = substr($DESCRIPTION[0],11);
echo "<br><H3>Configs for: $ACCT_INFO</h3>";

preg_match("/EFM0[0-6][A-Z]{3}.[A-Z]{3}0/",$DESCRIPTION[0],$SWITCH_NAME);
preg_match("/ip route 0.0.0.0 0.0.0.0.* 172/",$file,$DEFAULT_ROUTE);
$ETHERNET_INT = substr($DEFAULT_ROUTE[0],24,-4);	

echo "<br><br>";



//EFM SWITCH PRE-CONFIG EXTRACTION	
if(preg_match("/div id=\"efm_switch-pre[\s\S]*<\/div><!-- #efm_switch-pre/",$file,$EFM_switch)){
	
	echo "<u><b>EFM SWITCH/CPE LOGIN SCRIPT</u></b><br>";
	
	echo "<hr>";
	echo "<br>";
	
	echo "telnet $SWITCH_NAME[0] 3083<br><br><br>";
	
	echo "act-user::admin:::wr51dt;<br><br><br>";
	
	preg_match("/ENT-HSL.*O;/",$EFM_switch[0],$EFM_switch_HSL);
	
	$HSL = substr($EFM_switch_HSL[0],13,3);
	
	if(substr($HSL,-1) == ":"){
		$HSL = substr($HSL,0,2);
	}
		
	
	
	echo "act-user:hsl-" . $HSL . ":admin:::admin;<br><br>";
	
	echo "<hr>";
	echo "<br><br><br><br><br>";
	
	
	echo "<u><b>EFM SWITCH PRE-CONFIG</b></u><br>";
	
	echo "<hr>";
	echo "<br>";
	
	echo str_replace("ENT", "ED", $EFM_switch_HSL[0])."<br><br>";
	
	preg_match_all("/ENT-MLP.*LOWSNRMTH=0;/",$EFM_switch[0],$EFM_switch_MLP);
		
	$MLPS = $EFM_switch_MLP[0];
	
	foreach($MLPS as $MLP){
		echo "$MLP<br><br>";
	}

	preg_match("/ED-VLAN.*;/",$EFM_switch[0],$EFM_switch_VLAN);
	echo $EFM_switch_VLAN[0];
	
	echo "<br><br>";
	echo "<hr>";
	echo "<br><br><br><br><br>";
}

//EFM CPE PRE-CONFIG EXTRACTION	
if(preg_match("/div id=\"efm_cpe-pre[\s\S]*<\/div><!-- #efm_cpe-pre/",$file,$EFM_CPE)){

	echo "<u><b>EFM CPE PRE-CONFIG</b></u><br>";
	
	echo "<hr>";
	echo "<br>";
	
	
	preg_match_all("/ED-VLAN.*:;/",$EFM_CPE[0],$EFM_CPE_VLANA);
	
	$CPE_VLAN_DOWN = $EFM_CPE_VLANA[0];
	
	echo $CPE_VLAN_DOWN[0] . "<br><br>";
		
	//$CPE_VLANS[0] = str_replace("ENT", "ED", $CPE_VLANS[0]);
	
	preg_match_all("/ENT-VLAN.*:;/",$EFM_CPE[0],$EFM_CPE_VLANB);
	
	$CPE_VLAN_UP = $EFM_CPE_VLANB[0];
	
	echo $CPE_VLAN_UP[0] . "<br><br>";
		
	// foreach($CPE_VLANS as $VLAN){
		// echo "$VLAN<br><br>";
	// }
	
	preg_match_all("/ED-L.*;/",$EFM_CPE[0],$EFM_CPE_PRIO);
	
	$CPE_PRIOS = $EFM_CPE_PRIO[0];
	
	foreach($CPE_PRIOS as $PRIO){
		echo "$PRIO<br>";
	}
	echo "<br>";
	
	preg_match_all("/ED-COMM.*.0/",$EFM_CPE[0],$EFM_CPE_IP);
	
	$CPE_IP = $EFM_CPE_IP[0];
	
	echo $CPE_IP[0];
	
	echo "<br><br>";
	echo "<hr>";
	echo "<br><br><br><br><br>";
}

if(preg_match("/div id=\"iad-efm_rehome_post[\s\S]*<\/div><!-- #iad-efm_rehome_post/",$file,$EFM_IAD_POST)){

	preg_match("/<pre>[\s\S]+<\/pre>/",$EFM_IAD_POST[0],$EFM_IAD_CONFIG);
		
	$ftpfile = "Acct" . $acctnum . "-" . date('mdy-Gis') . ".txt";
	
	$fh = fopen("configfiles/$ftpfile", 'w') or die ("Can't open file");
	
	$NEW_CONFIG = preg_replace("/no ip address.*secondary/","no ip address",$EFM_IAD_CONFIG[0]);
	$NEW_CONFIG = str_replace("<pre>","",$NEW_CONFIG);
	$NEW_CONFIG = str_replace("</pre>","",$NEW_CONFIG);
	
	if(preg_match("/h323-gateway voip bind srcaddr [0-9]+.[0-9]+.[0-9]+.[0-9]+/",$EFM_IAD_CONFIG[0],$SIP_BIND)){
		$NEW_CONFIG = preg_replace("/interface Serial1\/0:0[\s]*no ip address/","interface Serial1/0:0\n$SIP_BIND[0]\nno ip address",$NEW_CONFIG);
		}
	
	if($ip_choice == "none"){
		$NEW_CONFIG = str_replace("ip address 10.0.1.1 255.255.255.0 secondary","",$NEW_CONFIG);
		$NEW_CONFIG = str_replace("no ip nat inside source list 101 interface FastEthernet0/0 overload","",$NEW_CONFIG);
		$NEW_CONFIG = str_replace("ip nat inside source list 101 interface FastEthernet0/0.20 overload","",$NEW_CONFIG);
	}elseif($ip_choice == "other"){
		$NEW_CONFIG = str_replace("ip address 10.0.1.1 255.255.255.0 secondary","ip address $private_ip 255.255.255.0 secondary",$NEW_CONFIG);
	}
	
	if($vpn){
		$NEW_CONFIG = $NEW_CONFIG . "\n!\nroute-map LAN_ROUTES_ONLY permit 10\nno match interface FastEthernet0/0\nmatch interface FastEthernet0/0.20\n!\nrouter rip\nno passive-interface Serial1/0:0\npassive-interface $ETHERNET_INT\n!\n";
	}
	
	$NEW_CONFIG = $NEW_CONFIG . "!\nno network-clock-select 1 T1 1/0\n!\nint serial1/0:0\nshut\nno description\nno ip address\n!\n!\n\ncontroller t1 1/0\nshut\nno description\nno channel-group 0\n!\n";
	
	if(preg_match("/[\s\S]+Multilink[\s\S]+/",$NEW_CONFIG)){
		$NEW_CONFIG = $NEW_CONFIG . "\nint serial0/0\nshut\nno description\n!\nint multilink1\nshut\nno description\nno ip address\n!\nno int multilink1\n!";
	}
	
	$NEW_CONFIG = str_replace("&gt;",">",$NEW_CONFIG);
	$NEW_CONFIG = str_replace("&lt;","<",$NEW_CONFIG);
	
	fwrite($fh,$NEW_CONFIG . "\n");

	fclose($fh);
}




$conn = ftp_connect("10.128.8.16") or die("Could not connect");
ftp_login($conn,"ios","i0sb00t");

ftp_chdir($conn,"serviceactivations");

ftp_put($conn,$ftpfile,"configfiles/$ftpfile",FTP_ASCII);
ftp_close($conn);



echo "<u><b>IAD FTP COPY CONFIG ---->>> <font color=\"red\">SET \"RELOAD IN 5\" BEFORE DROPPING THIS</b></font></u><br>";
echo "<hr>";
echo "<br>";
echo "clear ip nat trans *<br>";
echo "copy ftp://ios:i0sb00t@10.128.8.16/serviceactivations/" . $ftpfile . " run<br>";
echo "<br>";
echo "<hr>";
echo "<br><a href=configfiles/$ftpfile target=\"_blank\">Click here to see contents of IAD config</a><br>";
echo "<br><br><br><br><br>";

if(preg_match("/div id=\"router-efm_rehome_post[\s\S]*<\/div><!-- #router-efm_rehome_post/",$file,$EFM_10K_POST)){

	echo "<u><B>10K POST CONFIGURATION</B></u><br><br>";

	preg_match("/<pre>[\s\S]+<\/pre>/",$EFM_10K_POST[0],$EFM_10K_CONFIG);
	
	$NEW_10K_CONFIG = preg_replace("/no ip address.*secondary/","no ip address",$EFM_10K_CONFIG[0]);
	
	preg_match("/interface S.*\n\n!/",$NEW_10K_CONFIG,$OLD_SER_CONFIG);
	preg_match("/interface M[\s\S]*!/",$NEW_10K_CONFIG,$OLD_MUL_CONFIG);
	echo $OLD_SER_CONFIG[0];
	
	$NEW_10K_CONFIG = str_replace("<pre>","",$NEW_10K_CONFIG);
	$NEW_10K_CONFIG = str_replace("</pre>","",$NEW_10K_CONFIG);
	
	$LINES_10K_CONFIG = explode("\n",$NEW_10K_CONFIG);

	echo "<font color=\"red\">DROP THIS SECTION IN THE <B>OLD</b> TAR IF CHANGING TARS!</font><br>";
	echo "<hr>";
	echo "<br>";
	echo "!<br>";
	echo "$LINES_10K_CONFIG[10]<br>";
	echo "$LINES_10K_CONFIG[11]<br>";
	echo "!<br>";	
	echo "$LINES_10K_CONFIG[20]<br>";
	echo "!<br>";
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	echo "<br>";
	echo "<font color=\"red\">DROP THIS SECTION IN THE <B>NEW</b> TAR IF CHANGING TARS!</font><br>";
	echo "<hr>";
	echo "<br>";
	echo "!<br>";
	echo "$LINES_10K_CONFIG[2]<br>";
	echo "$LINES_10K_CONFIG[3]<br>";
	echo "$LINES_10K_CONFIG[4]<br>";
	echo "$LINES_10K_CONFIG[15]<br>";
	echo "$LINES_10K_CONFIG[5]<br>";
	echo "$LINES_10K_CONFIG[6]<br>";
	echo "!<br>";
	echo "$LINES_10K_CONFIG[19]<br>";
	echo "!<br>";
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	echo "<br>";
		
}

?>

<br>
<u><h2><font color="red"><blink>******* REMEMBER TO DO THE FOLLOWING! *******</blink></h2></u>
<h3><UL>
<LI>WRITE THE IAD CONFIG
<LI>CANCEL THE IAD RELOAD ("reload cancel")
<LI>VERIFY THE IAD CONFIGURATION (port forwarding, access-lists, etc.)
<LI>CLEAN UP THE T1 INTERFACE(S) ON THE 10K
</UL></font></h3>

<br><br><br><br>

<a href="efm_expedite.html">Go Back</a>

</BODY>
</HTML>

