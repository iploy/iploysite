<?php
	include_once('_system/classes/affiliate.php') ;
	include_once('_system/_config/config_affiliates_arrays.php') ;

	// redirect if we have no affiliate ID, no querystring variable or the requested number of grads is not offered as payment
	if(!$_SESSION['affiliate_id']||$_GET['graduates']==''||!is_numeric($_GET['graduates'])||!in_array($_GET['graduates'],$payouts)){
		header('Location: home.php?action='.$_GET['action']) ;
		exit ;
	}


	// by this point we can process the claim
	$affiliate = new affiliate ;
	$affiliate->setAffiliateId($_SESSION['affiliate_id']) ;
	$claimResult = $affiliate->claimPayment($_GET['graduates'],$payouts) ;
	// echo '<p>'.$claimResult.'</p>' ;
	header('Location: home.php?action='.$_GET['action'].'&claim='.$claimResult) ;

?>