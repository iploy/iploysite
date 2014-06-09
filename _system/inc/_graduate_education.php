<?php

	include_once('_system/_config/_multiple_choice_arrays.php') ;
	include_once('_system/functions/check_my_array.php') ;
	include_once('_system/classes/graduate_admin.php') ;

	$screen_message = '' ;
	// Always declare the graduate class
	if($_POST){
		$graduate_function = new graduate_info ;
		$graduate_update = $graduate_function->saveEducationInfo($_SESSION['user_id'],true) ;
		if($graduate_update!=''){ // if this value is not blank, there was an error
			$screen_message= draw_icon(ICON_BAD).$graduate_update ;
			$screen_message_type = 'error' ;
		} else {
			$screen_message = draw_icon(ICON_GOOD).'Education Information Updated Successfully' ;
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
		toggleElements: '.datetoggle'
    });
});
</script>

<h1>Edit Education Information</h1>

<form action="?action=<?php echo $_GET['action'] ; ?>" onsubmit="isSumbition();" name="optionsform" id="optionsform" method="post" >


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
    <div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="education_start" id="education_start" class="text date fldmar" value="<?php echo ($_SESSION['education_start']!='' ? date(DM_PHP_DATE_PICK_FORMAT,strtotime($_SESSION['education_start'])) : '') ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>&nbsp;<span onclick="document.getElementById('education_start').value=''; isModified();" class="pointer" ><?php echo draw_icon('delete.png') ; ?></span>

    <label for="education_end" >End <?php echo check_my_array('education_end',$graduate_required_fields,'*') ; ?></label>
    <div class="datetoggle" ></div><input onchange="isModified();" readonly="readonly" tabindex="3" type="text" name="education_end" id="education_end" class="text date fldmar" value="<?php echo ($_SESSION['education_end']!='' ? date(DM_PHP_DATE_PICK_FORMAT,strtotime($_SESSION['education_end'])) : '') ;?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>&nbsp;<span onclick="document.getElementById('education_end').value=''; isModified();" class="pointer" ><?php echo draw_icon('delete.png') ; ?></span>



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

    
    <div style="margin:6px; "><input type="submit" name="gradsubmit" id="gradsubmit" value="Update Education Information" /></div>

</form>
