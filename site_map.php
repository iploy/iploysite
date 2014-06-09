<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : Site Map</title>
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
                <h1>Site Map</h1>
                <div class="sitemap" >
                    <ul>
                        <li><a href="./" ><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> Homepage</a></li>
                      <li><a href="about_iploy.php" >About <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
                        <li><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> For Employers
                        <ul>
                            <li><a href="employers.php" >Employer Home</a></li>
                            <li><a href="employer_signup.php" >Employer Signup</a></li>
                        </ul>
                        </li>
                        <li><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> For Graduates
                        <ul>
                            <li><a href="graduates.php" >Graduate Home</a></li>
                            <li><a href="graduate_signup.php" >Graduate Signup</a></li>
                        </ul>
                        </li>
                        <li><a href="advice/" >Advice</a></li>
                        <li><a href="blog/" >Blog</a></li>
                        <li><a href="login.php" >Login</a></li>
                        <li><a href="iploy_terms_and_conditions.php" >Terms &amp; Conditions</a></li>
                        <li><a href="site_map.php" >Site Map</a></li>
                        <li><a href="contact_us.php" >Contact <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></li>
                    </ul>
                </div>
            </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
