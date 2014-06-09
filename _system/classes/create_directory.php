<?php

	class create_directory {
		public function go($directory){
			$directory = str_replace(SITE_PATH,'',$directory) ;
			if(!is_dir(SITE_PATH.$directory)){
				$dirArray = explode('/',$directory) ;
				$dirCurrent = '' ;
				foreach($dirArray as $dir){
					if($dir!=''){
						$dirCurrent.= $dir.'/' ;
						if(!is_dir(SITE_PATH.$dirCurrent)){
							mkdir(SITE_PATH.$dirCurrent) ;
						}
					}
				}
			}
		}
	}

?>
