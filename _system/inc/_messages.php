<?php

	// message process actions
	// THIS IS DONE IN THE NAVIGATION INCLUDE FOR THE BENEFIT OF UPDATING THE INBOX COUNT

	// delcare the class if not done already by the previous include
	if(!$msgf){
		$msgf = new messages ;
		$msgf->setRecipients($_SESSION['user_id']) ;
	}

	// - - - - - - - - - - - - - - - - - - - -
	$link_base = '?action=messages' ;
	$page_size = 20 ; // default is 20 at current CSS height
	$page_offset = '0' ;
	if($_GET['page_offset']!=''&&is_numeric($_GET['page_offset'])&&$_GET['page_offset']>0){
		$page_offset = $_GET['page_offset'] ;
	}

	//if($checked_for_new_messages==true){
	//	echo '<p>Checked for new messages</p>' ;
	//}

	include('_system/inc/_screen_message_handler.php') ;


	if($_GET['subaction']=='folders'){
		include('_system/inc/_messages_folders.php') ;
	} else {
		include('_system/inc/_messages_inbox.php') ;
	}

?>
