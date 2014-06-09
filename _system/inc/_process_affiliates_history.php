<?php

	// redirect if we have no affiliate ID, no querystring variable or the requested number of grads is not offered as payment
	if(!$_SESSION['affiliate_id']){
		header('Location: home.php?action='.$_GET['action']) ;
		exit ;
	}

	include_once('_system/classes/affiliate_list.php') ;
	include_once('_system/_config/config_affiliates_arrays.php') ;

	$affiliateList = new affiliate_list ;
	$affiliateList->setAffiliateId($_SESSION['affiliate_id']) ;
	// get the unpaid list
	$affiliateList->setIsPaid(false) ;
	$affiliatesUnpaid = $affiliateList->getTransactions() ;
	//echo '<p>'.print_r($affiliatesUnpaid).'</p>' ;

	// get the paid list
	$affiliateList->setIsPaid(true) ;
	$affiliatesPaid = $affiliateList->getTransactions() ;
	//echo '<p>'.print_r($affiliatesPaid).'</p>' ;

?>