<?php

	include_once('_system/_config/configure.php') ;
	// authentication included for basic "are you logged in" check
	include('_system/inc/_authentication.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 


	// NEED TO CHECK THE USER ACTUALLY EXISTS


	// check for a profileID in the querystring and redirect if not present
	if($_GET['profileid']==''||!is_numeric($_GET['profileid'])){
		header('Location: search.php?error=noprofileid') ;
		exit() ;
	}

	// SCREEN MESSAGE HANDLER
	// is the user an employer... if not, display an "employer account required for this action
	if($_SESSION['user_level']!=2){
		$screen_message = draw_icon(ICON_BAD).'<b>Employers Only</b>: You must have an employer account in order to buy profiles' ;
		$screen_message_type = 'error' ;

	}else{// if the user is an employer, perform the checks
		// First, check the requested user even exists
		include_once('_system/classes/user_info.php') ;
		$user_info_function = new user_info ;
		$user_info = $user_info_function->getInfoByUserId($_GET['profileid'],false) ;
		if($user_info==false){
			header('Location: search.php?error=nouser') ;
			exit() ;
		} elseif($user_info['is_active']==0){ // and if the user is active or not
			header('Location: search.php?error=inactive') ;
			exit() ;
		}
		// does the user have any credits, if so... disply the "confirm purchase" message
		include_once('_system/classes/credits.php') ;
		$credits_function = new credits ;
		$remaining_credits = $credits_function->checkCredits($_SESSION['user_id']) ;
		if($remaining_credits==0){
			$screen_message = draw_icon(ICON_BAD).'<b>No Credits Remaining</b>: You have no credits left in order to buy profiles' ;
			$screen_message_type = 'error' ;
		}elseif($remaining_credits==1){
			$screen_message = draw_icon(ICON_ALERT).'<b>Confirm Unlock Profile</b>: Unlocking this profile will spend your last remaining credit, please confirm this request to continue.' ;
			$screen_message_type = 'notice' ;
		} else {
			$screen_message = draw_icon(ICON_ALERT).'<b>Confirm Unlock Profile</b>: Unlocking this profile will spend <b>1</b> credit from your account, please confirm this request to continue.' ;
			$screen_message_type = 'notice' ;
		}
		// initiate the transactions function
		include_once('_system/classes/transactions.php') ;
		$transactions_function = new transactions ;
	}
	
	// has the user confirmed the purchase, if so.. unlock the profile, if not, do an error redirect
	if($_GET['unlock']=='confirm'&&$_GET['profileid']!=''&&$_GET['requestid']!=''){
		$confirm_string = $transactions_function->generateRequestId($_SESSION['user_id'],$_GET['profileid']) ;
		if($confirm_string==$_GET['requestid']&&$remaining_credits>0){
			$transaction_check_access_result = $transactions_function->checkEmployerToGraduateAccess($_SESSION['user_id'],$_GET['profileid']) ;
			if($transaction_check_access_result!=true){
				// if the employer doesnt already have access, grant access
				$transaction_grant_access_result = $transactions_function->grantEmployerToGraduateAccess($_SESSION['user_id'],$_GET['profileid'],$confirm_string) ;
				if($transaction_grant_access_result==true){
					// specnd the credit and grant access
					$credits_function->spendCredits($_SESSION['user_id'],1) ;
					header('Location: view_profile.php?profileid='.$_GET['profileid'].'&unlock=confirmed') ;
					exit() ;
				} else {
					header('Location: view_profile.php?profileid='.$_GET['profileid'].'&error=unlockfailed') ;
					exit() ;
				}
			} else {
				header('Location: view_profile.php?profileid='.$_GET['profileid'].'&notice=unlocked') ;
				exit() ;
			}
		} else {
			if($remaining_credits<1){
				header('Location: search.php?error=nocredits') ;
				exit() ;
			} else {
				header('Location: search.php?error=badrequest') ;
				exit() ;
			}
		}
	}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : Unlock Profile</title>
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
                <?php
					// has the user confirmed the purchase, if so.. try to unlock the profile
					// REQUESTED PROFILE SUMMARY HERE
					include_once('_system/classes/graduate_frontend.php') ;
					include_once('_system/functions/censor_word.php') ;
					$graduate_data_function = new graduate_data ;
					$graduate_data = $graduate_data_function->getGraduateByID($_GET['profileid']) ;
					$graduate_name_censored = $graduate_data['first_name'] ;
					if(trim($graduate_data['surname'])!=''){
						$graduate_name_censored.=' '.censor_word($graduate_data['surname']) ;
					}
				?>
                <h2>Please Confirm That You Wish To Unlock <?php echo $graduate_data['first_name'] ; ?>'s Profile</h2>
                    <?php
					
					// is the user an employer... if not, display an "employer account required for this action
					if($_SESSION['user_level']!=2){
						?>
						<p><?php echo draw_icon(ICON_ACCOUNT) ; ?>For more information about employer accounts, <a href="employers.php">click here</a>.</p>
						<p><?php echo draw_icon(ICON_ACCOUNT) ; ?>To sign up for an employer account, <a href="employer_signup.php">click here</a>.</p>
						<?php
					} else {
						if($remaining_credits==0){
							?>
							<h5>Please Credit Your Account</h5>
							<p>You have no credits remaining in order to buy credits</p>
							<div align="left" >
							<ul class="li_buttons" >
								<li><a href="?action=buy&profileid=<?php echo $_GET['profileid'] ; ?>" >Buy Credits and View Profile</a></li>
								<li><a href="view_profile.php?profileid=<?php echo $_GET['profileid'] ; ?>" onclick="this.href='javascript:history.back();'" >Back to Profile</a></li>
							</ul>
							</div>
							<?php
						}else{	
							// format the screen message
							if($remaining_credits==1){
								$scr_msg = 'You are about to spend your final credit to unlock '.$graduate_name_censored.'\'s profile. You will need to credit your account if you wish to unlock any additional profiles.' ;
							} else {
								$scr_msg = 'You are about to spend 1 of your '.$remaining_credits.' remaining credits to unlock '.$graduate_name_censored.'\'s profile. Please confirm this request to continue.' ;
							}
							// does the user have any credits, if so... disply the "confirm purchase" message
							$confirm_string = $transactions_function->generateRequestId($_SESSION['user_id'],$_GET['profileid']) ;
							?>
							<h5>Confirm Unlock Profile</h5>
							<p><?php echo $scr_msg ; ?></p>
							<div align="left" >
							<ul class="li_buttons" >
								<li><a href="?unlock=confirm&profileid=<?php echo $_GET['profileid'] ; ?>&requestid=<?php echo $confirm_string ; ?>" >I Wish To Unlock This Profile</a></li>
								<li><a href="view_profile.php?profileid=<?php echo $_GET['profileid'] ; ?>" >Cancel This Request</a></li>
							</ul>
							</div>
							<?php
						}
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
