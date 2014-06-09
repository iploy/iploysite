<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	include_once('_system/classes/session_killer.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	// Process Logout
	if($_GET['action']=='logout'){
		$allow_bounce = true ;
		$no_bounceback_array = array() ;
		$no_bounceback_array[] = 'login.php' ;
		$no_bounceback_array[] = 'home.php' ;
		$no_bounceback_array[] = 'message_send.php' ;
		$no_bounceback_array[] = 'message_view.php' ;
		$no_bounceback_array[] = 'https://' ;
		$kill_function = new sessionKiller ;
		$kill_function->killAll() ;
		foreach($no_bounceback_array as $no_bounceback){
			if(strstr($_SERVER['HTTP_REFERER'],$no_bounceback)){
				$allow_bounce = false ;
			}
			if($_SERVER['HTTP_REFERER']==''){
				$allow_bounce = false ;
			}
		}
		if($allow_bounce==true){
			if(!strstr($_SERVER['HTTP_REFERER'],'?')){
				$postfix = '?' ;
			} else {
				$postfix = '&amp;' ;
			}
			$postfix.='action=logout' ;
			header('Location: '.$_SERVER['HTTP_REFERER'].$postfix) ;
			exit() ;
		}
	}

	// Process login
	if($_POST['email']!=''&&$_POST['password']!=''){
		$login_success = false ;
		include_once('_system/classes/user_info.php') ;
		$user_function = new user_info ;
		$email_check_row = $user_function->getInfoByEmail($_POST['email'],true) ;
		if($email_check_row['id']){
			//if($email_check_row['is_active']==1){
				if(md5($_POST['password'])==$email_check_row['password']){
					$login_success = true ;
					// Now if the email is confirmed go to the Homepage, else go to the resend confimation screen
					if($_SESSION['email_is_confirmed']==1){
						include_once('_system/classes/affiliate.php') ;
						// if employer, get the base level contact information from the address book
						if($_SESSION['user_level']==1){
							include_once('_system/classes/graduate_admin.php') ;
							$graduate_function = new graduate_info ;
							$this_graduate = $graduate_function->getGraduateByID($_SESSION['user_id'],true) ;
						}elseif($_SESSION['user_level']==2){
							include_once('_system/classes/employer_admin.php') ;
							$employer_login_func = new employer_admin ;
							$employer_login_func->getEmployerByID($_SESSION['user_id'],true) ;
						}
						// grab the affiliate info
						$affiliate = new affiliate ;
						$affiliate->setAffiliateUserId($_SESSION['user_id']) ;
						$this_affiliate = $affiliate->getaffiliate(true) ;
						// now redirect to home
						header('Location: home.php') ;
						exit() ;
					} else {
						$_SESSION['new_signup'] = true ;
						if($_SESSION['user_level']==1){
							header('Location: graduate_signup.php?step=2') ;
							exit() ;
						} elseif($_SESSION['user_level']==2){
							header('Location: employer_signup.php?step=2') ;
							exit() ;
						}
					}
				}
			//} else {
			//	// do nothing and report problem
			//	$email_check_row = $user_function->killSessions() ;
			//	$screen_message = draw_icon(ICON_BAD).'Login failed. Your account has been disabled by our administrators.' ;
			//	$screen_message_type = 'error' ;
			//}
		}
		if($login_success==false&&$screen_message==''){
			// do nothing and report problem
			$email_check_row = $user_function->killSessions() ;
			$screen_message = draw_icon(ICON_BAD).'Login failed. The user email or password may have been entered incorrectly, please try again.' ;
			$screen_message_type = 'error' ;
		}
	}


	$reminder_done = false ;
	if($_GET['action']=='reminder'&&$_POST['reminder_email']!=''){
		include_once('_system/classes/user_info.php') ;
		$user_function = new user_info ;
		$email_check_row = $user_function->getInfoByEmail($_POST['reminder_email'],false) ;
		if($email_check_row['id']){
			if($email_check_row['is_active']==1){
				// now send the info
				$this_random_str = $user_function->createPasswordResetRequest($email_check_row['id'],mysql_escape_string($_POST['reminder_email'])) ;
				include('_system/inc/_send_password_reset.php') ;
				// report to screen
				$screen_message = draw_icon(ICON_GOOD).'An email was sent to '.$_POST['reminder_email'].' with instructions on how to reset your password.' ;
				$screen_message_type = 'success' ;
				$reminder_done = true ;
			} else {
				// do nothing and report problem
				$email_check_row = $user_function->killSessions() ;
				$screen_message = draw_icon(ICON_BAD).'Your account has been disabled by our administrators.' ;
				$screen_message_type = 'error' ;
			}
		} else {
			// do nothing and report problem
			$email_check_row = $user_function->killSessions() ;
			$screen_message = draw_icon(ICON_BAD).'The email you entered does not exist on our system.' ;
			$screen_message_type = 'error' ;
		}
	}



	// Check password reset validity
	$genuine_reminder = false ;
	if($_GET['action']=='reset'){
		include_once('_system/classes/user_info.php') ;
		$user_function = new user_info ;
		$email_check_row = $user_function->getInfoByEmail($_GET['email'],false) ;
		if($email_check_row['id']){
			if($email_check_row['is_active']==1){
				// Now check reset requests
				$email_check_row = $user_function->confirmPasswordResetRequest($_GET['resetstr'],$_GET['email']) ;
				if($email_check_row!=0){
					if($_GET['subaction']=='changepass'){
						if($_POST['new_password']==$_POST['conf_new_password']){
							$user_function->updatePassword($email_check_row,$_POST['new_password']) ;
							$screen_message = draw_icon(ICON_GOOD).'Your password was updated successfully. Please login using your new password.' ;
							$screen_message_type = 'success' ;
						} else {
							$genuine_reminder = true ;
							$screen_message = draw_icon(ICON_BAD).'The passwords you entered did not match, please try again.' ;
							$screen_message_type = 'error' ;
						}
					} else {
						$genuine_reminder = true ;
					}
				} else {
					// do nothing and report problem
					$email_check_row = $user_function->killSessions() ;
					$screen_message = draw_icon(ICON_BAD).'There was no valid request made to reset this email address or the request has expired.' ;
					$screen_message_type = 'error' ;
				}
			} else {
				// do nothing and report problem
				$email_check_row = $user_function->killSessions() ;
				$screen_message = draw_icon(ICON_BAD).'Your account has been disabled by our administrators.' ;
				$screen_message_type = 'error' ;
			}
		} else {
			// do nothing and report problem
			$email_check_row = $user_function->killSessions() ;
			$screen_message = draw_icon(ICON_BAD).'The email you entered does not exist on our system.' ;
			$screen_message_type = 'error' ;
		}
	}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> | My iPloy Login</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
            <div class="bodymain_center" >
				<h1><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> Login</h1>
				<?php
                    if($_GET['action']=='reminder'&&$reminder_done!=true){
                    ?>
                    <p>Please enter your login information in the form fields provided.</p>
                    <div class="divider" ></div>
                    <h1>Reset Password</h1>
                    <p>In order to recieve this password reminder, you must have access to the email address used to signup for the account.</p>
                    <p>Once you submit this request, and email will be sent to your registered address.</p>
                    <?php include('_login_reminder_form.php') ; ?>
                    <p><?php echo draw_icon(ICON_BACK) ; ?><a href="login.php" >Back to Login</a></p>
                    <?php
                } elseif($_GET['action']=='reset'&&$genuine_reminder==true){
                    ?>
                    <p>Please enter your required password in the boxes below.</p>
                    <div class="divider" ></div>
                    <h1>Change Your Password</h1>
                    <p>Please enter the new password you would like to use to access <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?>.</p>
                    <?php include('_system/inc/_reset_password_form.php') ; ?>
                    <p><?php echo draw_icon(ICON_BACK) ; ?><a href="login.php" >Back to Login</a></p>
                    <?php
                } else {
                    ?>
                    <p>Please enter your login information in the form fields provided.</p>
                    <div class="divider" ></div>
                    <?php include('_login_form.php') ; ?>
                    <p><img src="images/icons/question.png" width="16" height="16" border="0" alt="" class="ico" /><a href="login.php?action=reminder" >Forgotten your password? Click here to reset it.</a></p>
                    <p><?php echo draw_icon(ICON_ACCOUNT) ; ?>Don't have an account? <a href="graduate_signup.php" >Click here if you are a graduate</a> or <a href="employer_signup.php" >click here if you are an employer</a>.</p>
                    <?php
                }
                ?>
            </div>
        </div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>

</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
