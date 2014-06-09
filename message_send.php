<?php
	include_once('_system/_config/configure.php') ;
	include('_system/inc/_authentication.php') ;
	include_once('_system/inc/app_top.php') ;
	include_once('_system/_config/_message_required_fields.php') ;

	// force logout for bad referer
	/*
	if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_REFERER'])){
		include_once('_system/classes/session_killer.php') ;
		$session_killer = new sessionKiller ;
		$session_killer->killAll() ;
		header('Location: login.php?error=permissionerror') ;
		exit() ;
	}
	*/

	if($_POST['profileid']!=''){
		$profileid = $_POST['profileid'] ;
	} else {
		$profileid = $_GET['profileid'] ;
	}
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	// referal keeper 
	$referer_array = explode("/",$_SERVER['HTTP_REFERER']) ;
	if(!strstr($referer_array[sizeof($referer_array)-1],"message_send.php")){
		$_SESSION['message_referer'] = $referer_array[sizeof($referer_array)-1] ;
	} elseif($_SESSION['message_referer']==''){
		$_SESSION['message_referer'] = 'home.php' ;
	}
	// echo '<p><a href="'.$_SESSION['message_referer'].'" >'.$_SESSION['message_referer'].'</a></p>' ;
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	$error = false ;
	$showLogin = false ;
	$page_title = 'Send Message' ;
	$is_reply = false ;

	include_once('_system/classes/messages.php') ;
	$mvf = new messages ;

	// load message info
	if(($_GET['replyid']==''||!is_numeric($_GET['replyid'])||$_GET['replyid']<1)&&($_GET['followupid']==''||!is_numeric($_GET['followupid'])||$_GET['followupid']<1)){
		if(($profileid==''||!is_numeric($profileid)||$profileid<1)&&($_SESSION['user_level']!=0&&$_SESSION['user_level']!=3)){
			// no user requests
			$error = '<b>Recipient Error</b>: No user was requested' ;
		} elseif($_SESSION['user_id']==''){
			// no user ID
			$error  = '<b>Authentication Error</b>: You must logged in order to use this feature.' ;
			$showLogin = true ;
		} elseif($_SESSION['user_level']==1){
			// the user is a graduate
			$error  = '<b>Authentication Error</b>: This feature is not available to graduates' ;
		} else {
			// by this point we know the user is an employer or administrator so check permissions for employers / dont do anything for admins
			if($_SESSION['user_level']==2){
				include('_system/classes/transactions.php') ;
				$epf = new transactions ;
				if($epf->checkEmployerToGraduateAccess($_SESSION['user_id'],$profileid)!=true){
					$error  = '<b>Authentication Error</b>: You must unlock this user\'s profile to send a message.' ;
				}
			}
		}
	} else {
		include_once('_system/classes/messages.php') ;
		$show_conversation = true ;
		$mrf = new messages ;
		// no user requests
		if($_GET['replyid']!=''){
			$mvf = new messages ;
			$message = $mvf->getMessageById($_GET['replyid'],$_SESSION['user_id'],false) ; // flag as false to just get this message (for now)
			$page_title = 'Send Reply' ;
			$conversationId = $mrf->getConversationRoot($_GET['replyid']) ;
			if($conversationId==0){
				$conversationId = $_GET['replyid'] ;
			}
		} else {
			$message = $mvf->getMessageById($_GET['followupid'],$_SESSION['user_id'],false) ; // flag as false to just get this message (for now)
			$page_title = 'Send Follow Up' ;
			$conversationId = $mrf->getConversationRoot($_GET['followupid']) ;
			if($conversationId==0){
				$conversationId = $_GET['followupid'] ;
			}
		}
		include_once('_system/inc/_messages_conversation_top.php') ;
	}


	if($error===false&&$profileid!=''){
		include_once('_system/classes/messages.php') ;
		$mrf = new messages ;
		$recipientsArray = explode(",",$profileid) ;
		$mrf->setRecipients($recipientsArray) ;
		$msg_recipient = $mrf->getRecipientsString() ;
		if($msg_recipient['status']===true){
			$msg_recipient = $msg_recipient['statustxt'] ;
			if(msg_recipient==''){
				$msg_recipient = 'Recipient ID: '.$profileid ;
			}
		} else {
			// $error = $msg_recipient['status'] ;
			$screen_message = draw_icon(ICON_BAD).'<b>Recipient Error</b>: '.$sendResult['statustxt'] ;
			$screen_message_type = 'error' ;
		}
	}



	// if sent message, process
	if($_GET['sendmessage']=='true'&&$error==false){
		$message_sent = false ;
		$error_message = array() ;
		for($i=0;$i<sizeof($message_required_fields);$i++){
			if(trim($_POST[$message_required_fields[$i]])==''){
				$error_message[] = $message_friendly_fields[$i] ;
			}
		}
		$message_array = $_POST ;
		// build the message
		$screen_message = '' ;
		$screen_message_type = 'error' ;
		for($i=0;$i<sizeof($error_message);$i++){
			if($i>0&&$i<(sizeof($error_message)-1)){
				$screen_message.= ', ' ; //  add a comma to anything but the first and last
			} else if (sizeof($error_message)>1&&$i==(sizeof($error_message)-1)){
				$screen_message.= ' and ' ; // add and to the last one
			}
			$screen_message.= $error_message[$i] ;
		}
		if(strlen($screen_message)>0){
			// draw an error if screen message detected at this point
			if(sizeof($error_message)==1){
				$field_txt = 'field is' ;
			} else {
				$field_txt = 'fields are' ;
			}
			$screen_message = draw_icon(ICON_BAD).'<b>Your message could not be sent</b>: The '.$screen_message.' '.$field_txt.' required.' ;
		} elseif($profileid==0&&($_SESSION['user_level']!=0&&$_SESSION['user_level']!=3)){
			$screen_message = draw_icon(ICON_BAD).'<b>Error 101</b>: Messages of that type can not be sent by none administrators.' ;
		} else {
			// if no message, send the mail
			include_once('_system/classes/messages.php') ;
			$message_sent = true ;
			$smf = new messages ;
			$smf->setRecipients(explode(",",$profileid)) ;
			$smf->setMessageSubject($_POST['msg_subject']) ;
			$smf->setMessageContent($_POST['msg_content']) ;
			$smf->setAuthorId($_SESSION['user_id']) ;
			$smf->setUserLevel($_SESSION['user_level']) ;
			$smf->setConversationIndex($_POST['conversation_index']) ;// only accepted by the class if not null and bigger than 0
			$sendResult= $smf->sendMessage() ;
			if($sendResult['status']===true){
				$screen_message = draw_icon(ICON_GOOD).'Message Sent' ;
				$screen_message_type = 'success' ;
				// REDIRECT TO THE REFERAL
				if(strstr($_SESSION['message_referer'],'message_view.php')){
					$_SESSION['message_referer'] = 'home.php?action=messages' ;
				}
				if(strstr($_SESSION['message_referer'],"?")){
					$redirect = $_SESSION['message_referer'].'&message=sent' ;
				} else {
					$redirect = $_SESSION['message_referer'] = '?message=sent' ;
				}
				// echo '<p>RD: '.$redirect.'</p>' ;
				unset($_SESSION['message_referer']) ;
				header('Location: '.$redirect) ;
				exit() ;
			} else {
				$screen_message = draw_icon(ICON_BAD).'<b>The message could not be sent</b>: '.$sendResult['statustxt'] ;
				$screen_message_type = 'error' ;
			}
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : Send Message</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php
	include_once('_system/inc/_include_mootools_head.php') ;
	if($show_conversation==true&&sizeof($converastion)>0){
		include_once('_system/inc/_messages_conversation_head.php') ;
	}
?>
<?php include('_global_head.php') ; ?>
<link rel="stylesheet" type="text/css" href="js/MooEditable/MooEditable.css" />
<link rel="stylesheet" type="text/css" href="js/MooEditable/MooEditable.SilkTheme.css" />
<script type="text/javascript" src="js/MooEditable/MooEditable.js" ></script>
<script type="text/javascript" src="js/MooEditable/MooEditable.UI.MenuList.js"></script>
<script type="text/javascript" src="js/MooEditable/MooEditable.Extras.js"></script>
<script type="text/javascript">
window.addEvent('domready', function(){
	$('msg_content').mooEditable({
		actions: 'bold italic underline | formatBlock justifyleft justifyright justifycenter justifyfull | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | urlimage', baseCSS: 'html{ height: 100%; font-size:12px; cursor: text; color:#555; } body{ font-family:Arial, Helvetica, sans-serif; } h1, h2, h3, p { margin:10px 0px; } h1, h2, h3 { padding:0; border:none; font-weight:bold; } h1 { font-size:22px; } h2 { font-size:18px; } h3 { font-size:16px; } p+h1, p+h2, p+h3 { margin-top:26px; } a { text-decoration:none; color:#00F; display:inline-block; background:url(images/icons/link.png) left bottom no-repeat; padding-left:10px; }'
	});// , paragraphise: false

	$('editorResizeLinks').innerHTML = '<b>Resize Editor:</b> <?php echo str_replace('class="ico"','class="ico pointer" id="msgSmaller" onclick="resizeEditor(\\\'smaller\\\');" title="Reduce"',draw_icon('textarea_smaller.png')) ; ?> | <?php echo str_replace('class="ico"','class="ico pointer" id="msgBigger" onclick="resizeEditor(\\\'bigger\\\');" title="Expand"',draw_icon('textarea_bigger.png')) ; ?> | <?php echo str_replace('class="ico"','class="ico pointer" id="msgOriginal" onclick="resizeEditor(\\\'original\\\');" title="Default"',draw_icon('textarea_reset.png')) ; ?>' ;

	// init editor
	resizeEditor('noresize') ;

});

function focusEditor(){
	document.location.hash = 'msgContent' ;
}

function resizeEditor(direction){
	editorDefaultHeight = 262 ; 
	editorMaxHeight = editorDefaultHeight+200 ; 
	editorCurrentHeight = parseFloat($$('#msgform .mooeditable-iframe').getStyle('height')) ;
	editorNewHeight = 0 ;
	if(direction=='bigger'){
		editorNewHeight = editorCurrentHeight + 100 ;
	} else if (direction=='smaller'){
		editorNewHeight = editorCurrentHeight - 100 ;
	} else if (direction=='original'){
		editorNewHeight = editorDefaultHeight ;
	}
	if(editorNewHeight>=editorDefaultHeight&&editorNewHeight<=editorMaxHeight){
		$$('#msgform .mooeditable-iframe').morph({'height':editorNewHeight}) ;
		focusEditor.delay(600) ;
	}
	// bogger link disable on max or more
	if(editorNewHeight>=editorMaxHeight){
		//$('msgBigger').src = 'images/icons/down_grey.png' ;
		$('msgBigger').morph({'opacity':0.3}) ;
		$('msgBigger').set('class','ico') ;
	} else {
		//$('msgBigger').src = 'images/icons/down.png' ;
		$('msgBigger').morph({'opacity':1}) ;
		$('msgBigger').set('class','ico pointer') ;
	}
	// smaller link disable on default or less
	if(editorNewHeight<=editorDefaultHeight){
		//$('msgSmaller').src = 'images/icons/up_grey.png' ;
		$('msgSmaller').morph({'opacity':0.3}) ;
		$('msgSmaller').set('class','ico') ;
	} else {
		//$('msgSmaller').src = 'images/icons/up.png' ;
		$('msgSmaller').morph({'opacity':1}) ;
		$('msgSmaller').set('class','ico pointer') ;
	}
}

</script>
<script language="javascript" type="text/javascript" src="js/messageUrlPop.js" ></script>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
                <h1><?php echo $page_title ; ?></h1>
              	<?php
				
				if($error===false){
					include('_system/inc/_message_form.php') ;
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

				include_once('_system/inc/_messages_conversation_body.php') ;
        
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
