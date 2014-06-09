<?php

	include_once('_system/classes/graduate_admin.php') ;

	$screen_message = '' ;
	$goCrop = false ;
	if($_GET['subaction']=='photo'&&$_GET['docrop']=='true'){
		$goCrop = true ;
	}
	// Always declare the graduate class
	if($_POST&&$_GET['upload']=='true'){
		$graduate_function = new graduate_info ;
		$graduate_update = $graduate_function->uploadFile($_SESSION['user_id'],$_GET['subaction'],true) ;
		if($graduate_update!=''){ // if this value is not blank, there was an error
			$screen_message= draw_icon(ICON_BAD).$graduate_update ;
			$screen_message_type = 'error' ;
		} else {
			$screen_message = draw_icon(ICON_GOOD).'Profile Information Updated Successfully' ;
			$screen_message_type = 'success' ;
			if($_GET['subaction']=='photo'){
				$goCrop = true ;
			}
		}
	}
	// FOR THE CROPPING = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = 
	if (isset($_POST['crop'])&&$_GET['crop']=='true') {
		include_once('_system/functions/delete_directory.php') ;
		include_once('_system/classes/imaging.php') ;
		$path_images = 'userfiles/photo/'.$_SESSION['user_id'].'/' ; // modify this
		// set the vars
		$script_self = $_SERVER['PHP_SELF'] ; // better: absolute URL here
		// make sure the working directory is present
		if(is_dir($path_images.'_working/')){ delete_directory($path_images.'_working/') ; }
		mkdir($path_images.'_working/') ;
		// crop the image into the working folder
		list($editWidth, $editHeight, $editType) = getimagesize($path_images.'_editable/'.$_POST['filename']);
		$vars = array('x','y','w','h');
		foreach ($vars as $var) {
			$crop[$var] = isset($_POST['crop'][$var]) ? intval($_POST['crop'][$var]) : 0;
		}
		$res = Imaging::cropImage($path_images.'_editable/'.$_POST['filename'], $path_images.'_working/'.$_POST['filename'], $crop['x'], $crop['y'], $crop['w'], $crop['h']);
		// pump out the other sizes
		$image = new SimpleImage();
		$image->load($path_images.'_working/'.$_POST['filename']);
		// large
		$image->resizeToWidth(IMAGE_UPLOAD_LARGE_WIDTH);
		if(is_dir($path_images.'_large/')){ delete_directory($path_images.'_large/') ; }
		mkdir($path_images.'_large/') ;
		$image->save($path_images.'_large/'.$_POST['filename']);
		// largemedium (Search Results)
		$image->resizeToWidth(IMAGE_UPLOAD_LRGMEDIUM_WIDTH);
		if(is_dir($path_images.'_lrgmed/')){ delete_directory($path_images.'_lrgmed/') ; }
		mkdir($path_images.'_lrgmed/') ;
		$image->save($path_images.'_lrgmed/'.$_POST['filename']);
		// meduim
		$image->resizeToWidth(IMAGE_UPLOAD_MEDIUM_WIDTH);
		if(is_dir($path_images.'_med/')){ delete_directory($path_images.'_med/') ; }
		mkdir($path_images.'_med/') ;
		$image->save($path_images.'_med/'.$_POST['filename']);
		// Thumb
		$image->resizeToWidth(IMAGE_UPLOAD_THUMBNAIL_WIDTH);
		if(is_dir($path_images.'_thumb/')){ delete_directory($path_images.'_thumb/') ; }
		mkdir($path_images.'_thumb/') ;
		$image->save($path_images.'_thumb/'.$_POST['filename']);
		// remove the working folder
		// if(is_dir($path_images.'_working/')){ delete_directory($path_images.'_working/') ; }
	}
	// FOR THE CROPPING = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = 


	if($_GET['remove']=='photo'||$_GET['remove']=='cv'||$_GET['remove']=='certificate'){
		$graduate_function = new graduate_info ;
		$graduate_function->deleteFile($_SESSION['user_id'],$_GET['remove']) ;
	}


