<?php
	//session_start();
	error_reporting(0);
	ini_set('display_errors', 1);
	ini_set('date.timezone', 'Asia/Bangkok');
	if(isset($isAjax)){ require('../../include/global_config.php'); }else{ require('./../include/global_config.php'); }
	require(ROOT_PATH.'include/tinymce_ezfileconfig.php');
	require('config/backend_config.php');
	require('config/language.php');
	require(ROOT_PATH.'include/systemManagement.php');
	require(ROOT_PATH.'include/Database.php');
	#$db = Database::getInstance();
	/*echo ' ## $_SESSION: '; print_r($_SESSION);
	if(isset($_SESSION['sessLogin']) && ctype_digit($_SESSION['sessLogin'])){
		
	}else{
		header('Location:'.ROOT_URL.'backend/signout.php');
		exit();
	}*/
	
?>