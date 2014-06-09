<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : Terms and Conditions</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
                <h1>Graduate payment incentive</h1>
              <p><b>How to qualify for payment</b></p>
              <p>In order to qualify for payment, the graduate must:</p>
              <ul>
                <li>Have a CV uploaded on their profile</li>
                <li>Have a photograph uploaded on their profile</li>
                <li>Completed all the required fields within their profile</li>
              </ul>
              <p><b>Withdrawing payment </b></p>
              <p> If the graduate meets all the above conditions, they will receive £5 credit every time an employer unlocks their profile.</p>
              <p> The minimum required amount before a graduate can redeem payment is 5 visits (£25.00)</p>
              <p><?php echo draw_icon('graduate.png') ; ?><a href="graduate_signup.php">Click here to signup for a free graduate account</a>.<br />
              </p>
		  </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
