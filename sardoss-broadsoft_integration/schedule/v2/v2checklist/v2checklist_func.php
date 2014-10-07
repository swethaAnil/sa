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
			$SQL = "SELECT structure FROM checklist_structure WHERE listName = '" . $order[access_type] . "_BUNDLE'";
			break;
		
		case "90002":
			$SQL = "SELECT structure FROM checklist_structure WHERE listName = '" . $voice_type . "_BUNDLE'";
			break;
			
		case "90003":
			$SQL = "SELECT structure FROM checklist_structure WHERE listName = 'VPN_BUNDLE'";
			break;
			
		case "90004":
			$SQL = "SELECT structure FROM checklist_structure WHERE listName = 'WBU_BUNDLE'";
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


function build_checklist($order_num,$connection){

	//get order data
	$order_data_SQL = "SELECT * FROM current_v2_orders WHERE order_num = '$order_num' ORDER BY activation_date LIMIT 1";
	$order_data_result = mysql_query($order_data_SQL, $connection);
	
	while($row = mysql_fetch_array($order_data_result)){
		$order_data = $row;
	}
	
	//get checklist structure from structure table
	$checklist_structure_SQL = "SELECT structure FROM checklist_structure WHERE listName = 'v2_prechecks'";
	$checklist_structure_result = mysql_query($checklist_structure_SQL, $connection);
	$checklist_structure = mysql_fetch_row($checklist_structure_result);
	
	//build checklist from checklist structure
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
		
		//return CSV list of question IDs
		return $item_list;
		
	}else{
		echo "<br><b>There is no checklist structure found for this order type.</b><br><br>";
		exit();
	}
}

?>