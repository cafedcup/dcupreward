<?php
	require('accessControl.php');
	
	require(ROOT_PATH.'include/backendPaginate.php');
	require(ROOT_PATH.'include/functions.php');
	
	$menuInfo['curmenu'] = COM;
	$eleCount = '';
	$alertMsg = $pagingInfo = $comptypeInfo = array();
	#$db->get_admin_pw('Pakrub') . " Passward";
	if(ctype_digit($_GET['id'])){
		switch($_GET['act']){
			case 'del':
				/*$curInfo = $db->fetch_array($db->query('SELECT id FROM province_tbl WHERE zone_id='.$_GET['id'].' LIMIT 1'));
				if(isset($curInfo['id'])){
					$alertMsg['type'] = 0;
					$alertMsg['msg'] = ERR_INFO_INUSE;
				}else{
					$db->delete_data('zone_tbl',' id ='.$_GET['id']);
					$alertMsg['type'] = 1;
					$alertMsg['msg'] = COMP_DEL;
				}
				unset($curInfo);
				*/
			break;
			case 'active':
			case 'inactive':
				if($_GET['act'] == 'active'){
					$db->update_data('tb_test',array('isactive'=>1),' id='.$_GET['id']);
					$alertMsg['type'] = 1;
					$alertMsg['msg'] = COMP_ACT;
				}else{
					$db->update_data('tb_test',array('isactive'=>0),' id='.$_GET['id']);
					$alertMsg['type'] = 1;
					$alertMsg['msg'] = COMP_DEACT;
				}
			break;
		}
		
	}

	if(!ctype_digit($_GET['p'])){
		$_GET['p'] = '1';
		$eleCount = $db->fetch_row($db->query('SELECT count(id) FROM tb_test WHERE 1 '));
		$_SESSION['pagenum'] = $eleCount[0];
	}else{
		if(!ctype_digit($_SESSION['pagenum'])){
			$eleCount = $db->fetch_row($db->query('SELECT count(id) FROM tb_test WHERE 1 '));
			$_SESSION['pagenum'] = $eleCount[0];
		}
		$eleCount[0] = $_SESSION['pagenum'];
	}
	$pagingInfo = setPaginate($eleCount[0],$_GET['p']);

	$result = $db->query('SELECT * FROM tb_test WHERE isactive=1 ORDER BY id ASC');
	while($rec = $db->fetch_array($result)){
		$comptypeInfo[$rec['id']]['id'] = $rec['id'];
		$comptypeInfo[$rec['id']]['title'] = $rec['title'];
	}
	$db->free_result($result);
						
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $menuInfo['curmenu']; ?></title>
<link rel="stylesheet" href="<?php echo ROOT_URL; ?>css/backend_style.css" type="text/css" media="screen" />
<?php require('backend_style_control.php'); ?>
</head>
<body>
<?php require('top_sidebar_panel.php'); ?>
<section id="main" class="column">
	<?php include('msg_display_panel.php'); ?>
	<article class="module width_full">
		<header><h3><?php echo $menuInfo['curmenu']; ?></h3></header>
		<table class="tbl" cellpadding="0" cellspacing="0"> 
		<thead> 
		<tr> 
			<th class="id_tbl"><?php echo ID; ?></th>
			<th><?php echo COM; ?></th>
			<!--<th><?php echo PROV; ?></th>
			<th><?php echo NAME; ?></th>-->
			<th class="act_tbl"><?php echo ACT; ?></th>
		</tr> 
		</thead> 
		<tbody>
		<?php
			$result = $db->query('SELECT * FROM tb_test WHERE 1 ORDER BY id DESC LIMIT '.$pagingInfo['limitSQL']);
			while($rec = $db->fetch_array($result)){
		?>
		<tr>
			<td><?php echo $rec['id']; ?></td>
			<td><?php echo $rec['title']; ?></td>
			<td align="center">
			<?php
				if($rec['isactive'] == '1'){
					echo '<a href="test_page.php?act=inactive&amp;id=',$rec['id'],'&amp;p=',$_GET['p'],'" title="',CLK_DEACT,'" class="ico_active_block" />';
				}else{
					echo '<a href="test_page.php?act=active&amp;id=',$rec['id'],'&amp;p=',$_GET['p'],'" title="',CLK_ACT,'" class="ico_inactive_block" />';
				}
			?>
			<a href="test_update_page.php?id=<?php echo $rec['id']; ?>" class="ico_update_block" target="_blank" title="<?php echo UPDATE; ?>" />&nbsp;</a><!--<a href="javascript:void(0);" class="ico_del_block" onclick="eleDel('<?php echo $rec['id']; ?>')" title="<?php echo DEL; ?>" />&nbsp;</a>--></td> 
		</tr>
		<tr class="hide" id="id_<?php echo $rec['id']; ?>">
			<td colspan="3">
			<?php
				echo '<strong>',COM,':</strong>&nbsp;',$rec['title'];
				if(strlen(trim($rec['isactive'])) > 0){ echo '<br /><strong>',STATUS,':</strong>&nbsp;',$rec['isactive']; }
			?>
			</td>
		</tr>
		<?php
			}
			$db->free_result($result);
			unset($eleCount,$alertMsg,$menuInfo,$comptypeInfo,$provinceInfo);
		?>
		</tbody> 
		</table>
	</article>
    <?php
		if(strlen(trim($pagingInfo['paginate'])) > 0){
			echo '<ul class="pagination">',$pagingInfo['paginate'],'</ul>';
		}
		unset($pagingInfo,$eleCount,$alertMsg,$order);
	?>
	<div style="clear: both;"></div>
</section>
<?php require('general_script_section.php'); ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
function eleDel(eleId){
	if(confirm("<?php echo DEL_CF; ?>")){
		window.location = "test_page.php?act=del&id="+eleId+"&p=<?php echo $_GET['p']; ?>";
	}else{ return false; }
}//end function
/* ]]> */
</script>
</body>
</html>