function getReplyDescription(targetEle){
	$('#'+targetEle).html('');
	$('#'+targetEle+'_loading').removeClass('hide');
	var reId = $('#reply_text').val();
	var langId = $('#reply_lang').val();
	$.post('ajax/ajax_backend.php',{'type':'1','reid':reId,'lang':langId},function(data){
		tinyMCE.activeEditor.setContent(data.descrip);
		$('#'+targetEle+'_loading').addClass('hide');
	},'json');
}

/*
function getWebboardSubcategory(catId,targetEle){
	$('#'+targetEle+' option[value!=""]').remove();
	$('#'+targetEle+'_loading').removeClass('hide');
	$.post('ajax/ajax_backend.php',{'type':'4','catid':catId},function(data){
		$.each(data, function(key,val){
			$('#'+targetEle+'').append($('<option></option>').val(key).html(val.name));
		});
		$('#'+targetEle+'_loading').addClass('hide');
	},'json');
}
*/