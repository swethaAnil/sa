<html>


<head>

<title>BWAS LOOKUP</title>


</head>


<body>
<br />

<?php

$account = $_GET['account'];
$urls = array(
	"bwas00" => "bwas00.voice.cbeyond.net",
	"bwas01" => "bwas01.voice.cbeyond.net",
	"bwas" => "bwas02atl.voice.cbeyond.net",
	"bwas03" => "bwas03dal.voice.cbeyond.net",
	"bwas04" => "bwas04.voice.cbeyond.net",
	"bwas05" => "bwas05.voice.cbeyond.net",
	"bwas10" => "bwas10.voice.cbeyond.net",
	"bwas11" => "bwas11.voice.cbeyond.net",
	"bwas12" => "bwas12.voice.cbeyond.net",
	"bwas13" => "bwas13.voice.cbeyond.net",
	"bwas14" => "bwas14.voice.cbeyond.net",
	"bwas15" => "bwas15.voice.cbeyond.net",
	"bwas20" => "bwas20dal.voice.cbeyond.net",
	"bwas21" => "bwas21dal.voice.cbeyond.net"
	);

$url_key = "/Operator/ServiceProviders/Modify/index.jsp?key=";

if($account != ""){
	$url_key = "/Operator/ServiceProviders/Modify/index.jsp?key=$account";
	$bsm_error = 0;
	$broadsoft = NULL;
	$bsm_client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");
	try{
		if(!$bsm_result = $bsm_client->findCustomerLocation(array('accountId' => $account))){
			throw new Exception ('Could not locate account in Broadsoft');
	}
	$broadsoft = $bsm_result->return;
	$url = $urls[$broadsoft];
	echo "Redirecting to $url <br />";
	$full_url = "http://" . $url . $url_key;
	echo $full_url;
	header("Location: " . $full_url);
	}catch (Exception $fault){
		$bsm_error = 1;
		echo "Account $account not found in Broadsoft.";
	}	
	
	
	
}

?>



</body>
</html>
