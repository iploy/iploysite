<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
<script language="javascript" type="text/javascript" src="js/form_is_modified.js" ></script>

<script language="javascript" type="text/javascript" >
function cloneEmployerContact(){
	if(confirm('BILLING ADDRESS CLONE\nYou are about to clone your contact values to your billing address.\nAre you sure you wish to do this?\n\nPLEASE NOTE:\nYou will still need to save the profile informate to commit these changes.')){
	<?php
	foreach($address_all_fields as $address_field){
		?>
		document.getElementById('billing_<?php echo $address_field ; ?>').value = document.getElementById('<?php echo $address_field ; ?>').value ;
		<?php
	}
	?>
	}
}
</script>

<h1>Employer Profile Administration</h1>
<?php

	include_once('_system/functions/check_my_array.php') ;
	include_once('_system/functions/country_drowdown.php') ;
	include_once('_system/_config/_multiple_choice_arrays.php') ;
	include_once('_system/functions/employer_profile_progress.php') ;

	if($profile_progress<100){
		?>
        <p class="error" ><?php echo draw_icon(ICON_BAD) ; ?>Please complete the required fields on your profile to use the <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> system</p>
        <?php
	}


?>




<div class="profile_info" >

	<h2>Profile Progress</h2>
    <p>Please answer all of the required (*) fields to complete your profile. You will be able to submit this form without completing all required information, but your administration abilities will be restricted.</p>
	<?php
		$profile_progress = employer_profile_progress($employer_required_fields,$_SESSION) ;
		$profile_progress_color = progress_colour($profile_progress) ;
	?>
    <div class="percentage">
    	<div class="text" ><span><?php echo $profile_progress ; ?>%</span></div>
        <div class="bar" style="width:<?php echo $profile_progress ; ?>%; background:<?php echo $profile_progress_color ; ?>;" ></div>
    </div>
   
</div>


<script language="javascript" type="text/javascript" src="js/mooChars/mooChars.js" ></script>
<script language="javascript" type="text/javascript" src="js/validateUrl.js" ></script>
<script language="javascript" type="text/javascript" >
function validateForm(){
	scrnMsg = '' ;
	if(document.getElementById('bio').value.length>650){
		scrnMsg= 'Your \'Company Bio\' should be no more than 650 characters.' ;
	}
	if(document.getElementById('bio').value.length<10){
		// scrnMsg= 'Please give us a brief description of you company in the \'Company Bio\' field.' ;
	}
	if(document.getElementById('website').value.length>0&&validateUrl(document.getElementById('website').value)!=true){
		scrnMsg= 'The website you entered is not a valid address' ;
	}
	// report message
	if(scrnMsg!=''){
		alert('SUBMISSION ERROR\n'+scrnMsg) ;
		return false ;
	} else {
		return true ;
	}
}
</script>

<script language="javascript" type="text/javascript" src="js/mooRequired/mooRequired.js" ></script>

