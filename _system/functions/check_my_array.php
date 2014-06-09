<?php

	// Function used on the form page to check if the field is required
	function check_my_array($search_string,$search_array,$return_string){
		if(in_array($search_string,$search_array)){
			if($return_string!=''){
				return $return_string ;
			} else {
				return true ;
			}
		}
	}

?>