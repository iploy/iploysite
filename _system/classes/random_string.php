<?php

	class random_string {
		/* defaults */
		private $stringLength = 10 ;
		private $useUpperCase = true ;
		private $useLowerCase = true ;
		private $useVowels = true ;
		private $useConsonants = true ;
		private $useNumbers = true ;
		/* Generate Number function */
		public function generate(){
			$returnVar = false ;
			$characters = '' ;
			if($this->useUpperCase){
				if($this->useConsonants){ $characters.= 'BCDFGHJKLMNPQRSTVWXYZ' ; }
				if($this->useVowels){ $characters.= 'AEIOU' ; }
			}
			if($this->useLowerCase){
				if($this->useConsonants){ $characters.= 'bcdfghjklmnpqrstvwxyz' ; }
				if($this->useVowels){ $characters.= 'aeiou' ; }
			}
			if($this->useNumbers){
				$characters.= '0123456789' ;
			}
			if(strlen($characters)>0&&$this->stringLength>0){
				$returnVar = "" ;    
				for ($p=0;$p<$this->stringLength;$p++) {
					$returnVar.= $characters[mt_rand(0,strlen($characters))];
				}
			}
			return $returnVar ;
		}
		/* Getters & Setters */
		public function stringLength($var){
			if(is_numeric($var)&&$var>0&&$var!=''){
				$this->stringLength = $var ;
			}
		}
		// use Upper
		public function setUseUpperCase($var){
			if($var==true){
				$this->useUpperCase = true ;
			} else {
				$this->useUpperCase = false ;
			}
		}
		// use Lower
		public function setUseLowerCase($var){
			if($var==true){
				$this->useLowerCase = true ;
			} else {
				$this->useLowerCase = false ;
			}
		}
		// use numbers
		public function setUseNumbers($var){
			if($var==true){
				$this->useNumbers = true ;
			} else {
				$this->useNumbers = false ;
			}
		}
		// use vowels
		public function setUseVowels($var){
			if($var==true){
				$this->useVowels = true ;
			} else {
				$this->useVowels = false ;
			}
		}
		// use Consonants
		public function setUseConsonants($var){
			if($var==true){
				$this->useConsonants = true ;
			} else {
				$this->useConsonants = false ;
			}
		}
	}

?>
