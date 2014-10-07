
<div id="title">SELECT AN<br>OPTION BELOW</div>

<div id="sidebar">
	
	<p><a href="#" onclick="displayConfig('tar','<?= $val_map['[VAR_10K_NAME]'] ?>')"><?= $val_map['[VAR_10K_NAME]'] ?></a></p>

	<?php if($val_map['[VAR_ACCESS]'] == "T1"): ?>
		<a href="#" onclick="displayConfig('tar-post','<?= $val_map['[VAR_10K_NAME]'] ?><br>(Post-Install)')"><?= $val_map['[VAR_10K_NAME]'] ?><br>(Post-Install)</a></p>
	<?php endif ?>

	<?php if($val_map['[VAR_ACCESS]'] == "FIBER" && $val_map['[VAR_FIBER_PROVIDER]'] == "FIBR1"): ?>
		<p><a href="#" onclick="displayConfig('tar-secondary','<?= $val_map['[VAR_10K_NAME_SECONDARY]'] ?>')"><?= $val_map['[VAR_10K_NAME_SECONDARY]'] ?></a></p>
		<p><a href="#" onclick="displayConfig('bas_cli','BAS CLI')">BAS CLI</a></p>
	<?php endif ?>
	
	<?php if($val_map['[VAR_ACCESS]'] == "EFM"): ?>
		<p><a href="#" onclick="displayConfig('efm-switch','EFM SWITCH')">EFM SWITCH</a></p>
		<p><a href="#" onclick="displayConfig('efm-cpe','EFM CPE')">EFM CPE</a></p>
	<? endif ?>
	
	<p><a href="#" onclick="displayConfig('ios','IOS')">IOS (flash)</a></p>
	
	<?php if($val_map['[VAR_NUM_DEVICES]'] == 2): ?>
		<p><a href="#" onclick="displayConfig('cpe_device','<?= $val_map['[VAR_IADNAME]'] ?> (Primary)<br>')"><?= $val_map['[VAR_IADNAME]'] ?> (Primary)</a></p>
		<p><a href="#" onclick="displayConfig('cpe_device_secondary','<?= $val_map['[VAR_IADNAME_SECONDARY]'] ?> (Secondary)<br>')"><?= $val_map['[VAR_IADNAME_SECONDARY]'] ?> (Secondary)</a></p>
	<?php else: ?>
		<p><a href="#" onclick="displayConfig('cpe_device','<?= $val_map['[VAR_IADNAME]'] ?><br>')"><?= $val_map['[VAR_IADNAME]'] ?></a></p>
	<?php endif ?>
	
	<div id="details">
		<strong><u>Config Details</u></strong><br>
		Device: <?= $val_map['[VAR_DEVICE]'] ?><br>
		Access: <?= $val_map['[VAR_ACCESS]'] . " (" . $val_map['[VAR_NUM_CIRCUITS]'] . ")" ?><br>
		PCA: <?= $val_map['[VAR_PCA]'] ?><br>
		Voice: <?= $val_map['[VAR_VOICE_TYPE]'] ?>
	</div>
		
</div>


<div id="configframe">

<div id="tar"  style="visibility:hidden;display:none;">
<?php

$lockout=0;
if($error == 0 && $lockout == 0){
	echo "<span style=\"color:#CC0000;font-family:courier;font-size:80%;\">";
	//PRINT 10K config
	if($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] == 1){
		$module = $val_map['[VAR_ACCESS]'] . "_SINGLE_TAR_module";
		$i = 1;
		$configFunctions->print_serial_port_module($val_map,$module,$val_map['[VAR_T1_INTERFACE' . $i . ']'],$val_map['[VAR_T1_CID' . $i . ']'],$val_map['[VAR_T1_SERIAL' . $i . ']']);
	}elseif($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_MULTI_TAR_module";
		$configFunctions->print_module($val_map,$module);
	}elseif($val_map['[VAR_ACCESS]'] == "FIBER" && $val_map['[VAR_FIBER_PROVIDER]'] == "FIBR1"){  //DARK FIBER TAR MODULE
		$module = "DARK_FIBER_PRIMARY_TAR_module";
		$configFunctions->print_module($val_map,$module);
	}elseif($val_map['[VAR_ACCESS]'] == "FIBER" && $val_map['[VAR_FIBER_PROVIDER]'] == "FIBR5"){  //ZAYO TAR MODULE
		$module = "ZAYO_FIBER_TAR_module";
		$configFunctions->print_module($val_map,$module);
	}else{
		$module = $val_map['[VAR_ACCESS]'] . "_TAR_module";
		$configFunctions->print_module($val_map,$module);
	}
	
	if($val_map['[VAR_NUM_DEVICES]'] == 2){
		$module = "SECOND_SERIAL_TAR_module";
		$configFunctions->print_module($val_map,$module);
	}
	
	
	//PRINT SERIAL INTERFACE ACCESS CONFIGS
	if($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_MULTI_TAR_serial_port_module";
		for($i=1;$i<=$val_map['[VAR_NUM_CIRCUITS]'];$i++){
			$configFunctions->print_serial_port_module($val_map,$module,$val_map['[VAR_T1_INTERFACE' . $i . ']'],$val_map['[VAR_T1_CID' . $i . ']'],$val_map['[VAR_T1_SERIAL' . $i . ']']);
		}
	}
	echo "</span>";
	
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END 10K CONFIG  -->

