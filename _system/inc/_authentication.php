<?php

	if(!isset($_SESSION['user_level'])){
		header('Location: login.php?session=expired') ;
		exit() ;
	}
	if($_SESSION['email_is_confirmed']!=1){
		if($_SESSION['user_level']==1){
			$signupPage = 'graduate_signup.php' ;
		}elseif($_SESSION['user_level']==2){
			$signupPage = 'employer_signup.php' ;
		}
		header('Location: '.$signupPage.'?step=2') ;
		exit() ;
	}

?>