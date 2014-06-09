<?php


	include_once('_system/classes/affiliate.php') ;
	include_once('_system/classes/affiliate_list.php') ;
	include_once('_system/_config/config_affiliates_arrays.php') ;

	// LOAD THE DATA IF NOT ALREADY LOADED
	if($_GET['affiliateId']){
		// get curent affiliate info
		$affiliate = new affiliate ;
		$affiliate->setAffiliateId($_GET['affiliateId']) ;
		$affiliateInfo = $affiliate->getaffiliate(false) ; // add "true" to set sessions
	}// we will work from the session variables at this point.

	if(sizeof($affiliateInfo)<2){
		header('Location: home.php?action='.$_GET['action']) ;
		exit() ;
	}

	$affiliateList = new affiliate_list ;
	$affiliateList->setAffiliateId($_GET['affiliateId']) ;
	// get the unpaid list
	$affiliateList->setIsPaid(false) ;
	$affiliatesUnpaid = $affiliateList->getTransactions() ;
	//echo '<p>'.print_r($affiliatesUnpaid).'</p>' ;

	// get the paid list
	$affiliateList->setIsPaid(true) ;
	$affiliatesPaid = $affiliateList->getTransactions() ;
	//echo '<p>'.print_r($affiliatesPaid).'</p>' ;

?>