<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<?php 

$username = $_SERVER['PHP_AUTH_USER'];

?>

<html>


<head>
<title>Search - Service Activations</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/tracker_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/tracker_style.css" />
<!--<![endif]-->


<style>

.topfull{
border-width:0px;
border-style:hidden;
font-family:"arial";
font-size:10px;
width:100%;
padding-right:40px;
}

.top{
border-width:0px;
border-style:hidden;
font-family:"arial";
font-size:12px;
padding-right:40px;
font-weight:bold;
}


</style>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>

<script>
$(document).ready(function(){

	$("#submitSearch").click(function(){
		var formData = $('#searchForm').serialize();
		$.post('displayResults.php',formData,function(data,status){
			$("#results").html(data);
		});
		$("#searchEntry").val("");
		
	});
	
	 $("#searchEntry").keypress(function(e) {
		if(e.which == 13) {
			var formData = $('#searchForm').serialize();
			$.post('displayResults.php',formData,function(data,status){
				$("#results").html(data);
			});
			$("#searchEntry").val("");
		}
	}); 
	
});
</script>	



<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=700,height=600,scrollbars=yes,status=no,toolbar=no,location=no');
return false;
}
//-->

</script>



</head>

<body onLoad="document.forms.searchForm.searchEntry.focus()">

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:0px;" src="../images/cbey_logo_small.png"></div>


<div class="pagetitle">
<a class="pagetitle" href="../../">Service Activations</a>
 / <a class="pagetitle" href="../current_schedule.php">Online Schedule</a>
 / 
<span class="location">
<u>Search</u>
</span></div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="../current_schedule.php">Today's Schedule</a>
<a style="padding-left:10px;" href="../view_schedules.htm">View Other Schedules</a>
<div class="current_user">User: <?php echo $username; ?></div>
</div>
</div>

<div style="min-width:600px;position:relative;top:75px;width:100%;">
<br>
<h3 style="display:inline;margin-left:20px;">Search </h3> &nbsp;&nbsp;
<form name="searchForm" id="searchForm" method="POST" action="javascript:void(0);" style="display:inline;">
<input type="text" id="searchEntry" name="searchEntry">
</form>
<button name="submitSearch" id="submitSearch" class="bodylink" style="padding:2px;">SUBMIT</button>

<br><br><hr>

<div style="margin:30px;" id="results"></div>



</div>
</body>
</html> 