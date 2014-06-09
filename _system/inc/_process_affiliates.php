<?php
	include_once('_system/classes/affiliate.php') ;
	include_once('_system/_config/config_affiliates_arrays.php') ;

	// LOAD THE DATA IF NOT ALREADY LOADED
	if(!$_SESSION['affiliate_id']){
		// get curent affiliate info
		$affiliate = new affiliate ;
		$affiliate->setAffiliateUserId($_SESSION['user_id']) ;
		$affiliateInfo = $affiliate->getaffiliate(true) ; // add "true" to set sessions
	}// we will work from the session variables at this point.


	if($_SESSION['affiliate_id']){
		// get a list of unpaid, Eligible referrals
		include_once('_system/classes/affiliate_list.php') ;
		$affiliateList = new affiliate_list ;
		$affiliateList->setAffiliateId($_SESSION['affiliate_id']) ;
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
		// list of paid
		$affiliateList = new affiliate_list ;
		$affiliateList->setAffiliateId($_SESSION['affiliate_id']) ;
		$affiliateList->setIsDeclined(false) ;
		$affiliateList->setIsRequested(true) ;
		$affiliateList->setIsPaid(false) ;
		$affiliateRequested = $affiliateList->getReferrals() ;
		$affiliateList->setIsPaid(true) ;
		$affiliatePaid = $affiliateList->getReferrals() ;
		// total up
		$affiliateTotal = sizeof($affiliateEligible) + sizeof($affiliateConfirmed) + sizeof($affiliateUnconfirmed) + sizeof($affiliateRequested) + sizeof($affiliatePaid) ;
	}


	// MESSAGES TO SCREEN
	if($_GET['signup']=='good'){
		$screen_message = draw_icon(ICON_GOOD).'Thank you for signing up as an iPloy affliate. Your unique signup URL has been activated.' ;
		$screen_message_type = 'success' ;
	}
	if($_GET['update']=='good'){
		$screen_message = draw_icon(ICON_GOOD).'Your account information was successfully updated.' ;
		$screen_message_type = 'success' ;
	}
	if($_GET['claim']=='success'){
		$screen_message = draw_icon(ICON_GOOD).'You payment claim was successful. You will be contacted regarding payment within the next 5-7 working days.' ;
		$screen_message_type = 'success' ;
	}
	if($_GET['claim']=='error151'||$_GET['claim']=='error161'){
		$screen_message = draw_icon(ICON_GOOD).'A database error occured. Please contact our administrator quoting the error code &quot;'.$_GET['claim'].'&quot;.' ;
		$screen_message_type = 'error' ;
	}
	if($_GET['claim']=='notEnoughGraduates'){
		$screen_message = draw_icon(ICON_GOOD).'You do not have enough completed graduate referrals to claim that reward.' ;
		$screen_message_type = 'error' ;
	}

?>