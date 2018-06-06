<?php
	function property_deletion($db,$propId){
		if(ctype_digit($propId)){
			$db->delete_data('tbl_prop_client_intro_history',' prop_id='.$propId);
			$db->delete_data('tbl_prop_pricing',' prop_id='.$propId);
			$db->delete_data('tbl_prop_descrip',' prop_id='.$propId);
			$db->delete_data('tbl_prop_bathroom',' prop_id='.$propId);
			$db->delete_data('tbl_prop_bedroom',' prop_id='.$propId);
			$db->delete_data('tbl_prop_collection',' prop_id='.$propId);
			$db->delete_data('tbl_prop_interest',' prop_id='.$propId);
			$db->delete_data('tbl_prop_setting',' prop_id='.$propId);
			$db->delete_data('tbl_prop_view',' prop_id='.$propId);
			$db->delete_data('tbl_prop_style',' prop_id='.$propId);
			$db->delete_data('tbl_prop_feature',' prop_id='.$propId);
			$db->delete_data('tbl_prop_image',' prop_id='.$propId);
			$db->delete_data('tbl_property',' id='.$propId);
			removeDir(LST_DIR.$propId.'/');
		}
	}
?>