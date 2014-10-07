
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title>Service Activations - TCDC</title>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<!--<![endif]-->

<style>

</style>

</head>

<body onLoad="document.forms.input.iad_name.focus()">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../images/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
<span class="location">
 / <a href="../tcdc/">TCDC</a>
 / <a href="../tcdc/tcdc_build.php">VDOM Build</a>
  / <u>VDOM Build Output</u></span>
</span>
</div>

</div>

<hr class="topline" />
<?php include 'tcdc_optionbar.php'; ?>


<?php


$vdom_name = $_POST['vdom_name'];
$natip = $_POST['natip'];
$untrust_vlan = $_POST['untrust_vlan'];
$untrust_ip = $_POST['untrust_ip'];
$inside_vlan_name = $_POST['inside_vlan_name'];
$trust_vlan = $_POST['trust_vlan'];
$trust_ip = $_POST['trust_ip'];
$def_gw_1 = $_POST['def_gw_1'];
$def_gw_2 = $_POST['def_gw_2'];
$ssl_vpn_ip = $_POST['ssl_vpn_ip'];
$ssl_vpn_user = $_POST['ssl_vpn_user'];
$ssl_vpn_pass = $_POST['ssl_vpn_password'];
$trust_subnet = $_POST['trust_subnet'];

echo "<br /><br />";
echo "<span style=\"font-size:120%;font-weight:bold;margin-left:25px;\">Server Configuration</span>";
echo "<blockquote><textarea style=\"margin:20px;\" rows=\"50\" cols=\"80\" readonly>

echo \"config vdom\"

#Create the VDOM 
echo \"edit $vdom_name\"

#Create Interfaces
echo \"config system interface\" 
 
echo \"edit $vdom_name-p5-V$untrust_vlan\"
echo \"set vdom $vdom_name\"
echo \"set vlanid $untrust_vlan\"
echo \"set interface port5\"
echo \"set ip $untrust_ip/29\"
echo \"set allowaccess ping\"
echo \"next\"
 
echo \"edit $vdom_name-p6-V$trust_vlan\"
echo \"set vdom $vdom_name\"
echo \"set vlanid $trust_vlan\"
echo \"set interface port6\"
echo \"set ip $trust_ip/27\"
echo \"set allowaccess ping\"
echo \"next\"
 
echo \"edit $vdom_name.loopback1\"
echo \"set vdom $vdom_name\"
echo \"set type loopback\"
echo \"set ip $ssl_vpn_ip/32\"
echo \"set allowaccess ping\"
echo \"next\"
 
echo \"end\"

#Configure Zones 
echo \"config system zone\"
 
echo \"edit untrust\"
echo \"set interface $vdom_name-p5-V$untrust_vlan\"
echo \"next\"
echo \"edit trust\"
echo \"set interface $vdom_name-p6-V$trust_vlan\"
echo \"set intrazone allow\"
echo \"next\"
 
echo \"end\"

#Config Default Routes 
echo \"config router static\"
 
echo \"edit 1 \"
echo \"set device $vdom_name-p5-V$untrust_vlan\"
echo \"set dst 0.0.0.0/0\"
echo \"set gateway $def_gw_1\"
echo \"next\"
 
echo \"edit 2 \"
echo \"set device $vdom_name-p5-V$untrust_vlan\"
echo \"set dst 0.0.0.0/0\"
echo \"set gateway $def_gw_2\"
echo \"next\"
 
echo \"end\"

#Configure NAT Pool  
echo \"config firewall ippool\"
echo \"edit $vdom_name-$nat_ip\"
echo \"set endip $nat_ip\"
echo \"set startip $nat_ip \"
echo \"next\"
 
echo \"end\"

#Configure Firewall Address Book
echo \"config firewall address\"
echo \"edit CBeyond_DNS1\"
echo \"set subnet 10.200.78.4 255.255.255.255\"
echo \"set associated-interface \"untrust\"\"
echo \"next\"

echo \"edit CBeyond_DNS2\"
echo \"set subnet 10.200.79.4 255.255.255.255\"
echo \"set associated-interface \"untrust\"\"
echo \"next\"

echo \"edit $trust_subnet\"
echo \"set subnet $trust_subnet\"
echo \"set associated-interface trust\"
echo \"next\"

echo \"edit SSLVPN_TUNNEL_ADDR1\"
echo \"set type iprange\"
echo \"set end-ip 192.168.2.250\"
echo \"set start-ip 192.168.2.200\"

echo \"end\"

#Configure Firewall DNS Group
echo \"config firewall addrgrp\"
echo \"edit CBeyond_DNS\"
echo \"set member CBeyond_DNS1\"
echo \"set member CBeyond_DNS2\"

echo \"end\"

#Configure SSL VPN User
echo \"config user local\"
echo \"edit $ssl_vpn_user\"
echo \"set type password\"
echo \"set passwd $ssl_vpn_pass\"
echo \"next\"

echo \"end\"

#Configure SSL VPN User Group
echo \"config user group\"
echo \"edit SSL_VPN_GROUP\"
echo \"set sslvpn-portal full-access\"
echo \"set member $ssl_vpn_user\"
echo \"next\"

echo \"end\"

