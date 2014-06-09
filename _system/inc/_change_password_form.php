<!-- MooStrong -->
<script language="javascript" type="text/javascript" > var password_field_name = 'new_password' ; </script>
<script language="javascript" type="text/javascript" src="js/mooStrong.js" ></script>
<link rel="stylesheet" href="js/mooStrong.css" type="text/css" media="screen" />
<!-- MooStrong -->
<script language="javascript" type="text/javascript" >

function verifyPassChange(){

	error_msg = '' ;

	if(document.getElementById('new_password').value.length<6||document.getElementById('new_password').value.length<6){
		error_msg = 'Your new password must be at least 6 characters in length' ;
	}

	if(document.getElementById('new_password').value!=document.getElementById('conf_new_password').value){
		error_msg = 'The new password and confirm password entered do not match' ;
	}

	if(document.getElementById('current_password').value.length<6){
		error_msg = 'You must enter your current password in order to enter a new one' ;
	}

	if(error_msg==''){
		return true ;
	} else {
		alert('PASSWORD CHANGE ERROR:\n'+error_msg) ;
		return false ;
	}

}

</script>
<ul>
    <li>Passwords must be a minimum of 6 characters in length (10 or above is recommended)</li>
    <li>For maximum security use a combination of numbers, capital and lower case letters and a symbol.</li>
</ul>
<form action="?action=<?php echo $_GET['action'] ; ?>&subaction=changepass" method="post" onsubmit="return verifyPassChange();" >

	<label for="email" >Current Password</label>
    <input type="password" name="current_password" id="current_password" class="text" />

	<label for="email" >New Password</label>
    <input type="password" name="new_password" id="new_password" class="text" />
	<div id="password_strength" ></div>

	<label for="email" >Confirm New Password</label>
    <input type="password" name="conf_new_password" id="conf_new_password" class="text" />


	<div><input type="submit" name="submit_password" value="Change Password" /></div>

</form>