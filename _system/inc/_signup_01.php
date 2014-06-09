<script language="javascript" type="text/javascript" src="js/isEmail.js" ></script>
<!-- MooStrong -->
<script language="javascript" type="text/javascript" > var password_field_name = 'password' ; </script>
<script language="javascript" type="text/javascript" src="js/mooStrong.js" ></script>
<link rel="stylesheet" href="js/mooStrong.css" type="text/css" media="screen" />
<!-- MooStrong -->
<script language="javascript" type="text/javascript" >

function validatelogin(){
	dm_commframe_doc = 'dm_commframe.php' ;
	if(isEmail('email')){
		document.getElementById('dm_commframe').src = dm_commframe_doc+'?action=uniquemail&email='+document.getElementById('email').value ;
	} else {
		alert('FORM SUBMISSION ERROR:\nPlease enter a valid email address') ;
		// $('inline_message_p').innerHTML = 'Please enter a valid email address' ;
		// $('inline_message_p').set('class','notice') ;
	}
	return false ;
}

function validatefields(){
	email = document.getElementById('email').value ;
	password = document.getElementById('password').value ;
	password_conf = document.getElementById('password_conf').value ;
	error_msg = '' ;
	if(password!=password_conf){
		error_msg = 'The entered password and confirmation password do not match' ;
	}
	if(password.length<password_minimum_lenght){
		error_msg = 'Your password must be a minimum of '+password_minimum_lenght+' characters in length' ;
	}
	if(!isEmail('email')){
		error_msg = 'Please enter a valid email address' ;
	}
	if(error_msg==''){
		document.forms['signupform'].submit() ;
	} else {
		// $('inline_message_p').innerHTML = error_msg ;
		// $('inline_message_p').set('class','notice') ;
		alert('FORM SUBMISSION ERROR:\n'+error_msg) ;
		return false ;
	}
}
function duplicateemail(){
	alert('FORM SUBMISSION ERROR:\nThat email address was already used to register an account.') ;
	// $('inline_message_p').innerHTML = 'That email address was already used to register an account' ;
	// $('inline_message_p').set('class','notice') ;
}
</script>
<style type="text/css" >
.left_float {
	float:left;
}
.right_float {
	float:left;
}
form {
	margin:16px 0 16px 24px;
}
.step1 {
	font-weight:bold;
	color:<?php echo $color ; ?> !important ;
}
</style>

<?php include('_signup_steps_ul_li.php') ; ?>

<p>Please use the form below to signup for a <b>FREE <?php echo $_SESSION['APP_CLIENT_NAME'].' '.ucfirst($signup_mode) ;?> account</b>. This will allow you to create a profile and add your details to the <?php echo $_SESSION['APP_CLIENT_NAME'].' '.ucfirst($signup_mode) ;?> database.</p>

<div class="divider" ></div>

<?php include('_screen_message_handler.php') ; ?>

<!--
<div id="inline_message" >
	<p id="inline_message_p" ></p>
</div>
-->

	<h2>Signup Tips</h2>
	<ul>
		<li>Your E-Mail address will be used to login to manage your profile.</li>
		<li>Passwords must be a minimum of 6 characters in length (10 or above is recommended)</li>
		<li>While any password longer than 6 characters can be used, for maximum security we recommend you use a combination of numbers, capital and lower case letters, and maybe even a symbol for further enhanced security.</li>
	</ul>

<h2>Create <?php echo ($signup_mode=='employer' ? 'an' : 'a').' '.ucfirst($signup_mode) ; ?> Profile</h2>
<form id="signupform" action="?post=step1" onsubmit="return validatelogin();" method="post" >
	<label for="email" >Email Address</label>
	<input class="text" type="text" name="email" id="email" value="<?php echo $_POST['email'] ; ?>" />
	<label for="password" >Password</label>
	<input class="text" type="password" name="password" id="password" />
	<div id="password_strength" ></div>
	<label for="password_conf" >Confirm Password</label>
	<input class="text" type="password" name="password_conf" id="password_conf" />
	<div><input type="submit" value="Submit" /></div>
    <input type="hidden" name="referrer" id="referrer" value="<?php echo $_SESSION['signup_referrer'] ; ?>" />
</form>

<p><?php echo draw_icon('key.png') ; ?>If you have already started the signup process or you already have an account, <a href="login.php">click here to login now</a>.</p>


<?php include('_dm_commframe.php') ; ?>
