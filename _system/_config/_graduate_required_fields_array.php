<?php
	/*
	
		IMPORTANT:
		if a field is not required, move it to the optional array
	
	*/

	$graduate_required_fields = array() ;
	$graduate_optional_fields = array() ;

	$graduate_required_fields[] = 'first_name' ;
	$graduate_required_fields[] = 'surname' ;
	$graduate_required_fields[] = 'tel_mobile' ;
	$graduate_required_fields[] = 'date_of_birth' ;
	$graduate_required_fields[] = 'hours' ;
	$graduate_required_fields[] = 'emploment_location' ;
	$graduate_required_fields[] = 'job_category' ;
	$graduate_required_fields[] = 'will_travel' ;
	$graduate_required_fields[] = 'has_driving_licence' ;
	$graduate_required_fields[] = 'will_do_antisocial' ;
	$graduate_required_fields[] = 'availability' ;

	/*
	$graduate_required_fields[] = 'education_level' ;
	$graduate_required_fields[] = 'education_degree_title' ;
	$graduate_required_fields[] = 'education_start' ;
	$graduate_required_fields[] = 'education_end' ;
	$graduate_required_fields[] = 'education_has_graduated' ;
	$graduate_required_fields[] = 'education_grade' ;
	$graduate_required_fields[] = 'education_institution' ;
	$graduate_required_fields[] = 'education_location' ;
	$graduate_required_fields[] = 'education_certification_title' ;
	*/


	//Optional (TEMP)
	$graduate_required_fields[] = 'subject' ;
	$graduate_required_fields[] = 'education_location' ;
	$graduate_required_fields[] = 'education_level' ;
	$graduate_required_fields[] = 'education_end' ;
	$graduate_required_fields[] = 'education_institution' ;

	$graduate_optional_fields[] = 'education_degree_title' ;
	$graduate_optional_fields[] = 'education_start' ;
	// $graduate_optional_fields[] = 'education_has_graduated' ;
	$graduate_optional_fields[] = 'education_grade' ;
	$graduate_optional_fields[] = 'education_certificate_title' ;


	// Uploads
	$graduate_optional_fields[] = 'upload_photo' ;
	$graduate_optional_fields[] = 'upload_cv' ;
	$graduate_optional_fields[] = 'upload_certificate' ;



?>
