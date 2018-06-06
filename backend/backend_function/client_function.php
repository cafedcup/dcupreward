<?php
	function client_deletion($db,$eleId){
		if(ctype_digit($eleId)){
			$db->delete_data('tbl_client_request_prefer_locat',' client_id='.$eleId);
			$db->delete_data('tbl_client_request_interest',' client_id='.$eleId);
			$db->delete_data('tbl_client_request_view',' client_id='.$eleId);
			$db->delete_data('tbl_client_request_style',' client_id='.$eleId);
			$db->delete_data('tbl_client_request_setting',' client_id='.$eleId);
			$db->delete_data('tbl_client',' id='.$eleId);
			
			/////removeDir(LST_DIR.$propId.'/');
		}
	}
?>