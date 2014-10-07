<html>
<head>
<script type="text/javascript">

</script>
</head>

<body>



<?php
$order_num = $_POST['order_num'];
$url = "http://papa01prd.bay.cbeyond.net:8000/getAccountAndCircuitInfo.json?order=" . $order_num;

$json = file_get_contents($url);

$json_output = json_decode($json);
print_r($json_output);

$client = new SoapClient("http://bsft-mgr.cbeyond.net/BroadsoftManagerService/BroadsoftManagerService?WSDL");
$result = $client->findCustomerLocation(array('accountId' => 140534));

//phpinfo() ; 
echo "<br />";
echo "<br />";
echo "ACCOUNT NUMBER: " . $json_output->account_id . "<br />";
echo "ACCOUNT NAME: " . $json_output->account_name . "<br />";
echo "ACCESS TYPE: " . $json_output->access_type . "<br />";
echo "CALL AGENT: " . $json_output->call_agent . "<br />";
echo "BROADSOFT: " . $result->return . "<br />";
echo "VOICE SERVICE TYPE: " . $json_output->voice_service_type . "<br />";
echo "CA_TYPE: " . $json_output->ca_type . "<br />";
echo "AGG ROUTER: " . $json_output->aggregation_router . "<br />";
echo "COMMITTED BW: " . $json_output->committed_bw . "<br />";
echo "BTN: " . $json_output->btn . "<br />";
echo "EFM SWITCH: " . $json_output->efm_switch . "<br />";
echo "HSL: " . $json_output->hsl . "<br />";
echo "VLAN: " . $json_output->vlan . "<br />";
echo "<br />";
foreach ( $json_output->local_lines as $local_line )
{
    echo "LOCAL LINE TN: " . "{$local_line->tn}<br />";
	echo "LOCAL LINE PORT: " . "{$local_line->port}<br />";
	echo "<br />";
}

foreach ( $json_output->networks as $network )
{
    echo "TYPE: " . "{$network->type}<br />";
	echo "DESCRIPTION: " . "{$network->description}<br />";
	echo "ADDR: " . "{$network->addr}<br />";
	echo "CIDR: " . "{$network->cidr}<br />";
	echo "IAD: " . "{$network->iad}<br />";
	echo "<br />";
}

foreach ( $json_output->circs as $circ )
{
    echo "CID: " . "{$circ->cid}<br />";
	echo "CFA: " . "{$circ->cfa}<br />";
	echo "MLP: " . "{$circ->mlp}<br />";
	echo "MLU: " . "{$circ->mlu}<br />";
	echo "SLOT: " . "{$circ->slot}<br />";
	echo "SUBSLOT: " . "{$circ->subslot}<br />";
	echo "PORT: " . "{$circ->port}<br />";
	echo "PORT-BW: " . "{$circ->port_bw}<br />";
	echo "<br />";
}
echo "<br />";
echo "ERROR: " . $json_output->db_error . "<br />";
echo "MISSING: " . $json_output->missing . "<br />";
echo "NOT FOUND: " . $json_output->not_found . "<br />";

?> 
</body>
 </html>
 