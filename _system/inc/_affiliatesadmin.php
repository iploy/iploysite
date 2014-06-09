<?php
if($_GET['subaction']!=''){

	if($_GET['subaction']=='requests'){
		// requests for payment
		include(SITE_PATH.'_system/inc/_affiliatesadmin_requests.php') ;
	
	} elseif($_GET['subaction']=='viewrequest'){
		// requests for payment
		include(SITE_PATH.'_system/inc/_affiliatesadmin_viewrequest.php') ;
	
	} elseif($_GET['subaction']=='users'){
		// list of users
		include(SITE_PATH.'_system/inc/_affiliatesadmin_users.php') ;
	
	} elseif($_GET['subaction']=='history'){
		// payment history
		include(SITE_PATH.'_system/inc/_affiliatesadmin_history.php') ;
	
	} elseif($_GET['subaction']=='historyout'){
		// payment history
		include(SITE_PATH.'_system/inc/_affiliatesadmin_historyout.php') ;
	
	} elseif($_GET['subaction']=='viewuser'){
		// list of users
		include(SITE_PATH.'_system/inc/_affiliatesadmin_viewuser.php') ;
	} else {
		echo '<h1>Error</h1>' ;
		echo '<p>The page you are trying to access does not exist.</p>' ;
	}

	echo '<div class="greydivider" ></div>'."\n" ;
	$subClass = 'inline' ;
	include(SITE_PATH.'_system/inc/_nav_sub_affliates.php') ;

} else {
	echo '<h1>Affiliates Admin</h1>'."\n" ;
	$subClass = 'icolist' ;
	include(SITE_PATH.'_system/inc/_nav_sub_affliates.php') ;
}

?>