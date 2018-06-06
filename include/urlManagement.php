<?php
	function urlAnalysis($requestURI,$startIndex){
		$arr_return = $arr_uri = array();
		$arr_uri = explode('/',$requestURI);
		array_shift($arr_uri);
		if($startIndex > 0){ for($i=1;$i<=$startIndex;$i++){ array_shift($arr_uri); } }
		if($arr_uri[0] == 'en'){
			$arr_return['lang'] = '2';
			$arr_return['linkURL'] = ROOT_URL.'en/';
			array_shift($arr_uri);
		}else{
			$arr_return['lang'] = '1';
			$arr_return['linkURL'] = ROOT_URL;
		}
		
		//echo 'count($arr_uri): '.count($arr_uri);
		switch(count($arr_uri)){
			case '1':
				if(strlen(trim($arr_uri[0])) > 0){	
					$htmlExt = strrpos($arr_uri[0],'.html');
					//echo ' @@@@ htmlExt: '.$htmlExt;
					if($htmlExt !== false){
						$arr_curPage = explode('-',substr($arr_uri[0],0,$htmlExt));
						if($arr_curPage[count($arr_curPage)-2] == 'p' && ctype_digit($arr_curPage[count($arr_curPage)-1])){
							$arr_return['curPage'] = array_pop($arr_curPage);
							array_pop($arr_curPage);
							$arr_return['targetPage'] = implode('-',$arr_curPage);
						}else{ $arr_return['targetPage'] = implode('-',$arr_curPage); }
						unset($arr_curPage);
					}
					else{ 
						$arr_return['targetPage'] = '404'; 
					}				
					unset($htmlExt);
				}else{ $arr_return['targetPage'] = ''; }		
			break;
			case '2':
				$arr_return['targetPage'] = $arr_uri[0];
				array_shift($arr_uri);
				if(strlen(trim($arr_uri[0])) > 0){	
					$htmlExt = strrpos($arr_uri[0],'.html');
					if($htmlExt!== false){
						$arr_curPage = explode('-',substr($arr_uri[0],0,$htmlExt));						
						if($arr_curPage[count($arr_curPage)-2] == 'p' && ctype_digit($arr_curPage[count($arr_curPage)-1])){
							$arr_return['curPage'] = array_pop($arr_curPage);
							array_pop($arr_curPage);
							$arr_return['detailPage'] = implode('-',$arr_curPage);
						}else{ $arr_return['detailPage'] = implode('-',$arr_curPage); }
						unset($arr_curPage);
					}else{ $arr_return['targetPage'] = '404'; }
					unset($htmlExt);
				}else{ $arr_return['targetPage'] = '404'; }			
			break;
			default: $arr_return['targetPage'] = '';
		}
		unset($arr_uri);
		return $arr_return;
	}
?>