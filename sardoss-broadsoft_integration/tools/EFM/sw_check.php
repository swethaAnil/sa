
<HTML>

<BODY>

<?php 


$input = $_POST[text1];

$lines = explode("\n",$input);
$update_serials;
echo "<table border=1><tr><th>CPE Serial #</th><th>STATE</th><th>Current Version</th><th>New Version</th></tr>";

foreach($lines as $line){
	
	$line = trim($line);
	
	if(substr($line,0,1) == "A"){
		echo "<tr><td>" . substr($line,0,11) . "</td>";
	}

	if(substr($line,1,5) == "STATE"){
		
		$state = explode(",",$line);
		
		foreach($state as $segment){
			
			$values = explode("=",$segment);
			
			switch($values[0]){
				case "\"STATE":
					echo "<td>" . $values[1] . "</td>";
					break;
				case "CURRENTSW":
					if($values[1] != "7.10/36"){
						echo "<td><font color=red>" . $values[1] . "</font></td>";
					}else{
						echo "<td>" . $values[1] . "</td>";
					}
					
					break;
				case "NEWSW":
					echo "<td>" . $values[1] . "</td>";
					break;
				default:
					break;
			}
		}		
	echo "</tr>";
	}
	
}

echo "</table><br><br>";

?>

<FORM METHOD="POST" ACTION="sw_load.php">

<P><TEXTAREA NAME="text1" COLS=45 ROWS=8 WRAP=virtual></TEXTAREA>
<br><INPUT TYPE="reset" value="Reset"></P>

<P><INPUT TYPE="submit" NAME="submit" value="Submit"></p>



</BODY>

</HTML>