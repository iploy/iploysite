<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/classes/download.php') ;

	$download_function = new download ;
	$download_function->setUserId($_GET['userid']) ;
	$download_function->setSize($_GET['size']) ;
	$download_function->setType($_GET['type']) ;
	$download_function->setAdminMode($_GET['admin']) ;

	$download_function->startDownload() ;

?>