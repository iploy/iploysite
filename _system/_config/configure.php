<?php
	session_start() ;
	date_default_timezone_set("Europe/London") ;

	// TEMP DEFINES
	define('IPAD_PROMO_ID',1) ;
	define('IPAD_PROMO_EMAIL','admin@iploy.co.uk') ;

	define('DEMO_PROFILE_ID','224070911105646') ;

	define('PROFILE_PURCHASE_EXPIRY','6 months') ;

	// Filetypes
	define('DISALLOWED_FILETYPES','exe|php|php1|php2|php3|php4|php5|php6|php7|php8|js|cgi|perl|html|htm') ;

	// Search result setup
	define('SEARCH_RESULTS_PAGE_SIZE',20) ;
	define('SEARCH_RESULTS_AVAILABLE_PAGE_SIZES','20,50') ;


	// employer credits
	define('SSL_PAGES','home.php?action=credits,transaction_process.php,home.php?action=purchasehistory') ; // if the array string is present, redirect to SSL
	define('CREDIT_PACKS','1,5,20,50,100') ;
	define('CREDIT_PRICES','20,75,350,750,1200') ;


	// colors
	define('COLOR_EMPLOYER_NORMAL','#006FC2') ;
	define('COLOR_EMPLOYER_OVER','#00487C') ;
	define('COLOR_EMPLOYER_FAVICON','favicon_blue.ico') ;
	define('COLOR_GRADUATE_NORMAL','#E20514') ;
	define('COLOR_GRADUATE_OVER','#B2030F') ;
	define('COLOR_GRADUATE_FAVICON','favicon.ico') ;
	define('COLOR_ADMIN_NORMAL','#5C3CBF') ;
	define('COLOR_ADMIN_OVER','#461E9B') ;
	define('COLOR_ADMIN_FAVICON','favicon_purple.ico') ;
	define('COLOR_SUPERUSER_NORMAL','#DF5900') ;
	define('COLOR_SUPERUSER_OVER','#9F4000') ;
	define('COLOR_SUPERUSER_FAVICON','favicon_orange.ico') ;


	// Image uploads predefined sizes
	define('IMAGE_UPLOAD_THUMBNAIL_WIDTH',54) ;
	define('IMAGE_UPLOAD_THUMBNAIL_HEIGHT',45) ;
	define('IMAGE_UPLOAD_MEDIUM_WIDTH',110) ;
	define('IMAGE_UPLOAD_MEDIUM_HEIGHT',92) ;
	define('IMAGE_UPLOAD_LRGMEDIUM_WIDTH',160) ;
	define('IMAGE_UPLOAD_LRGMEDIUM_HEIGHT',134) ;
	define('IMAGE_UPLOAD_LARGE_WIDTH',270) ;
	define('IMAGE_UPLOAD_LARGE_HEIGHT',226) ;
	define('IMAGE_UPLOAD_EDITABLE_WIDTH',400) ;
	define('IMAGE_UPLOAD_EDITABLE_HEIGHT',400) ;

	// Lists
	define('DEFAULT_PAGE_SIZE',20) ;

	// Icons
	define('ICON_PATH','images/icons/') ;
	define('ICON_GOOD','thumb-up.png') ;
	define('ICON_BAD','stop.png') ;
	define('ICON_DELETE','delete.png') ;
	define('ICON_ALERT','alert.png') ;
	define('ICON_DOWNLOAD','download.png') ;
	define('ICON_BACK','back.png') ;
	define('ICON_ACCOUNT','account_sm.png') ;

	define('VACANCY_ICON_PATH','images/icons/bookmarks/') ;

	// Force reload of dynamic config after this length of time:
	define('DYNAMIC_CONFIG_FORCED_RELOAD','30 mins') ;
	define('DM_CHECK_FOR_NEW_MESSAGES','3 mins') ;

	// Database
	define('DIR_DB_HOST', 'localhost');
	define('DIR_DB_USER', 'dbo367746662');
	define('DIR_DB_PASSWORD', 'kes45kiw4hki83y');
	define('DIR_DB_DATABASE', 'db367746662');
	define('DIR_DB_PORT', '3316');
	define('DIR_DB_SOCKET', '/tmp/mysql5.sock');

	// Dates / Times
	define(DM_PHP_DATE_FORMAT,'Y-m-d H:i:s') ;
	define(DM_PHP_SCREENDATE_FORMAT,'l jS F, Y') ;
	define(DM_PHP_SCREENDATETIME_FORMAT,'l jS F, Y @ H:i') ;
	define(DM_PHP_SCREENSHORTDATE_FORMAT,'D jS M, Y') ;
	define(DM_PHP_SCREENSHORTDATETIME_FORMAT,'jS M Y H:i') ;
	define('DATE_TIME_NOW',date(DM_PHP_DATE_FORMAT)) ;
	define(DM_FORMS_DATE_FORMAT,'YYYY-MM-DD') ; // Standard date format, written to display and for null value removal
	define(DM_PHP_DATE_PICK_FORMAT,'jS F Y') ; // See CalendarEightysix config for more info / Must translate to DM_FORMS_DATE_FORMAT
	define(DM_JS_DATE_PICK_FORMAT,'%e%o %B %Y') ; // See CalendarEightysix config for more info / Must translate to DM_FORMS_DATE_FORMAT
	define(DM_FORMS_NULL_DATE,str_replace("D","0",str_replace("M","0",str_replace("Y","0",strtoupper(DM_FORMS_DATE_FORMAT))))) ;

	// config for image protection
	define(DM_DATESTR_FORMAT,'YmdHis') ;
	define(DM_GETIMG_ALLOWANCE,2) ;


