<?php
	session_start();
	require('../../config/global_config.php');
	require(ROOT_PATH.'include/Database.php');
	$db = Database::getInstance();
	$returnStr = '0';
	if(!ctype_digit($_POST['chktype']) || !ctype_digit($_POST['tblchk']) || strlen(trim($_POST['rewrite'])) <= 0){
		$returnStr = '2';
	}elseif($_POST['chktype'] == '2' && !ctype_digit($_POST['chkid'])){
		$returnStr = '2';
	}else{
		$arr_url = explode(' ',$_POST['rewrite']);
		if(is_array($arr_url)){
			$arr_finalUrl = array();
			foreach($arr_url as $value){ if(strlen(trim($value)) > 0){ $arr_finalUrl[] = $value; }}
			$_POST['rewrite'] = htmlentities(implode('-',$arr_finalUrl),ENT_QUOTES,'UTF-8');
			switch($_POST['tblchk']){
				case '1'://content
					if($_POST['chktype'] == '2'){
						$isUrlExist = $db->fetch_array($db->query('SELECT id AS ele_id FROM tbl_content_management WHERE url_rewrite = "'.$_POST['rewrite'].'" AND id != '.$_POST['chkid'].' LIMIT 1'));
					}
				break;
				case '2'://product category
					if($_POST['chktype'] == '2'){
						$isUrlExist = $db->fetch_array($db->query('SELECT id AS ele_id FROM tbl_pd_category WHERE url_rewrite = "'.$_POST['rewrite'].'" AND id != '.$_POST['chkid'].' LIMIT 1'));
					}else{
						$isUrlExist = $db->fetch_array($db->query('SELECT id AS ele_id FROM tbl_pd_category WHERE url_rewrite = "'.$_POST['rewrite'].'" LIMIT 1'));		
					}
				break;
				default: $isUrlExist['ele_id'] = '99';
			}
			if(ctype_digit($isUrlExist['ele_id'])){ $returnStr = '2'; }
			else{ $returnStr = '1'; }
			unset($arr_finalUrl,$isUrlExist);
		}
		unset($arr_url);		
	}
	echo $returnStr;
	unset($returnStr);
?>