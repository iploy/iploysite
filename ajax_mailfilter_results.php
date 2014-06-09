<?php

include_once('_system/classes/mass_mail.php') ;

$graduateFeilds = array('graduate_files_photo','graduate_files_cv','graduate_files_certificate','graduate_current_location','graduate_desired_location','graduate_subject_studied','graduate_job_category','graduate_education_level','graduate_education_grade') ;
$employerFeilds = array('employer_industry_sector') ;
$adminFeilds = array() ;
$suFeilds = array() ;


// only process this request if it comes from the mass mailing script
if(strstr($_SERVER['HTTP_REFERER'],'home.php?action=massmailing')){ ;
	// define the stock variables
	$scrTxt = '<b>Error</b>: No type was selected' ; // Must contain the word "error"
	// check if the type variable exists
	if($_POST['type']){
		$scrTxt = '' ;
		// define the class
		$mailFunction = new mass_mail ;
		// loop through the selected types
		foreach($_POST['type'] as $type){
			$valuesArray = array() ;
			if($scrTxt!=''){ $scrTxt.= "|" ; }
			// first process graduates
			if($type=='graduate'){ $fields = $graduateFeilds ; }
			if($type=='employer'){ $fields = $employerFeilds ; }
			if($type=='admin'){ $fields = $adminFeilds ; }
			if($type=='superuser'){ $fields = $suFeilds ; }
			foreach($fields as $field){
				$valuesArray[$field] = $_POST[$field] ;
			}
			$result = $mailFunction->getData($type,$valuesArray) ;
			$scrTxt.= $type.':'.$result['found_count'].':'.$result['total_count'].':'.$result['id_string'] ;
		}
		echo $scrTxt ;
	} else {
		echo 'No user type selected. Aborting.' ;
	}
} else {
	header('Location: http://www.iploy.co.uk/') ;
	exit() ;
}
// echo print_r($_POST,true)

?>

