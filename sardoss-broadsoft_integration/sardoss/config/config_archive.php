<?php
error_reporting(0);

require("config_func.php");
require("config_constants.php");

$dir = "maps";
$sorting_order = 1;
$maps = scandir($dir,$sorting_order);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title> Service Activations - RedCON</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<!--<![endif]-->

</head>

<script type="text/javascript">

function open_window() {
	window.open('','build_window','width=1000,height=800,location=0,toolbar=0,status=0,menubar=0,resizable=1,scrollbars=1').focus();

}

</script>

<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../images/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
<span class="location">
 / <a href="../config/config_gen_order_form.php">ReDCON</a>
 / <u>Config Archive</u></span>
</span>
</div>

</div>

<hr class="topline" />

<?php include 'optionbar.php'; ?>

</div>

<div style="min-width:800px;position:relative;top:75px;margin-left:10px;width:100%;font-size:80%">
<form action="config_build.php" method="POST" target="build_window" onsubmit="return open_window()">
<table>
<br><br>
<?php

foreach($maps as $map){
	if($map != "." && $map != ".."){
		$map = substr($map,0,-4);
		$parts = explode("-",$map);
		$timestamp = $parts[0];
		$time_parts = explode("_",$timestamp);
		$time = substr($time_parts[3],0,2) . ":" . substr($time_parts[3],2,2) . ":" . substr($time_parts[3],4,2);
		$acct_num = $parts[1];
		$user = $parts[2];
		echo "<tr><td>Account $acct_num built by $user on $time_parts[1]/$time_parts[2]/$time_parts[0] at $time ==></td><td><input type=\"submit\" value=\"$map\" name=\"filename\"></td></tr>";
	}
}

?> 
</table>
</form>
</div>
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


 