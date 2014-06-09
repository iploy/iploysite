<?php

	ob_start(); 

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	include_once('_system/inc/_authentication.php') ;

	include_once('_system/inc/_youtube_cache.php') ;

	// inculude a processing script if it exists - this enables redirects
	if(file_exists(SITE_PATH.'_system/inc/_process_'.$_GET['action'].'_'.$_GET['subaction'].'.php')){
		// check for _process_action_subaction.php first 
		include_once(SITE_PATH.'_system/inc/_process_'.$_GET['action'].'_'.$_GET['subaction'].'.php') ;
	} elseif($_GET['subaction']==''){
		// check for _process_action.php if no subaction 
		if(file_exists(SITE_PATH.'_system/inc/_process_'.$_GET['action'].'.php')){
			include_once(SITE_PATH.'_system/inc/_process_'.$_GET['action'].'.php') ;
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : <?php echo $my_account_title_txt ; ?></title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php $is_admin_home = true ; include('_global_head.php') ; ?>
<?php
	// inculude a head script if it exists - this enables redirects
	if(file_exists(SITE_PATH.'_system/inc/_head_'.$_GET['action'].'_'.$_GET['subaction'].'.php')){
		// check for _head_action_subaction.php first 
		include_once(SITE_PATH.'_system/inc/_head_'.$_GET['action'].'_'.$_GET['subaction'].'.php') ;
	} elseif($_GET['subaction']==''){
		// check for _head_action.php if no subaction 
		if(file_exists(SITE_PATH.'_system/inc/_head_'.$_GET['action'].'.php')){
			include_once(SITE_PATH.'_system/inc/_head_'.$_GET['action'].'.php') ;
		}
	}
?>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain" >
            <div class="bodymain_center" >
                <?php include('_system/inc/'.$content_include) ; ?>
            </div>
        </div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
