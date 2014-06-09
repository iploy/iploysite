<?php
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	function link_disabler($link){
		if(IS_LOCALHOST!=true){
			echo '#"  onclick="alert(\'PLEASE NOTE:\nThis site is in an early beta stage and is still under construction. Check back soon for regular updates and changes to functionality\');' ;
		} else {
			echo $link ;
		}
	}
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 
	//	TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP TEMP 

	include_once('_system/functions/draw_icon.php') ;
	// Global error messages
	if($_GET['session']=='expired'){
		$screen_message = draw_icon(ICON_BAD).'The page you are trying to access is for '.$_SESSION['APP_CLIENT_NAME'].' members only. If you were logged in, your session may have expired due to inactivity. <b>Please login to continue</b>.' ;
		$screen_message_type = 'error' ;
	}elseif($_GET['download']=='prohibited'){
		$screen_message = draw_icon(ICON_BAD).'Direct hotlinking to our files is prohibited.' ;
		$screen_message_type = 'error' ;
	}elseif($_GET['download']=='unverified'||$_GET['download']=='permissionerror'||$_GET['error']=='permissionerror'){
		$screen_message = draw_icon(ICON_BAD).'You do not have permission to do that. Session Terminated.' ;
		$screen_message_type = 'error' ;
	}elseif($_GET['download']=='qserror'){
		$screen_message = draw_icon(ICON_BAD).'The generated file link may be damaged, please contact an administrator.' ;
		$screen_message_type = 'error' ;
	}elseif($_GET['force_session_refresh']=='true'){
		$screen_message = draw_icon(ICON_GOOD).'Global session configuration variables have been refreshed.' ;
		$screen_message_type = 'success' ;
	}elseif($_GET['img']=='prohibited'){
		$screen_message = draw_icon(ICON_BAD).'Direct hotlinking to our images is prohibited.' ;
		$screen_message_type = 'error' ;
	}elseif($_GET['img']=='qserror'){
		$screen_message = draw_icon(ICON_BAD).'The image source link may be damaged, please contact an administrator.' ;
		$screen_message_type = 'error' ;
	}elseif($_GET['action']=='logout'){
		$screen_message = draw_icon(ICON_GOOD).'You have been logged out successfully' ;
		$screen_message_type = 'success' ;
	}elseif($_GET['message']=='sent'){
		$screen_message = draw_icon('email.png').'Your message was sent successfully' ;
		$screen_message_type = 'success' ;
	}

?>