<form action="home.php?action=profile&amp;subaction=saveprofile" onsubmit="isSumbition(); return validateForm();" name="optionsform" id="optionsform" method="post" >

    <label for="company_name" >Company Name <?php echo check_my_array('company_name',$employer_required_fields,'*') ; ?></label>
    <input name="company_name" id="company_name" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['company_name']) ;?>" />

    <label for="website" >Website <?php echo check_my_array('website',$employer_required_fields,'*') ; ?></label>
    <input name="website" id="website" type="text" class="text" value="<?php echo htmlspecialchars($_SESSION['website']) ;?>" />

    <label for="bio" >Company Bio <?php echo check_my_array('bio',$employer_required_fields,'*') ; ?></label>
    <textarea name="bio" id="bio" class="text mooChars mooRequired" style="width:500px; max-width:500px; min-width:500px; height:200px; min-height:200px; max-height:500px;"><?php echo htmlspecialchars($_SESSION['bio']) ;?></textarea>
    <p><?php echo draw_icon(ICON_ALERT) ; ?><span id="mooChars_bio" >650 characters max</span></p>


    <label for="industry_sector" >Industry Sector <?php echo check_my_array('industry_sector',$employer_required_fields,'*') ; ?></label>
	<div class="float_container lister mooAnyList" >
    <div class="mooRequiredList" >
	<?php
		$ul_split_at = ceil(sizeof($category_array)/2) ;
		$job_category_session_array = explode(',',$_SESSION['industry_sector'].'') ;
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
			<li><input onclick="isModified();" id="industry_sector_<?php echo $i ; ?>" type="checkbox" value="<?php echo $category_array[$i] ; ?>" name="industry_sector[]" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('industry_sector_<?php echo $i ; ?>','multi');" ><?php echo $category_array[$i] ; ?></span></li>
			<?php
			if(($i+1)==$ul_split_at||($i+1)==sizeof($category_array)){
				echo '</ul></div>'."\n" ;
			}
		}
    ?>
    </div>
    </div>


	<h3>Employer Contact</h3>

    <div class="formfloater" >

        <label for="contact_first_name" >Contact Name <?php echo check_my_array('contact_first_name',$employer_required_fields,'*') ; ?></label>
        <input onchange="isModified();" type="text" name="contact_first_name" id="contact_first_name" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_first_name']) ;?>" style="width:120px;" />&nbsp;<input onchange="isModified();" type="text" name="contact_surname" id="contact_surname" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_surname']) ;?>" style="width:170px;" />
    
        <label for="contact_position" >Contact Position <?php echo check_my_array('contact_position',$employer_required_fields,'*') ; ?></label>
        <input name="contact_position" id="contact_position" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_position']) ;?>" />
    
        <label for="contact_email" >Contact Email <?php echo check_my_array('contact_email',$employer_required_fields,'*') ; ?></label>
        <input name="contact_email" id="contact_email" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_email']) ;?>" />
    
        <label for="contact_tel" >Contact Tel <?php echo check_my_array('contact_tel',$employer_required_fields,'*') ; ?></label>
        <input name="contact_tel" id="contact_tel" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_tel']) ;?>" />

    </div>


    <div class="formfloater" >
    
        <label for="contact_address_1" >Contact Address Line 1 <?php echo check_my_array('contact_address_1',$employer_required_fields,'*') ; ?></label>
        <input name="contact_address_1" id="contact_address_1" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_address_1']) ;?>" />
    
        <label for="contact_address_2" >Contact Address Line 2 <?php echo check_my_array('contact_address_2',$employer_required_fields,'*') ; ?></label>
        <input name="contact_address_2" id="contact_address_2" type="text" class="text" value="<?php echo htmlspecialchars($_SESSION['contact_address_2']) ;?>" />
    
        <label for="contact_town" >Town <?php echo check_my_array('contact_town',$employer_required_fields,'*') ; ?></label>
        <input name="contact_town" id="contact_town" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_town']) ;?>" />
    
        <label for="contact_state" >County <?php echo check_my_array('contact_state',$employer_required_fields,'*') ; ?></label>
        <input name="contact_state" id="contact_state" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_state']) ;?>" />
    
        <label for="contact_postcode" >Postcode <?php echo check_my_array('contact_postcode',$employer_required_fields,'*') ; ?></label>
        <input name="contact_postcode" id="contact_postcode" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['contact_postcode']) ;?>" />
    
        <label for="contact_country" >Country <?php echo check_my_array('contact_country',$employer_required_fields,'*') ; ?></label>
        <div class="mooRequiredList"><?php echo country_dropdown('contact_country','contact_country',$_SESSION['contact_country']) ; ?></div>

	</div>




	<h3 style="clear:both;" >Default Billing Address</h3>
    <div class="lister" >
    <ul>
        <li><span onclick="cloneEmployerContact();" ><?php echo draw_icon('stamp.png') ; ?>Click here to clone from Employer Contact</span></li>
    </ul>
    </div>

    <div class="formfloater" >
    
        <label for="billing_contact_first_name" >Billing Contact Name <?php echo check_my_array('billing_contact_first_name',$employer_required_fields,'*') ; ?></label>
        <input onchange="isModified();" type="text" name="billing_contact_first_name" id="billing_contact_first_name" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_first_name']) ;?>" style="width:120px;" />&nbsp;<input onchange="isModified();" type="text" name="billing_contact_surname" id="billing_contact_surname" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_surname']) ;?>" style="width:170px;" />
    
        <label for="billing_contact_position" >Billing Contact Position <?php echo check_my_array('billing_contact_position',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_position" id="billing_contact_position" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_position']) ;?>" />
    
        <label for="billing_contact_email" >Billing Contact Email <?php echo check_my_array('billing_contact_email',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_email" id="billing_contact_email" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_email']) ;?>" />
    
        <label for="billing_contact_tel" >Billing Contact Tel <?php echo check_my_array('billing_contact_tel',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_tel" id="billing_contact_tel" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_tel']) ;?>" />
    
    </div>


    <div class="formfloater" >

        <label for="billing_contact_address_1" >Billing Address Line 1 <?php echo check_my_array('billing_contact_address_1',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_address_1" id="billing_contact_address_1" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_address_1']) ;?>" />
    
        <label for="billing_contact_address_2" >Billing Address Line 2 <?php echo check_my_array('billing_contact_address_2',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_address_2" id="billing_contact_address_2" type="text" class="text" value="<?php echo htmlspecialchars($_SESSION['billing_contact_address_2']) ;?>" />
    
        <label for="billing_contact_town" >Town <?php echo check_my_array('billing_contact_town',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_town" id="billing_contact_town" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_town']) ;?>" />
    
        <label for="billing_contact_state" >County <?php echo check_my_array('billing_contact_state',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_state" id="billing_contact_state" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_state']) ;?>" />
    
        <label for="billing_contact_postcode" >Postcode <?php echo check_my_array('billing_contact_postcode',$employer_required_fields,'*') ; ?></label>
        <input name="billing_contact_postcode" id="billing_contact_postcode" type="text" class="text mooRequired" value="<?php echo htmlspecialchars($_SESSION['billing_contact_postcode']) ;?>" />
    
        <label for="billing_contact_country" >Country <?php echo check_my_array('billing_contact_country',$employer_required_fields,'*') ; ?></label>
        <div class="mooRequiredList" ><?php echo country_dropdown('billing_contact_country','billing_contact_country',$_SESSION['billing_contact_country']) ; ?></div>


	</div>


    <div style="clear:both;"><input style="margin:0;" type="submit" name="gradsubmit" id="gradsubmit" value="Save Profile Information" /></div>

</form>