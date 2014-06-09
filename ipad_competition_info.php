<?php

	header('Location: graduates.php') ;
	exit() ;

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 


	if($_GET['promo']=='submited'){
		$screen_message = draw_icon(ICON_ALERT).'You have already submitted an entry to the '.$_SESSION['APP_CLIENT_NAME'].' iPad Givaway promotion. Only 1 entry is allowed per user.' ;
		$screen_message_type = 'notice' ;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> | iPloy iPad Giveaway</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
<style type="text/css" >
#ipadMain {
	width:700px;
	height:913px;
	background:url(images/graphics/ipad/ipad_xmas.jpg) no-repeat center;
	overflow:auto;
	margin:24px 0;
	color:#FFF;
}
#ipadContent {
	margin:108px 84px 0;
	height:706px;
	border-radius:20px;
	overflow:auto;
	font-size:16px;
	line-height:22px;
}
#ipadContent p {
	margin:12px;
}
#ipadContent .Highlight {
	color:#E40311;
}
#signuplink {
	margin-top:20px;
	display:block;
	width:160px;
	height:70px;
	background:#000;
	color:#FFF;
	font-size:30px;
	border-radius:16px;
	background:url(images/graphics/ipad/signup_bg.png) top repeat-x;
}
#signuplink:hover {
	text-decoration:none;
	background:url(images/graphics/ipad/signup_bg.png) bottom repeat-x;
}
</style>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
				<div align="center">
                	<div id="ipadMain" >
                    	<div id="ipadContent" align="left"  >
                            <div style="margin:110px 30px 0 45px;" align="center">
                              <p>We need graduates now and for 2012. 
                              Sign up and get<br />
                              entry into our iPad 2 (16gb Wi-Fi only) draw, it's free.</p>
                              <img src="images/graphics/ipad/logo_white_with_couk.png" width="381" height="139" border="0" alt="" style="margin:4px 0;" />
                              <p style="font-size:13px;">Three easy steps to win the incredible device, all you have to do is:</p>
                              <img src="images/graphics/ipad/steps_white_on_red.png" alt="" width="424" height="64" border="0" usemap="#Map" />
<map name="Map" id="Map"><area shape="rect" coords="7,22,126,59" href="http://www.facebook.com/pages/iPloy/261519700538406" target="_blank" />
</map>
                              <p style="font-size:14px;" ><span style="font-size:14px;" >Complete all steps to gain entry into prize draw.</span><br />
                              Deadline for entry is <b>31st December 2011</b>.<br />
                              Terms and Conditions apply.</p>
                              <p><em><b>Good luck! </b></em></p>
                              <div>
                              	<a href="graduate_signup.php" id="signuplink" ><img src="images/graphics/ipad/signup_text.png" width="160" height="70" border="0" alt="" /></a>
                              </div>
                          </div>
                        </div>
                    </div>
                </div>
                <?php
					/*
               		<h1>The <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> iPad Giveaway!</h1>
					if($_SESSION['user_id']!=''&&$_SESSION['user_level']==1){
						?>
                    	<p><?php echo draw_icon('ipad.png') ; ?><a href="home.php">Complete your graduate profile to enter our iPad Givaway Competition</a></p>
                        <?php
					} else {
						?>
                    	<p><?php echo draw_icon('ipad.png') ; ?><a href="graduate_signup.php">Sign up for a graduate account now to be in with a chance of winning this iPad 2!</a></p>
                        <?php
					}
					*/
				?>

          </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
