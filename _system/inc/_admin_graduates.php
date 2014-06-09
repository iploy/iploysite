<?php

if($_GET['action']=='changepass'){

	$screen_message = '' ;

	if($_GET['subaction']=='changepass'){
		include('_system/inc/_change_password_process.php') ;
	}

	// Write messages for image admin sub actions
	include('_screen_message_handler.php') ;

	?>
	<h1>Change Password</h1>
	<p>Please fill out the information below to change your password</p>
	<?php include('_system/inc/_change_password_form.php') ; ?>
	<?php 

}elseif($_GET['action']=='messages'){
	// Write messages for image admin sub actions
	include_once('_system/inc/_graduate_progress_bar.php') ;
	include('_system/inc/_messages.php') ;

}elseif($_GET['action']=='youtube'){
	// Write messages for image admin sub actions
	include('_system/inc/_youtube.php') ;


}elseif($_GET['action']=='personal'){
	include('_graduate_personal.php') ;

}elseif($_GET['action']=='employment'){
	include('_graduate_employment.php') ;

}elseif($_GET['action']=='education'){
	include('_graduate_education.php') ;

}elseif($_GET['action']=='upload'){
	include('_graduate_uploads.php') ;

}elseif($_GET['action']=='affiliates'){
	include('_affiliates.php') ;

}else{

	// Default is profile Admin - This will eventually be swapped for a homepage featuring Message/etc
	include('_system/inc/_graduate_admin_home.php') ;

} 

?>