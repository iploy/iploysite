<?php

	include_once('_system/_config/_multiple_choice_arrays.php') ;
	include_once('_system/functions/check_my_array.php') ;
	include_once('_system/classes/graduate_admin.php') ;

	$screen_message = '' ;
	// Always declare the graduate class
	if($_POST){
		$graduate_function = new graduate_info ;
		$graduate_update = $graduate_function->saveEmploymentInfo($_SESSION['user_id'],true) ;
		if($graduate_update!=''){ // if this value is not blank, there was an error
			$screen_message= draw_icon(ICON_BAD).$graduate_update ;
			$screen_message_type = 'error' ;
		} else {
			$screen_message = draw_icon(ICON_GOOD).'Employment Preferences Updated Successfully' ;
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
		minDate: '<?php echo date(DM_PHP_DATE_FORMAT) ; ?>',
		toggleElements: '.datetoggle'
    });
});
</script>

<h1>Edit Employment Preferences</h1>

<form action="?action=<?php echo $_GET['action'] ; ?>" onsubmit="isSumbition();" name="optionsform" id="optionsform" method="post" >

    <div class="float_container" >
		<div class="floater" >
            <label for="employment_status" >Current Employment Status <?php echo check_my_array('employment_status',$graduate_required_fields,'*') ; ?></label>
            <div class="lister" >
            <ul>
			<?php
				for($i=0;$i<sizeof($employment_status_array);$i++){
					// Check if selected
					if($_SESSION['employment_status']==$employment_status_array[$i]||($_SESSION['employment_status']==''&&$employment_status_array[$i]=='unemployed')){
						$is_selected = 'checked="checked" ' ;
					} else {
						$is_selected = '' ;
					}
					?>
               		<li><input onclick="isModified();" id="employment_status_<?php echo $i ; ?>" type="radio" value="<?php echo $employment_status_array[$i] ; ?>" name="employment_status" <?php echo $is_selected ; ?>/><span onclick="isModified(); check_me('employment_status_<?php echo $i ; ?>','radio');" ><?php echo ucfirst($employment_status_array[$i]) ; ?></span></li>
					<?php
				}
			?>
            </ul>
            </div>
		</div>
    </div>


    <label for="availability" >Available for Employment <?php echo check_my_array('availability',$graduate_required_fields,'*') ; ?></label>
	<div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="availability" id="availability" class="text date fldmar" value="<?php echo ($_SESSION['availability']!='' ? date(DM_PHP_DATE_PICK_FORMAT,strtotime($_SESSION['availability'])) : '') ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>&nbsp;<span onclick="document.getElementById('availability').value=''; isModified();" class="pointer" ><?php echo draw_icon('delete.png') ; ?></span>

    <div class="float_container" >

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
            <label for="hours" >Hours Required <?php echo check_my_array('hours',$graduate_required_fields,'*') ; ?></label>
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


    
    <div style="margin:6px; "><input type="submit" name="gradsubmit" id="gradsubmit" value="Update Employment Preferences" /></div>

</form>
