<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title>Service Activations - ReDCON</title>
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

//check for incorrect IADname value	
if(vlan < 2000 || vlan > 2999){
	alert("ERROR! - VLAN out of range for EFM.\n\nPlease double-check VLAN ID.")
	return false;
	}	


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
 / <a href="../config/config_gen_order_form.php">ReDCON</a>
 / <a href="../base/base_configs.php">Base Configs</a>
 / <u>T1</u></span>
</span>
</div>

</div>

<hr class="topline" />
<?php include '../config/optionbar.php'; ?>

<div id="nojs" style="text-align:center";><br /><br />
JavaScript must be enabled in your browser in order to use this tool.<br />
JavaScript is either disabled or not supported by your browser.<br /><br />
Enable Javascript in your browser options and try again.</div>

<div id="fullpage" style="display:none">

<center>
<br /><br /><br />


<form action="t1_base_config_output.php" onsubmit="return show_confirm()" method="POST">


<p>
<table>
<tr><td align="right"><strong>Device:</strong></td><td><input type="radio" name="device" value="IAD" >IAD&nbsp;&nbsp;<input type="radio" name="device" value="ISR">ISR/SPIAD</td></tr>
<tr><td align="right"><strong>Number of T1s:</strong></td><td><input type="radio" name="num_t1s" value="Single" >1&nbsp;&nbsp;<input type="radio" name="num_t1s" value="Multi">2 or more</td></tr>
<tr>
<td align="right">&nbsp;</td><td><input type="submit" name="submit" value="Submit" class="button">
<input type="reset" name="reset" value="Reset" class="button"></td>
</tr>
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


