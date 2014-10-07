<?php


$svc_type = $_POST['svc_type'];
$acct_num = $_POST['acct_num'];
$acct_name = $_POST['acct_name'];
$market = $_POST['market'];
$bwns_profile = $_POST['bwns_profile'];
$capacity = $_POST['capacity'];

$client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");

$method = "provision" . $svc_type . "Customer";

if($svc_type == "Analog"){
	$result = $client->$method(array('accountId' => $acct_num,'companyName' => $acct_name,'market' => $market,'bwnsProfile' => $bwns_profile));
}else{
	$result = $client->$method(array('accountId' => $acct_num,'companyName' => $acct_name,'market' => $market,'bwnsProfile' => $bwns_profile,'initialCallCapacity' => $capacity));
}

$return = $result->return;
echo "<br /><br />";
echo "<b>You submitted:</b> $svc_type, $acct_num, $acct_name, $market, $bwns_profile, $capacity<br /><br />";
echo "<b>Method called:</b> " . $method . "<br /><br />";
echo "<b>Status:</b> " . $return->status . "<br /><br />";
echo "<b>Message:</b> " . $return->message . "<br /><br />";
echo "<br /><br /><br /><br /><br /><br /><br /><br />";


echo "<b>RAW OUTPUT:</b><br />";
var_dump($result);


?>





