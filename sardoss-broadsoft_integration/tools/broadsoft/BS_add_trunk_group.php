<?php


foreach($_POST as $value){
	
	if($value == ""){
		echo "missing a value!  try again.";
		exit();
	}

}

$svc_type = $_POST['svc_type'];
$acct_num = $_POST['acct_num'];
$trunkTN = $_POST['trunkTN'];
$chargeNum = $_POST['chargeNum'];
$capacity = $_POST['capacity'];
$password = $_POST['password'];

$client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");

$method = "addTrunkUserFor" . $svc_type;
echo $method . "<br><br>";

print_r($_POST);

$result = $client->$method(array('accountId' => $acct_num,'trunkTN' => $trunkTN,'chargeNumber' => $chargeNum,'maxActiveCalls' => $capacity,'sipAuthenticationPassword' => $password));

$return = $result->return;
echo "<br /><br />";
echo "<b>You submitted:</b> $svc_type, $acct_num, $trunkTN, $chargeNum, $capacity, $password<br /><br />";
echo "<b>Method called:</b> " . $method . "<br /><br />";
echo "<b>Status:</b> " . $return->status . "<br /><br />";
echo "<b>Message:</b> " . $return->message . "<br /><br />";
echo "<b>Error:</b> " . $return->errorDescription . "<br /><br />";
echo "<br /><br /><br /><br /><br /><br /><br /><br />";


echo "<b>RAW OUTPUT:</b><br />";
var_dump($result);


?>






