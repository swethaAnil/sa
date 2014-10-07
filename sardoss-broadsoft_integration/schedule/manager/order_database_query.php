<head>
<title>SA Database Query</title>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>

<script>
$(document).ready(function(){
		
	$("#query_submit").click(function(){
		var formData = $('#query_form').serialize();
		$.post('query_form_action.php',formData,function(result){
			$("#query_output").html(result);
		});
	});
});

</script>


</head>
<html>

<h2>SA Database Query</h2>

THIS IS QUERYING THE PRODUCTION ('service_activations') DATABASE.<br>
This page is restricted to "SELECT" queries only.

<br><br>


<form id="query_form" action="query_form_export.php" method="POST">
<textarea rows="4" cols="80" name="query">
</textarea>
<br>
<br>
<button id="query_excel">SEND RESULTS TO EXCEL DOC</button>
</form>
<button id="query_submit">DISPLAY RESULTS BELOW</button> 
<br><hr><br><br>
<div id="query_output"></div>

</html> 
