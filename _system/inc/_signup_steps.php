<?php
	if($_GET['step']=='2'&&$_SESSION['email']!=''){
		// show the confirm email send script
		include('_signup_02.php') ;
	} elseif($_GET['step']=='3'&&$_GET['email']!=''&&$_GET['id']!=''&&$_GET['date_created']!=''){
		// show the confirm email send script
		include('_signup_03.php') ;
	} else {
		// show the signup form
		include('_signup_01.php') ;
	}
?>