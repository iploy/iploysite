<?php
	include_once('_system/_config/_multiple_choice_arrays.php') ;
	include_once('_system/functions/check_my_array.php') ;
	include_once('_system/classes/graduate_admin.php') ;
	include_once('_system/classes/file_verification.php') ;
	include_once('_system/functions/graduate_profile_progress.php') ;
?>
<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
<script language="javascript" type="text/javascript" src="js/form_is_modified.js" ></script>
<script language="javascript" type="text/javascript" src="js/mooAnyButton/mooAnyButton.js" ></script>
<style type="text/css" >

.files{
	margin-left:20px;
}
.files label{
	font-size:16px;
	margin-bottom:10px;
}
.files label[for="upload_cv"]{
	font-size:16px;
	margin-top:20px;
}

</style>
<!-- Profile ID : <?php echo $_SESSION['user_id'] ; ?> -->
<h1>Graduate Profile Administration</h1>
<?php

	$screen_message = '' ;
	// Always declare the graduate class
	if($_POST){
		$graduate_function = new graduate_info ;
		$graduate_update = $graduate_function->saveGraduateByID($_SESSION['user_id'],true) ;
		if($graduate_update!=''){ // if this value is not blank, there was an error
			$screen_message= $graduate_update ;
			$screen_message_type = 'error' ;
		} else {
			$screen_message = draw_icon(ICON_GOOD).'Profile Information Updated Successfully' ;
			$screen_message_type = 'success' ;
		}
	}



	if($_GET['remove']=='photo'||$_GET['remove']=='cv'||$_GET['remove']=='certificate'){
		$graduate_function = new graduate_info ;
		$graduate_function->deleteFile($_SESSION['user_id'],$_GET['remove']) ;
	}


?>
<?php include('_system/inc/_screen_message_handler.php') ; ?>

<?php

	$screen_message = '' ;
	$varification_function = new fileVerification ;
	$varification_function->setUserId($_SESSION['user_id']) ;
	$needs_approval_cv = false ;
	$needs_approval_photo = false ;
	$needs_approval_certificate = false ;
	$varification_function->setType('cv') ;
	if(!$varification_function->check()){
		$screen_message.= draw_icon(ICON_ALERT).'Your CV is currently awaiting approval<br />' ;
		$screen_message_type = 'notice' ;
		$needs_approval_cv = true ;
	}
	$varification_function->setType('photo') ;
	if(!$varification_function->check()){
		$screen_message.= draw_icon(ICON_ALERT).'Your Photograph is currently awaiting approval<br />' ;
		$screen_message_type = 'notice' ;
		$needs_approval_photo = true ;
	}
	$varification_function->setType('certificate') ;
	if(!$varification_function->check()){
		$screen_message.= draw_icon(ICON_ALERT).'Your Certificate is currently awaiting approval<br />' ;
		$screen_message_type = 'notice' ;
		$needs_approval_certificate = true ;
	}



	// at this point we set $is_admin_home to false to trick the notification into displaying here, as the variable is set to true on the admin hiome page.
	$is_admin_home = false ; include('_ipad_promotion_notification.php') ;

	// write the result to screen
	include('_system/inc/_screen_message_handler.php') ;




?>

<?php include_once('_system/inc/_graduate_profile_progress.php') ; ?>

<?php include_once('_system/inc/_include_mootools_head.php') ; ?>
<script src="js/mootools_datepicker/languages/Locale.en-US.DatePicker.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.Attach.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.Date.js" type="text/javascript" language="javascript" ></script>
<link href="js/mootools_datepicker/themes/iploy/iploy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" >
window.addEvent('domready', function(){
    new DatePicker($$('.date'), {
		format: '<?php echo DM_JS_DATE_PICK_FORMAT ; ?>',
		pickerClass: 'datepicker_carmemo',
		timePicker: false,
		startView: 'years',
		toggleElements: '.datetoggle'
    });
});
</script>

