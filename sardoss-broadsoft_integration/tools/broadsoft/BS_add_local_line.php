<?php

$lines = array();
$acct_num = trim($_POST['acct_num']);
$btn = trim($_POST['btn']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);

for($i=1;$i<=10;$i++){
	$line_var = "line" . $i;
	if($_POST[$line_var]){
		$lines[$i] = trim($_POST[$line_var]);
	}
}

$client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");

$addLineMethod = "addLocalLine";
$featuresMethod = "configureFeatures";

echo "<br /><b>Account Number:</b> " . $acct_num . "<br /><b>Charge Number:</b> " . $btn . "<br /><b>Username:</b> " . $username . "<br /><b>Password:</b> " . $password . "<br /><br />";


foreach($lines as $line){
	$addLineResult = $client->$addLineMethod(array('accountId' => $acct_num,'telephoneNumber' => $line,'chargeNumber' => $btn,'authenticationUsername' => $username,'authenticationPassword' => $password));
	$addLineReturn = $addLineResult->return;
	echo "<hr><b>Adding $line.</b><br><b>Status:</b> " . $addLineReturn->status . "<br><b>Message:</b> " . $addLineReturn->message . "<br /><br />";
	
	if($addLineReturn->status == "SUCCESS"){
		
		$featuresResult = $client->$featuresMethod(
			array(
				'accountId' => $acct_num,
				'telephoneNumber' => $line,
				'featureSet' => 
					array(
						'callRestrictions' => 
							array(
								'allowDirectoryAssistance' => true,
								'allowDomesticLongDistance' => true,
								'allowIncomingCollectCalls' => false,
								'allowInternationalLongDistance' => true,
								'allowNineHundredNumbers' => false,
								'allowNineSevenSixNumbers' => false,
								'allowOperatorAssisted' => true
							),
						'callTransfer' =>
							array(
								'recallActive' => true,
								'recallNumberOfRings' => 0,
								'useDiversionInhibitorForBlindTransfer' => false,
								'useDiversionInhibitorForConsultativeCalls' => false
							),
						'threeWayCalling' =>
							array(
								'active' => true
							),
						'incomingCallingLineIdInfo' => 
							array(
								'active' => true,
								'CNAMActive' => true
							)
					)
			)
		);
		
		$featuresReturn = $featuresResult->return;
		
		echo "<b>Adding features to $line.</b><br><b>Status:</b> " . $featuresReturn->status . "<br><b>Message:</b> " . $featuresReturn->message . "<br /><br />";
	}
}






?>





