<?php
	function setPaginate($amountOfElements,$curPage,$addition=array(),$isRewrite = '0'){
		$elementPerPage = 50;
		$pageIndexAmount = 5;//odd number only
		
		if(isset($addition['elementPerPage']) && ctype_digit($addition['elementPerPage'])){
			$elementPerPage = intval($addition['elementPerPage']);
		}
		$preTxt = $centerTxt = $nextTxt = '';
		$startLimit = 0;
		if(intval($amountOfElements) > $elementPerPage){
			$lastPageIndex = ceil(intval($amountOfElements)/$elementPerPage);
			$leftElementAmount = $rightElementAmount = floor($pageIndexAmount/2);
			if(!ctype_digit($curPage) || intval($curPage) <= 0){ $curPage = 1;
			}else{ $curPage = intval($curPage); }
			if($curPage >= $lastPageIndex){ $curPage = $lastPageIndex; }
			while($curPage-$leftElementAmount <= 0){
				$leftElementAmount--;
				$rightElementAmount++;
			}
			while($curPage+$rightElementAmount>$lastPageIndex){
				$leftElementAmount++;
				$rightElementAmount--;
			}
			$startLimit = $elementPerPage*($curPage-1);
			if($startLimit < 0){ $startLimit = 0; }
			if(!isset($addition['firstWord'])){ $addition['firstWord'] = 'First&nbsp;'; }
			if(!isset($addition['prevWord'])){ $addition['prevWord'] = 'Prev&nbsp;'; }
			if(!isset($addition['nextWord'])){ $addition['nextWord'] = '&nbsp;Next'; }
			if(!isset($addition['lastWord'])){ $addition['lastWord'] = '&nbsp;Last'; }
		
			
			$preTxt = '<a href="?p=1'.$addition['action'].'" >'.$addition['firstWord'].'</a>';
			$preTxt .= '<a href="?p='.($curPage-1).$addition['action'].'" >'.$addition['prevWord'].'</a>';
			$nextTxt = '<a href="?p='.($curPage+1).$addition['action'].'" >'.$addition['nextWord'].'</a>';			
			$nextTxt .= '<a href="?p='.$lastPageIndex.$addition['action'].'" >'.$addition['lastWord'].'</a>';	
										
		
			if($curPage == 1){ $preTxt = ''; }
			if($curPage == $lastPageIndex){ $nextTxt = ''; }
			while($leftElementAmount > 0){
				if(($curPage-$leftElementAmount) > 0){
					$centerTxt .= '<a class="number" href="?p='.($curPage-$leftElementAmount).$addition['action'].'" >'.($curPage-$leftElementAmount).'</a>';
										
				}		
				$leftElementAmount--;
			}
			$centerTxt .= $curPage;
			for($j=1;$j <= $rightElementAmount;$j++){
				$centerTxt .= '<a class="number" href="?p='.($curPage+$j).$addition['action'].'" >'.($curPage+$j).'</a>';		
																
			}	
		}
		$result['firstEleNoOfPage'] = ($curPage*$elementPerPage)-($elementPerPage-1);
		$result['limitSQL'] = "$startLimit,$elementPerPage"; 
		$result['paginate'] = $preTxt.$centerTxt.$nextTxt;
		unset($elementPerPage,$pageIndexAmount,$preTxt,$centerTxt,$nextTxt,$startLimit);
		return $result;
	}
?>