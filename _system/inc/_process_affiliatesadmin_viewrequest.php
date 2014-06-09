<?php

	if(!$_GET['requestid']){
		header('Location: home.php?action=affiliatesadmin&subaction=requests') ;
		exit() ;
	}

	include_once(SITE_PATH.'_system/classes/affiliate.php') ;
	include_once(SITE_PATH.'_system/classes/affiliate_list.php') ;


	$requestReferrals = new affiliate_list ; 
	$requestReferrals->setPaymentId($_GET['requestid']) ; 

	if($_GET['process']=='paid'){
		$processResult = $requestReferrals->markPaid() ;
		if($processResult==true){
			header('Location: home.php?action=affiliatesadmin&subaction=requests&payment=true') ;
			exit() ;
		}
	}
	if($_GET['process']=='reject'){
		$processResult = $requestReferrals->decline() ;
		if($processResult==true){
			header('Location: home.php?action=affiliatesadmin&subaction=requests&decline=true') ;
			exit() ;
		}
	}

	// if we are not processing, get the details for display
	$requestReferrals = $requestReferrals->getPaymentDetails() ;

	$recipient = new affiliate ;
	$recipient->setAffiliateId($requestReferrals[0]['affiliate_id']) ;
	$recipient = $recipient->getaffiliate(false) ;


	if($_GET['toggle']=='undone'){
		$screen_message = draw_icon(ICON_GOOD).'The status for the selected referral was set to not rejected.' ;
		$screen_message_type = 'success' ;
	}
	if($_GET['toggle']=='rejected'){
		$screen_message = draw_icon(ICON_ALERT).'The status for the selected referral was set to rejected.' ;
		$screen_message_type = 'notice' ;
	}


?>
