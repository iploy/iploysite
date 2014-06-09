<?php

include_once('_system/functions/graduate_profile_progress.php') ;

/*

	if(!$is_ipad_form_page&&!$is_admin_home&&$_SESSION['user_id']!=''&&$_SESSION['user_level']==1){
		// Get the profile progress if it hasnt been fetched already
		if($profile_progress==''){
			include_once('_system/functions/graduate_profile_progress.php') ;
			$profile_progress = graduate_profile_progress($graduate_required_fields,$_SESSION) ;
			$profile_progress_color = progress_colour($profile_progress) ;
		}
		if($profile_progress==100){
			if($_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID]==''){
				// check the user can even enter the promo
				include_once('_system/classes/promotions.php') ;
				$promotion_function = new promotion ;
				$promotion_function->setUserId($_SESSION['user_id']) ;
				$promotion_function->setPromoId(IPAD_PROMO_ID) ;
				$_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID] = $promotion_function->userCanEnter() ;
			}
			// now use the session to save lookups
			if($_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID]=='yes'){
				$msg = '' ;
				$msg.='<p><b>Name: </b><br />'.$_SESSION['first_name'].' '.$_SESSION['surname'].'</p>' ;
				$msg.='<p><b>Email: </b><br /><a href="mailto:'.$_SESSION['email'].'" >'.$_SESSION['email'].'</a></p>' ;
				$msg.='<p><b>Sent: </b><br />'.date(DM_PHP_SCREENDATE_FORMAT).'</a></p>' ;
				include_once('_system/classes/email.php') ;
				$email_function = new email ;
				$email_function->setToAddress(IPAD_PROMO_EMAIL) ;
				$email_function->setSubject('iPad Givaway 2 Entry Form') ;
				$email_function->setContents($msg) ;
				$email_send_result = $email_function->send() ;
				if(!$promotion_function){
					include_once('_system/classes/promotions.php') ;
					$promotion_function = new promotion ;
					$promotion_function->setUserId($_SESSION['user_id']) ;
					$promotion_function->setPromoId(IPAD_PROMO_ID) ;
				}
				$promotion_function_result = $promotion_function->userHasEntered() ;
				$_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID] = 'no' ;
			}
			?>
			<p class="success"><?php echo draw_icon('ipad.png') ; ?>You have completed enough of your profile to be entered into the iPad 2 prize draw. Be sure to <a href="http://www.facebook.com/pages/iPloy/261519700538406" target="_blank" ><b>like us on Facebook</b></a> for your chance to win.</p>
			<?php
		}
	}

*/

?>