<form action="?" onsubmit="isSumbition();" name="optionsform" id="optionsform" method="post" enctype="multipart/form-data" >

    <div class="float_container" >
        <div class="floater" >
            <label for="first_name" >Name <?php echo check_my_array('first_name',$graduate_required_fields,'*') ; ?></label>
            <input onchange="isModified();" tabindex="1" type="text" name="first_name" id="first_name" class="text fldmar" value="<?php echo htmlspecialchars($_SESSION['first_name']) ;?>" style="width:120px;" />&nbsp;<input onchange="isModified();" tabindex="2" type="text" name="surname" id="surname" class="text" value="<?php echo htmlspecialchars($_SESSION['surname']) ;?>" style="width:170px;" />
            <label for="date_of_birth" >Date of Birth <?php echo check_my_array('date_of_birth',$graduate_required_fields,'*') ; ?></label>
            <div class="datetoggle" >&nbsp;</div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="date_of_birth" id="date_of_birth" class="text date fldmar" value="<?php echo $_SESSION['date_of_birth'] ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>
        </div>
	</div>


    <div class="float_container" >
        <div class="floater" >
            <label for="tel_mobile" >Mobile Phone Number <?php echo check_my_array('tel_mobile',$graduate_required_fields,'*') ; ?></label>
            <input onchange="isModified();" tabindex="4" type="text" name="tel_mobile" id="tel_mobile" class="text fldmar" value="<?php echo $_SESSION['tel_mobile'] ;?>" />
        </div>

        <div class="floater" >
            <label for="social_skype" ><img class="ico18" src="images/icons/social_skype.png" width="18" height="18" border="0" />Skype Username <?php echo check_my_array('social_skype',$graduate_required_fields,'*') ; ?></label>
            <input onchange="isModified();" tabindex="4" type="text" name="social_skype" id="social_skype" class="text fldmar" value="<?php echo $_SESSION['social_skype'] ;?>" />
        </div>
    </div>
