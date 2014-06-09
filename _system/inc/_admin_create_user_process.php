<?php

	if($_GET['action']=='createuser'){

		if($_POST['email']!=''&&$_POST['password']!=''&&$_POST['user_level']!=''){
			include('_system/classes/user_info.php') ;
			$user_function = new user_info ;
			$email_check_row = $user_function->getInfoByEmail($_POST['email'],false) ;
			if(!$email_check_row['id']){
				$email_check_id = $user_function->createUserAndReturnId($_POST['email'],$_POST['password'],'1',$_POST['user_level'],'1',false) ;

				// E-Mail Basic Information
				$email_to = $_POST['email'] ;
				// Default subject if not set by form
				$email_subject = $_SESSION['APP_CLIENT_NAME'].' Account Now Active' ;
				$headers = "MIME-Version: 1.0\r\n" ;
				$headers.= "Content-type: text/html;" ;
				$headers.= " charset=iso-8859-1\r\n" ;
				$headers.= "From: ".$_SESSION['EMAIL_FROM_NAME']." <noreply@". str_replace("www.","", $_SERVER['HTTP_HOST']).">" ;
				// Create link and get mail content
				$login_link = SITE_DOMAIN.'login.php' ;
				if($_POST['user_level']==1){
					$user_type_text = 'Graduate' ;
					$msg = file_get_contents('email_templates/graduate_welcome_email.php') ;
				} elseif($_POST['user_level']==0){
					$user_type_text = 'Super User' ;
					$msg = file_get_contents('email_templates/superuser_welcome_email.php') ;
				} elseif($_POST['user_level']==2){
					$user_type_text = 'Employer' ;
					$msg = file_get_contents('email_templates/employer_welcome_email.php') ;
				} elseif($_POST['user_level']==3){
					$user_type_text = 'Administrator' ;
					$msg = file_get_contents('email_templates/admin_welcome_email.php') ;
				}
				
				$msg = str_replace('{SITENAME}',$_SESSION['APP_CLIENT_NAME'],$msg) ;
				$msg = str_replace('{APPNAME}',$_SESSION['APP_SITE_NAME'],$msg) ;
				$msg = str_replace('{SITEDOMAIN}',SITE_DOMAIN,$msg) ;
				$msg = str_replace('{LOGINLINK}',$login_link,$msg) ;
				// echo '<p>'.$confirm_link .'</p>' ;
				// echo '<div style="border:1px solid #F00;" >'.$msg.'</div>' ;
				// Send the mail

				if (mail($email_to, $email_subject, $msg, $headers)) {
					// do nothing, all good!
				} else {
					// do nothing and report problem
					$screen_message = draw_icon(ICON_BAD).'An error prevented the server sending the welcome email.' ;
					$screen_message_type = 'error' ;
					include('_screen_message_handler.php') ;
				}

				// Message to Screen
				$screen_message = draw_icon(ICON_GOOD).$user_type_text.' \''.$_POST['email'].'\' added successfully' ;
				$screen_message_type = 'success' ;

			} else {
				// do nothing and report problem
				$screen_message = draw_icon(ICON_BAD).'The email address used already exists on our sysem.' ;
				$screen_message_type = 'error' ;
			}
		} else {
			// do nothing and report problem
			$screen_message = draw_icon(ICON_BAD).'The fields on the create user form can not be blank, please try again.' ;
			$screen_message_type = 'error' ;
		}
	}

?>