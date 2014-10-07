<?php

session_start(); 

//define variables
$device = $_POST['device'];
$pca = $_POST['pca'];

//extract access type and number of circuits
$access_array = array(NONE,T1,EFM,FIBER);
$access_id = $_POST['access'];
$access = $access_array[$access_id];
$circuit_var = "num_circuits_" . $access;
$num_circuits = $_POST[$circuit_var];

//extract voice type and number of analogs and trunk groups
$voice_array = array(NONE,ANALOG,PRI,CAS,SIP,VOPRI,PRIM,CASM);
$voice_id = $_POST['voice_type'];
$voice_type = $voice_array[$voice_id];
$analog_var = "num_analog_lines_" . $voice_type;
$num_analogs = $_POST[$analog_var];
$trunk_var = "num_trunk_groups_" . $voice_type;
$num_trunk_groups = $_POST[$trunk_var];

//create session variables
$_SESSION['device'] = $device;
$_SESSION['access'] = $access;
$_SESSION['num_circuits'] = $num_circuits;
$_SESSION['pca'] = $pca;
$_SESSION['voice_type'] = $voice_type;
$_SESSION['num_analogs'] = $num_analogs;
$_SESSION['num_trunk_groups'] = $num_trunk_groups;

print_r($_SESSION);

?>

<html>

<head>
<title>SA Config Generator</title>

</head>

<body>

<form action="config_output.php" method="POST" target="new_window" onSubmit="window.open('','new_window','width=800,height=600,location=0,toolbar=0,status=0,menubar=0,resizable=1,scrollbars=1');">

<h4>General Info</h4>
<p>Account Number:<input type="text" name="acct_num" /></p>
<p>Account Name:<input type="text" name="acct_name" /></p>

<p>IAD Name:<input type="text" name="iad_name" /></p>
<p>Secondary IAD Name:<input type="text" name="second_iad_name" /></p>
<p>10K Name:<input type="text" name="tenk_name" /></p>
<p>Circuit ID:<input type="text" name="CID" /></p>
<p>VLAN:<input type="text" name="VLAN" /></p>
<p>Rate Limit:<input type="text" name="rate_limit" /></p>


<h4>Data Info</h4>
<p>Public IP:<input type="text" name="public_ip" /></p>
<p>Public Subnet Mask:<input type="text" name="public_subnet" /></p>


<h4>Voice Info</h4>
<p>BWAS:<input type="text" name="bwas" /></p>
<p>BWAS:<input type="text" name="btn" /></p>
<p>Line 1:<input type="text" name="analog1" /></p>
<p>Line 2:<input type="text" name="analog2" /></p>
<p>Line 3:<input type="text" name="analog3" /></p>
<p>Line 4:<input type="text" name="analog4" /></p>
<p>Line 5:<input type="text" name="analog5" /></p>
<p>Line 6:<input type="text" name="analog6" /></p>
<p>Line 7:<input type="text" name="analog7" /></p>
<p>Line 8:<input type="text" name="analog8" /></p>
<p>Line 9:<input type="text" name="analog9" /></p>
<p>Line 10:<input type="text" name="analog10" /></p>
<p>Line 11:<input type="text" name="analog11" /></p>
<p>Line 12:<input type="text" name="analog12" /></p>

<input type="submit" value="Submit">


</body>

</html>