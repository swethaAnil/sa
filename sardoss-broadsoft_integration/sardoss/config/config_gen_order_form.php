<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title> Service Activations - ReDCON</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<!--<![endif]-->

</head>

<script type="text/javascript">


function show_confirm(){

	var Missing = "";
	var Msg = "";
	var order_num = document.forms[0].order_num.value;
	
	//check order number format
	var order_num_pattern = /^1-\w{6,7}$/;
	if(order_num.match(order_num_pattern) == null && order_num != ""){
		alert("ERROR! - Order number is invalid. \n\n Please double-check the order number.")
		return false;
	}
		
}

</script>

<style type="text/css">

td{
	font-size:80%;
	font-weight:bold;
	padding-right:10px;
}

td.form{
	padding-bottom:10px;
}

td.line{
	text-align:right;
	width:50px;
	padding-right:1px;
	vertical-align:0px;
}


</style>

<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../style/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
<span class="location">
 / <u>ReDCON</u>
</span>
</div>

<hr class="topline" />
<?php include 'optionbar.php'; ?>
</div>


<div id="nojs" style="text-align:center;position:relative;top:75px;"><br /><br />
JavaScript must be enabled in your browser in order to use this tool.<br />
JavaScript is either disabled or not supported by your browser.<br /><br />
Enable Javascript in your browser options and try again.</div>

<div id="fullpage" style="display:none">

<div style="min-width:800px;position:relative;top:15px;margin-left:10px;width:100%;">




<center>

<blockquote>
<span style="font-size:120%;font-style:italic;font-weight:bold;">
<span style="color:red;">R</span>apid <span style="color:red;">D</span>eployment <span style="color:red;">CON</span>fig generator
</span>
</blockquote>
<br /><br />

<form action="config_gen_order.php" method="POST" onsubmit="return show_confirm()" >
<table>
<tr><td class="form"><strong>ENTER <u>ORDER</u> NUMBER</strong>: <input type="text" name="order_num"><input type="submit" class="button" name="Submit" value="Submit"></td></tr>
</table>
</form>
<br />
<span style="font-size:80%;">
For full manual operation, leave the field blank and click "Submit".
</span>

</center>

</div>
</div>
</body>
<script>
//if script enabled warning message hidden.
document.getElementById('nojs').style.display="none";
document.getElementById('fullpage').style.display="inline";
</script>



</html>
 