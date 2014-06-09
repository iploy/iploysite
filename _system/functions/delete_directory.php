<?php
	function empty_directory($dir){
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir"){
						delete_directory($dir."/".$object);
					} else {
						unlink($dir."/".$object);
					}
				}
			}
			reset($objects);
		}
	}
	function delete_directory($dir) {
		if (is_dir($dir)) {
			empty_directory($dir) ;
			rmdir($dir);
		}
	} 
?>