<?php
	if(isset($alertMsg['type'])){
		switch($alertMsg['type']){
			case 0: echo '<h4 class="alert_error">',$alertMsg['msg'],'</h4>'; break;
			case 1: echo '<h4 class="alert_success">',$alertMsg['msg'],'</h4>'; break;
		}
	}
?>