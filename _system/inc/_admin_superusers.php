<?php include('_admin_create_user_process.php') ; ?>
<?php

	// Process site option submission
	// APP_CLIENT_NAME APP_SITE_NAME EMAIL_FROM_NAME ALLOWED_FILES_CV ALLOWED_FILES_IMAGES
	if($_GET['action']=='options'){
		$all_fields_found = true ; 
		$operator = '' ;
		$sql = "UPDATE app_config SET " ;
		// Client name
		if($_POST['app_client_name']!=''){
			$sql.= $operator."app_client_name='".mysql_escape_string($_POST['app_client_name'])."' " ;
			$operator = ', ' ;
		} else {
			$screen_message = draw_icon(ICON_BAD).'You must specify a Client Name' ;
			$screen_message_type = 'error' ;
			$all_fields_found = false ;
		}
		// App name
		if($_POST['app_site_name']!=''){
			$sql.= $operator."app_site_name='".mysql_escape_string($_POST['app_site_name'])."' " ;
			$operator = ', ' ;
		} else {
			$screen_message = draw_icon(ICON_BAD).'You must specify an Application Name for this website' ;
			$screen_message_type = 'error' ;
			$all_fields_found = false ;
		}
		// Email from name
		if($_POST['email_from_name']!=''){
			$sql.= $operator."email_from_name='".mysql_escape_string($_POST['email_from_name'])."' " ;
			$operator = ', ' ;
		} else {
			$screen_message = draw_icon(ICON_BAD).'You must specify an Email Friendly Name' ;
			$screen_message_type = 'error' ;
			$all_fields_found = false ;
		}
		// Allowed in CV
		if($_POST['allowed_files_cv']!=''){
			$sql.= $operator."allowed_files_cv='".mysql_escape_string($_POST['allowed_files_cv'])."' " ;
			$operator = ', ' ;
		} else {
			$screen_message = draw_icon(ICON_BAD).'You must specify some allowed file types for the graduates CVs' ;
			$screen_message_type = 'error' ;
			$all_fields_found = false ;
		}
		// Allowed in photo
		if($_POST['allowed_files_images']!=''){
			$sql.= $operator."allowed_files_images='".mysql_escape_string($_POST['allowed_files_images'])."' " ;
			$operator = ', ' ;
		} else {
			$screen_message = draw_icon(ICON_BAD).'You must specify some allowed file types for the graduates photos' ;
			$screen_message_type = 'error' ;
			$all_fields_found = false ;
		}
		$sql.= "WHERE id='1' ; " ;
		// Update the database
		if($all_fields_found==true){
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			if(mysql_query($sql)){
				$screen_message = draw_icon(ICON_GOOD).'Changes to the website\'s core options have been updated' ;
				$screen_message_type = 'success' ;
				$dynamic_config_func = new dynamic_config ;
				$dynamic_config_func->setDynamicConfig() ;
			} else {
				$screen_message = draw_icon(ICON_BAD).'<b>The following MYSQL error occured:</b><br />'.mysql_error() ;
				$screen_message_type = 'error' ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}
		// echo '<p>'.$sql.'</p>' ;
	}

?>
<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>

<?php include('_screen_message_handler.php') ; ?>

<?php

	if($_GET['action']=='messages'){
		include('_system/inc/_messages.php') ;

	} elseif($_GET['action']=='massmailing'){
		include('_system/inc/_mass_mailing.php') ;

	} else {
		?>
		<h1>Super User Options</h1>
		<p>These are options exclusive to top level administrators.</p>
		<div class="divider" ></div>
		<h3>Create a New User</h3>
		<p>These users will be pre-confirmed and will NOT require account activation</p>
		<?php include('_admin_create_user_form.php') ; ?>
		<div class="divider" ></div>
		<h3>General Site Configuration</h3>
		<form action="?action=options" method="post" >
		
			<label for="app_client_name" >Client Name</label>
			<input type="text" name="app_client_name" id="app_client_name" value="<?php echo $_SESSION['APP_CLIENT_NAME'] ; ?>" class="text" />
		
			<label for="app_site_name" >Application Name (Header in outgoing emails and full site name)</label>
			<input type="text" name="app_site_name" id="app_site_name" value="<?php echo $_SESSION['APP_SITE_NAME'] ; ?>" class="text" />
		
			<label for="email_from_name" >Email Friendly Name (from name in outgoing emails)</label>
			<input type="text" name="email_from_name" id="email_from_name" value="<?php echo $_SESSION['EMAIL_FROM_NAME'] ; ?>" class="text" />
		
			<label for="allowed_files_cv" >Filetypes Allowed in CV Field (separate with a hard bar - |)</label>
			<input type="text" name="allowed_files_cv" id="allowed_files_cv" value="<?php echo implode("|",$_SESSION['ALLOWED_FILES_CV']) ; ?>" class="text" />
		
			<label for="allowed_files_images" >Filetypes Allowed in Photo Field (separate with a hard bar - |)</label>
			<input type="text" name="allowed_files_images" id="allowed_files_images" value="<?php echo implode("|",$_SESSION['ALLOWED_FILES_IMAGES']) ; ?>" class="text" />
		
			<div><input type="submit" name="submit" value="Submit" /></div>
		
		</form>
        <?php
	}
?>