<?php

	// Search enging fields
	$search_fld_array = array() ;
	$search_fld_array[] = 'keyword' ;
	$search_fld_array[] = 'availability' ;
	$search_fld_array[] = 'subject' ;
	$search_fld_array[] = 'job_category' ;
	$search_fld_array[] = 'emploment_location' ;
	$search_fld_array[] = 'will_travel' ;
	$search_fld_array[] = 'has_driving_licence' ;
	$search_fld_array[] = 'will_do_antisocial' ;
	$search_fld_array[] = 'hours' ;
	$search_fld_array[] = 'alternate_languages' ;
	$search_fld_array[] = 'education_has_graduated' ;
	$search_fld_array[] = 'education_degree_title' ;
	$search_fld_array[] = 'education_institution' ;
	$search_fld_array[] = 'education_grade' ;
	$search_fld_array[] = 'education_location' ;
	$search_fld_array[] = 'has_cv' ;
	$search_fld_array[] = 'has_photo' ;
	$search_fld_array[] = 'has_certificate' ;
	$search_fld_array[] = 'has_video' ;
	

	// Hours
	$hours_array = array() ;
	$hours_array[] = 'Full Time' ;
	$hours_array[] = 'Part Time' ;
	$hours_array[] = 'Temporary' ;

	// Availability
	$availability_array = array() ;
	$availability_array[] = 'Autumn 2011' ;
	$availability_array[] = 'Spring 2012' ;
	$availability_array[] = 'Autumn 2012' ;
	$availability_array[] = 'Spring 2013' ;
	$availability_array[] = 'Autumn 2013' ;
	$availability_array[] = 'Spring 2014' ;
	$availability_array[] = 'Autumn 2014' ;
	$availability_array[] = 'Spring 2015' ;
	$availability_array[] = 'Autumn 2015' ;

	// Subjects
	$subjects_array = array() ;
	$subjects_array[] = 'Agriculture' ;
	$subjects_array[] = 'Archaeology' ;
	$subjects_array[] = 'Architecture and Landscape' ;
	$subjects_array[] = 'Art and Design' ;
	$subjects_array[] = 'Biological Sciences' ;
	$subjects_array[] = 'Business and Management' ;
	$subjects_array[] = 'Chemical Engineering' ;
	$subjects_array[] = 'Chemistry' ;
	$subjects_array[] = 'Civil Engineering' ;
	$subjects_array[] = 'Classics' ;
	$subjects_array[] = 'Computer Science' ;
	$subjects_array[] = 'Dentistry and Dental Sciences' ;
	$subjects_array[] = 'Digital Media' ;
	$subjects_array[] = 'Economics' ;
	$subjects_array[] = 'Electronic and Computer Engineering' ;
	$subjects_array[] = 'English' ;
	$subjects_array[] = 'Environmental Science' ;
	$subjects_array[] = 'Film Studies' ;
	$subjects_array[] = 'Finance and Accounting' ;
	$subjects_array[] = 'Genetics' ;
	$subjects_array[] = 'Geochemistry' ;
	$subjects_array[] = 'Geography' ;
	$subjects_array[] = 'History' ;
	$subjects_array[] = 'Law' ;
	$subjects_array[] = 'Linguistics' ;
	$subjects_array[] = 'Marketing' ;
	$subjects_array[] = 'Materials Engineering' ;
	$subjects_array[] = 'Mathematics and Statistics' ;
	$subjects_array[] = 'Mechanical and Systems Engineering' ;
	$subjects_array[] = 'Media and Journalism' ;
	$subjects_array[] = 'Medicine and Surgery' ;
	$subjects_array[] = 'Modern Languages' ;
	$subjects_array[] = 'Music' ;
	$subjects_array[] = 'Philosophy' ;
	$subjects_array[] = 'Physics' ;
	$subjects_array[] = 'Politics' ;
	$subjects_array[] = 'Psychology' ;
	$subjects_array[] = 'Social Sciences' ;


	// Employment Locations
	$locations_array = array() ;
	$locations_array[] = 'North West England' ;
	$locations_array[] = 'North East England' ;
	$locations_array[] = 'East Midlands' ;
	$locations_array[] = 'West Midlands' ;
	$locations_array[] = 'London' ;
	$locations_array[] = 'South East England' ;
	$locations_array[] = 'South West England' ;
	$locations_array[] = 'East of England' ;
	$locations_array[] = 'Scotland' ;
	$locations_array[] = 'Northern Ireland' ;
	$locations_array[] = 'Ireland' ;
	$locations_array[] = 'Wales' ;
	$locations_array[] = 'Europe' ;
	$locations_array[] = 'Middle East' ;
	$locations_array[] = 'Far East' ;
	$locations_array[] = 'Americas' ;
	$locations_array[] = 'Australasia' ;
	$locations_array[] = 'Africa' ;

	// Employment Catagories
	$category_array = array() ;
	$category_array[] = 'Admin / Clerical' ;
	$category_array[] = 'Accounting / Finance' ;
	$category_array[] = 'Apprenticeships' ;
	$category_array[] = 'Business / Strategic Management' ;
	$category_array[] = 'Banking' ;
	$category_array[] = 'Construction' ;
	$category_array[] = 'Charity and Voluntary' ;
	$category_array[] = 'Construction and Property' ;
	$category_array[] = 'Creative / Design' ;
	$category_array[] = 'Customer Service' ;
	$category_array[] = 'Editorial' ;
	$category_array[] = 'Education' ;
	$category_array[] = 'Energy' ;
	$category_array[] = 'Engineering' ;
	$category_array[] = 'Estate Agency' ;
	$category_array[] = 'Financial Services' ;
	$category_array[] = 'General Insurance' ;
	$category_array[] = 'Health and Medicine' ;
	$category_array[] = 'Hospitality / Food' ;
	$category_array[] = 'Human Resources' ;
	$category_array[] = 'Installation / Maintenance' ;
	$category_array[] = 'IT / Software Development' ;
	$category_array[] = 'IT and Telecoms' ;
	$category_array[] = 'Legal' ;
	$category_array[] = 'Leisure and Tourism' ;
	$category_array[] = 'Logistics / Transport' ;
	$category_array[] = 'Manufacturing' ;
	$category_array[] = 'Marketing' ;
	$category_array[] = 'Media / Digital and Creative' ;
	$category_array[] = 'Medical / Health' ;
	$category_array[] = 'Motoring and Automotive' ;
	$category_array[] = 'Production / Operation' ;
	$category_array[] = 'Project / Program Management' ;
	$category_array[] = 'Public Sector' ;
	$category_array[] = 'Purchasing' ;
	$category_array[] = 'Recruitment' ;
	$category_array[] = 'Retail' ;
	$category_array[] = 'Quality Assurance' ;
	$category_array[] = 'Research and Development / Science' ;
	$category_array[] = 'Sales / Business Development' ;
	$category_array[] = 'Scientific' ;
	$category_array[] = 'Security and Safety' ;
	$category_array[] = 'Social Care' ;
	$category_array[] = 'Strategy and Consultancy' ;
	$category_array[] = 'Training / Instruction' ;



	// education level
	$education_level_array = array() ;
	$education_level_array[] = 'Foundation' ;
	$education_level_array[] = 'HND/HNC or Equivalent' ;
	$education_level_array[] = 'Bachelors' ;
	$education_level_array[] = 'Masters' ;
	$education_level_array[] = 'Doctorate' ;
	$education_level_array[] = 'Professional' ;

	// education grade
	$education_grade_array = array() ;
	$education_grade_array[] = 'Pass' ;
	$education_grade_array[] = 'Merit' ;
	$education_grade_array[] = 'Distinction' ;
	$education_grade_array[] = 'First Class Honours (1st)' ;
	$education_grade_array[] = 'Upper Second Class Honours (2.1)' ;
	$education_grade_array[] = 'Lower Second Class Honours (2.2)' ;
	$education_grade_array[] = 'Third Class Honours (3rd)' ;


	// Employment Status
	$employment_status_array = array() ;
	$employment_status_array[] = 'unemployed' ;
	$employment_status_array[] = 'employed' ;


	// Languages
	$languages_array = array() ;
	$languages_array[] = 'Afrikaans' ;
	$languages_array[] = 'Albanian' ;
	$languages_array[] = 'Amharic' ;
	$languages_array[] = 'Arabic' ;
	$languages_array[] = 'Armenian' ;
	$languages_array[] = 'Azerbaijani' ;
	$languages_array[] = 'Basque' ;
	$languages_array[] = 'Belarusian' ;
	$languages_array[] = 'Bengali' ;
	$languages_array[] = 'Bulgarian' ;
	$languages_array[] = 'Burmese' ;
	$languages_array[] = 'Cambodian' ;
	$languages_array[] = 'Cantonese' ;
	$languages_array[] = 'Catalan' ;
	$languages_array[] = 'Cebuano' ;
	$languages_array[] = 'Cham' ;
	$languages_array[] = 'Chamorro' ;
	$languages_array[] = 'Chechen' ;
	$languages_array[] = 'Czech' ;
	$languages_array[] = 'Danish' ;
	$languages_array[] = 'Dutch' ;
	$languages_array[] = 'Estonian' ;
	$languages_array[] = 'Farsi' ;
	$languages_array[] = 'Fijian' ;
	$languages_array[] = 'Finnish' ;
	$languages_array[] = 'Flemish' ;
	$languages_array[] = 'French' ;
	$languages_array[] = 'Galician' ;
	$languages_array[] = 'Georgian' ;
	$languages_array[] = 'German' ;
	$languages_array[] = 'Greek' ;
	$languages_array[] = 'Gujarati' ;
	$languages_array[] = 'Hakka' ;
	$languages_array[] = 'Hebrew' ;
	$languages_array[] = 'Hindi' ;
	$languages_array[] = 'Hmong' ;
	$languages_array[] = 'Hungarian' ;
	$languages_array[] = 'Ibo' ;
	$languages_array[] = 'Icelandic' ;
	$languages_array[] = 'Ilocano' ;
	$languages_array[] = 'Ilongo' ;
	$languages_array[] = 'Indonesian' ;
	$languages_array[] = 'Irish Gaelic' ;
	$languages_array[] = 'Italian' ;
	$languages_array[] = 'Japanese' ;
	$languages_array[] = 'Kazakh' ;
	$languages_array[] = 'Kurdish' ;
	$languages_array[] = 'Korean' ;
	$languages_array[] = 'Kyrgyz' ;
	$languages_array[] = 'Laotian' ;
	$languages_array[] = 'Latvian' ;
	$languages_array[] = 'Lithuanian' ;
	$languages_array[] = 'Macedonian' ;
	$languages_array[] = 'Malaysian' ;
	$languages_array[] = 'Mandarin' ;
	$languages_array[] = 'Marathi' ;
	$languages_array[] = 'Marshallese' ;
	$languages_array[] = 'Mien' ;
	$languages_array[] = 'Norwegian' ;
	$languages_array[] = 'Oromo' ;
	$languages_array[] = 'Pashto' ;
	$languages_array[] = 'Persian' ;
	$languages_array[] = 'Polish' ;
	$languages_array[] = 'Portuguese' ;
	$languages_array[] = 'Punjabi' ;
	$languages_array[] = 'Quechua' ;
	$languages_array[] = 'Romanian' ;
	$languages_array[] = 'Russian' ;
	$languages_array[] = 'Samoan' ;
	$languages_array[] = 'Serbo-Croatian' ;
	$languages_array[] = 'Sign Language' ;
	$languages_array[] = 'Sinhala' ;
	$languages_array[] = 'Slovak' ;
	$languages_array[] = 'Slovenian' ;
	$languages_array[] = 'Somali' ;
	$languages_array[] = 'Spanish' ;
	$languages_array[] = 'Sudanese' ;
	$languages_array[] = 'Swahili' ;
	$languages_array[] = 'Swedish' ;
	$languages_array[] = 'Tagalog' ;
	$languages_array[] = 'Taiwanese' ;
	$languages_array[] = 'Tajik' ;
	$languages_array[] = 'Tamil' ;
	$languages_array[] = 'Thai' ;
	$languages_array[] = 'Tibetan' ;
	$languages_array[] = 'Tigrigna' ;
	$languages_array[] = 'Toishanese' ;
	$languages_array[] = 'Tongan' ;
	$languages_array[] = 'Trukese' ;
	$languages_array[] = 'Turkish' ;
	$languages_array[] = 'Turkmen' ;
	$languages_array[] = 'Ukranian' ;
	$languages_array[] = 'Urdu' ;
	$languages_array[] = 'Uzbek' ;
	$languages_array[] = 'Vietnamese' ;
	$languages_array[] = 'Visayan' ;
	$languages_array[] = 'Welsh' ;
	$languages_array[] = 'Yoruba' ;



?>