<?php
	function validateEmailFormat($email){	
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$/",$email)){ return false; }
		return true;
	}
	
	function isValidateDate($insertDate,$diffDate = '0'){
		//this function can check the date format and if you fill in $diffDate, the function will compare $insertDate with $diffDate
		//$insertDate = Date
		//$diffDate numeric string
		//example: if today is 16 Jan and $diffDate = 2, the function will return true if $insertDate is more than or equal 18 Jan
		$arr_date = explode('/',$insertDate);
		if(count($arr_date) == 3 && (checkdate((int)$arr_date[1],(int)$arr_date[2],(int)$arr_date[0]) == 1)){
			if(ctype_digit($diffDate) && (int)$diffDate > 0){
				if(mktime(0,0,0,(int)$arr_date[1],(int)$arr_date[2],(int)$arr_date[0]) >= mktime(0,0,0,date('m'),date('d')+(int)$diffDate,date('Y'))){
					unset($arr_date);
					return true;
				}else{
					unset($arr_date);
					return false;	
				}
			}
			unset($arr_date);
			return true;
		}
		unset($arr_date);
		return false;
	}
	
	function getFileExtension($fileName){ return strtolower(substr($fileName,strrpos($fileName,'.'))); }
	
	function validateImageFormat($fileExt,$limitType = 1){
		switch($limitType){
			case 1:
				if($fileExt == '.jpg'){ return true; }
				return false;
			break;
			case 2:
				if($fileExt == '.jpg' || $fileExt == '.gif'){ return true; }
				return false;
			break;
			case 3:
				if($fileExt == '.gif' || $fileExt == '.png'){ return true; }
				return false;
			break;
			case 4:
				if($fileExt == '.jpg' || $fileExt == '.gif' || $fileExt == '.png'){ return true; }
				return false;
			break;
		}
	}
	
	function setValidUrlRewrite($url){
		$arr_url = explode(' ',$url);
		$arr_finalUrl = array();
		if(is_array($arr_url)){
			foreach($arr_url as $val){
				if(strlen(trim($val)) > 0){ $arr_finalUrl[] = $val; }
			}
		}
		return htmlentities(implode('-',$arr_finalUrl),ENT_QUOTES,'UTF-8');
	}
	
	function getNewsCategoryList($l='1'){
		switch($l){
			case '1': return array('1'=>array('url'=>'phueket-news','name'=>'ข่าวภูเก็ต'),'2'=>array('url'=>'hot-news','name'=>'ข่าวเด่น'),'3'=>array('url'=>'sports-news','name'=>'ข่าวกีฬา'),'4'=>array('url'=>'entertainment-news','name'=>'ข่าวบันเทิง'));
			break;
		}
	}
	
	
	function showAlertPopup($txt){
		echo '<!DOCTYPE HTML><html lang="en"><head><meta charset="UTF-8"></head><body><script language="javascript" type="text/javascript">alert("',$txt,'");window.close();</script></body></html>';exit();	
	}
?>