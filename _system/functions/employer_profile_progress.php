<?php

	include_once('_system/_config/_employer_required_fields_array.php') ;
	include_once('_system/functions/progress_colour.php') ;

	function employer_profile_progress($names_array,$the_values){

		$completed_fields = 0 ;

		if(is_array($names_array)&&is_array($the_values)){

			foreach($names_array as $the_name){
				// Check if the name has a value in the value array
				// echo $the_name.'<br />' ;
				if($the_values[$the_name]!=''){
					$completed_fields ++ ;
				}
				// echo $the_name.' - '.$the_values[$the_name].'<br />' ;
			}

			return round(($completed_fields / sizeof($names_array))*100,0) ;

		} else {
			return false ;
		}

	}

?>