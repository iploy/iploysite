<?php


//	THIS IS NOT FINISHED!!!!!!!!!!

	// E-Mail Basic Information
	$email_to = $_POST['reminder_email'] ;
	// Default subject if not set by form
	$email_subject = $_SESSION['APP_CLIENT_NAME'].' Account Password Reset Confirmation' ;
	$headers = "MIME-Version: 1.0\r\n" ;
	$headers.= "Content-type: text/html;" ;
	$headers.= " charset=iso-8859-1\r\n" ;
	$headers.= "From: ".$_SESSION['EMAIL_FROM_NAME']." <noreply@". str_replace("www.","", $_SERVER['HTTP_HOST']).">" ;
	// Create link and get mail content
	$reset_link = SITE_DOMAIN.'login.php?action=reset&amp;email='.$_POST['reminder_email'].'&amp;resetstr='.$this_random_str ;
	$msg = file_get_contents('email_templates/password_reset_email.php') ;
	$msg = str_replace('{SITENAME}',$_SESSION['APP_CLIENT_NAME'],$msg) ;
	$msg = str_replace('{APPNAME}',$_SESSION['APP_SITE_NAME'],$msg) ;
	$msg = str_replace('{USEREMAIL}',$_SESSION['email'],$msg) ;
	$msg = str_replace('{SITEDOMAIN}',SITE_DOMAIN,$msg) ;
	$msg = str_replace('{RESETLINK}',$reset_link,$msg) ;
	// echo '<p>'.$confirm_link .'</p>' ;
	// echo '<div style="border:1px solid #F00;" >'.$msg.'</div>' ;
	// Send the mail
	if (mail($email_to, $email_subject, $msg, $headers)) {
		// do nothing, all good!
	} else {
		$screen_message = draw_icon(ICON_BAD).'The information provided prevented the server sending the confirmation email. Please contact our administrator.' ;
		$screen_message_type = 'error' ;
	}

?>