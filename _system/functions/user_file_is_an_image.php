<?php

	function user_file_is_an_image($imgPath){
		// run through the contents of the folder to see if the filetype is an image
		$fileIsImage = false ;
		$thisFile = '' ;
		$this_dir = opendir($imgPath) ;
		while(($file=readdir($this_dir)) != false){
			if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
				$thisFile = $file ;
			}
		}
		closedir($this_dir) ;
		$thisFileArray = explode('.',$thisFile) ;
		if(in_array($thisFileArray[sizeof($thisFileArray)-1],$_SESSION['ALLOWED_FILES_IMAGES'])){
			$fileIsImage = true ;
		}
		return $fileIsImage ;
	}

?>
