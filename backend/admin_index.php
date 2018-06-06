<?php
	$menuInfo['curmenu'] = 'Home';
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo SITE_NAME; ?></title>
<link href="<?php echo IMG_URL; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link rel="stylesheet" href="<?php echo ROOT_URL; ?>css/backend_style.css" type="text/css" media="screen" />
<?php require('backend_style_control.php'); ?>
</head>
<body>
<?php require('top_sidebar_panel.php'); ?>
<section id="main" class="column">
</section>
<?php
	unset($menuInfo);
	require('general_script_section.php');
?>
</body>
</html>