<div id="tar-post"  style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	
	echo "<span style=\"color:#CC0000;font-family:courier;font-size:80%;\">";
	
	//PRINT 10K POST-INSTALL config
	if($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] == 1){
		$module = $val_map['[VAR_ACCESS]'] . "_SINGLE_TAR_POST_module";
		$i = 1;
		$configFunctions->print_serial_port_module($val_map,$module,$val_map['[VAR_T1_INTERFACE' . $i . ']'],$val_map['[VAR_T1_CID' . $i . ']'],$val_map['[VAR_T1_SERIAL' . $i . ']']);
	}elseif($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_MULTI_TAR_POST_module";
		$configFunctions->print_module($val_map,$module);
	}
	
	//PRINT SERIAL INTERFACE POST-INSTALL CONFIGS
	if($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_MULTI_TAR_POST_serial_port_module";
		for($i=1;$i<=$val_map['[VAR_NUM_CIRCUITS]'];$i++){
			$configFunctions->print_serial_port_module($val_map,$module,$val_map['[VAR_T1_INTERFACE' . $i . ']'],$val_map['[VAR_T1_CID' . $i . ']'],$val_map['[VAR_T1_SERIAL' . $i . ']']);
		}
	}
	
	echo "</span>";
		
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END 10K POST CONFIG  -->

<div id="tar-secondary"  style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	echo "<span style=\"color:#CC0000;font-family:courier;font-size:80%;\">";
	//PRINT 10K config
	
	$module = "DARK_FIBER_SECONDARY_TAR_module";
	$configFunctions->print_module($val_map,$module);
	

}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END 10K SECONDARY CONFIG  -->

<div id="bas_cli"  style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	echo "<span style=\"color:#CC0000;font-family:courier;font-size:80%;\">";
	
	//PRINT BAS CLI config
	$module = "DARK_FIBER_eservice_cli_module";
	$configFunctions->print_module($val_map,$module);
	
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END BAS CLI CONFIG  -->



<div id="efm-switch" style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	
	echo "<span style=\"color:#CC0000;font-family:courier;font-size:80%;\">";
	
	//PRINT EFM SWITCH UNIVERSAL CONFIG
	if($val_map['[VAR_ACCESS]'] == "EFM"){
		$module = $val_map['[VAR_ACCESS]'] . "_SWITCH_universal_module";
		$configFunctions->print_module($val_map,$module);
	}
	
	//PRINT EFM SWITCH MLP CONFIG
	if($val_map['[VAR_ACCESS]'] == "EFM" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_SWITCH_MLP_module";
		for($i=1;$i<=$val_map['[VAR_NUM_CIRCUITS]'];$i++){
			$configFunctions->print_mlp_module($val_map,$module,$i);
		}
	}
	
	echo "</span>";
		
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>   <!-- END EFM SWITCH CONFIG  -->

<div id="efm-cpe" style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	
	echo "<span style=\"color:darkgreen;font-family:courier;font-size:80%;\">";
	
	//PRINT EFM CPE UNIVERSAL CONFIG
	if($val_map['[VAR_ACCESS]'] == "EFM"){
		$module = $val_map['[VAR_ACCESS]'] . "_CPE_universal_module";
		$configFunctions->print_module($val_map,$module);
	}
	
	//PRINT EFM CPE MLP CONFIG
	if($val_map['[VAR_ACCESS]'] == "EFM" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_CPE_MLP_ENABLE_module";
		for($i=1;$i<=$val_map['[VAR_NUM_CIRCUITS]'];$i++){
			$configFunctions->print_mlp_module($val_map,$module,$i);
			$next_port = $i + 1;
		}
		
		if($next_port <= 8){
			$module = $val_map['[VAR_ACCESS]'] . "_CPE_MLP_DISABLE_module";
			for($i=$next_port;$i<=8;$i++){
				$configFunctions->print_mlp_module($val_map,$module,$i);
			}
		}
	}
	
	echo "</span>";
		
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>   <!-- END EFM CPE CONFIG  -->

