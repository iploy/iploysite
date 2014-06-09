<?php
	include_once('_system/_config/configure.php') ;
	include('_system/inc/_authentication.php') ;
	include_once('_system/inc/app_top.php') ;
	include_once('_system/_config/_message_required_fields.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	$error = false ;
	if($_GET['messageid']==''||!is_numeric($_GET['messageid'])||$_GET['messageid']<1){
		// basic querystring error
		$error  = 'qserror' ;
		$error  = 'Bad request detected, please do not manually modify the address bar.' ;
	} else {
		// get message info
		include_once('_system/classes/messages.php') ;
		$mvf = new messages ;
		$message = $mvf->getMessageById($_GET['messageid'],$_SESSION['user_id'],false) ; // flag as false to just get this message (for now)
		if($message==false){
			// basic querystring error
			$error  = 'permissionerror' ;
			$error  = 'You do not have permission to view that message.' ;
		} else {
			if($message['conversation_index']!=0){
				$conversationId = $message['conversation_index'] ;
			} else {
				$conversationId = $_GET['messageid'] ;
			}
			include_once('_system/inc/_messages_conversation_top.php') ;
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : Employer Profile</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
<?php
	if(sizeof($converastion)>0){
		include_once('_system/inc/_messages_conversation_head.php') ;
	}
?>
<script language="javascript" type="text/javascript" src="js/messageUrlPop.js" ></script>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
                <h1>View Message</h1>
              	<?php
					if($error===false){
						// get message contents
						$show_links = true ;
						include('_system/inc/_message_viewer.php') ;
						$show_conversation = true ; 
						include_once('_system/inc/_messages_conversation_body.php') ;
						unset($_SESSION['inbox_message_last_count']) ;
					} else {
						// do the error
						?>
                        <p class="error" ><?php echo draw_icon(ICON_BAD).$error ; ?></p>
						<?php
						if($showLogin==true){
							?>
                            <p><?php echo draw_icon(ICON_ACCOUNT) ; ?><a href="login.php" >Click here to login now</a></p>
                            <?php
						}
					}
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
