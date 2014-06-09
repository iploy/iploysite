<?php

include_once('_system/_config/_graduate_required_fields_array.php') ;
include_once('_system/functions/progress_colour.php') ;

function graduate_profile_progress($names_array,$the_values){

	$completed_fields = 0 ;

	foreach($names_array as $the_value){
		// Check if the seession exists
		if($the_values[$the_value]!=''){
			$completed_fields ++ ;
		}

		// if CV, check the if the file exists
		if($the_value=='upload_cv'||$the_value=='upload_photo'||$the_value=='upload_certificate'){
			if($the_value=='upload_cv'){
				$this_folder = 'cv' ;
			}elseif($the_value=='upload_photo'){
				$this_folder = 'photo' ;
			}elseif($the_value=='upload_certificate'){
				$this_folder = 'certificate' ;
			}
			$this_path = 'userfiles/'.$this_folder.'/'.$the_values['user_id'].'/' ;
			// echo $this_path."<br />" ;
			if(is_dir($this_path)){
				$this_dir = opendir($this_path) ;
				while(($file=readdir($this_dir)) != false){
					if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
						$completed_fields++ ;
						// echo $file.' - '.$the_value.'<br />' ;
					}
				}
				closedir($this_dir) ;
			}
		}

	}

	return round(($completed_fields / sizeof($names_array))*100,0) ;

}


?>
