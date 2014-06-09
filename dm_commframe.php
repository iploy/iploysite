<?php include_once('_system/_config/configure.php') ; ?>
<?php include_once('_system/inc/app_top.php') ; ?>
<script type="text/javascript" language="javascript" src="js/mootools-1.2.4-core.js" ></script>
<?php
	// Only dispolay this java when the SQ is not present
	if(!$_GET['error']=='noframe'){
	?>
	<script type="text/javascript" language="javascript" >
		var noframes_redirect_url = '?error=noframe' ;
		var toploc = ''+top.location ;
		if(toploc.indexOf("dm_commframe.php")>-1){
			document.location = noframes_redirect_url ;
		}
	</script>
	<?php
	}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	if(strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])&&$_GET['error']!='noframe'){

		$action = 'No Action' ;

		// Check Unique email - - - - - - 
		if($_GET['action']=='uniquemail'){
			$action = 'Check Email ('.$_GET['email'].')' ;
			include_once('_system/classes/user_info.php') ;
			$email_is_unique = false ;
			$email_check = new user_info ;
			$email_check_row = $email_check->getInfoByEmail($_GET['email']) ;
			if(!$email_check_row['id']){
				$email_is_unique = true ;
				//$action.= ' (is unique)' ;
				$top_js_function = 'validatefields()' ;
			} else {
				//$action.= ' (is not unique)' ;
				$top_js_function = 'duplicateemail()' ;
			}
			?>
			<script type="text/javascript" language="javascript" >
				top.<?php echo $top_js_function ; ?>
			</script>
			<?php
		}
		// - - - - - - - - - - - - - - - 
		// echo "<p>".$action.'</p>' ;
	} else {
		echo '<h1>Authetication Error</h1><p>You are not authorised to access this page</p>' ;
	}

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	include_once('_system/inc/app_bottom.php') ;

?>
