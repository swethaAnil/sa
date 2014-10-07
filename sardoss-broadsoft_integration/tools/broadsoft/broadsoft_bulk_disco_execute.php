<html>


<head>

<title>Broadsoft Bulk Delete Tool - Service Activations</title>

</head>


<body>
<br />


Results:
<br /><br />

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$count = $_POST['count'];

for($i=1;$i<=$count;$i++){

	if($_POST[$i]){
		$account = $_POST[$i];
		if(trim($account) == "1"){
			die("CRITICAL STOP: Attempting to delete Enterprise 1!");
		}else{
			$bsm_error = 0;
			$bsm_client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");
			try{
				if(!$bsm_result = $bsm_client->disconnectCustomer(array('accountId' => $account))){
					throw new Exception ('Disconnect failed');
				}
				$result = $bsm_result->return;
				echo $account . " - SUCCESS<br />";
			}catch (Exception $fault){
				$bsm_error = 1;
				echo $account . " - FAILED<br />";
			}
		}
	}

}

echo "<br /><br />DONE!";






?>