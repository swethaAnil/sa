
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

<script type="text/javascript">
    

function show_confirm(){

var Missing = "";
var Msg = "";
var iad = document.forms[0].iad_name.value.toUpperCase();
var vlan = document.forms[0].vlan.value;
var iad_patt = /IAD[0-9]+[A-Z][A-Z][A-Z]/i;

if(iad == ""){
	Missing = Missing + "IAD Name\n";
	}
if(vlan == ""){
	Missing = Missing + "VLAN ID\n";
	}	

	
//NULL values alert	
if(Missing != ""){	
	Missing = "No values provided for the following field(s):\n\n" + Missing;
	alert(Missing)
	return false;
	}

//check for incorrect IADname value	
if(iad.match(iad_patt) == null || iad.length < 10 || iad.length > 11){
	alert("ERROR! - IAD Name doesn't look right. (" + document.forms[0].iad_name.value + ")\n\nPlease double-check IAD name.")
	return false;
	}	

//check for incorrect VLAN value	
//if(vlan < 2000 || vlan > 2999){
//	alert("ERROR! - VLAN out of range for EFM.\n\nPlease double-check VLAN ID.")
//	return false;
//	}	


}

</script>


</head>

<body onLoad="document.forms.input.iad_name.focus()">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../images/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
<span class="location">
 / <a href="../tcdc/">TCDC</a>
  / <u>VDOM Build</u></span>
</span>
</div>

</div>

<hr class="topline" />
<?php include 'tcdc_optionbar.php'; ?>

<div id="nojs" style="text-align:center";><br /><br />
JavaScript must be enabled in your browser in order to use this tool.<br />
JavaScript is either disabled or not supported by your browser.<br /><br />
Enable Javascript in your browser options and try again.</div>

<div id="fullpage" style="display:none">

<center>
<div style="min-width:350px;margin:20px;padding:5px;width:50%;border-style:ridge;border-width: 3px;text-align: center;">
DISCLAIMER: There is minimal error checking on these values.  
    If you enter the wrong info, your configs will be wrong.<br /><br />  
<font color=red>Review ALL configs (especially IPs) before use.</font>
    </div>


<form action="tcdc_build_output.php" onsubmit="return show_confirm()" method="POST">
<p>Please provide the following information and click Submit.</p>

<p>
<table>
<tr><td align="right">VDOM Name: </td><td><input type="text" name="vdom_name"></td><td> - "C####"</td></tr>
<tr><td align="right">NAT Public IP: </td><td><input type="text" name="natip"></td></tr>
<tr><td align="right">Outside VLAN #: </td><td><input type="text" name="untrust_vlan"></td></tr>
<tr><td align="right">Outside VLAN IP: </td><td><input type="text" name="untrust_ip"></td></tr>
<tr><td align="right">Inside VLAN Name: </td><td><input type="text" name="inside_vlan_name"></td><td> - "trust"</td></tr>
<tr><td align="right">Inside VLAN #: </td><td><input type="text" name="trust_vlan"></td></tr>
<tr><td align="right">Inside VLAN IP: </td><td><input type="text" name="trust_ip"></td></tr>
<tr><td align="right">CDCR00 IP: </td><td><input type="text" name="def_gw_1"></td></tr>
<tr><td align="right">CDCR01 IP: </td><td><input type="text" name="def_gw_2"></td></tr>
<tr><td align="right">SSL VPN IP: </td><td><input type="text" name="ssl_vpn_ip"></td></tr>
<tr><td align="right">SSL VPN USER: </td><td><input type="text" name="ssl_vpn_user"></td></tr>
<tr><td align="right">SSL VPN PASSWORD: </td><td><input type="text" name="ssl_vpn_password"></td></tr>
<tr><td align="right">Inside Subnet: </td><td><input type="text" name="trust_subnet"></td><td> - "1.2.3.4/27"</td></tr>
<tr><td></td><td><input type="submit" name="Submit" value="Submit"></td></tr>
</table>
</p>
</div>

</body>

<script>
//if script enabled warning message hidden.
document.getElementById('nojs').style.display="none";
document.getElementById('fullpage').style.display="inline";
</script>

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