#Configure SSL VPN Service Port (10443)
echo \"config firewall service custom\"
echo \"edit SSLVPN\"
echo \"set protocol TCP/UDP/SCTP\"
echo \"set tcp-portrange 10443:0-65535\"
echo \"next\"
echo \"end\"

#Configure Firewall Policies
echo \"config firewall policy\"

echo \"edit 1\"
echo \"set srcintf trust\"
echo \"set srcaddr all\"
echo \"set dstintf untrust\"
echo \"set dstaddr all\"
echo \"set schedule always\"
echo \"set service ANY\"
echo \"set action accept\"
echo \"set nat enable\"
echo \"set ippool enable \"
echo \"set poolname \"$vdom_name-$nat_ip\"\"
echo \"next\"
 
echo \"edit 2\"
echo \"set srcintf untrust\"
echo \"set srcaddr all\"
echo \"set dstintf trust\"
echo \"set dstaddr all\"
echo \"set schedule always\"
echo \"set service ANY\"
echo \"set action deny\"
echo \"set logtraffic enable\"
echo \"next\"

echo \"edit 3\"
echo \"set srcintf any\"
echo \"set srcaddr all\"
echo \"set dstintf trust\"
echo \"set dstaddr $trust_subnet\"
echo \"set action ssl-vpn\"
echo \"set identity-based enable\"
echo \"config identity-based-policy\"
echo \"edit 1\"
echo \"set schedule always\"
echo \"set groups SSL_VPN_GROUP\"
echo \"set service ANY\"
echo \"end\"
echo \"next\"

echo \"move 3 before 1\"

echo \"edit 4\"
echo \"set srcintf any\"
echo \"set srcaddr all\"
echo \"set dstintf untrust\"
echo \"set dstaddr CBeyond_DNS\" 
echo \"set action ssl-vpn\"
echo \"set identity-based enable\"
echo \"config identity-based-policy\"
echo \"edit 1\"
echo \"set schedule always\"
echo \"set groups SSL_VPN_GROUP\"
echo \"set service DNS\"
echo \"end\"
echo \"next\"

echo \"move 4 before 1\"

echo \"edit 6\"
echo \"set srcintf any\"
echo \"set dstintf $vdom_name.loopback1\"
echo \"set srcaddr all\"
echo \"set dstaddr all\"
echo \"set action accept\"
echo \"set schedule always\"
echo \"set service PING SSLVPN\"
echo \"next\"

echo \"edit 7\"
echo \"set srcintf any\"
echo \"set dstintf untrust\"
echo \"set srcaddr all\"             
echo \"set dstaddr CBeyond_DNS\"             
echo \"set action accept\"
echo \"set schedule always\"
echo \"set service PING DNS\"             
echo \"next\"

echo \"move 7 before 1\"
 
echo \"end\"

#Configure IPS Sensor Policies
echo \"config ips sensor\"

echo \"edit CBeyond_default_client\"
echo \"config entries\"
echo \"edit 1\"
echo \"set location client\"
echo \"set os windows\"
echo \"set log enable\"
echo \"next\"

echo \"end\"

echo \"next\"

echo \"edit CBeyond_default_client\"
echo \"config entries\"
echo \"edit 1\"
echo \"set location server\"
echo \"set os windows\"
echo \"set log enable\"
echo \"next\"

echo \"end\" 

echo \"next\"

echo \"edit CBeyond_IIS\"
echo \"config entries\"
echo \"edit 1\"
echo \"set location server\"
echo \"set os windows\"
echo \"set application IIS\"
echo \"set log enable\"
echo \"next\"

echo \"end\"

echo \"next\"

echo \"edit CBeyond_SQL\"
echo \"config entries\"
echo \"edit 1\"
echo \"set location server\"
echo \"set os windows\"
echo \"set application MSSQL MySQL PostgreSQL\"
echo \"set log enable\"
echo \"next\"

echo \"end\"

echo \"next\"

echo \"edit CBeyond_MAIL\"
echo \"config entries\"
echo \"edit 1\"
echo \"set location server\"
echo \"set os windows\"
echo \"set application MS_Exchange Sendmail MailEnable\"
echo \"set log enable\"
echo \"next\"

echo \"end\"

echo \"next\"

echo \"end\"

#Configure SSL VPN
echo \"config vpn ssl settings\"
echo \"set dns-server1 10.200.78.4\"
echo \"set dns-server2 10.200.79.4\"
echo \"set servercert cbeyond\"
echo \"set algorithm high\"
echo \"set idle-timeout 3600\"
echo \"set tunnel-ip-pools SSLVPN_TUNNEL_ADDR1\"

echo \"end\"

 
echo \"end\" 

#Configure default VDOM Limits
echo \"config global\"
echo \"config system vdom-property\"
echo \"edit $vdom_name\"
echo \"set session 8000 0\"
echo \"set ipsec-phase1 5 0\"
echo \"set ipsec-phase2 5 0\"
echo \"set firewall-policy 100 0\"
echo \"set firewall-address 1000 0\"
echo \"set firewall-addrgrp 100 0\"
echo \"set user 5 0\"
echo \"set sslvpn 5 0\"
echo \"next\"

echo \"end\"

echo \"end\"
</textarea></blockquote>";



?>



</body>
</html>