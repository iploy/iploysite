<?php

	class bookmarkIcons {

		private $path = 'images/icons/bookmarks/' ;
		private $reverseArray = false ;

		// List the icons based on the template
		public function getList(){
			$return_array = array() ;
			if(is_dir($this->path)){
				$this_dir = opendir($this->path) ;
				while(($file=readdir($this_dir)) != false){
					if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
						$return_array[] = $file ;
					}
				}
				closedir($this_dir) ;
			}
			if($this->reverseArray==true){
				return array_reverse($return_array) ;
			} else {
				return $return_array ;
			}
		}

		// Getters / Setters
		public function setPath($var){
			$this->path = $var ;
		}
		public function setReverseArray($var){
			if($var===true){
				$this->reverseArray = true ;
			} else {
				$this->reverseArray = false ;
			}	
		}

	}

?>