<div id="ios"  style="visibility:hidden;display:none;">
<?php
$lockout=0;
if($error == 0 && $lockout == 0){
	echo "<span style=\"font-family:arial;font-size:80%;\">";
	$configFunctions->print_module($val_map, 'ios_module');
	echo "</span>";
}else{
	echo "NO CONFIGS FOR YOU!";
}
?>
</div>  <!--  END DEVICE IOS CONFIG  -->

<div id="cpe_device"  style="visibility:hidden;display:none;">
<?php

if($error == 0){
	
	echo "<span style=\"color:darkgreen;font-family:courier;font-size:80%;\">";
	
	//PRINT UNIVERSAL DEVICE CONFIG
	$module = $val_map['[VAR_DEVICE]'] . "_universal_module";
	$configFunctions->print_module($val_map,$module);
	
	for($i=1;$i<=$val_map['[VAR_NUM_CIRCUITS]'];$i++){
		$T1_interfaces[] = $val_map['[VAR_T1_INTERFACE' . $i . ']'];
	}	

	//PRINT WIC ACTIVATE CONFIG
	if(count($T1_interfaces) > 0){
		$configFunctions->print_wic_module($T1_interfaces);
	}
	
	//PRINT MPLS QOS CONFIG

	if($val_map['[VAR_MPLS]']){	
		$module = "MPLS_QOS_ACL_module";
		$configFunctions->print_module($val_map,$module);
	}
	
	//PRINT DEVICE ACCESS CONFIG
	if($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] == 1){
		$module = $val_map['[VAR_ACCESS]'] . "_SINGLE_access_module";
	}elseif($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_MULTI_access_module";
	}elseif($val_map['[VAR_ACCESS]'] == "FIBER" && $val_map['[VAR_FIBER_PROVIDER]'] == "FIBR1"){
		$module = "DARK_FIBER_access_module";
	}else{
		$module = $val_map['[VAR_ACCESS]'] . "_access_module";
	}
	$configFunctions->print_module($val_map,$module);

	//PRINT SERIAL INTERFACE ACCESS CONFIGS
	if($val_map['[VAR_ACCESS]'] == "T1" && $val_map['[VAR_NUM_CIRCUITS]'] > 1){
		$module = $val_map['[VAR_ACCESS]'] . "_MULTI_serial_port_module";
		for($i=1;$i<=$val_map['[VAR_NUM_CIRCUITS]'];$i++){
			$configFunctions->print_serial_port_module($val_map,$module,$val_map['[VAR_T1_INTERFACE' . $i . ']'],$val_map['[VAR_T1_CID' . $i . ']'],$val_map['[VAR_T1_SERIAL' . $i . ']']);
		}
	}

	//PRINT BANNER MODULE
	$module = "banner_module";
	$configFunctions->print_module($val_map,$module);
	
	//PRINT CROSSOVER MODULE FOR DUAL DEVICE CONFIGURATION
	if($val_map['[VAR_NUM_DEVICES]'] == 2){
		$module = "SECOND_DEVICE_crossover_module";
		$configFunctions->print_module($val_map,$module);
	}
	
	//PRINT DATA CONFIG
	if($val_map['[VAR_VOICE_TYPE]'] != "VOPRI"){
		if($val_map['[VAR_ACCESS]'] == "EFM"){
			$module = "EFM_" . $val_map['[VAR_DATA_TYPE]'] . "_data_module";
		}else{
			$module = "STD_" . $val_map['[VAR_DATA_TYPE]'] . "_data_module";
		}
		$configFunctions->print_module($val_map,$module);
	}
		
		
	if($_POST['submit'] == "Activation Config"){

		//PRINT UNIVERSAL VOICE CONFIG
		$module = $val_map['[VAR_PCA]'] . "_" . $val_map['[VAR_VOICE_TYPE]'] . "_universal_voice_module";
		$configFunctions->print_module($val_map,$module);

		//PRINT VOICE TRUNK CONFIGS
		if($val_map['[VAR_NUM_TRUNK_GROUPS]']){
			if($val_map['[VAR_VOICE_TYPE]'] == "TCPS"){
				$module = $val_map['[VAR_PCA]'] . "_PRI_" . $val_map['[VAR_NUM_TRUNK_GROUPS]'] . "_trunk_module";
			}else{
				$module = $val_map['[VAR_PCA]'] . "_" . $val_map['[VAR_VOICE_TYPE]'] . "_" . $val_map['[VAR_NUM_TRUNK_GROUPS]'] . "_trunk_module";
			}
			$configFunctions->print_module($val_map,$module);
		}

		//PRINT ANALOG VOICE-PORT CONFIGS
		if($val_map['[VAR_NUM_ANALOGS]']){
			if($val_map['[VAR_VOICE_TYPE]'] == "SIP"){
				$module = $val_map['[VAR_PCA]'] . "_" . $val_map['[VAR_VOICE_TYPE]'] . "_voice_port_module";
				for($i=0;$i<$val_map['[VAR_NUM_ANALOGS]'];$i++){
					$configFunctions->print_voice_port_module($val_map,$module,$i);
				}
			}elseif($val_map['[VAR_VOICE_TYPE]'] == "PRI" || $val_map['[VAR_VOICE_TYPE]'] == "VOPRI"){
				$module = $val_map['[VAR_PCA]'] . "_MIXED_ANALOG_voice_port_module";
				for($i=0;$i<$val_map['[VAR_NUM_ANALOGS]'];$i++){
					$configFunctions->print_voice_port_module($val_map,$module,$i);
				}
			}else{
				$module = $val_map['[VAR_PCA]'] . "_ANALOG_voice_port_module";
				$line_marker = 0;
				for($i=0;$i<$val_map['[VAR_NUM_ANALOGS]'];$i++){
					if($i < $val_map['[VAR_NUM_FXS_PORTS]']){
						$configFunctions->print_voice_port_module($val_map,$module,$i);
						$line_marker = $i+2;
					}else{
						break;
					}
				}
			}
			//SHUT UNUSED VOICE PORTS
			$configFunctions->shut_unused_voice_ports($val_map,$model,$val_map['[VAR_NUM_FXS_PORTS]'],$val_map['[VAR_NUM_ANALOGS]']);
		}
					
	}
	echo "</span>";
}else{
	echo "NO CONFIGS FOR YOU!";
}
 ?>
 </div>  <!--  END DEVICE CONFIG DIV  -->
 
 
