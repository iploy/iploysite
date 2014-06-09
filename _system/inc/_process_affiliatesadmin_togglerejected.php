<?php
	include_once('_system/classes/affiliate.php') ;

	if($_GET['requestid']==''||$_GET['referralid']==''){
		header('Location: home.php?action=affiliatesadmin&subaction=requests') ;
		exit() ;
	}


	$affiliate = new affiliate ;
	$affiliate->setPaymentId($_GET['requestid']) ;
	$affiliate->setReferralId($_GET['referralid']) ;
	$affiliate = $affiliate->toggleDeclinedStatus() ;

	header('Location: home.php?action=affiliatesadmin&subaction=viewrequest&requestid='.$_GET['requestid'].'&toggle='.($affiliate=='0' ? 'undone' : 'rejected')) ;
	exit() ;


?>