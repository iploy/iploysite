<?php

	include('_system/_config/_address_required_fields_array.php') ;

	$employer_required_fields = array() ;
	$employer_optional_fields = array() ;



	// ============= [ REQUIRED ] =============

	$employer_required_fields[] = 'company_name' ;
	$employer_required_fields[] = 'industry_sector' ;
	$employer_required_fields[] = 'bio' ;

	// Auto add the address fields for both basic and billing address
	foreach($address_required_fields as $address_required_field){
		$employer_required_fields[] = $address_required_field ;
		$employer_required_fields[] = 'billing_'.$address_required_field ;
	}




	// ============= [ OPTIONAL ] =============

	$employer_optional_fields[] = 'website' ;
	$employer_optional_fields[] = 'send_promo_mails' ;

?>