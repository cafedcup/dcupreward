<?php
	session_start();
	ini_set('date.timezone', 'Asia/Bangkok');
	require('./../include/global_config.php');
	require(ROOT_PATH.'backend/config/backend_config.php');
	require(ROOT_PATH.'backend/config/language.php');
	
	$alertMsg = '';
	/*if(isset($_SESSION['sessLogin']) && ctype_digit($_SESSION['sessLogin'])){
		header('Location:'.ROOT_URL.'backend/index_page.php');
		exit();
	}*/
	//elseif(isset($_GET['flag'])){ $alertMsg = LOG_IN_EXPIRED; }
	
	if($_POST['act'] == 'login'){
		if(!empty($_POST['usrname']) && !empty($_POST['usrpass'])){
			if($_POST['usrname'] == 'Admin' && $_POST['usrpass'] == 'TMP_PorJ2018'){
				$_SESSION['sessLogin'] = 1;
				$_SESSION['sessName'] = "Admin";
				$_SESSION['last_login'] = time();
				unset($_POST);
				header('Location:'.ROOT_URL.'backend/index_page.php');
				exit();
			}
			else{
				$alertMsg = WRONG_USR_PWD;
				unset($_POST);
			}
		}else{
			$alertMsg = WRONG_USR_PWD;
			unset($_POST);	
		}
	}
?>
