<?php	
	function checkShowWebPdfVal($arr_web,$arr_pdf,$val){
		$returnVal['web'] = $returnVal['pdf'] = '0';
		if(is_array($arr_web)){
			if(in_array($val,$arr_web)){
				$returnVal['web'] = '1';
			}
		}
		
		if(is_array($arr_pdf)){
			if(in_array($val,$arr_pdf)){
				$returnVal['pdf'] = '1';
			}
		}
		return $returnVal;
	}//end function
	
	function setWebPdfHtml($featSec,$name,$nameInfo,$arrShowWebPdf,$isHilight = '0'){
		$fieldName = '';
		if(isset($arrShowWebPdf['ele_name'])){
			$nameInfo = $arrShowWebPdf['ele_name'];
		}
		
		if(isset($arrShowWebPdf['ele_count'])){
			$fieldName = '['.$arrShowWebPdf['ele_count'].'][]';
		}
		else{
			$fieldName = '[]';
		}
		
		echo '<tr><th width="500">';
		if($isHilight == '1'){
			echo '<span class="requirefield">**</span>';		
		}
		echo $name,'&nbsp;&nbsp;<input type="checkbox" name="web_',$featSec,$fieldName,'" value="',$nameInfo,'"';
		if($arrShowWebPdf['web'] == '1'){
			echo ' checked="checked" ';
		}
		echo ' />&nbsp;Web&nbsp;&nbsp;&nbsp;<input type="checkbox" name="pdf_',$featSec,$fieldName,'" value="',$nameInfo,'" ';
		if($arrShowWebPdf['pdf'] == '1'){
			echo ' checked="checked" ';
		}
		echo ' />&nbsp;PDF</th>
				<th>&nbsp;</th>
			</tr>';
	}
	
	function setFeatureInLongHtml($featSec,$name,$inInfo,$longInfo,$arrShowWebPdf = array(),$isHilight = 0){
		/*
			parameter
			$featSec = name of feature section used for create "showweb" and "showpdf" name
			$name = name of feature(string)
			$inInfo = array(name = string,val = string)
			$longInfo = array(name = string,val = string)
			$arrShowWebPdf = array of feature name
		*/
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>';
		setWebPdfHtml($featSec,$name,$inInfo['name'],$arrShowWebPdf,$isHilight);
		echo '</thead>
			<tbody>
				<tr class="evenrow">';
		setFeatureInHtml($featSec,$name,$inInfo,2);
		setFeatureLongHtml($featSec,$name,$longInfo,2);
		echo '</tr></tbody></table>';
	}//end function
	
	function setFeatureDropdownLongHtml($featSec,$name,$dropInfo,$longInfo,$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$dropInfo = array of dropdown(name = string,ele = array,val = string)
			$longInfo = array(name = string,val = string)
		*/
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<thead>';
		setWebPdfHtml($featSec,$name,$dropInfo['name'],$arrShowWebPdf);
		echo '</thead>
		<tbody>
			<tr class="evenrow">';
		setFeatureDropdownHtml($featSec,$name,$dropInfo,2);
		setFeatureLongHtml($featSec,$name,$longInfo,2);
		echo '</tr></tbody></table>';
	}//end function
	
	function setFeatureNumdropdownLongHtml($featSec,$name,$dropInfo,$longInfo,$arrShowWebPdf = array(),$isHilight = '0'){
		/*
			parameter
			$name = name of feature(string)
			$dropInfo = array of dropdown(name = string,startno=integer,endno=integer,val = string)
			$longInfo = array(name = string,val = string)
		*/
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<thead>';
		setWebPdfHtml($featSec,$name,$dropInfo['name'],$arrShowWebPdf,$isHilight);
		echo '</thead>
		<tbody>
			<tr class="evenrow">';
		setFeatureNumdropdownHtml($featSec,$name,$dropInfo,2,$isHilight);
		setFeatureLongHtml($featSec,$name,$longInfo,2);
		echo '</tr></tbody></table>';
	}//end function
	
	function setFeatureYesnoradioLongHtml($featSec,$name,$radioInfo,$longInfo,$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$radioInfo = array(name = string,val = string)
			$longInfo = array(name = string,val = string)
		*/
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<thead>';
		setWebPdfHtml($featSec,$name,$radioInfo['name'],$arrShowWebPdf);
		echo '</thead>
		<tbody>
			<tr class="evenrow">';
		setFeatureYesnoradioHtml($featSec,$name,$radioInfo,2);
		setFeatureLongHtml($featSec,$name,$longInfo,2);
		echo '</tr></tbody></table>';
	}//end function
	
	function setFeatureYesnoradioCheckboxHtml($featSec,$name,$radioInfo,$chkboxInfo,$valDelimiter = '|',$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$radioInfo = array(name = string,val = string)
			$chkboxInfo = array of checkbox(name = string,ele = array,val = string of values)
			$valDelimiter = delimiter for exploding $chkboxInfo['val']
		*/
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<thead>';
		setWebPdfHtml($featSec,$name,$radioInfo['name'],$arrShowWebPdf);
		echo '</thead>
		<tbody>
			<tr class="evenrow">';
		setFeatureYesnoradioHtml($featSec,$name,$radioInfo,2);
		echo '<td align="left" valign="top">&nbsp;</td></tr>';
		setFeatureCheckboxHtml($featSec,'',$chkboxInfo,2);
		echo '</tbody></table>';
	}//end function
	
	/*
	function to create element for other function start
	*/
	function setFeatureYesnoradioHtml($featSec,$name,$radioInfo,$type = 1,$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$radioInfo = array(name = string,val = string)
			$type = 1(create new table, use $name),2(create <td>, not use $name)
		*/
		if($type == 1){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>';
			setWebPdfHtml($featSec,$name,$radioInfo['name'],$arrShowWebPdf);
			echo '</thead>
			<tbody>
				<tr class="evenrow">';
		}
		
		echo '<td align="left" valign="top">
			<p>
				<label for="',$radioInfo['name'],'">',$name,':</label>
				<input type="radio" id="',$radioInfo['name'],'" name="',$radioInfo['name'],'" value="1"';

		if($radioInfo['val'] == '1'){
			echo 'checked="checked"';
		}
		echo '/>&nbsp;',YES,'&nbsp;&nbsp;<input type="radio" name="',$radioInfo['name'],'" value="0"';
		if($radioInfo['val'] == '0'){
			echo 'checked="checked"';
		}
		echo '/>&nbsp;',NO,'&nbsp;&nbsp;<input type="radio" name="',$radioInfo['name'],'" value="2"';
		if(!ctype_digit($radioInfo['val']) || $radioInfo['val'] == '2'){
			echo 'checked="checked"';
		}
		echo '/>&nbsp;',UNKNOWN,'</p></td>';
		if($type == 1){
			echo '</tr></tbody></table>';
		}
	}//end function
	
	function setFeatureDropdownHtml($featSec,$name,$dropInfo,$type = 1,$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$dropInfo = array of dropdown(name = string,ele = array,val = string)
			$type = 1(create new table, use $name),2(create <td>, not use $name)
		*/
		if($type == 1){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>';
			setWebPdfHtml($featSec,$name,$dropInfo['name'],$arrShowWebPdf);
			echo '</thead>
			<tbody>
				<tr class="evenrow">';
		}
		
		echo '<td align="left" valign="top">
				<p>
					<label for="',$dropInfo['name'],'">',$name,':</label>
					<select id="',$dropInfo['name'],'" name="',$dropInfo['name'],'">
					<option value="">',PLS_SLECT,'</option>';
		if(count($dropInfo['ele']) > 0){
			foreach($dropInfo['ele'] as $key=>$val){
				echo '<option value="',$key,'" ';
				if($dropInfo['val'] == $key){
					echo 'selected="selected" ';
				}
				echo '>',$val['name'],'</option>';
			}//end foreach
		}//end if		
		echo '</select></p></td>';
		
		if($type == 1){
			echo '</tr></tbody></table>';
		}
	}//end function
	
	function setFeatureNumdropdownHtml($featSec,$name,$dropInfo,$type = 1,$arrShowWebPdf = array(),$isHilight = '0'){
		/*
			parameter
			$name = name of feature(string)
			$dropInfo = array of dropdown(name = string,startno=integer,endno=integer,val = string)
			$type = 1(create new table, use $name),2(create <td>, not use $name)
		*/
		if($type == 1){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>';
			setWebPdfHtml($featSec,$name,$dropInfo['name'],$arrShowWebPdf,$isHilight);
			echo '</thead>
			<tbody>
				<tr class="evenrow">';
		}
		
		echo '<td align="left" valign="top"><p>
		<label for="',$dropInfo['name'],'">',$name,':</label>
		<select id="',$dropInfo['name'],'" name="',$dropInfo['name'],'">
		<option value="">',PLS_SLECT,'</option>';
		if(!isset($dropInfo['startno']) ||  $dropInfo['startno'] < 0){
			$dropInfo['startno'] = 0;
		}
		
		if(!isset($dropInfo['endno']) || $dropInfo['endno'] > 100){
			$dropInfo['endno'] = 100;
		}
		
		for($i=$dropInfo['startno'];$i<=$dropInfo['endno'];$i++){
			echo '<option value="',$i,'"';
			if($dropInfo['val'] == $i){
				echo ' selected="selected" ';
			}
			echo '>',$i,'</option>';
		}//end for
		
		echo '</select></p></td>';
		
		if($type == 1){
			echo '</tr></tbody></table>';
		}
	}//end function
	
	function setFeatureInHtml($featSec,$name,$inInfo,$type = 1,$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$inInfo = array(name = string,val = string)
			$type = 1(create new table, use $name),2(create <td>, not use $name)
		*/
		if($type == 1){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>';
			setWebPdfHtml($featSec,$name,$inInfo['name'],$arrShowWebPdf);
			echo '</thead>
			<tbody>
				<tr class="evenrow">';
		}
		
		echo '<td align="left" valign="top">
			<p>
				<label for="',$inInfo['name'],'">',$name,':</label>
				<input type="text" id="',$inInfo['name'],'" name="',$inInfo['name'],'" class="inputbox" value="',$inInfo['val'],'" />
			</p>
		</td>';
		
		if($type == 1){
			echo '</tr></tbody></table>';
		}
	}//end function
	
	function setFeatureLongHtml($featSec,$name,$longInfo,$type = 1,$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$longInfo = array(name = string,val = string)
			$type = 1(create new table, use $name),2(create <td>, not use $name)
		*/
		if($type == 1){
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>';
			setWebPdfHtml($featSec,$name,$longInfo['name'],$arrShowWebPdf);
			echo '</thead>
			<tbody>
				<tr class="evenrow">';
		}
		
		echo '<td align="left" valign="top">
			<p>
				<label for="',$longInfo['name'],'">',COMMENT,':</label>
				<textarea id="',$longInfo['name'],'" name="',$longInfo['name'],'" rows="5" cols="50">',$longInfo['val'],'</textarea>
			</p>
		</td>';
		
		if($type == 1){
			echo '</tr></tbody></table>';
		}
	}//end function
	
	function setFeatureCheckboxHtml($featSec,$name,$chkboxInfo,$type = 1,$valDelimiter = '|',$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$chkboxInfo = array of checkbox(name = string,ele = array,val = string of values)
			$type = 1(create new table, use $name),2(create <tr>, not use $name)
			$valDelimiter = delimiter for exploding $chkboxInfo['val']
		*/
		
		if(count($chkboxInfo['ele']) > 0){
			if($type == 1){
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<thead>';
				setWebPdfHtml($featSec,$name,$chkboxInfo['name'],$arrShowWebPdf);
				echo '</thead>
				<tbody>';
			}
			$arr_val = explode($valDelimiter,$chkboxInfo['val']);
			echo '<tr class="evenrow"><td colspan="2">';
			foreach($chkboxInfo['ele'] as $key=>$val){
				echo '<p style="float:left;width:32%"><input type="checkbox" name="',$chkboxInfo['name'],'[]" value="',$key,'"';
				if(in_array($key,$arr_val)){
					echo ' checked="checked" ';
				}
				echo ' />&nbsp;',$val['name'],'</p>';
			}//end foreach
			echo '</td></tr>';
			if($type == 1){
				echo '</tbody></table>';
			}
		}
	}
	
	function setFeatureOnlyCheckboxHtml($featSec,$name,$chkboxInfo,$type = 1,$valDelimiter = '|',$arrShowWebPdf = array()){
		/*
			parameter
			$name = name of feature(string)
			$chkboxInfo = array of checkbox(name = string,ele = array,val = string of values)
			$type = 1(create new table, use $name),2(create <tr>, not use $name)
			$valDelimiter = delimiter for exploding $chkboxInfo['val']
		*/
		if(count($chkboxInfo['ele']) > 0){			
			$arr_val = explode($valDelimiter,$chkboxInfo['val']);
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>';
			echo '<tr class="evenrow"><td colspan="2">';
			foreach($chkboxInfo['ele'] as $key=>$val){
				echo '<p style="float:left;width:32%"><input type="checkbox" name="',$chkboxInfo['name'],'[]" value="',$key,'"';
				if(in_array($key,$arr_val)){
					echo ' checked="checked" ';
				}
				echo ' />&nbsp;',$val['name'],'</p>';
			}//end foreach
			echo '</td></tr>';
			if($type == 1){
				echo '</tbody></table>';
			}
		}
	}
?>