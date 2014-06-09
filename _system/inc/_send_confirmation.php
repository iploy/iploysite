<?php

	// E-Mail Basic Information
	$email_to = $_SESSION['email'] ;
	// Default subject if not set by form
	$email_subject = $_SESSION['APP_CLIENT_NAME'].' '.ucfirst($signup_mode).' Account Confirmation' ;
	$headers = "MIME-Version: 1.0\r\n" ;
	$headers.= "Content-type: text/html;" ;
	$headers.= " charset=iso-8859-1\r\n" ;
	$headers.= "From: ".$_SESSION['EMAIL_FROM_NAME']." <noreply@". str_replace("www.","", $_SERVER['HTTP_HOST']).">" ;
	// Create link and get mail content
	$confirm_link = SITE_DOMAIN.$signup_mode.'_signup.php?step=3&email='.urlencode($_SESSION['email']).'&id='.$_SESSION['user_id'].'&date_created='.urlencode($_SESSION['date_created']) ;
	$msg = file_get_contents('email_templates/_confirm_email.php') ;
	$msg = str_replace('{SITENAME}',$_SESSION['APP_CLIENT_NAME'],$msg) ;
	$msg = str_replace('{APPNAME}',$_SESSION['APP_SITE_NAME'],$msg) ;
	$msg = str_replace('{USEREMAIL}',$_SESSION['email'],$msg) ;
	$msg = str_replace('{SIGNUPTYPE}',ucfirst($signup_mode),$msg) ;
	$msg = str_replace('{SITEDOMAIN}',SITE_DOMAIN,$msg) ;
	$msg = str_replace('{CONFIRMLINK}',$confirm_link,$msg) ;
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