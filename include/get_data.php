<?php 
		$company_infoarr = array();
		$result = $db->query('SELECT * FROM ntb_company WHERE 1');
		while($rec = $db->fetch_array($result)){
			$comp_infoarr[$rec['id']]['id'] = $rec['id'];
			$comp_infoarr[$rec['id']]['status'] = $rec['status'];
			$comp_infoarr[$rec['id']]['prov_id'] = $rec['prov_id'];
			$comp_infoarr[$rec['id']]['com_type'] = $rec['com_type'];
			$comp_infoarr[$rec['id']]['name'] = $rec['name_lang_1'];
			$comp_infoarr[$rec['id']]['contact_name'] = $rec['contact_name_lang_1'];
			$comp_infoarr[$rec['id']]['phone'] = $rec['phone'];
			$comp_infoarr[$rec['id']]['mobile'] = $rec['mobile'];
			$comp_infoarr[$rec['id']]['fax'] = $rec['fax'];
			$comp_infoarr[$rec['id']]['email_1'] = $rec['email_1'];
			$comp_infoarr[$rec['id']]['email_2'] = $rec['email_2'];
			$comp_infoarr[$rec['id']]['website'] = $rec['website'];
			$comp_infoarr[$rec['id']]['address'] = $rec['address_lang_1'];
			$comp_infoarr[$rec['id']]['descrip'] = $rec['descrip_lang_1'];
			$comp_infoarr[$rec['id']]['logo_img'] = $rec['logo_img'];
			$comp_infoarr[$rec['id']]['img'] = explode(',',$rec['img']);
		}
		$db->free_result($result);
		unset($rec,$result);
?>