<div id="cpe_device_secondary"  style="visibility:hidden;display:none;">
<?php

if($error == 0){
	
	echo "<span style=\"color:darkgreen;font-family:courier;font-size:80%;\">";
	
	//PRINT UNIVERSAL SECONDARY DEVICE CONFIG
	$module = $second_device . "_universal_secondary_module";
	$configFunctions->print_module($val_map,$module);
	
	
	//PRINT SECONDARY BANNER MODULE
	$module = "secondary_banner_module";
	$configFunctions->print_module($val_map,$module);
	
		
	if($_POST['submit'] == "Activation Config"){

		//PRINT UNIVERSAL VOICE CONFIG
		$module = $val_map['[VAR_PCA]'] . "_" . $val_map['[VAR_VOICE_TYPE]'] . "_universal_secondary_voice_module";
		$configFunctions->print_module($val_map,$module);


		//PRINT ANALOG VOICE-PORT CONFIGS
		$remaining_analogs = $val_map['[VAR_NUM_ANALOGS]'] - $line_marker + 1;
		
		
		if($remaining_analogs > 0){
			$module = $val_map['[VAR_PCA]'] . "_ANALOG_voice_port_module";
			for($i=0;$i<$remaining_analogs;$i++){
				$configFunctions->print_voice_port_module($val_map,$module,$i,$line_marker);
				$line_marker++;
			}
		}
		
		$num_analogs_second = $val_map['[VAR_NUM_ANALOGS]'] - $val_map['[VAR_NUM_FXS_PORTS]'];
		
		//SHUT UNUSED VOICE PORTS
		$configFunctions->shut_unused_voice_ports($val_map,$second_model,$val_map['[VAR_NUM_FXS_PORTS_SECOND]'],$num_analogs_second);
	}
		
	echo "</span>";
		
}else{
	echo "NO CONFIGS FOR YOU!";
}

 ?>
 </div>  <!--  END SECONDARY DEVICE CONFIG DIV  -->
 </div>

 <iframe id='displayFrame' width="85%" height="92%" frameborder="0" marginwidth="20px" style="margin-left:150px;border-left:thick double #333333;">&nbsp;</iframe>
