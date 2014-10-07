
<HTML>

<BODY>

<?php 
session_start();

if($_SESSION[chassis]){
	$chassis = $_SESSION[chassis];
}else{
	$chassis = $_POST[chassis];
	$_SESSION[chassis] = $chassis;
}

if($_SESSION[hsl]){
	$hsl = $_SESSION[hsl];
}else{
	$hsl = $_POST[hsl];
	$_SESSION[hsl] = $hsl;
}




echo "<hr>Login/retrieve script<hr>";
echo "<textarea readonly rows=10 cols=60>

telnet $chassis 3083






act-user::cwittwer:::Kerry7677;







rtrv-hsl::hsl-$hsl;

rtrv-mlp;


</textarea>";
echo "<hr>";


if($_POST[text1]){
	
	$input = $_POST[text1];
	$lines = explode("\n",$input);
	
	
	foreach($lines as $line){
		
		$line = trim($line);
				
		if(substr($line,1,4) == "HSL-"){
			
			$hslinfo = explode(":",$line);
			$hsldata = explode(",",$hslinfo[1]);
			
			
			echo "<b>HSL STATUS: " . $hslinfo[0] . " - " . $hslinfo[2] . " - " . $hsldata[3] . " - " . $hsldata[17] . "</b><br><hr>";
			
			echo "<br><br><hr>Disco script<hr><br>";
			echo "<textarea readonly rows=12 cols=60>

canc-init-hsl::hsl-$hsl
";
		}
		
		if(substr($line,1,4) == "MLP-"){
			
			$mlpinfo = explode(":",$line);
			
			$mlpdata = explode(",",$mlpinfo[1]);
			
			if($mlpdata[0] == "HSL=HSL-$hsl"){
				
				$cleanmlp = str_replace("\"","",$mlpinfo[0]);
				echo "
dlt-mlp::$cleanmlp
";
			}
		}
	}
	echo "
dlt-hsl::hsl-$hsl
</textarea><br><br>";
	
	
	session_destroy();
	echo "<a href=\"efm_disco.htm\">BACK</a>";
}



?>




<FORM METHOD="POST" ACTION="efm_disco.php">

<P><TEXTAREA NAME="text1" COLS=45 ROWS=8 WRAP=virtual></TEXTAREA>
<br><INPUT TYPE="reset" value="Reset"></P>

<P><INPUT TYPE="submit" NAME="submit" value="Submit"></p>



</BODY>

</HTML>