<?php
	function createDirectory($targetDir){		
		$arr_path = explode('/',$targetDir);		
		$curPath = '';
		foreach($arr_path as $val){
			$curPath .= $val.'/';
			if(@!is_dir($curPath)){
				@mkdir($curPath);
				@chmod($curPath, 0755);				
			}				
		}
		unset($arr_path,$curPath);		
	}
	
	function createFile($targetDir,$fileName,$fileContent){
		createDirectory($targetDir);
		if($fileHandler = fopen($targetDir.$fileName,"wb")){			
			fwrite($fileHandler,$fileContent);
			fclose($fileHandler);
			chmod($targetDir.$fileName, 0755);
		}else{
			echo 'cannot write to file ',$fileName;
			exit();
		}
	}
	
	function removeAllFilesInDir($targetDir){
		if(is_dir($targetDir)){
			if($handler = opendir($targetDir)){
				while(false !== ($file = readdir($handler))){
					if($file != "." && $file != ".."){ if(is_file($targetDir.$file)){ unlink($targetDir.$file); } }
				}
				closedir($handler);
			}
		}		
	}
?>