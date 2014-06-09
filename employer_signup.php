<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	if($_GET['step']==1||$_GET['step']==''){
		include('_system/classes/session_killer.php') ;
		$session_killer = new sessionKiller ;
		$session_killer->killAll() ;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	$signup_mode = 'employer' ;
	include('_system/inc/_signup_process.php') ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : <?php echo ucfirst($signup_mode) ; ?> Signup</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php $_SESSION['site_style'] = 'employers' ; include('_global_head.php') ; ?>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
            <div class="bodymain_center" >
                <h1><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> <?php echo ucfirst($signup_mode) ; ?> Signup</h1>
                <?php include('_system/inc/_signup_steps.php') ; ?>
            </div>
        </div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>

</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
