<?php
	require('accessControl.php');
	
	require(ROOT_PATH.'include/functions.php');
	$alertMsg = $cacheInfo = array();
	$menuInfo['curmenu'] = COM.' - '.ADD;
	
	if($_POST['act'] == 'add'){
		if(strlen(trim($_POST['title'])) > 0 ){
			$arr_in = array();
			if(ctype_digit($_POST['isactive'])){ $arr_in['isactive'] = $_POST['isactive']; }
			$arr_in['title'] = $_POST['title'];
			$arr_in['create_date'] = date('Y-m-d H:i:s');
			$db->insert_data('tb_test',$arr_in);
			$alertMsg['type'] = 1;
			$alertMsg['msg'] = COMP_ADD;
			
			unset($_POST,$arr_in);
		}else{
			$alertMsg['type'] = 0;
			$alertMsg['msg'] = ERR_REQ_INFO;
		}
	}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $menuInfo['curmenu']; ?></title>
<link href="<?php echo IMG_URL; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="<?php echo ROOT_URL; ?>css/backend_style.css" rel="stylesheet" type="text/css" />
<?php require('backend_style_control.php'); ?>
</head>
<body>
<?php require('top_sidebar_panel.php'); ?>
<section id="main" class="column">
	<?php include('msg_display_panel.php'); ?>
	<h4 class="alert_warning"><?php echo REQ_FIELD; ?></h4>
	<form name="pageform" action="" method="post">
	<input type="hidden" name="act" value="add" />
	<article class="module width_full">
		<header><h3><?php echo $menuInfo['curmenu']; ?></h3></header>
		<div class="module_content">
			<fieldset class="two_portion">
				<label><?php echo STATUS; ?></label>
				<input type="radio" name="isactive" value= "1" <?php if(!isset($_POST['isactive']) || $_POST['isactive'] == '1' ){ echo 'checked="checked"'; } ?> />&nbsp;<?php echo ACTIVE; ?>
				&nbsp;&nbsp;
				<input type="radio" name="isactive" value="0" <?php if(isset($_POST['isactive']) && $_POST['isactive'] == '0' ){ echo 'checked="checked"'; } ?> />&nbsp;<?php echo INACTIVE; ?>
			</fieldset>
            <fieldset class="two_portion">
                <label><span class="requirefield">*</span><?php echo NAME; ?> (Thai)</label>
                <input type="text" id="title" name="title" class="txt_ele" value="<?php echo $_POST['title']; ?>" maxlength="200" />
            </fieldset>
            <div class="clear"></div>
                
			<footer>
				<div class="submit_link"><input type="submit" value="<?php echo SAVE; ?>" class="alt_btn" /></div>
			</footer>
		</div>
	</article>
	</form>
</section>
<?php
	unset($alertMsg,$menuInfo,$cacheInfo);
	require('general_script_section.php');
?>
<script language="javascript" type="text/javascript" src="<?php echo ROOT_URL; ?>js-system/numberPatternValidate.js"></script>
</body>
</html>