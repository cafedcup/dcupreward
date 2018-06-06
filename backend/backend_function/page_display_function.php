<?php
	function setPageLimitVal($pageLimit){
		if(ctype_digit($pageLimit)){
			switch($pageLimit){
				case '10':
				case '20':
				case '50':
				case '100':
					return $pageLimit;
				break;
				default:
					return '10';
			}
		}
		else{
			return '10';
		}
	}
	
	function getPageLimitBlock($curVal){
		echo ELE_DISPLAY,'&nbsp;:&nbsp;&nbsp;<select name="plm" class="smallinputbox">
		<option value="10"';
		if($curVal == '10'){
			echo 'selected="selected" ';
		}
		echo '>10</option>
		<option value="20"';
		if($curVal == '20'){
			echo 'selected="selected" ';
		}
		echo '>20</option>
		<option value="50"';
		if($curVal == '50'){
			echo 'selected="selected" ';
		}
		echo '>50</option>
		<option value="100"';
		if($curVal == '100'){
			echo 'selected="selected" ';
		}
		echo '>100</option></select>&nbsp;&nbsp;';
	}
?>