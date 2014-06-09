<?php

	include_once('_system/classes/user_info.php') ;

	if(strlen($_POST['new_password'])>5){
		if($_POST['new_password']==$_POST['conf_new_password']){
			$password_change_user_info = new user_info ;
			$pass_change_user = $password_change_user_info->getInfoByUserId($_SESSION['user_id'],false) ;
			if(md5($_POST['current_password'])==$pass_change_user['password']){
				$password_change_user_info->updatePassword($_SESSION['user_id'],$_POST['new_password']) ;
				$screen_message = draw_icon(ICON_GOOD).'The password change has been completed. Please use the new password next time you login' ;
				$screen_message_type = 'success' ;
			} else {
				$screen_message = draw_icon(ICON_BAD).'The password change request could not be completed. The old password was entered incorrectly.' ;
				$screen_message_type = 'error' ;
			}
		} else {
			$screen_message = draw_icon(ICON_BAD).'The password change request could not be completed. The confirmation password did not match.' ;
			$screen_message_type = 'error' ;
		}
	} else {
		$screen_message = draw_icon(ICON_BAD).'The password change request could not be completed. The new password must be 6 or more characters in length.' ;
		$screen_message_type = 'error' ;
	}


?>