<?php

	include_once('_system/classes/user_info.php') ;
	$user_function = new user_info ;
	$user_function->killSessions() ;
	$user_info_row = $user_function->getInfoByEmail($_GET['email'],false) ;
	// Perform confrimation actions
	if($user_info_row['email_is_confirmed']==1){
		$user_confirmed = true ;
		$send_welcome_mail = false ;
		$screen_message = draw_icon(ICON_ALERT).'The  user \''.$_GET['email'].'\' was already active. Please <a href="login.php" >login</a> to manage your profile.' ;
		$screen_message_type = 'notice' ;
	} elseif ($user_info_row['id']==$_GET['id']&&$user_info_row['date_created']==$_GET['date_created']){
		$user_function->confirmEmail($_GET['email']) ;
		$user_info_row = $user_function->getInfoByEmail($_GET['email'],true) ;
		$user_confirmed = true ;
		$send_welcome_mail = true ;
		$screen_message = draw_icon(ICON_GOOD).'The user \''.$_GET['email'].'\' has been activated.' ;
		$screen_message_type = 'success' ;
	} else {
		$user_confirmed = false ;
		$send_welcome_mail = false ;
		$user_function->killSessions() ;
	}

?>
<style type="text/css" >
.step3 {
	font-weight:bold;
	color:<?php echo $color ; ?> !important ;
}
</style>

<?php include('_signup_steps_ul_li.php') ; ?>

<div class="divider" ></div>

<h1>Step 3</h1>
<?php include('_screen_message_handler.php') ; ?>
<?php
	if($user_confirmed==true){
		?><p><b>Thank you for confirming your email address.</b></p>
		<p>Your Free <?php echo $_SESSION['APP_CLIENT_NAME'].' '.ucfirst($signup_mode) ; ?> Account is now active.</p>
		<p>If you are not redirected automatically in <span id="countdown" >3 seconds</span>, <a href="home.php" >please click here to continue.</a></p>
		<script language="javascript" type="text/javascript" src="js/redirectWithCountdown.js" ></script>
		<script language="javascript" type="text/javascript" >
		window.addEvent('domready', function(){// Dom open
			redirectWithCountdown("home.php",3,'countdown');
		});
		</script>
		<?php
	} else {
		echo '<p>An issue with the link you clicked in the confirmation email has prevented your account from being activated.</p>' ;
		echo '<p>Please contact us immediately for further assistance.</p>' ;
	}

	if($send_welcome_mail==true){
		include('_send_welcome_mail.php') ;
	}
?>

