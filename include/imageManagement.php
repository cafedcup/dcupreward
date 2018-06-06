<?php
	function upload_resize($fileInfo,$newName,$targetDir,$maxWidth,$maxHeight,$fixsize=1,$imgQuality=100){
		$uploadResult['uploadResult'] = 0;
		$uploadResult['imageName'] = '';
		if($fileInfo['error'] == 0){
			$fileExt = getFileExtension($fileInfo['name']);
			if(validateImageFormat($fileExt)){	
				$newName = setImageFileName($fileInfo['name'],$newName,$fileExt,$targetDir);
				$resizeResult = createResizeImage($fileInfo['tmp_name'],$newName,$fileExt,$targetDir,$maxWidth,$maxHeight,$fixsize,$imgQuality);
				if($resizeResult['resizeResult'] == 1){
					$uploadResult['uploadResult']= 1;
					$uploadResult['imageName']= $resizeResult['resizeImgName'];						
				}
				unset($newName);
			}
			unset($fileExt);
		}	
		return $uploadResult;			
	}
		
	function setImageFileName($curName,$newFileName,$fileExt,$targetDir){
		if($newFileName != ''){
			if(strrpos($newFileName,'.') === true){ $newFileName = substr($newFileName,0,strrpos($newFileName,'.')); }				
			$newFileName = str_replace(' ','-',$newFileName);
			$newFileName = str_replace('.','',$newFileName);						
		}else{
			$newFileName = substr($curName,0,strrpos($curName,'.'));
			$newFileName = str_replace(' ','-',$newFileName);
			$newFileName = str_replace('.','',$newFileName);						
		}
		if(!is_dir($targetDir)){ createDirectory($targetDir); }
		if(is_file($targetDir.$newFileName.$fileExt)){ $newFileName .= '_'.date('His'); }
		return $newFileName;
	}

	function createResizeImage($tempFileName,$fileName,$fileExt,$targetDir,$maxWidth,$maxHeight,$fixsize = 1,$imgQuality = 100){
		$resizeResult['resizeResult'] = 0;
		$resizeResult['resizeImgName'] = '';
		if(!ctype_digit($maxWidth)){ $maxWidth = 200; }
		else{ $maxWidth = intval($maxWidth); }
		if(!ctype_digit($maxHeight)){ $maxHeight = 200; }
		else{ $maxHeight = intval($maxHeight); }
		list($oriWidth, $oriHeight) = getimagesize($tempFileName);
		if($fixsize == 0){
			$oriRatio = $oriWidth/$oriHeight;
			if($maxWidth/$maxHeight > $oriRatio){ $maxWidth = $maxHeight*$oriRatio; }
			else{ $maxHeight = $maxWidth/$oriRatio; }	
		}
		$gdImg = imagecreatetruecolor($maxWidth, $maxHeight);
		switch($fileExt){
			case '.jpg':
				$srcImg = imagecreatefromjpeg($tempFileName);				
				imagecopyresampled($gdImg, $srcImg, 0, 0, 0, 0, $maxWidth, $maxHeight, $oriWidth, $oriHeight);					
				if(imagejpeg($gdImg,$targetDir.$fileName.$fileExt, $imgQuality)){						
					$resizeResult['resizeResult'] = 1;
					$resizeResult['resizeImgName'] = $fileName.$fileExt;
				}
			break;
			case '.gif':				
				$srcImg = imagecreatefromgif($tempFileName);				
				imagecopyresampled($gdImg, $srcImg, 0, 0, 0, 0, $maxWidth, $maxHeight, $oriWidth, $oriHeight);
				if(imagegif($gdImg,$targetDir.$fileName.$fileExt)){
					$resizeResult['resizeResult'] = 1;
					$resizeResult['resizeImgName'] = $fileName.$fileExt;
				}
			break;
			case '.png':
				$srcImg = imagecreatefrompng($tempFileName);					
				imagecopyresampled($gdImg, $srcImg, 0, 0, 0, 0, $maxWidth, $maxHeight, $oriWidth, $oriHeight);
				if(imagepng($gdImg,$targetDir.$fileName.$fileExt, 9)){
					$resizeResult['resizeResult'] = 1;
					$resizeResult['resizeImgName'] = $fileName.$fileExt;
				}
			break;				
		}
		imagedestroy($gdImg);
		unset($oriRatio);
		return $resizeResult;
	}
?>