<br />
    <div class="float_container" >

        <div class="floater" >
            <label for="hours" >Hours <?php echo check_my_array('hours',$graduate_required_fields,'*') ; ?></label>
            <div class="lister" >
            <ul>
            <?php
				$hous_session_array = explode(',',$_SESSION['hours']) ;
                for($i=0;$i<sizeof($hours_array);$i++){
					// Check if selected
					if(in_array($hours_array[$i],$hous_session_array)){
						$is_selected = 'checked="checked" ' ;
					} else {
						$is_selected = '' ;
					}
                    ?>
                    <li><input onclick="isModified();" id="hours_<?php echo $i ; ?>" type="checkbox" value="<?php echo $hours_array[$i] ; ?>" name="hours[]" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('hours_<?php echo $i ; ?>','multi');" ><?php echo $hours_array[$i] ; ?></span></li>
                    <?php
                }
            ?>
            </ul>
            </div>
        </div>

        <div class="floater" >
            <label for="will_travel" >Prepared To Travel as Part of Your Job <?php echo check_my_array('will_travel',$graduate_required_fields,'*') ; ?></label>
            <div class="lister" >
            <ul>
			<?php
                for($i=0;$i<=1;$i++){
					if($i==0){

						$this_value = 'yes' ;
					} else {
						$this_value = 'no' ;
					}
					// Check if selected
					if($_SESSION['will_travel']==$this_value){
						$is_selected = 'checked="checked" ' ;
					} else {
						$is_selected = '' ;
					}
					?>
					<li><input onclick="isModified();" id="travel_<?php echo $i ; ?>" type="radio" value="<?php echo $this_value ; ?>" name="will_travel" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('travel_<?php echo $i ; ?>','radio');" ><?php echo ucfirst($this_value) ; ?></span></li>
					<?php
				}
			?>
            </ul>
            </div>
    	</div>

	</div>



    <div class="float_container" >

		<div class="floater" >
            <label for="has_driving_licence" >Full Driving Licence <?php echo check_my_array('has_driving_licence',$graduate_required_fields,'*') ; ?></label>
            <div class="lister" >
            <ul>
			<?php
                for($i=0;$i<=1;$i++){
					if($i==0){
						$this_value = 'yes' ;
					} else {
						$this_value = 'no' ;
					}
					// Check if selected
					if($_SESSION['has_driving_licence']==$this_value){
						$is_selected = 'checked="checked" ' ;
					} else {
						$is_selected = '' ;
					}
					?>
                	<li><input onclick="isModified();" id="driving_licence_<?php echo $i ; ?>" type="radio" value="<?php echo $this_value ; ?>" name="has_driving_licence" <?php echo $is_selected ;?>/><span onclick="isModified(); check_me('driving_licence_<?php echo $i ; ?>','radio');" ><?php echo ucfirst($this_value) ; ?></span></li>
					<?php
				}
			?>
            </ul>
            </div>
        </div>

		<div class="floater" >
            <label for="will_do_antisocial" >Willing to Work Unsociable Hours <?php echo check_my_array('will_do_antisocial',$graduate_required_fields,'*') ; ?></label>
            <div class="lister" >
            <ul>
			<?php
                for($i=0;$i<=1;$i++){
					if($i==0){
						$this_value = 'yes' ;
					} else {
						$this_value = 'no' ;
					}
					// Check if selected
					if($_SESSION['will_do_antisocial']==$this_value){
						$is_selected = 'checked="checked" ' ;
					} else {
						$is_selected = '' ;
					}
					?>
               		<li><input onclick="isModified();" id="antisocial_hours_<?php echo $i ; ?>" type="radio" value="<?php echo $this_value ; ?>" name="will_do_antisocial" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('antisocial_hours_<?php echo $i ; ?>','radio');" ><?php echo ucfirst($this_value) ; ?></span></li>
					<?php
				}
			?>
            </ul>
            </div>
		</div>

	</div>



    <label for="availability" >Available for Employment <?php echo check_my_array('availability',$graduate_required_fields,'*') ; ?></label>
	<div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="availability" id="availability" class="text date fldmar" value="<?php echo $_SESSION['availability'] ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>
	<?php
		/*
		echo '<div class="float_container lister" >' ;
		$ul_split_at = ceil(sizeof($availability_array)/2) ;
		for($i=0;$i<sizeof($availability_array);$i++){
			if($availability_array[$i]==$_SESSION['availability']){
				$is_selected = 'checked="checked" ' ;
			} else {
				$is_selected = '' ;
			}
			if($i==0||($i+1)==($ul_split_at+1)){
				echo '<div class="floater" ><ul>'."\n" ;
			}
			?>
			<li><input onclick="isModified();" id="availability_<?php echo $i ; ?>" type="radio" value="<?php echo $availability_array[$i] ; ?>" name="availability" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('availability_<?php echo $i ; ?>','radio');" ><?php echo $availability_array[$i] ; ?></span></li>
			<?php
			if(($i+1)==$ul_split_at||($i+1)==sizeof($availability_array)){
				echo '</ul></div>'."\n" ;
			}
		}
		echo '</div>' ;
		*/
    ?>


    <label for="education_location" >Current Location <?php echo check_my_array('education_location',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister" >
    <select name="education_location" id="education_location" >
	<option value="" >Please select an option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
	<?php
		for($i=0;$i<sizeof($locations_array);$i++){
			// Check if selected
			if($locations_array[$i]==$_SESSION['education_location']){
				$is_selected = 'selected="selected" ' ;
			} else {
				$is_selected = '' ;
			}
			?>
			<option value="<?php echo $locations_array[$i] ; ?>" <?php echo $is_selected ; ?>><?php echo $locations_array[$i] ; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<?php
		}
    ?>
    </select>
    </div>
    



    <label for="employment_location" >Where Are You Willing to Work <?php echo check_my_array('emploment_location',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister mooAnyList" >
	<?php
		// $ul_split_at = ceil(sizeof($locations_array)/2) ;
		$ul_split_at = 9 ;
		$emploment_location_session_array = explode(',',$_SESSION['emploment_location']) ;
		for($i=0;$i<sizeof($locations_array);$i++){
			// Check if selected
			if(in_array($locations_array[$i],$emploment_location_session_array)){
				$is_selected = 'checked="checked" ' ;
			} else {
				$is_selected = '' ;
			}
			if($i==0||($i+1)==($ul_split_at+1)){
				echo '<div class="floater" ><ul>'."\n" ;
			}
			?>
			<li><input onclick="isModified();" id="emploment_location_<?php echo $i ; ?>" type="checkbox" value="<?php echo $locations_array[$i] ; ?>" name="emploment_location[]" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('emploment_location_<?php echo $i ; ?>','multi');" ><?php echo $locations_array[$i] ; ?></span></li>
			<?php
			if(($i+1)==$ul_split_at||($i+1)==sizeof($locations_array)){
				echo '</ul></div>'."\n" ;
			}
		}
    ?>
    </div>
    <div class="float_container lister mooAnyButton" >
    </div>


    <label for="job_category" >Category(s) of Job You Would Like <?php echo check_my_array('job_category',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister mooAnyList" >
	<?php
		$ul_split_at = ceil(sizeof($category_array)/2) ;
		$job_category_session_array = explode(',',$_SESSION['job_category']) ;
		for($i=0;$i<sizeof($category_array);$i++){
			// Check if selected
			if(in_array($category_array[$i],$job_category_session_array)){
				$is_selected = 'checked="checked" ' ;
			} else {
				$is_selected = '' ;
			}
			if($i==0||($i+1)==($ul_split_at+1)){
				echo '<div class="floater" ><ul>'."\n" ;
			}
			?>
			<li><input onclick="isModified();" id="job_category_<?php echo $i ; ?>" type="checkbox" value="<?php echo $category_array[$i] ; ?>" name="job_category[]" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('job_category_<?php echo $i ; ?>','multi');" ><?php echo $category_array[$i] ; ?></span></li>
			<?php
			if(($i+1)==$ul_split_at||($i+1)==sizeof($category_array)){
				echo '</ul></div>'."\n" ;
			}
		}
    ?>
    </div>
    <div class="float_container lister mooAnyButton" >
    </div>




<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

	<h3>Uploadable Files</h3>

    <div class="files" >
    
        <label for="upload_photo" >Photograph <?php echo check_my_array('upload_photo',$graduate_required_fields,'*') ; ?></label>
        <?php
			if(is_dir('userfiles/photo/'.$_SESSION['user_id'].'/')){
				if($needs_approval_photo==true){
					?>
					<p class="notice"><?php echo draw_icon(ICON_ALERT) ; ?>Your recently uploaded photograph is currently awaiting approval</p>
					<?php
				}
				//<img src="<?php echo 'user_image.php?userid='.$_SESSION['user_id'].'&size=thumb' ; ? >" width="16" height="16" border="0" alt="" />
				?>
	<p><?php echo draw_icon('user_image.php?userid='.$_SESSION['user_id'].'&size=thumb&admin=true',SITE_FOLDER) ; ?><a href="user_image.php?userid=<?php echo $_SESSION['user_id'] ; ?>&amp;size=original&admin=true&.jpg" class="lytebox" target="_blank" title="Your current photograph" >View your current photograph on <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
				<p><?php echo draw_icon(ICON_DOWNLOAD) ; ?><a href="download_userfile.php?userid=<?php echo $_SESSION['user_id'] ; ?>&amp;type=photo&amp;size=original" >Download your current photograph from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
				<p><?php echo draw_icon(ICON_DELETE) ; ?><a href="?remove=photo" >Delete your current photograph from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
                <?php
			} else {
				?>
				<p><?php echo draw_icon(ICON_BAD) ; ?><b>You have not yet uploaded a photograph</b></p>
				<?php
			}
		?>
        <input onchange="isModified();" type="file" name="upload_photo" id="upload_photo" class="text" value="<?php echo $upload_photo ;?>" />
        <div class="pointer" onclick="document.getElementById('upload_photo').value='';">Click here if you wish to unselect a file</div>
    
        <label for="upload_cv" >Curriculum Vitae <?php echo check_my_array('upload_cv',$graduate_required_fields,'*') ; ?></label>
        <?php
			if(is_dir('userfiles/cv/'.$_SESSION['user_id'].'/')){
				if($needs_approval_cv==true){
					?>
					<p class="notice"><?php echo draw_icon(ICON_ALERT) ; ?>Your recently uploaded CV is currently awaiting approval</p>
					<?php
				}
				?>
                <p><?php echo draw_icon(ICON_DOWNLOAD) ; ?><a href="download_userfile.php?userid=<?php echo $_SESSION['user_id'] ; ?>&amp;type=cv" >Download your current CV from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
				<p><?php echo draw_icon(ICON_DELETE) ; ?><a href="?remove=cv" >Delete your current CV from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
                <?php
			} else {
				?>
				<p><?php echo draw_icon(ICON_BAD) ; ?><b>You have not yet uploaded your CV</b></p>
				<?php
			}
		?>
        <input onchange="isModified();" type="file" name="upload_cv" id="upload_cv" class="text" value="<?php echo $upload_cv ;?>" />
        <div class="pointer" onclick="document.getElementById('upload_cv').value='';">Click here if you wish to unselect a file</div>
    
    </div>





<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

	<h3>Education Information</h3>


    <label for="education_level" >Education Level <?php echo check_my_array('education_level',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister" >
    <select name="education_level" id="education_level" >
	<option value="" >Please select an option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
	<?php
		for($i=0;$i<sizeof($education_level_array);$i++){
			// Check if selected
			if($education_level_array[$i]==$_SESSION['education_level']){
				$is_selected = 'selected="selected" ' ;
			} else {
				$is_selected = '' ;
			}
			?>
			<option value="<?php echo $education_level_array[$i] ; ?>" <?php echo $is_selected ; ?>><?php echo $education_level_array[$i] ; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<?php
		}
    ?>
    </select>
    </div>


    <label for="education_degree_title" >Degree Title <?php echo check_my_array('education_degree_title',$graduate_required_fields,'*') ; ?></label>
    <input onchange="isModified();" tabindex="3" type="text" name="education_degree_title" id="education_degree_title" class="text fldmar" value="<?php echo htmlspecialchars($_SESSION['education_degree_title']) ;?>" style="width:320px;" />



    <label for="subject" >Subject Studied <?php echo check_my_array('subject',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister" >
    <select name="subject" id="subject" >
	<option value="" >Please select an option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
	<?php
		for($i=0;$i<sizeof($subjects_array);$i++){
			// Check if selected
			if($subjects_array[$i]==$_SESSION['subject']){
				$is_selected = 'selected="selected" ' ;
			} else {
				$is_selected = '' ;
			}
			?>
			<option value="<?php echo $subjects_array[$i] ; ?>" <?php echo $is_selected ; ?>><?php echo $subjects_array[$i] ; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<?php
		}
    ?>
    </select>
    </div>



    <label for="education_start" >Start <?php echo check_my_array('education_start',$graduate_required_fields,'*') ; ?></label>
    <div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="education_start" id="education_start" class="text date fldmar" value="<?php echo $_SESSION['education_start'] ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>

    <label for="education_end" >End <?php echo check_my_array('education_end',$graduate_required_fields,'*') ; ?></label>
    <div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="education_end" id="education_end" class="text date fldmar" value="<?php echo $_SESSION['education_end'] ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>



    <label for="education_has_graduated" >I Have Not Graduated <?php echo check_my_array('education_has_graduated',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister" >
	<?php
		// Check if selected
		if($_SESSION['education_has_graduated']=='no'){
			$is_selected = 'checked="checked" ' ;
		} else {
			$is_selected = '' ;
		}
		echo '<div class="floater" ><ul>'."\n" ;
		?>
		<li><input onclick="isModified();" id="education_has_graduated_<?php echo $i ; ?>" type="checkbox" value="no" name="education_has_graduated" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('education_has_graduated_<?php echo $i ; ?>','multi');" >I have not graduated</span></li>
		<?php
		echo '</ul></div>'."\n" ;
    ?>
    </div>


    <label for="education_grade" >Education Grade <?php echo check_my_array('education_grade',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister" >
    <select name="education_grade" id="education_grade" >
	<option value="" >Please select an option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
	<?php
		for($i=0;$i<sizeof($education_grade_array);$i++){
			// Check if selected
			if($education_grade_array[$i]==$_SESSION['education_grade']){
				$is_selected = 'selected="selected" ' ;
			} else {
				$is_selected = '' ;
			}
			?>
			<option value="<?php echo $education_grade_array[$i] ; ?>" <?php echo $is_selected ; ?>><?php echo $education_grade_array[$i] ; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			<?php
		}
    ?>
    </select>
    </div>

    <label for="education_institution" >Institution Name <?php echo check_my_array('education_institution',$graduate_required_fields,'*') ; ?></label>
    <input onchange="isModified();" tabindex="3" type="text" name="education_institution" id="education_institution" class="text fldmar" value="<?php echo htmlspecialchars($_SESSION['education_institution']) ;?>" style="width:320px;" />



    <label for="upload_certificate" >Upload Certification <?php echo check_my_array('upload_certificate',$graduate_required_fields,'*') ; ?></label>
    <?php
        if(is_dir('userfiles/certificate/'.$_SESSION['user_id'].'/')){
            if($needs_approval_certificate==true){
                ?>
                <p class="notice"><?php echo draw_icon(ICON_ALERT) ; ?>Your recently uploaded certificate is currently awaiting approval</p>
                <?php
            }
            //<img src="<?php echo 'user_image.php?userid='.$_SESSION['user_id'].'&size=thumb' ; ? >" width="16" height="16" border="0" alt="" />
            ?>
<p><?php echo draw_icon('certificate.png') ; ?><a href="user_image.php?userid=<?php echo $_SESSION['user_id'] ; ?>&amp;size=original&type=certificate&admin=true&.jpg" class="lytebox" target="_blank" title="Your current certificate" >View your current certificate from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
            <p><?php echo draw_icon(ICON_DOWNLOAD) ; ?><a href="download_userfile.php?userid=<?php echo $_SESSION['user_id'] ; ?>&amp;type=certificate" >Download your current certificate from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
            <p><?php echo draw_icon(ICON_DELETE) ; ?><a href="?remove=certificate" >Delete your current certificate from <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
            <?php
        } else {
            ?>
            <p><?php echo draw_icon(ICON_BAD) ; ?><b>You have not yet uploaded a certificate</b></p>
            <?php
        }
    ?>
    <input onchange="isModified();" type="file" name="upload_certificate" id="upload_certificate" class="text" value="<?php echo $upload_certificate ;?>" />
    <div class="pointer" onclick="document.getElementById('upload_certificate').value='';">Click here if you wish to unselect a file</div>



    <label for="education_certificate_title" >Certification Title <?php echo check_my_array('education_certificate_title',$graduate_required_fields,'*') ; ?></label>
    <input onchange="isModified();" tabindex="3" type="text" name="education_certificate_title" id="education_certificate_title" class="text fldmar" value="<?php echo htmlspecialchars($_SESSION['education_certificate_title']) ;?>" style="width:320px;" />


    
    <div style="margin:6px; "><input type="submit" name="gradsubmit" id="gradsubmit" value="Save Profile Information" /></div>

</form>
<!-- first_name surname date_of_birth  -->
