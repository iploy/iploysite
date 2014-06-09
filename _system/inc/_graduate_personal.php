<?php

	include_once('_system/_config/_multiple_choice_arrays.php') ;
	include_once('_system/functions/check_my_array.php') ;
	include_once('_system/classes/graduate_admin.php') ;

	$screen_message = '' ;
	// Always declare the graduate class
	if($_POST){
		$graduate_function = new graduate_info ;
		$graduate_update = $graduate_function->savePersonalInfo($_SESSION['user_id'],true) ;
		if($graduate_update!=''){ // if this value is not blank, there was an error
			$screen_message= draw_icon(ICON_BAD).$graduate_update ;
			$screen_message_type = 'error' ;
		} else {
			$screen_message = draw_icon(ICON_GOOD).'Personal Information Updated Successfully' ;
			$screen_message_type = 'success' ;
		}
	}

?>

<?php include_once('_system/inc/_graduate_progress_bar.php') ; ?>
<?php include('_system/inc/_screen_message_handler.php') ; ?>
<?php include_once('_system/inc/_graduate_profile_progress.php') ; ?>

<?php include_once('_system/inc/_include_mootools_head.php') ; ?>
<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
<script language="javascript" type="text/javascript" src="js/form_is_modified.js" ></script>
<script language="javascript" type="text/javascript" src="js/mooAnyButton/mooAnyButton.js" ></script>
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
		maxDate: '<?php echo date(DM_PHP_DATE_FORMAT) ; ?>',
		toggleElements: '.datetoggle'
    });
});
</script>
<h1>Edit Personal Information</h1>

<form action="?action=<?php echo $_GET['action'] ; ?>" onsubmit="isSumbition();" name="optionsform" id="optionsform" method="post" >

    <div class="float_container" >
        <div class="floater" >
            <label for="first_name" >Name <?php echo check_my_array('first_name',$graduate_required_fields,'*') ; ?></label>
            <input onchange="isModified();" tabindex="1" type="text" name="first_name" id="first_name" class="text fldmar" value="<?php echo htmlspecialchars($_SESSION['first_name']) ;?>" style="width:120px;" />&nbsp;<input onchange="isModified();" tabindex="2" type="text" name="surname" id="surname" class="text" value="<?php echo htmlspecialchars($_SESSION['surname']) ;?>" style="width:170px;" />
            <label for="date_of_birth" >Date of Birth <?php echo check_my_array('date_of_birth',$graduate_required_fields,'*') ; ?></label>
            <div class="datetoggle" >&nbsp;</div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="date_of_birth" id="date_of_birth" class="text date fldmar" value="<?php echo ($_SESSION['date_of_birth']!='' ? date(DM_PHP_DATE_PICK_FORMAT,strtotime($_SESSION['date_of_birth'])) : '') ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>&nbsp;<span onclick="document.getElementById('date_of_birth').value=''; isModified();" class="pointer" ><?php echo draw_icon('delete.png') ; ?></span>
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


	<div class="float_container" >

		<div class="floater lister" >
    		<label for="education_location" >Current Location <?php echo check_my_array('education_location',$graduate_required_fields,'*') ; ?></label>
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

		<div class="floater" >
            <label for="has_driving_licence" >Do You Have A Full Driving Licence <?php echo check_my_array('has_driving_licence',$graduate_required_fields,'*') ; ?></label>
            <div class="lister" >
            <ul class="inline" >
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
    </div>


    <label for="job_category" >Do You Speak Any Additional Languages? <?php echo check_my_array('alternate_languages',$graduate_required_fields,'*') ; ?></label>
	<div class="float_container lister" >
	<?php
		$ul_split_at = ceil(sizeof($languages_array)/5) ;
		$languages_session_array = explode(',',$_SESSION['alternate_languages']) ;
		for($i=0;$i<sizeof($languages_array);$i++){
			// Check if selected
			if(in_array($languages_array[$i],$languages_session_array)){
				$is_selected = 'checked="checked" ' ;
			} else {
				$is_selected = '' ;
			}
			if($i==0||($i)==($ul_split_at*2)||($i)==($ul_split_at*3)||($i)==($ul_split_at*4)||($i+1)==($ul_split_at+1)){
				echo '<div class="floater thinfloater" ><ul>'."\n" ;
			}
			?>
			<li><input onclick="isModified();" id="alternate_languages_<?php echo $i ; ?>" type="checkbox" value="<?php echo $languages_array[$i] ; ?>" name="alternate_languages[]" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('alternate_languages_<?php echo $i ; ?>','multi');" ><?php echo $languages_array[$i] ; ?></span></li>
			<?php
			if(($i+1)==$ul_split_at||($i+1)==($ul_split_at*2)||($i+1)==($ul_split_at*3)||($i+1)==($ul_split_at*4)||($i+1)==sizeof($languages_array)){
				echo '</ul></div>'."\n" ;
			}
		}
    ?>
    </div>

    
    <div style="margin:6px; "><input type="submit" name="gradsubmit" id="gradsubmit" value="Update Personal Information" /></div>

</form>
