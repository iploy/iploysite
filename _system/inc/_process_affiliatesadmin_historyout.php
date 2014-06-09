<?php

	include_once('_system/classes/affiliate_list.php') ;
	include_once('_system/_config/config_affiliates_arrays.php') ;

	$affiliateList = new affiliate_list ;
	if($_GET['paymentid']!=''){
		// try to load the individual
		$affiliateList->setPaymentId($_GET['paymentid']) ;
		$affiliateList->setGroupList(false) ;
		$affiliateList->setIsPaid(true) ;
		$transactionData = $affiliateList->getTransactions() ;
	}
	// echo sizeof($transactionData) ;
	// if no individual is requested, or it failed to load, just show the list
	// get the unpaid list
	if(sizeof($transactionData)==0){
		$affiliateList->setPaymentId('') ;
		$affiliateList->setGroupList(true) ;
		$affiliateList->setIsPaid(true) ;
		$affiliatesPaid = $affiliateList->getTransactions() ;
		//echo '<p>'.print_r($affiliatesUnpaid).'</p>' ;
	}

?>