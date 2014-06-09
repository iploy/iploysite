<?php
	if($profile_progress==''){
		include_once('_system/functions/graduate_profile_progress.php') ;
		$profile_progress = graduate_profile_progress($graduate_required_fields,$_SESSION) ;
		$profile_progress_color = progress_colour($profile_progress) ;
	}
?>
<div class="profile_info" >

	<h2>Profile Progress</h2>
    <p>Please answer all of the required (*) fields to complete your profile. You will be able to submit this form without completing all required information, but your profile will not be searchable from the main website until you have completed these fields.</p>
    <div class="percentage" >
    	<div class="text" ><span><?php echo $profile_progress ; ?>%</span></div>
        <div class="bar" style="width:<?php echo $profile_progress ; ?>%; background:<?php echo $profile_progress_color ; ?>;" ></div>
    </div>


    <p>Optional fields will help you get noticed.</p>
	<?php
		$profile_progress = graduate_profile_progress($graduate_optional_fields,$_SESSION) ;
		$profile_progress_color = progress_colour($profile_progress) ;
	?>
    <div class="percentage">
    	<div class="text" ><span><?php echo $profile_progress ; ?>%</span></div>
        <div class="bar" style="width:<?php echo $profile_progress ; ?>%; background:<?php echo $profile_progress_color ; ?>;" ></div>
    </div>

	<h2>Uploading files:</h2>
    <ul>
    	<li><a href="home.php?action=upload" >Uploads</a> are optional, but will help you get noticed.</li>
    	<li>Uploading a new photograph, CV or certificate will delete the old file in favor of the new one.</li>
    	<li>Uploads require approval from our moderators. This process should take on average 24-48 hours.</li>
    	<li>If you update your uploads, they will need to be approved again by an administrator.</li>
        <li>Smaller file sizes will ensure successful upload.</li>
        <li>Photographs will be resized.</li>
    </ul>
   
</div>