<?php
	function setPaginate($amountOfElements,$curPage,$addition=array(),$isRewrite = '0'){
		$elementPerPage = 6;
		$pageIndexAmount = 5;//odd number only		
		
		if(isset($addition['elementPerPage']) && ctype_digit($addition['elementPerPage'])){
			$elementPerPage = intval($addition['elementPerPage']);
		}//end if
		
		$preTxt = $centerTxt = $nextTxt = '';
		$startLimit = 0;
		
		if(intval($amountOfElements) > $elementPerPage){
			$lastPageIndex = ceil(intval($amountOfElements)/$elementPerPage);
			
			$leftElementAmount = $rightElementAmount = floor($pageIndexAmount/2);
				
			if(!ctype_digit($curPage) || intval($curPage) <= 0){
				$curPage = 1;
			}//end if
			else{
				$curPage = intval($curPage);
			}
		
			if($curPage >= $lastPageIndex){
				$curPage = $lastPageIndex;
			}						
			
			while($curPage-$leftElementAmount <= 0){
				$leftElementAmount--;
				$rightElementAmount++;					
			}//end while
			
			while($curPage+$rightElementAmount>$lastPageIndex){
				$leftElementAmount++;
				$rightElementAmount--;
			}//end while
			
			$startLimit = $elementPerPage*($curPage-1);
			if($startLimit < 0){
				$startLimit = 0;	
			}//end if
			if(!isset($addition['firstWord'])){
				$addition['firstWord'] = 'First';
			}//end if			
			if(!isset($addition['prevWord'])){
				$addition['prevWord'] = 'Prev';
			}//end if
			if(!isset($addition['nextWord'])){
				$addition['nextWord'] = 'Next';
			}//end if
			if(!isset($addition['lastWord'])){
				$addition['lastWord'] = 'Last';
			}//end if
		
			if($isRewrite == '1'){
				$preTxt = '<a class="page_link"  href="'.$addition['action'].'p/1" >'.$addition['firstWord'].'</a>';
				$preTxt .= '<a class="page_link"  href="'.$addition['action'].'p/'.($curPage-1).'" >'.$addition['prevWord'].'</a>';
				$nextTxt = '<a class="page_link"  href="'.$addition['action'].'p/'.($curPage+1).'" >'.$addition['nextWord'].'</a>';			
				$nextTxt .= '<a class="page_link"  href="'.$addition['action'].'p/'.$lastPageIndex.'" >'.$addition['lastWord'].'</a>';	
			}
			elseif($isRewrite == '2'){
				$preTxt = '<a class="page_link"  href="'.$addition['action'].'-p-1.html" >'.$addition['firstWord'].'</a>';
				$preTxt .= '<a class="page_link"  href="'.$addition['action'].'-p-'.($curPage-1).'.html" >'.$addition['prevWord'].'</a>';
				$nextTxt = '<a class="page_link"  href="'.$addition['action'].'-p-'.($curPage+1).'.html" >'.$addition['nextWord'].'</a>';			
				$nextTxt .= '<a class="page_link"  href="'.$addition['action'].'-p-'.$lastPageIndex.'.html" >'.$addition['lastWord'].'</a>';	
			}
			else{
				$preTxt = '<a class="page_link"  href="?p=1'.$addition['action'].'" >'.$addition['firstWord'].'</a>';
				$preTxt .= '<a class="page_link"  href="?p='.($curPage-1).$addition['action'].'" >'.$addition['prevWord'].'</a>';
				$nextTxt = '<a class="page_link"  href="?p='.($curPage+1).$addition['action'].'" >'.$addition['nextWord'].'</a>';			
				$nextTxt .= '<a class="page_link"  href="?p='.$lastPageIndex.$addition['action'].'" >'.$addition['lastWord'].'</a>';	
			}							
		
			if($curPage == 1){					
				$preTxt = '';				
			}
			if($curPage == $lastPageIndex){
				$nextTxt = '';				
			}
			
			while($leftElementAmount > 0){
				if(($curPage-$leftElementAmount) > 0){
					if($isRewrite == '1'){
						$centerTxt .= '<a class="page_link"  href="'.$addition['action'].'p/'.($curPage-$leftElementAmount).'" >'.($curPage-$leftElementAmount).'</a>';	
					}
					elseif($isRewrite == '2'){
						$centerTxt .= '<a class="page_link"  href="'.$addition['action'].'-p-'.($curPage-$leftElementAmount).'.html" >'.($curPage-$leftElementAmount).'</a>';	
					}					
					else{
						$centerTxt .= '<a class="page_link"  href="?p='.($curPage-$leftElementAmount).$addition['action'].'" >'.($curPage-$leftElementAmount).'</a>';	
					}					
				}//end if				
				$leftElementAmount--;
			}
			
			$centerTxt .= '<span class="page_notlink">'.$curPage.'</span>';
			
			for($j=1;$j <= $rightElementAmount;$j++){
				if($isRewrite == '1'){
					$centerTxt .= '<a class="page_link"  href="'.$addition['action'].'p/'.($curPage+$j).'" >'.($curPage+$j).'</a>';	
				}
				elseif($isRewrite == '2'){
					$centerTxt .= '<a class="page_link"  href="'.$addition['action'].'-p-'.($curPage+$j).'.html" >'.($curPage+$j).'</a>';	
				}
				else{
					$centerTxt .= '<a class="page_link"  href="?p='.($curPage+$j).$addition['action'].'" >'.($curPage+$j).'</a>';		
				}													
			}//end for				
		}//end if
		
		$result['firstEleNoOfPage'] = ($curPage*$elementPerPage)-($elementPerPage-1);
		$result['limitSQL'] = "$startLimit,$elementPerPage"; 
		$result['paginate'] = $preTxt.$centerTxt.$nextTxt;
		unset($elementPerPage,$pageIndexAmount,$preTxt,$centerTxt,$nextTxt,$startLimit);
		return $result;
	}//end function
?>