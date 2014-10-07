
<HTML>

<BODY>

<a href="bulk_port_disco.htm">BACK</a><br><br>

<?php 


$tar = $_POST[tar];
$vlans = $_POST[vlans];
//$user = $_POST[user];
//$pass = $_POST[pass];

$user = "cwittwer";//
$pass = "Kerry7677";

$vlans_array = explode("\n",$vlans);



echo "<hr>Login/show script<hr><br>";
echo "cbt $tar<br>$user<br>$pass<br><br><br>";

foreach($vlans_array as $vlan){
	
	if($vlan){
		echo "show int port-channel1.$vlan<br>";
	}
}


?>

<br><br><hr><hr>


<FORM METHOD="POST" ACTION="bulk_port_disco2.php">
Paste SHOW output here:<br>
<TEXTAREA NAME="port_data" COLS=45 ROWS=8 WRAP=virtual></TEXTAREA><br><br>
10K: <input type="text" name="chassis" value="<?php echo $tar; ?>" size=20 maxlength=13><br>
<br><INPUT TYPE="submit" NAME="submit" value="Submit"><INPUT TYPE="reset" value="Reset">





</BODY>

</HTML>