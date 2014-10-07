
<HTML>

<BODY>

<?php 


$input = $_POST[text1];

$lines = explode("\n",$input);
echo "LOAD SW<br><br>";
foreach($lines as $line){
	
	$line = trim($line);
	echo "load-sw:" . $line . ":::::ipaddr=10.128.8.16,filename=/nfs/tftpboot/ml600-R710-36-ssh-cby.mft,uid=cwittwer,pid=Kerry7677<br>";
}
echo "<hr><hr>RTRV SW<br><br>";
foreach($lines as $line){
	$line = trim($line);
	echo "rtrv-sw:" . $line . "<br>";
}


echo "<hr><hr>INVOKE SW<br><br>";
foreach($lines as $line){
	$line = trim($line);
	echo "invk-sw:" . $line . "<br>";
}
echo "<hr><hr>COMMIT SW<br><br>";
foreach($lines as $line){
	$line = trim($line);
	echo "cmmt-sw:" . $line . "<br>";
}

echo "<hr><hr>LOGIN TO CPE<br><br>";
foreach($lines as $line){
	$line = trim($line);
	echo "act-user:" . $line . ":admin:::admin;<br>";
}
?>


</BODY>

</HTML>