if($goCrop==true){

	include('_system/inc/_graduate_crop_image.php') ;

} else {

	// DO CHECK HERE TO MAKE SURE UPLOAD WAS NOT STRATED BUT UNFINISHED. IF IT WAS, DELETE THE FOLDER.


	if($_GET['subaction']=='photo'||$_GET['subaction']=='cv'||$_GET['subaction']=='certificate'){
	
		include_once('_system/functions/check_my_array.php') ;
		include_once('_system/classes/file_verification.php') ;
	
		// set switches
		switch($_GET['subaction']){
			 // photo
			case 'photo' :
				$titleText = 'Photo' ;
				$screenText = 'photo' ;
				$activeText = 'photo' ;
				$icon = 'images.png' ;
			break ;
			// cv
			case 'cv' : 
				$titleText = 'CV' ;
				$screenText = 'CV' ;
				$activeText = 'cv' ;
				$icon = 'profile.png' ;
			break ;
			// cert
			case 'certificate' : 
				$titleText = 'Certificate' ;
				$screenText = 'certificate' ;
				$activeText = 'certificate' ;
				$icon = 'certificate.png' ;
			break ;
		}
	
		// check for files awaiting approval
		$screen_message = '' ;
		$varification_function = new fileVerification ;
		$varification_function->setUserId($_SESSION['user_id']) ;
		$needs_approval_cv = false ;
		$needs_approval_photo = false ;
		$needs_approval_certificate = false ;
		$varification_function->setType($activeText) ;
		if(!$varification_function->check()){
			$screen_message.= draw_icon(ICON_ALERT).'Your recently uploaded '.$screenText.' is awaiting approval from our administrators.' ;
			$screen_message_type = 'notice' ;
			$needs_approval_cv = true ;
		}
	
		?>
		<script language="javascript" type="text/javascript" src="js/form_is_modified.js" ></script>
        <?php include_once('_system/inc/_graduate_progress_bar.php') ; ?>
		<?php include_once('_system/inc/_graduate_profile_progress.php') ; ?>
		<div style="margin-right:360px;">
        <h1>Upload <?php echo $titleText ; ?></h1>
		<?php include('_system/inc/_screen_message_handler.php') ; ?>
		<?php
		// === ===	===	===	===	===	[ PREVIEW ] === ===	===	===	===	===	===	===	
		if($_GET['subaction']=='photo'){
			?>
			<div class="img" style="width:<?php echo IMAGE_UPLOAD_LARGE_WIDTH ; ?>px; height:<?php echo IMAGE_UPLOAD_LARGE_HEIGHT ; ?>px; float:left; margin-right:12px; overflow:hidden;" ><img src="user_image.php?userid=<?php echo $_SESSION['user_id'] ; ?>&size=large&admin=true" alt="" /></div>
			<style type="text/css" >
			.lister {
				padding-top:10px;
			}
			.lister li {
				padding-bottom:4px;
			}
			</style>
			<?php
		}
		// === ===	===	===	===	===	[ PREVIEW ] === ===	===	===	===	===	===	===	
		?>
		<form action="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=<?php echo $_GET['subaction'] ; ?>&amp;upload=true" onsubmit="isSumbition();" name="optionsform" id="optionsform" method="post" enctype="multipart/form-data" >
			<div class="lister" style="margin:12px 0 0;" >
			<ul>
			<?php
				// - - - - - - -
				$thisFolder = 'userfiles/'.$activeText.'/'.$_SESSION['user_id'].'/' ;
				if(is_dir($thisFolder)){
					include('_system/functions/user_file_is_an_image.php') ;
					if($_GET['subaction']=='photo'){
						?><li><?php echo draw_icon('move.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=<?php echo $_GET['subaction'] ; ?>&amp;docrop=true" >Crop my image</a></li>
						<?php
					}
					// draw a lytebox link if image
					if(user_file_is_an_image($thisFolder)==true){
						?>
						<li><?php echo draw_icon($icon) ; ?><a href="user_image.php?userid=<?php echo $_SESSION['user_id'] ; ?>&amp;size=original&type=<?php echo $activeText ; ?>&admin=true&.jpg" class="lytebox" target="_blank" title="Your current certificate" >View your current <?php echo $screenText ; ?> from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
						<?php
					}				
					?>
					<li><?php echo draw_icon(ICON_DOWNLOAD) ; ?><a href="download_userfile.php?userid=<?php echo $_SESSION['user_id'] ; ?>&amp;type=<?php echo $activeText ; ?>" >Download your current <?php echo $screenText ; ?> from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
					<li><?php echo draw_icon(ICON_DELETE) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=<?php echo $_GET['subaction'] ; ?>&amp;remove=<?php echo $activeText ; ?>" >Delete your current <?php echo $screenText ; ?> from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
					<?php
				} else {
					?>
					<li><?php echo draw_icon(ICON_BAD) ; ?><b>You have not yet uploaded a <?php echo $screenText ; ?></b></li>
					<?php
				}
			?>
			</ul>
			</div>
			<?php
				// add the extra form field for the certificate
				if($_GET['subaction']=='certificate'){
					?>
					<div class="float_container" >
						<label for="education_certificate_title" >Certification Title <?php echo check_my_array('education_certificate_title',$graduate_required_fields,'*') ; ?></label>
						<input onchange="isModified();" tabindex="3" type="text" name="education_certificate_title" id="education_certificate_title" class="text fldmar" value="<?php echo htmlspecialchars($_SESSION['education_certificate_title']) ;?>" style="width:320px;" />
					</div>
					<?php
				}
			?>
			<div class="float_container" style="clear:left;" >
				<div class="floater" style="margin:0 10px 0;" >
					<label for="upload" >Select a file to upload</label>
					<input onchange="isModified();" type="file" name="upload" id="upload" class="text" />
					<div class="pointer" onclick="document.getElementById('upload').value='';" style="margin-top:4px;">Click here to unselect this file</div>
				</div>
			</div>
	
			<div style="margin:6px; "><input type="submit" name="gradsubmit" id="gradsubmit" value="Upload This File" /></div>
	
		</form>
	
		<p style="clear:left;"><br /><?php echo draw_icon('back.png') ; ?><a href="home.php?action=upload" >Select another type of file to upload</a></p>
        </div>
		<?php
	
	
	} else {
        $uploadFlds = array('photo','certificate','cv') ;
        foreach($uploadFlds as $fld){
			$file_base = 'userfiles/'.$fld.'/'.$_SESSION['user_id'].'/' ;
			if(is_dir($file_base)){
				// get the original filename and type
				$this_dir = opendir($file_base) ;
				while(($file=readdir($this_dir))!= false){
					if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
						$exists[$fld] = true ;
					}
				}
				closedir($this_dir) ;
			}
        }
		?>
        <?php include_once('_system/inc/_graduate_progress_bar.php') ; ?>
		<?php include_once('_system/inc/_graduate_profile_progress.php') ; ?>
		<div style="margin-right:360px;">
		<h1>Upload Files</h1>
		<p>Please select a filetype to upload ;</p>
        <style type="text/css" >
			.lister li {
				padding-bottom:3px;
			}
		</style>
		<div class="lister" style="margin:12px 16px 10px;" >
		<ul>
			<li><?php echo draw_icon('images.png') ; ?>&nbsp;<a href="home.php?action=upload&subaction=photo" >Upload / Crop Photograph</a> - <?php echo $exists['photo'] ? '<span class="green" >Complete</span>' : '<span class="red" >Incomplete</span>' ; ?></li>
			<li><?php echo draw_icon('profile.png') ; ?>&nbsp;<a href="home.php?action=upload&subaction=cv" >Upload CV</a> - <?php echo $exists['cv'] ? '<span class="green" >Complete</span>' : '<span class="red" >Incomplete</span>' ; ?></li>
			<li><?php echo draw_icon('certificate.png') ; ?>&nbsp;<a href="home.php?action=upload&subaction=certificate" >Upload Certificate</a> - <?php echo $exists['certificate'] ? '<span class="green" >Complete</span>' : '<span class="red" >Incomplete</span>' ; ?></li>
		</ul>
		</div>
        </div>
		<?php
	
	}
}
?>