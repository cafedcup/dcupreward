<?php
	function numdropdownLongPrintFormat($arr_pdf,$featSec,$name,$dropInfo,$longInfo){
		if(is_array($arr_pdf)){
			if(in_array($featSec,$arr_pdf)){
				return '<tr><td>'.$name.'</td><td>'.$dropInfo.'</td><td>'.$longInfo.'</td></tr>';				
			}
		}
	}
	
	function dropdownLongPrintFormat($arr_pdf,$featSec,$name,$dropInfo,$longInfo,$dropEle){
		if(is_array($arr_pdf)){
			if(in_array($featSec,$arr_pdf)){
				return '<tr><td>'.$name.'</td><td>'.$dropEle[$dropInfo]['name'].'</td><td>'.$longInfo.'</td></tr>';				
			}
		}
	}
		
	function yesnoradioLongPrintFormat($arr_pdf,$featSec,$name,$radioInfo,$longInfo){
		if(is_array($arr_pdf)){
			if(in_array($featSec,$arr_pdf)){
				$returnStr = '<tr><td>'.$name.'</td><td>';
				if($radioInfo == '1'){
					$returnStr .= YES;	
				}
				elseif($radioInfo == '2'){
					$returnStr .= NO;	
				}
				else{
					$returnStr .=  UNKNOWN;	
				}
				$returnStr .= '</td><td>'.$longInfo.'</td></tr>';
				return $returnStr;	
			}
		}	
	}
?>