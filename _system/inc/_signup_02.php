<?php

	include_once('_system/classes/user_info.php') ;
	$user_function = new user_info ;
	$user_info_row = $user_function->getInfoByEmail($_SESSION['email'],false) ;

	$mail_sent = false ;
	if(!$user_info_row['id']){
		$user_exists = false ;
		$screen_message = draw_icon(ICON_BAD).'The specified email address does not exist on our system.' ;
		$screen_message_type = 'error' ;
		$user_function->killSessions() ;
	} else {
		$user_exists = true ;
	}

	if($_SESSION['new_signup']==true&&$user_exists==true){
		include('_send_confirmation.php') ;
		unset($_SESSION['new_signup']) ;
		$mail_sent = true ;
	}

	if($mail_sent==true||$_GET['resent']=='true'){
		$screen_message = draw_icon(ICON_GOOD).'The confirmation email has been resent to '.$_SESSION['email'] ;
		$screen_message_type = 'success' ;
	}

?>
<style type="text/css" >
.step2 {
	font-weight:bold;
	color:<?php echo $color ; ?> !important ;
}
</style>
<?php include('_signup_steps_ul_li.php') ; ?>

<div class="divider" ></div>
<h1>Confirm Your Email Address</h1>
<?php include('_screen_message_handler.php') ; ?>
<?php
	if($user_exists==true){
		?>
		<p>An email has been sent to the email address associated with this account with instructions on how to confirm your email address.</p>
		<p>If you do not receive this email within the next few minutes, please <a href="?step=2&resend_confirmation=true" >click here to send the email again</a>.</p>
		<?php
	} else {
		?>
		<p><b>An unexpected error occured</b></p>
		<p><a href="<?php echo $signup_mode ; ?>_signup.php" >Return to the <?php echo ucfirst($signup_mode) ; ?> Signup homepage</a></p>
		<?php
	}
?>
