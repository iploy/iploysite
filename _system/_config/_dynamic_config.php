<?php

	include_once(SITE_PATH.'_system/classes/connection.php') ;
	include_once(SITE_PATH.'_system/classes/dynamic_config.php') ;

	$force_reload = false ;

	// force reload if the last load was longer than the config time suggests
	if($_SESSION['LAST_LOAD'] < date(DM_PHP_DATE_FORMAT,strtotime(date(DM_PHP_DATE_FORMAT).' -'.DYNAMIC_CONFIG_FORCED_RELOAD))){
		$force_reload = true ;
	}
	// Force reload if requested by the querystring
	if($_GET['force_session_refresh']=='true'){
		$force_reload = true ;
	}

	if($_SESSION['APP_CLIENT_NAME']==''||$force_reload==true){
		$dynamic_config_func = new dynamic_config ;
		$dynamic_config_func->setDynamicConfig() ;
	}

?>