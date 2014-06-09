<?php

	include_once('_system/functions/delete_directory.php') ;

	function doDir($dir){
		if(is_dir($dir)){
			if($dh=opendir($dir)) {
				echo '<ul>'."\n" ;
				while(($file=readdir($dh))!==false) {
					// echo "filename: $file : filetype: " . filetype($dir . $file) . "<br />\n";
					$thisDir = $dir.$file.'/' ;
					if($file=='_notes'||$file=='_large'||$file=='_lrgmed'||$file=='_med'||$file=='_thumb'||$file=='_custom'){
						delete_directory($thisDir) ;
					}elseif(substr($file,0,1)!='.'){
						if(is_dir($thisDir)){
							echo '<li>'.$file ;
							doDir($thisDir) ;
							echo '</li>'."\n" ;
						}
					}
				}
				closedir($dh);
				echo '</ul>'."\n" ;
			}
		}
	}

	// let's go
	doDir('userfiles/photo/') ;

?>