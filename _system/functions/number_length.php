<?php

	function number_length($orig,$reqLen){
		if(strlen(strval($orig))<$reqLen){
			while(strlen(strval($orig))<$reqLen){
				$orig = '0'.$orig ;
			}
		}
		return $orig ;
	}

?>