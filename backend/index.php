<?php
	session_start();
	ini_set('date.timezone', 'Asia/Bangkok');
	require('./../include/global_config.php');
	require(ROOT_PATH.'backend/config/backend_config.php');
	require(ROOT_PATH.'backend/config/language.php');
	require(ROOT_PATH.'include/Database.php');
	
	$alertMsg = '';
	/*if(isset($_SESSION['sessLogin']) && ctype_digit($_SESSION['sessLogin'])){
		header('Location:'.ROOT_URL.'backend/index_page.php');
		exit();
	}*/
	//elseif(isset($_GET['flag'])){ $alertMsg = LOG_IN_EXPIRED; }
	
	if($_POST['act'] == 'login'){
		if(!empty($_POST['usrname']) && !empty($_POST['usrpass'])){
			#$admin_name = $db->is_admin_exist($_POST['usrname']);

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
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<link href="<?php echo IMG_URL; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="<?php echo ROOT_URL; ?>css/backend_signin_style.css" rel="stylesheet" type="text/css" />
<?php require('backend_style_control.php'); ?>
<title><?php echo SITE_NAME; ?></title>
</head>
<body>
<div class="container">
	<section id="content">
		<form action="" method="post" name="signinform" >
		<input type="hidden" name="act" value="login" />
		<h1>Login Form</h1>
		<div><input type="text" id="username" name="usrname" autofocus /></div>
		<div><input type="password" id="password" name="usrpass" /></div>
		<?php
			if($alertMsg != ''){ echo '<div class="alertMsg">',$alertMsg,'</div>'; }
			unset($alertMsg);
		?>
		<div><input type="submit" value="Log in" /></div>
		</form>
	</section>
</div>
</body>
</html>