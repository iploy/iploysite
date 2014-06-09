<?php
	include_once('_system/classes/affiliate.php') ;
	include_once('_system/classes/user_info.php') ;
	include_once('_system/_config/config_affiliates_arrays.php') ;

	// LOAD THE DATA IF NOT ALREADY LOADED
	if($_GET['affiliateId']){
		// get curent affiliate info
		$affiliate = new affiliate ;
		$affiliate->setAffiliateId($_GET['affiliateId']) ;
		$affiliateInfo = $affiliate->getaffiliate(false) ; // add "true" to set sessions
	}// we will work from the session variables at this point.

	if(sizeof($affiliateInfo)<2){
		header('Location: home.php?action=affiliatesadmin&subaction=users') ;
		exit() ;
	}

	if($_GET['affiliateId']){
		// get a list of unpaid, Eligible referrals
		include_once('_system/classes/affiliate_list.php') ;
		$affiliateList = new affiliate_list ;
		$affiliateList->setAffiliateId($_GET['affiliateId']) ;
		$affiliateList->setIsDeclined(false) ;
		$affiliateList->setIsRequested(false) ;
		// eligible
		$affiliateList->setIsEligible(true) ;
		$affiliateEligible = $affiliateList->getReferrals() ;
		// not eligible
		$affiliateList->setIsEligible(false) ;
			// confirmed
			$affiliateList->setEmailIsConfirmed(true) ;
			$affiliateConfirmed = $affiliateList->getReferrals() ;
			// unconfirmed
			$affiliateList->setEmailIsConfirmed(false) ;
			$affiliateUnconfirmed = $affiliateList->getReferrals() ;
		// list of paid / requested
		$affiliateList = new affiliate_list ;
		$affiliateList->setAffiliateId($_GET['affiliateId']) ;
		$affiliateList->setIsDeclined(false) ;
		$affiliateList->setIsRequested(true) ;
		$affiliateList->setIsPaid(false) ;
		$affiliateRequested = $affiliateList->getReferrals() ;
		$affiliateList->setIsPaid(true) ;
		$affiliatePaid = $affiliateList->getReferrals() ;
		// total up
		$affiliateTotal = sizeof($affiliateEligible) + sizeof($affiliateConfirmed) + sizeof($affiliateUnconfirmed) + sizeof($affiliateRequested) + sizeof($affiliatePaid) ;
	}

?>