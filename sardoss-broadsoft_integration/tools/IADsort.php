
<HTML>

<HEAD>
<TITLE> IAD Sort Tool Results </TITLE>
</HEAD>

<BODY>



<h1>Results:</h1>


<?php


$IADnames = explode("\n",$_POST[text1]);

$counter = 0;

foreach($IADnames as $value){
	
	$trimmed = trim($value); 						//remove whitespace
	$IADindex[$trimmed] = substr($trimmed, -3); 	//create array with market name values
	
	}

if ($_POST[checkbox] == "YES"){	
	asort($IADindex);								//sort array by market name values if sort box checked
	}

if ($_POST[submit] == "Sort"){

	foreach($IADindex as $key => $value){
		if($key != NULL){
			echo "$key<br>";
		}
	}
}

elseif ($_POST[submit] == "Sort and show MGWs"){

	foreach($IADindex as $key => $value){
		if($key != NULL){
			echo "show mgw id=$key<br>";
		}
	}
}
	
elseif ($_POST[submit] == "Sort and show 1st SUB"){

	foreach($IADindex as $key => $value){
		if($key != NULL){
			$trimmed = trim($key);
			echo "show sub id=$trimmed-1<br>";
		}
	}
}

elseif ($_POST[submit] == "Sort and show all SUBs"){

	foreach($IADindex as $key => $value){
		if($key != NULL){
			$trimmed = trim($key);
			echo "show sub id=$trimmed-%<br>";
		}	
	}
}


elseif ($_POST[submit] == "Sort and show FQDN"){

	foreach($IADindex as $key => $value){
		if($key != NULL){
			$trimmed = trim($key);
			$newval = trim($value)."0";
			echo "$trimmed.$newval.cbeyond.net<br>";
		}
	}
echo "<br><br><br><button class=\"mybutton\" onClick=\"window.location='/tools/ortool.html'\">OR Tool</button>";
}

elseif ($_POST[submit] == "Disco Bounce"){

	foreach($IADindex as $key => $value){
		if($key != NULL){
			echo "control mgw id=$key;mode=forced;target-state=ins<br>";
			echo "equip subscriber-termination id=*@$key<br>";
			echo "control subscriber-termination id=*@$key;mode=forced;target-state=ins<br><br>";

			echo "control subscriber-termination id=*@$key;mode=forced;target-state=oos<br>";
			echo "unequip subscriber-termination id=*@$key<br>";
			echo "control mgw id=$key;mode=forced;target-state=oos<br><br><br>";
		}
	}
}


elseif ($_POST[submit] == "Show subs based on account number"){

	foreach($IADindex as $key => $value){
		if($key != NULL){
			echo "show sub account_id=$key<br>";
		}
	}
}

?>


<br><br>
<P><a href="IADsort.html">Go Back</a></P>

</BODY>

</HTML>
	
	







