<?php

	function censor_word($var){
		// if the word is less than this length, everything after the first character will be blanked else the first and last characters will show
		$censor_word_minlen = 4 ;
		$returnvar = '' ;
		for($i=0;$i<strlen($var);$i++){
			if($i==0||($i==strlen($var)-1&&strlen($var)>$censor_word_minlen)){
				$returnvar.= substr($var,$i,1) ;
			} else {
				$returnvar.= '*' ;
			}
		}
		return $returnvar ;
	}

?>
