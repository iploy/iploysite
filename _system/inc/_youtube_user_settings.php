
<h1>Youtube User Settings</h1>
<?php
include('_system/inc/_screen_message_handler.php') ;
if($_SESSION['youtube_id']!=''){
    ?>
    <p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?><b>WARNING</b>: Only 1 youtube account may be used. Changing this information will remove any videos currently listed on your <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> profile.</p>
    <?php


} else {
    ?>
    <p><?php echo draw_icon('add.png') ; ?>Adding your Youtube profile ID will allow you to pick from videos on your Youtube account to be listed with your <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> profile.</p>
    <?php
}
?>
<script language="javascript" type="text/javascript" >
function confirmChange(old){
	if(old==document.getElementById('username').value){
		alert('UPDATE CANCELED\nYour <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> account is already linked to the Youtube profile \''+old+'\'') ;
		return false ;
	} else {
		if(confirm('WARNING:\nChanging the Youtube profile associated with this <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> account will also detatch all videos currently listed on your <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> profile. You will only be able to add videos from the new Youtube account after performing this action.\n\nPress OK to confirm this action or cancel to retain the current Youtube videos')){
			return true ;
		} else {
			return false ;
		}
	}
}
</script>
<form action="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=changeusername" method="post" onsubmit="return confirmChange('<?php echo addslashes($_SESSION['youtube_id']) ; ?>');" >
    <label>Youtube User ID / Profile ID</label>
    <input type="text" class="text" name="username" id="username" value="<?php echo $_SESSION['youtube_id'] ; ?>" />
    <div style="margin-top:-10px;"><input type="submit" name="submitbut" id="submitbut" value="Update Profile ID" /></div>
    <input type="hidden" name="issubmit" value="true" />
</form>
<div class="greydivider" ></div>
<p><?php echo draw_icon(ICON_BACK) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>" >Cancel and go back to the previous screen</a></p>
