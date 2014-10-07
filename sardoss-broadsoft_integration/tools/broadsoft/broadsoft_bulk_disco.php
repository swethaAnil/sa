<html>


<head>

<title>Broadsoft Bulk Delete Tool - Service Activations</title>


<script language="javascript">
function checkAll(){
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		var e=document.forms[0].elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			e.checked=document.forms[0].allbox.checked;
		}
	}
}
</script>

</head>


<body>
<br />

<form action="broadsoft_bulk_disco_execute.php" method="POST">

<input type="checkbox" value="on" name="allbox" onclick="checkAll();"/> Check all<br />

<table border="1">
<tr><th>Account Number</th><th>BWAS</th><th>DISCONNECT?</th></tr>
<?php

$data = $_POST['accounts'];
$accounts = explode("\n",$data);
$count = 0;
foreach($accounts as $account){
	
	if($account != ""){
		$bsm_error = 0;
		$broadsoft = NULL;
		$bsm_client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");
		try{
			if(!$bsm_result = $bsm_client->findCustomerLocation(array('accountId' => $account))){
				throw new Exception ('Could not locate account in Broadsoft');
		}
		$broadsoft = $bsm_result->return;
		$count++;
		if($broadsoft == "bwas21" || $broadsoft == "bwas20"){
			$tcpsStyle = "style=\"color:red;\"";
		}else{
			$tcpsStyle = "";
		}
		echo "<tr><td>$account</td><td $tcpsStyle>$broadsoft</td><td align=\"center\"><input type=\"checkbox\" name=\"$count\" value=\"$account\"></td></tr>";
		}catch (Exception $fault){
			$bsm_error = 1;
			echo "<tr><td>$account</td><td> ------ </td><td></td></tr>";
		}	
		
	}
}
echo "<input type=\"hidden\" name=\"count\" value=\"$count\">";
?>

</table>

<input type="submit" name="submit" value="Submit">

</form>


</body>
</html>
