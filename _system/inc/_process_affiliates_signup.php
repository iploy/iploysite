<?php
	include_once('_system/classes/affiliate.php') ;

	$affiliateInfo = array() ;
	$affiliateInfo = $_POST ;
	// if signup attepted, process it here
	if($_POST){
		$affiliateSignup = new affiliate ;
		$affiliateSignup->setAffiliateUserId($_SESSION['user_id']) ;
		$screen_message_array = array() ;
		// check username first
		if(strlen($_POST['username'])<4){
			$screen_message_array[] = 'Your username must be at least 3 characters in length.' ;
		}
		// check username
		if(preg_match('/[^A-Za-z0-9]/',$_POST['username'])){
			$screen_message_array[] = 'Your username can only contain letters and numbers.' ;
		}
		// if no signup errors, check if the username is unique
		if($affiliateSignup->usernameIsUnique($_POST['username'])==false){
			$screen_message_array[] = 'The username you entered already exists on our system.' ;
		}
		// check username first
		if(strlen($_POST['payment_name'])<4){
			$screen_message_array[] = 'Please enter the name of the payment recipient.' ;
		}
		// check username first
		if(strlen($_POST['payment_address_1'])<4){
			$screen_message_array[] = 'Address Line 1 is a required field.' ;
		}
		// check username first
		if(strlen($_POST['payment_address_town'])<4){
			$screen_message_array[] = 'Town is a required field.' ;
		}
		// check username first
		if(strlen($_POST['payment_address_county'])<4){
			$screen_message_array[] = 'County is a required field.' ;
		}
		// check username first
		if(strlen($_POST['payment_address_postcode'])<4){
			$screen_message_array[] = 'Postcode is a required field.' ;
		}
		// check for errors
		if(sizeof($screen_message_array)==0){
			// add true to set sessions and log the user in ready
			if($affiliateSignup->signup(true)!=''){
				header('Location: home.php?action=affiliates&signup=good') ;
				exit() ;
			} else {
				$screen_message = draw_icon(ICON_BAD).'An unknown error occured with the signup process. Please try again or notify our admin team via the <a href="contact_us.php" >contact page</a>.' ;
				$screen_message_type = 'error' ;
			}
		} else {
			// else set the affiliateInfo variable as the post array for repopulation
			$screen_message = '<b>Your affiliate account could not be created due to the following errors</b>:' ;
			$screen_message_type = 'error' ;
		}
	}

?>