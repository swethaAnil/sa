
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
$nat_ip = $_POST['natip'];
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
echo "<span style=\"font-size:120%;font-weight:bold;margin-left:25px;\">Fortinet [10.200.25.22] Configuration</span>";
echo "<blockquote><textarea style=\"margin:20px;\" rows=\"50\" cols=\"80\" readonly>



config vdom

#Create the VDOM 
edit $vdom_name

#Create Interfaces
config system interface 
 
edit $vdom_name-p5-V$untrust_vlan
set vdom $vdom_name
set vlanid $untrust_vlan
set interface port5
set ip $untrust_ip/29
set allowaccess ping
next
 
edit $vdom_name-p6-V$trust_vlan
set vdom $vdom_name
set vlanid $trust_vlan
set interface port6
set ip $trust_ip/27
set allowaccess ping
next
 
edit $vdom_name.loopback1
set vdom $vdom_name
set type loopback
set ip $ssl_vpn_ip/32
set allowaccess ping
next
 
end

#Configure Zones 
config system zone
 
edit untrust
set interface $vdom_name-p5-V$untrust_vlan
next
edit $inside_vlan_name
set interface $vdom_name-p6-V$trust_vlan
set intrazone allow
next
 
end

#Config Default Routes 
config router static
 
edit 1 
set device $vdom_name-p5-V$untrust_vlan
set dst 0.0.0.0/0
set gateway $def_gw_1
next
 
edit 2 
set device $vdom_name-p5-V$untrust_vlan
set dst 0.0.0.0/0
set gateway $def_gw_2
next
 
edit 3
set device ssl.$vdom_name
set dst 192.168.2.0 255.255.255.0
set dynamic-gateway disable
next

end



#Configure NAT Pool  
config firewall ippool
edit $vdom_name-$nat_ip
set endip $nat_ip
set startip $nat_ip
next
 
end

#Configure Firewall Address Book
config firewall address
edit CBeyond_DNS1
set subnet 10.200.78.4 255.255.255.255
set associated-interface untrust
next

edit CBeyond_DNS2
set subnet 10.200.79.4 255.255.255.255
set associated-interface untrust
next

edit $trust_subnet
set subnet $trust_subnet
set associated-interface $inside_vlan_name
next

edit SSLVPN_TUNNEL_ADDR1
set type iprange
set start-ip 192.168.2.200
set end-ip 192.168.2.250
next

edit NetCool Monitoring
set subnet 10.128.11.61 255.255.255.255


end

#Configure Firewall DNS Group
config firewall addrgrp
edit CBeyond_DNS
set member CBeyond_DNS1
set member CBeyond_DNS2

end

#Configure SSL VPN User
config user local
edit $ssl_vpn_user
set type password
set passwd $ssl_vpn_pass
next

end

#Configure SSL VPN User Group
config user group
edit SSL_VPN_GROUP
set sslvpn-portal full-access
set member $ssl_vpn_user
next

end

#Configure SSL VPN Service Port (10443)
config firewall service custom
edit SSLVPN
set protocol TCP/UDP/SCTP
set tcp-portrange 10443:0-65535
next
end

#Configure Firewall Policies
config firewall policy

edit 1
set srcintf $inside_vlan_name
set srcaddr all
set dstintf untrust
set dstaddr all
set schedule always
set service ANY
set action accept
set nat enable
set ippool enable 
set poolname $vdom_name-$nat_ip
next
 
edit 2
set srcintf untrust
set srcaddr all
set dstintf $inside_vlan_name
set dstaddr all
set schedule always
set service ANY
set action deny
set logtraffic enable
next

edit 3
set srcintf any
set srcaddr all
set dstintf $inside_vlan_name
set dstaddr $trust_subnet
set action ssl-vpn
set identity-based enable
config identity-based-policy
edit 1
set schedule always
set groups SSL_VPN_GROUP
set service ANY
end
next

move 3 before 1

edit 4
set srcintf any
set srcaddr all
set dstintf untrust
set dstaddr CBeyond_DNS 
set action ssl-vpn
set identity-based enable
config identity-based-policy
edit 1
set schedule always
set groups SSL_VPN_GROUP
set service DNS
end
next

move 4 before 1

edit 6
set srcintf any
set dstintf $vdom_name.loopback1
set srcaddr all
set dstaddr all
set action accept
set schedule always
set service PING SSLVPN
next

move 6 before 3

edit 7
set srcintf any
set dstintf untrust
set srcaddr all             
set dstaddr CBeyond_DNS             
set action accept
set schedule always
set service PING DNS             
next

move 7 before 1

edit 8
set srcintf ssl.$vdom_name
set dstintf $inside_vlan_name
set srcaddr all            
set dstaddr all            
set action accept
set status enable
set schedule always
set service ANY
next

edit 9
set srcintf untrust
set dstintf $inside_vlan_name
set srcaddr NetCool Monitoring
set dstaddr all
set action accept
set schedule always
set service PING
next

move 9 before 2

end


#Configure IPS Sensor Policies
config ips sensor

edit CBeyond_default_client
config entries
edit 1
set location client
set os windows
set log enable
next

end

next

edit CBeyond_default_client
config entries
edit 1
set location server
set os windows
set log enable
next

end 

next

edit CBeyond_IIS
config entries
edit 1
set location server
set os windows
set application IIS
set log enable
next

end

next

edit CBeyond_SQL
config entries
edit 1
set location server
set os windows
set application MSSQL MySQL PostgreSQL
set log enable
next

end

next

edit CBeyond_MAIL
config entries
edit 1
set location server
set os windows
set application MS_Exchange Sendmail MailEnable
set log enable
next

end

next

end

#Configure SSL VPN
config vpn ssl settings
set dns-server1 10.200.78.4
set dns-server2 10.200.79.4
set servercert cert
set algorithm high
set idle-timeout 3600
set tunnel-ip-pools SSLVPN_TUNNEL_ADDR1

end

 
end 

#Configure default VDOM Limits
config global
config system vdom-property
edit $vdom_name
set session 8000 0
set ipsec-phase1 5 0
set ipsec-phase2 5 0
set firewall-policy 100 0
set firewall-address 1000 0
set firewall-addrgrp 100 0
set user 5 0
set sslvpn 5 0
next

end

end

</textarea></blockquote>";



?>



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

