<?php	
	function dateConvertToNormal($inputDate,$lang=1,$dateWithTime=0){
		$arr_input = explode(' ',$inputDate);
		$arr_date = explode('-',$arr_input[0]);
		switch($dateWithTime){
			case 0:
				if(count($arr_date) == 3){
					return intval($arr_date[2]).' '.getMonthName($arr_date[1],$lang).' '.getYear($arr_date[0],$lang);
				}else{ return $inputDate; }
			break;
			case 1:
				if(count($arr_date) == 3){
					return intval($arr_date[2]).' '.getMonthName($arr_date[1],$lang).' '.getYear($arr_date[0],$lang).' '.$arr_input[1];
				}else{ return $inputDate; }
			break;
		}
	}
	
	function getYear($y,$lang){
		if($lang == 2){ return $y; }else{ if(ctype_digit($y)){ return intval($y)+543; }else{ return $y; }}
	}
	
	function getMonthName($month,$lang){
		switch($lang){
			case '2':
				switch($month){
					case '01': return 'January'; break;
					case '02': return 'February'; break;
					case '03': return 'March'; break;
					case '04': return 'April'; break;
					case '05': return 'May'; break;
					case '06': return 'June'; break;
					case '07': return 'July'; break;
					case '08': return 'August'; break;
					case '09': return 'September'; break;
					case '10': return 'October'; break;
					case '11': return 'November'; break;
					case '12': return 'December'; break;
					default: return $month;
				}
			default:	
				switch($month){
					case '01': return 'มกราคม'; break;
					case '02': return 'กุมภาพันธ์'; break;	
					case '03': return 'มีนาคม'; break;	
					case '04': return 'เมษายน'; break;	
					case '05': return 'พฤษภาคม'; break;	
					case '06': return 'มิถุนายน'; break;	
					case '07': return 'กรกฎาคม'; break;	
					case '08': return 'สิงหาคม'; break;	
					case '09': return 'กันยายน'; break;	
					case '10': return 'ตุลาคม'; break;	
					case '11': return 'พฤศจิกายน'; break;	
					case '12': return 'ธันวาคม'; break;	
					default: return $month;
				}	
		}
	}
?>