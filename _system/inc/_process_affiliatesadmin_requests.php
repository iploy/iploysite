<?php

	include_once(SITE_PATH.'_system/classes/affiliate_list.php') ;

	$payment_requests = new affiliate_list ;
	$payment_requests->setIsPaid(false) ;
	$payment_requests->setIsDeclined(false) ;
	$payment_requests = $payment_requests->getPayments() ;









	if($_GET['payment']=='true'){
		$screen_message = draw_icon(ICON_GOOD).'The selected payment request was successfully marked as paid.' ;
		$screen_message_type = 'success' ;
	}
	if($_GET['decline']=='true'){
		$screen_message = draw_icon(ICON_ALERT).'The selected payment request was successfully rejected.' ;
		$screen_message_type = 'notice' ;
	}

?>