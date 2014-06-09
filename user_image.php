<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/classes/user_img.php') ;
	// include_once('_system/classes/user_img_OLD.php') ;

	$user_img_function = new userImg ;
	$user_img_function->setUserId($_GET['userid']) ;
	if($_GET['size']!=''){
		$user_img_function->setSize($_GET['size']) ;
	} else {
		if($_GET['width']!=''){
			$user_img_function->setWidth($_GET['width']) ;
		}
		if($_GET['height']!=''){
			$user_img_function->setHeight($_GET['height']) ;
		}
	}
	// $user_img_function->setAdminMode($_GET['admin']) ;
	// $user_img_function->setType($_GET['type']) ;
	//$user_img_function->setDateString($_GET['datestr']) ;

	$user_img_function->getUserImage() ;

?>