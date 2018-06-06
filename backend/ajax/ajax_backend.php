<?php
	$isAjax = 1;
	require('./../accessControl.php');
	require(ROOT_PATH.'include/cacheManagement.php');
	$arr_return = array();
	switch($_POST['type']){
		case '1':// get reply description
			if(ctype_digit($_POST['reid'])){
				switch($_POST['lang']){
					case 2: case 3: break;
					default: $_POST['lang'] = 1;
				}
				$rec = $db->fetch_array($db->query('SELECT descrip_lang_'.$_POST['lang'].' AS descrip FROM tbl_reply WHERE status=1 AND id='.$_POST['reid'].' LIMIT 1'));								
				$arr_return['descrip'] = html_entity_decode($rec['descrip'],ENT_QUOTES,'UTF-8');
			}else{ $arr_return['descrip'] = ''; }
		break;
		/*case '2'://get webboard subcategory by category id
			if(ctype_digit($_POST['catid'])){
				$arr_return = getWebboardSubCategoryByCategoryListCache($db,$_POST['catid']);
			}
		break;
		*/
		default: $arr_return['return_result'] = '0';
	}
	echo json_encode($arr_return);
?>