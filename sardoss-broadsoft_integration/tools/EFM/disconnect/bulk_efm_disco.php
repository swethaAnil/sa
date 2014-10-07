
<HTML>

<BODY>

<a href="bulk_efm_disco.htm">BACK</a><br><br>

<?php 


$chassis = $_POST[chassis];
$hsls = $_POST[hsls];

$hsls_array = explode("\n",$hsls);



echo "<hr>Login/retrieve script<hr><br>";
echo "telnet $chassis 3083<br><br><br>act-user::cwittwer:::Kerry7677<br><br><br>";

foreach($hsls_array as $hsl){
	
	if($hsl){
		echo "rtrv-hsl::hsl-$hsl<br>";
	}
}
	
echo "<br><br><br>rtrv-mlp;<br><br><br><hr>";

?>




<FORM METHOD="POST" ACTION="bulk_efm_disco2.php">
Paste HSL output here:<br>
<TEXTAREA NAME="hsl_data" COLS=45 ROWS=8 WRAP=virtual></TEXTAREA><br><br>
Paste MLP output here:<br>
<TEXTAREA NAME="mlp_data" COLS=45 ROWS=8 WRAP=virtual></TEXTAREA><br><br>
EFM SWITCH: <input type="text" name="chassis" value="<?php echo $chassis; ?>" size=20 maxlength=13><br>
<br><INPUT TYPE="submit" NAME="submit" value="Submit"><INPUT TYPE="reset" value="Reset">





</BODY>

</HTML>