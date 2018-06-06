<?php
	require('accessControl.php');

	require(ROOT_PATH.'include/functions.php');
	$alertMsg = $cacheInfo = array();
	$ele_id = '';
	$menuInfo['curmenu'] = COM.' - '.UPDATE;
	
	if($_POST['act'] == 'update'){	
		$ele_id = $_POST['id'];
		if(ctype_digit($ele_id)){
			if(strlen(trim($_POST['title'])) > 0){
				
				$arr_in = array();
				if(ctype_digit($_POST['isactive'])){ $arr_in['isactive'] = $_POST['isactive']; }
				$arr_in['title'] = $_POST['title'];
				
				$db->update_data('tb_test',$arr_in,' id='.$ele_id);
				unset($arr_curImg);
				/* logo and image end */
				$alertMsg['type'] = 1;
				$alertMsg['msg'] = COMP_UD;
				
				unset($_POST,$arr_in);		
			}else{
				$alertMsg['type'] = 0;
				$alertMsg['msg'] = ERR_REQ_INFO;
			}		
		}else{ echo '<script language="javascript" type="text/javascript">window.close();</script>';exit(); }
	}else{ $ele_id = $_GET['id']; }
	
	if(ctype_digit($ele_id)){
		$pageInfo = $db->fetch_array($db->query('SELECT * FROM tb_test WHERE id='.$ele_id));
		$pageInfo['img'] = explode(',',$pageInfo['img']);
	}else{
		unset($ele_id,$alertMsg,$menuInfo,$pageInfo,$cacheInfo);
		echo '<script language="javascript" type="text/javascript">window.close();</script>';exit();
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
	<form name="pageform" action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="act" value="update" />
	<input type="hidden" name="id" value="<?php echo $ele_id; ?>" />
	<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo $menuInfo['curmenu']; ?></h3>
			<ul class="tabs">
				<li><a href="#tab_gen"><?php echo GEN_INFO; ?></a></li>
			</ul>
		</header>
		<div class="tab_container">
			<div id="tab_gen" class="tab_content module_content">
            	<fieldset class="two_portion">
                    <label><?php echo STATUS; ?></label>
                    <input type="radio" name="isactive" value= "1" <?php if($pageInfo['isactive'] == '1' ){ echo 'checked="checked"'; } ?> />&nbsp;<?php echo ACTIVE; ?>
                    &nbsp;&nbsp;
                    <input type="radio" name="isactive" value="0" <?php if($pageInfo['isactive'] == '0' ){ echo 'checked="checked"'; } ?> />&nbsp;<?php echo INACTIVE; ?>
                </fieldset>
				<fieldset class="two_portion">
					<label><?php echo DP_OD; ?></label>
					<input type="text" id="display_order" name="display_order" class="txt_ele" value="<?php echo $pageInfo['display_order']; ?>" maxlength="200" />
				</fieldset>
                <fieldset class="two_portion">
					<label><span class="requirefield">*</span><?php echo NAME; ?></label>
					<input type="text" id="title" name="title" class="txt_ele" value="<?php echo $pageInfo['title']; ?>" maxlength="200" />
				</fieldset>
				<div class="clear"></div>
				
				<footer>
					<div class="submit_link"><input type="submit" value="<?php echo SAVE; ?>" class="alt_btn" />&nbsp;<input type="button" value="<?php echo CLS; ?>" class="alt_btn" onclick="window.close();" /></div>
				</footer>
			</div>
		</div>
	</article>
	</form>
</section>
<?php
	unset($alertMsg,$menuInfo,$cacheInfo);
	require('general_script_section.php');
?>
<script language="javascript" type="text/javascript" src="<?php echo ROOT_URL; ?>js-system/characterPatternValidate.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo ROOT_URL; ?>js-system/numberPatternValidate.js"></script>
</body>
</html>