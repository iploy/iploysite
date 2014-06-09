<?php

	$headersArray = array() ;
	$headersArray['Name'] = 'name' ;
	$headersArray['Username'] = 'username' ;
	$headersArray['Referrals'] = 'referrals' ;
	// - - - -
	$_SESSION['aff_orderby'] = 'name' ;
	$_SESSION['aff_orderdir'] = 'asc' ;
	if(in_array(strtolower($_GET['orderby']),$headersArray)){
		$_SESSION['aff_orderby'] = strtolower($_GET['orderby']) ;
	}
	if(strtolower($_GET['orderdir'])=='asc'||strtolower($_GET['orderdir'])=='desc'){
		$_SESSION['aff_orderdir'] = strtolower($_GET['orderdir']) ;
	}

	include_once(SITE_PATH.'_system/classes/affiliate_list.php') ;

	$users = new affiliate_list ;
	$users->setOrderBy($_SESSION['aff_orderby']) ;
	$users->setOrderDir($_SESSION['aff_orderdir']) ;
	$users = $users->getUsers() ;


?>
