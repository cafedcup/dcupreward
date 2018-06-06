<?php	
class AvailableCalendar{		
	private $carHead = array('Mo','Tu','We','Th','Fr','Sa','Su');		
	public function setCalendarHeader($arr_name){			
		if(count($arr_name) == 7){				
			$this->carHead = $arr_name;			
		}		
	}	
				
	private function getCalendarHeader(){			
		return $this->carHead;		
	}				
	
	private function setDayBlockStatus($year,$month,$actday,$arr_takendate){			
		if(is_array($arr_takendate) && in_array($year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad($actday,2,'0',STR_PAD_LEFT),$arr_takendate)){				
			return ' class="rm-occupy" ';			
		} //end if			
		else{				
			return ' class="rm-avail" ';				
		}		
	}		    	
	
	public function setCalendar($year = '0',$month = '0',$arr_pricing = array(),$arr_takendate = array()){			
	// Get today, reference day, first day and last day info			
		if (($year == '0') || ($month == '0')){			   
			$refDay = getdate();			
		} 
		else {			   
			$refDay = getdate(mktime(0,0,0,intval($month),1,intval($year)));	
		}    		
		
		$firstDay = getdate(mktime(0,0,0,$refDay['mon'],1,$refDay['year']));			
		$lastDay = getdate(mktime(0,0,0,$refDay['mon']+1,0,$refDay['year']));			
			// Create a table with the necessary header informations			
			echo '<table class="month"><tr><th colspan="7">'.$refDay['month']." - ".$refDay['year']."</th></tr>";			
			$arr_head = $this->getCalendarHeader();						
			echo '<tr>';			
			foreach($arr_head as $val){				
			echo '<td>',$val,'</td>';			
		}//end foreach			
		echo '</tr>';						
		// Display the first calendar row with correct positioning			
		echo '<tr>';			
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;			
		for($i=1;$i<$firstDay['wday'];$i++){				
			echo '<td>&nbsp;</td>';			
		}			
		
		$actday = 0;			
		
		for($i=$firstDay['wday'];$i<=7;$i++){				
			$actday++;				
			$class = $this->setDayBlockStatus($year,$refDay['mon'],$actday,$arr_takendate);				
			echo '<td',$class,'>',$actday,'</td>';			
		}			
		
		echo '</tr>';						
		//Get how many complete weeks are in the actual month			
		$fullWeeks = floor(($lastDay['mday']-$actday)/7);			
		
		for ($i=0;$i<$fullWeeks;$i++){				
		echo '<tr>';				
		for ($j=0;$j<7;$j++){					
			$actday++;					
			$class = $this->setDayBlockStatus($year,$refDay['mon'],$actday,$arr_takendate);					
			echo '<td',$class,'>',$actday,'</td>';				
		}				
			echo '</tr>';			
	}						
	
	//Now display the rest of the month			
	if ($actday < $lastDay['mday']){				
		echo '<tr>';				
		for ($i=0; $i<7;$i++){					
			$actday++;					
			$class = $this->setDayBlockStatus($year,$refDay['mon'],$actday,$arr_takendate);					
			if ($actday <= $lastDay['mday']){						
				echo '<td',$class,'>',$actday,'</td>';					
			}					
			else {						
				echo '<td>&nbsp;</td>';					
				}			
			}				
			echo '</tr>';		
	   }			
	   echo '</table>';		
	 }	
	 
}?>