// ---------------------------------------------------------------------------------------------------------
	if(!defined(SITE_PATH)){
		if(strpos($_SERVER['HTTP_HOST'],'localhost')>-1){ // fix for local 
			define('SITE_FOLDER','/iPloy/') ; 
			define('IS_LOCALHOST',true) ;
			define('IS_DEV',true) ;
		} elseif(strpos($_SERVER['HTTP_HOST'],'devmacserver')>-1){ // Fix for iploy dev
			define('SITE_FOLDER','/iploydev/') ; 
			define('IS_LOCALHOST',false) ;
			define('IS_DEV',true) ;
		} else { // else live site
			define('SITE_FOLDER','/') ;
			define('IS_LOCALHOST',false) ;
			define('IS_DEV',false) ;
		}
		// generic
		define('SITE_PATH', $_SERVER['DOCUMENT_ROOT'].SITE_FOLDER);
		define('SITE_DOMAIN',($_SERVER["HTTPS"]=="on" ? "https://" : "http://").$_SERVER["HTTP_HOST"].SITE_FOLDER) ;
	}
	// Local Database fix for Offline Dev
	if(strpos($_SERVER['HTTP_HOST'],'localhost')>-1||strpos($_SERVER['HTTP_HOST'],'bob-pc')>-1){
		define(DM_DB_HOST,'localhost') ;
		define(DM_DB_NAME,'iploy_main') ;
		define(DM_DB_USER,'root') ;
		define(DM_DB_PASSWORD,'trombone') ;
	} else {
		define(DM_DB_HOST,DIR_DB_HOST.':'.DIR_DB_SOCKET) ;
		define(DM_DB_NAME,DIR_DB_DATABASE) ;
		define(DM_DB_USER,DIR_DB_USER) ;
		define(DM_DB_PASSWORD,DIR_DB_PASSWORD) ;
	}

	// Emails & comunication
	define('EMAIL_NOREPLY_ADDRESS','noreply@'.str_replace("www.","",$_SERVER["HTTP_HOST"])) ;
	define('EMAIL_NOSUBJECT','Do Not Reply To This Email') ;

// ---------------------------------------------------------------------------------------------------------

	include_once(SITE_PATH.'_system/inc/_ssl_redirect.php') ;
	include_once(SITE_PATH.'_system/_config/_dynamic_config.php') ;
	include_once(SITE_PATH.'_system/_config/_user_level_config.php') ;
?>