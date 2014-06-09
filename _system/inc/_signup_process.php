<?php

	// Set options based on previous page's include prefix variable
	if($signup_mode=='graduate'){
		$signup_mode = 'graduate' ;
		$user_level = 1 ;
	} else {
		$signup_mode = 'employer' ;
		$user_level = 2 ;
	}

	if($_GET['post']=='step1'&&$_POST['email']!=''&&$_POST['password']!=''&&$_POST['password_conf']!=''){
		if($_POST['password']==$_POST['password']){
			include_once('_system/classes/user_info.php') ;
			$user_function = new user_info ;
			$email_check_row = $user_function->getInfoByEmail($_POST['email'],false) ;
			if(!$email_check_row['id']){
				$email_check_id = $user_function->createUserAndReturnId($_POST['email'],$_POST['password'],'1',$user_level,'0',true,$_POST['referrer']) ;
				header('Location: ?step=2') ;
				exit() ;
			} else {
				// do nothing and report problem
				$screen_message = draw_icon(ICON_BAD).'The email address used already exists on our sysem. <a href="password_reminder.php" >Click here to send a password reminder</a>.' ;
				$screen_message_type = 'error' ;
			}
		} else {
			// passwords do not match, error and return
			$screen_message = draw_icon(ICON_BAD).'The passwords you entered did not match, please try again.' ;
			$screen_message_type = 'error' ;
		}
	}

	// Fix step 2 with no session variable
	if($_GET['step']=='2'&&$_SESSION['email']==''){
		// echo '<!-- '.$_SESSION['email'].':'.$_POST['email'].':'.print_r($_POST,true).':'.print_r($_SESSION,true).' -->'."\n" ;
		header('Location: login.php?session=expired&subcode=001') ;
		exit() ;
	}

	// Set variables to resend the email
	if($_GET['resend_confirmation']=='true'){
		if($_SESSION['email']){
			$_SESSION['new_signup'] = true ;
			header('Location: '.$signup_mode.'_signup.php?step=2&resent=true') ;
			exit() ;
		} else {
			header('Location: login.php?session=expired&subcode=002') ;
			exit() ;
		}
	}

?>