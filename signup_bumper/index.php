<?php

	include_once('../_system/_config/configure.php') ;
	include_once(SITE_PATH.'_system/classes/affiliate.php') ;
	$plit = explode('/',$_GET['af']) ;
	// get info
	$affiliate = new affiliate ;
	$affiliate->setAffiliateUserName($plit[0]) ;
	$affiliate = $affiliate->getaffiliate($setSessions=false) ;
	// set the referrer
	$_SESSION['signup_referrer'] = $affiliate['affiliate_id'] ;
	// echo '<br />'.$_SESSION['signup_referrer'] ;
	//echo '<br />'.$plit[0] ;
	header('Location: '.SITE_FOLDER.'graduate_signup.php') ;
	exit() ;

?>