<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : Help</title>
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
                <h1>Help</h1>
              <p>Lorem ipsum   dolor sit amet, consectetur adipiscing elit. Morbi consectetur, urna ac   pharetra accumsan, nunc leo sagittis ligula, id consequat metus ante   iaculis dui. Morbi sagittis vestibulum est commodo scelerisque.   Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere   cubilia Curae; Proin ultricies adipiscing mi id pharetra. Mauris   posuere tellus non nunc viverra vitae semper urna tincidunt.</p>
                <p>Aenean   sodales lacus sed ante aliquam tempus sit amet ut arcu. Donec id   aliquam leo. Duis vel tellus at orci dapibus pulvinar in eget orci.   Pellentesque vel diam dictum arcu viverra adipiscing eu et nulla. Etiam   vitae purus nec leo sollicitudin ultrices. Sed tempus, sem eleifend   fermentum tristique, lacus urna feugiat ante, ut imperdiet lacus eros   non magna. Vivamus massa metus, molestie sed mattis id, aliquet a   sapien.</p>
                <h3>Sign Up Now For Your FREE Graduate Profile.</h3>
                <p><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> is free to graduates. Sign up right away to have your profile listed the moment our exclusive  search engine goes live and have the best possible chance of being found by employers.</p>
                <p><?php echo draw_icon(ICON_ACCOUNT) ; ?><a href="graduate_signup.php">Click here to register for a FREE graduate account.</a></p>
            </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
