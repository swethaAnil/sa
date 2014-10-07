<?php

function process_checklist_bundle($bundleID,$order,$connection){

	$SQL = NULL;
	//detect SIP
	if(strpos("ip",$order['package'])){
		$voice_type = "SIP";
	}else{
		$voice_type = "other";
	}
	
	switch($bundleID){
	
		case "90001":
			$SQL = "SELECT checklist_$order[access_type] FROM settings";
			break;
		
		case "90002":
			$SQL = "SELECT checklist_$voice_type FROM settings";
			break;
			
		case "90003":
			$SQL = "SELECT checklist_VPN FROM settings";
			break;
			
		case "90004":
			$SQL = "SELECT checklist_WBU FROM settings";
			break;
		
	}
	
	if($SQL){
		$bundle_item_list = NULL;
		$checklist_bundle_result = mysql_query($SQL, $connection);
		$checklist_bundle = mysql_fetch_row($checklist_bundle_result);
		
		if($checklist_bundle[0]){
			$bundle_items = explode(",",$checklist_bundle[0]);
			foreach($bundle_items as $bundle_item){
			
				if($bundle_item_list == NULL){
						$bundle_item_list = $bundle_item;
				}else{
						$bundle_item_list .= "," . $bundle_item;
				}
			}
		}
	}
	
	return $bundle_item_list;
}


function build_checklist($order_num,$connection,$source){

	if($source == "v2_order_dashboard"){
		$adjusted_order_type = "v2_prechecks";
	}else{
		$order_data_SQL = "SELECT * FROM current_orders WHERE order_num = '$order_num' ORDER BY activation_date LIMIT 1";
		$order_data_result = mysql_query($order_data_SQL, $connection);
		
		while($row = mysql_fetch_array($order_data_result)){
			$order_data = $row;
		}

		$item_list = NULL;
		$question_count = 0;
		$checklist_html = NULL;
		$adjusted_order_type = preg_replace( '/\s+/', '', $order_data['order_type']);
		$adjusted_order_type = preg_replace( '/-/', '', $adjusted_order_type);
		$adjusted_order_type = preg_replace( '/\//', '', $adjusted_order_type);
	}
	
	$checklist_structure_SQL = "SELECT checklist_$adjusted_order_type FROM settings";
	$checklist_structure_result = mysql_query($checklist_structure_SQL, $connection);
	$checklist_structure = mysql_fetch_row($checklist_structure_result);
		
	if($checklist_structure[0]){
		$items = explode(",",$checklist_structure[0]);
		foreach($items as $item){
			
			if(substr($item,0,1) == 1){
				
				if($item_list == NULL){
					$item_list = $item;
					
					
				}else{
					$item_list .= "," . $item;
					
					
				}
			}elseif(substr($item,0,1) == 9){
				$result = NULL;
				$result = process_checklist_bundle($item,$order_data,$connection);
				if($result){
					if($item_list == NULL){
						$item_list = $result;
					}else{
						$item_list .= "," . $result;
					}
				}
			}
			
		}
		
		$checklist_questions_SQL = "SELECT * FROM checklist_questions WHERE qID in ($item_list) ORDER BY FIND_IN_SET(qID,\"$item_list\")";
		
		$checklist_questions_result = mysql_query($checklist_questions_SQL,$connection);
		
		return $checklist_questions_result;
		
	}else{
		echo "<br>No checklist structure found for order type: \"$order_data[order_type]\"<br>";
		exit();
	}
}

?>