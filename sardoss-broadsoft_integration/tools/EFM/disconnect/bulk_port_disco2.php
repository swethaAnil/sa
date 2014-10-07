
<HTML>

<BODY>

<a href="bulk_port_disco.htm">BACK</a><br><br>

<?php 

$port_data = explode("\n",$_POST[port_data]);

$count = 0;


foreach($port_data as $port_line){

	$trimline = trim($port_line);
//echo substr($trimline,0,14) . "<br>";
	
	if(substr($trimline,0,14) == "Port-channel1."){
		
		echo "<hr>" . $trimline . " - ";
		
		$portline = explode(" ",$trimline);
		$disco_ports[$count] = $portline[0];
		$count++;
	}
	
	if(substr($trimline,0,11) == "Description"){
		
		echo "<b>" . $trimline . "</b><hr>";
		
	}
}

?>

<br><br><hr>DISCO SCRIPT<hr><br>

<textarea readonly cols="80" rows="50">


!
en
wr51dt
!
conf t
!
<?php
foreach($disco_ports as $disco_port){
echo "int " . $disco_port . "
!
shut
!
no int " . $disco_port . "
!
!
";}?>
exit
</textarea>


</BODY>

</HTML>