<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> |  About the iPloy Graduate recruitment website</title>
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
                <h1>About <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></h1>
                <h6><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?>. Join the Graduate Recruitment Revolution...</h6>
                <p> <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> is a specialist  graduate recruitment website with a difference. We give both the graduate and  the employer a new, fresh and interactive path through the graduate recruitment  journey.</p>
                <p> Employers are finding it increasingly difficult to find  the right graduates they require, despite application volumes increasing. We  provide all the information and tools you need to land you the perfect job.</p>
              <ol>
                <li><strong>Register FREE with <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?><br />
                </strong>Sign up  for a&nbsp;<a href="graduate_signup.php"><strong>FREE account</strong></a>&nbsp;that  will promote all your characteristics, background and academic achievements  that you want to stand out to prospective employers.<br />
                The  more you put into your profile, the more you’ll get out of it!<br />
                <br />
                </li>
                <li><strong>Let the Employer Find  You<br />
                </strong>The  employer comes to you, meaning you don’t have to waste time trawling through  hundreds of jobs you can't apply for. <br />
                  All you  have to do is make sure your profile is as good as you can make it<br />
                  <br />
                </li>
                <li><strong>Get  to Know Your Employer<br />
                </strong><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> graduate  recruitment offers you the opportunity to interact and get to know your  potential employer though our unique online service, before even reaching the  interview stage!</li>
              </ol>
              <p><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> give the best employers access to the  most employable graduates in the UK. <strong>Sign  up</strong> to let employers interact with you. Help them find&nbsp;<strong>graduates </strong>with the right personality, background  and academic achievement to fit their company culture.</p>
		  </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
