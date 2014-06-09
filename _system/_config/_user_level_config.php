<?php

	$user_level_labels = array() ;
	$user_level_labels['0'] = 'Superuser' ;
	$user_level_labels['1'] = 'Graduate' ;
	$user_level_labels['2'] = 'Employer' ;
	$user_level_labels['3'] = 'iPloy Administrator' ;

	// Super Users pretending to be other users
	if($_SESSION['user_level']==0&&$_GET['action']=='su0'){
		$_SESSION['su_level_mask'] = 0 ;
	}elseif($_SESSION['user_level']==0&&$_GET['action']=='su1'){
		$_SESSION['su_level_mask'] = 1 ;
	}elseif($_SESSION['user_level']==0&&$_GET['action']=='su2'){
		$_SESSION['su_level_mask'] = 2 ;
	}elseif($_SESSION['user_level']==0&&$_GET['action']=='su3'){
		$_SESSION['su_level_mask'] = 3 ;
	}

	// Super Users = 0
	if(($_SESSION['user_level']==='0'||$_SESSION['user_level']===0)&&$_SESSION['su_level_mask']===0){
		$nav_include = '_nav_superusers.php' ;
		$content_include = '_admin_superusers.php' ;
		$my_account_title_txt = 'Super User Settings' ;
		$my_account_link_txt = 'Site Admin' ;
		$my_account_icon_class = 'link_settings' ;
		$_SESSION['site_style'] = 'superuser' ;
	// Graduates = 1
	} elseif($_SESSION['user_level']==1||($_SESSION['user_level']==0&&$_SESSION['su_level_mask']==1)){
		$nav_include = '_nav_graduates.php' ;
		$content_include = '_admin_graduates.php' ;
		$my_account_title_txt = 'Graduate Profile' ;
		$my_account_link_txt = 'Profile Admin' ;
		$my_account_icon_class = 'link_profile' ;
		$_SESSION['site_style'] = 'graduates' ;
	// Employers = 1
	} elseif($_SESSION['user_level']==2||($_SESSION['user_level']==0&&$_SESSION['su_level_mask']==2)){
		$nav_include = '_nav_employers.php' ;
		$content_include = '_admin_employers.php' ;
		$my_account_title_txt = 'Manage Account' ;
		$my_account_link_txt = 'My Account' ;
		$my_account_icon_class = 'link_account' ;
		$_SESSION['site_style'] = 'employers' ;
	// iPloy Administrators = 3
	} elseif($_SESSION['user_level']==3||($_SESSION['user_level']==0&&$_SESSION['su_level_mask']==3)){
		$nav_include = '_nav_iploy_admin.php' ;
		$content_include = '_admin_iploy_admin.php' ;
		$my_account_title_txt = $_SESSION['APP_CLIENT_NAME'].' Administrator Settings' ;
		$my_account_link_txt = 'Site Admin' ;
		$my_account_icon_class = 'link_settings' ;
		$_SESSION['site_style'] = 'admin' ;
	}

?>