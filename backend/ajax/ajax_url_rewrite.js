function chkUrlRewrite(chktype,tblChk,sourceVal,chkId){
	//chktype 1=add , 2=update
	$('#'+sourceVal+'_alert_0').addClass('hide');
	$('#'+sourceVal+'_alert_1').addClass('hide');
	$('#'+sourceVal+'_alert_2').addClass('hide');
	if($('#'+sourceVal).val() != ''){
		$('#b_'+sourceVal).addClass('hide');
		$('#'+sourceVal+'_loading').removeClass('hide');
		$.post("ajax/ajax_urlRewrite.php",{'chktype':chktype,'tblchk':tblChk,'rewrite':$('#'+sourceVal).val(),'chkid':chkId},
		   function(data){
				$('#b_'+sourceVal).removeClass('hide');
				$('#'+sourceVal+'_loading').addClass('hide');
				switch(data){
					case '0'://not enough info
						$('#'+sourceVal+'_alert_0').removeClass('hide'); break;
					case '1'://ok
						$('#'+sourceVal+'_alert_1').removeClass('hide'); break;
					case '2'://duplicate
						$('#'+sourceVal+'_alert_2').removeClass('hide'); break;
				}
		   });
	}else{ $('#'+sourceVal+'_alert_0').removeClass('hide'); }
}