<?php include_once('_admin_create_user_process.php') ; ?>
<?php include_once('_system/classes/user_info.php') ; ?>
<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>

<?php

	$screen_message = '' ;

	if($_GET['action']=='messages'){
		// Write messages for image admin sub actions
		include('_system/inc/_messages.php') ;

	} elseif($_GET['action']=='massmailing'){
		include('_system/inc/_mass_mailing.php') ;

	} elseif($_GET['action']=='affiliatesadmin'){
		include('_system/inc/_affiliatesadmin.php') ;

	} elseif($_GET['action']=='changepass'){
		
		if($_GET['subaction']=='changepass'){
			include('_change_password_process.php') ;
		}
		?>
        <h1>Member Admin Options</h1>
        <p>These are options exclusive to <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> administrators.</p>
        <?php
			// Write messages for image admin sub actions
			include('_screen_message_handler.php') ;
		?>
        <div class="divider" ></div>
		<h3>Change Password</h3>
		<p>Please fill out the information below to change your password</p>
		<?php include('_change_password_form.php') ; ?>
		<?php 

	} elseif($_GET['action']=='adminusers'){

		if($_GET['editid']!=''&&is_numeric($_GET['editid'])){

		} else {
			include('_admin_users_list.php') ;
		}

	} elseif($_GET['action']=='adduser'){

		?>
		<h1>Create a New User</h1>
		<p>These users will be pre-confirmed and will NOT require account activation</p>
		<?php include('_admin_create_user_form.php') ; ?>
		<?php

	} elseif($_GET['action']=='adminimg'||$_GET['action']=='admincv'||$_GET['action']=='admincertificate'){
		include('_system/inc/_confirm_files.php') ;

	// purchase report
	} elseif($_GET['action']=='purchasereport'){
		include('_system/inc/_admin_purchase_report.php') ;
	
	}else{
		
		if($_GET['subaction']=='changepass'){
			include('_change_password_process.php') ;
		}

		?>
        <h1>Statistics</h1>
        <p>Current information about the <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> website.</p>
        <div class="divider" ></div>
		<?php include('_iploy_admin_stats.php') ; ?>
        <?php

	} 

		?>