<?php

	include_once('_system/classes/messages.php') ;

	// form processing

	if($_GET['action']=='messages'&&$_GET['subaction']=='msgaction'&&$_POST['msgaction']!=''){
		unset($_SESSION['unread_count_'.$folder_id]) ;
		if($_POST['msgaction']=='mark_read'||$_POST['msgaction']=='mark_unread'||(is_numeric($_POST['msgaction'])&&$_POST['msgaction']>-1)&&(is_array($_POST['msgidarray'])&&$_POST['msgidarray']!='')){
			if(!$msgf){
				$msgf = new messages ;
				$msgf->setRecipients($_SESSION['user_id']) ;
			}
			$msgUpdate = $msgf->markMessages($_POST['msgaction'],$_POST['msgidarray']) ;
			if($msgUpdate['status']==false){
				$screen_message = draw_icon(ICON_BAD).$msgUpdate['statustxt'] ;
				$screen_message_type = 'error' ;
			} else {
				$screen_message = draw_icon(ICON_GOOD).$msgUpdate['statustxt'] ;
				$screen_message_type = 'success' ;
			}
		}
		if($_POST['msgaction']=='delete'&&is_array($_POST['msgidarray'])&&$_POST['msgidarray']!=''&&$_POST['folder_type']!=''){
			if(!$msgf){
				$msgf = new messages ;
				$msgf->setRecipients($_SESSION['user_id']) ;
				$deleteResult = $msgf->deleteMessagesByArray($_POST['msgidarray'],$_POST['folder_type']) ;
				if($deleteResult['status']==false){
					$screen_message = draw_icon(ICON_BAD).$deleteResult['statustxt'] ;
					$screen_message_type = 'error' ;
				} else {
					$screen_message = draw_icon(ICON_GOOD).$deleteResult['statustxt'] ;
					$screen_message_type = 'success' ;
				}
			}
		}
	}


	// Add a folcer
	$min_name_length = 3 ;
	$max_name_length = 25 ;
	if($_GET['action']=='messages'&&($_GET['subaction']=='addfolder')||(($_GET['subaction']=='folders'&&$_GET['subsubaction']=='addfolder'))&&trim($_POST['foldername'])!=''){
		if(strlen(trim($_POST['foldername']))<$minimum_folder_name_length){
			// folder name too short
			$screen_message = draw_icon(ICON_BAD).'Folder names must be at least '.$min_name_length.' character'.(strlen(trim($_POST['foldername']))==1 ? '' : 's').' in length' ;
			$screen_message_type = 'error' ;
		}elseif(strlen(trim($_POST['foldername']))>$max_name_length){
			// folder name too long
			$screen_message = draw_icon(ICON_BAD).'Folder names must be under '.$max_name_length.' characters in length' ;
			$screen_message_type = 'error' ;
		} else {
			if(!$msgf){
				$msgf = new messages ;
				$msgf->setRecipients($_SESSION['user_id']) ;
			}
			$msgUpdate = $msgf->addFolder(trim($_POST['foldername'])) ;
			if($msgUpdate['status']==true){
				$screen_message = draw_icon(ICON_GOOD).$msgUpdate['statustxt'] ;
				$screen_message_type = 'success' ;
			} else {
				$screen_message = draw_icon(ICON_BAD).$msgUpdate['statustxt'] ;
				$screen_message_type = 'error' ;
			}
		}
	}
	
	// edit a folder
	if($_GET['action']=='messages'&&$_GET['subaction']=='folders'&&$_GET['subsubaction']=='rename'&&$_POST['foldername']!=''&&$_GET['folderid']!=''&&is_numeric($_GET['folderid'])&&$_GET['folderid']>0){
		if(strlen(trim($_POST['foldername']))<$minimum_folder_name_length){
			// folder name too short
			$screen_message = draw_icon(ICON_BAD).'Folder names must be at least '.$min_name_length.' character'.(strlen(trim($_POST['foldername']))==1 ? '' : 's').' in length' ;
			$screen_message_type = 'error' ;
		}elseif(strlen(trim($_POST['foldername']))>$max_name_length){
			// folder name too long
			$screen_message = draw_icon(ICON_BAD).'Folder names must be under '.$max_name_length.' characters in length' ;
			$screen_message_type = 'error' ;
		} else {
			if(!$msgf){
				$msgf = new messages ;
				$msgf->setRecipients($_SESSION['user_id']) ;
			}
			$msgf->setFolderId($_GET['folderid']) ;
			$msgUpdate = $msgf->editFolder(trim($_POST['foldername'])) ;
			if($msgUpdate['status']==true){
				$screen_message = draw_icon(ICON_GOOD).$msgUpdate['statustxt'] ;
				$screen_message_type = 'success' ;
				$folderUpdated = true ;
				$_SESSION['folders_list'] = false ;
			} else {
				$screen_message = draw_icon(ICON_BAD).$msgUpdate['statustxt'] ;
				$screen_message_type = 'error' ;
				$edit_name = trim($_POST['foldername']) ;
			}
		}
	}


	// remove a folder
	if($_GET['action']=='messages'&&$_GET['subaction']=='folders'&&$_GET['subsubaction']=='delete'&&$_GET['folderid']!=''&&is_numeric($_GET['folderid'])&&$_GET['folderid']>0){
		if(!$msgf){
			$msgf = new messages ;
			$msgf->setRecipients($_SESSION['user_id']) ;
		}
		$msgf->setFolderId($_GET['folderid']) ;
		$message_count = $msgf->messagesInUserFolderCount() ;
		// check if confirmation is required
		if($_GET['deleteaction']==''&&$message_count>0){
			$requires_confirmation = true ;
		} else {
			if($message_count==0){
				$delete_action = 'empty' ;
			} else {
				$delete_action = $_GET['deleteaction'] ;
			}
			$deleteFolder = $msgf->deleteFolder($delete_action) ;
			if($deleteFolder['status']==true){
				$screen_message = draw_icon(ICON_GOOD).$deleteFolder['statustxt'] ;
				$screen_message_type = 'success' ;
				$folderUpdated = true ;
				$_SESSION['folders_list'] = false ;
			} else {
				$screen_message = draw_icon(ICON_BAD).$deleteFolder['statustxt'] ;
				$screen_message_type = 'error' ;
				$edit_name = trim($_POST['foldername']) ;
			}
		}
	}
	



	// available folders list
	if($_SESSION['folders_list']==false){
		if(!$msgf){
			$msgf = new messages ;
			$msgf->setRecipients($_SESSION['user_id']) ;
		}
		$_SESSION['folders_list'] = $msgf->getFoldersList() ;
	}


	if($_GET['action']=='messages'&&$_GET['subaction']==''){
		unset($_SESSION['unread_count_0']) ;
	}

	// always refresh if ?action=messages and ?subaction= 
	if($_SESSION['inbox_message_last_count'] < date(DM_PHP_DATE_FORMAT,strtotime(date(DM_PHP_DATE_FORMAT).' -'.DM_CHECK_FOR_NEW_MESSAGES))){
		unset($_SESSION['unread_count_0']) ;
	}

	$folder_recount_required = false ;

	if($_GET['forcerecount']=='true'){
		unset($_SESSION['unread_count_0']) ;
		foreach($_SESSION['folders_list'] as $userFolder){
			unset($_SESSION['unread_count_'.$userFolder['folder_id']]) ;
		}
	}

	// forced inbox check
	if(!isset($_SESSION['unread_count_0'])){
		if(!$msgf){
			$msgf = new messages ;
			$msgf->setRecipients($_SESSION['user_id']) ;
		}
		$msgf->setFolderId('0') ;
		$_SESSION['unread_count_0'] = $msgf->getMessageList(true,'unread') ;
		$folder_recount_required = true ;
	}


	foreach($_SESSION['folders_list'] as $userFolder){
		if(!isset($_SESSION['unread_count_'.$userFolder['folder_id']])){
			if(!$msgf){
				$msgf = new messages ;
				$msgf->setRecipients($_SESSION['user_id']) ;
			}
			$msgf->setFolderId($userFolder['folder_id']) ;
			$_SESSION['unread_count_'.$userFolder['folder_id']] = $msgf->getMessageList(true,'unread') ;
			$folder_recount_required = true ;
		}
	}

	if($folder_recount_required==true){
		$_SESSION['unread_messages'] = $_SESSION['unread_count_0'] ;
		foreach($_SESSION['folders_list'] as $userFolder){
			$_SESSION['unread_messages'] = $_SESSION['unread_messages'] + $_SESSION['unread_count_'.$userFolder['folder_id']] ;
		}
		$_SESSION['inbox_message_last_count'] = date(DM_PHP_DATE_FORMAT) ;
	}




	// get folder info if this is the messages screen
	if($_GET['action']=='messages'){
		if($_SESSION['unread_count_0']>0){
			$inbox_icon = 'inbox.png' ;
		} else {
			$inbox_icon = 'inbox_empty.png' ;
		}
		$folder_id = false ;
		if($_GET['folderid']!=''&&is_numeric($_GET['folderid'])&&$_GET['folderid']>0){
			if(!$msgf){
				$msgf = new messages ;
				$msgf->setRecipients($_SESSION['user_id']) ;
			}
			$msgf->setFolderId($_GET['folderid']) ;
			$folder_info = $msgf->getFolderInfo() ;
			if($folder_info!=false){
				$folder_id = $folder_info['folder_id'] ;
				$folder_name = $folder_info['folder_name'] ;
				$folder_type = 'generic' ;
				$folder_icon = 'inbox_folder.png' ;
			}
		} elseif($_GET['folderid']=='sent'){
			$folder_id = 0 ;
			$folder_type = 'sent' ;
			$folder_name = 'Sent Items' ;
			$folder_icon = 'inbox_out.png' ;
		}
		// use the inbox if get folder fails.
		if($folder_id===false){
			$folder_id = '0' ;
			$folder_type = 'inbox' ;
			$folder_name = 'Inbox' ;
			$folder_icon = $inbox_icon ;
		}
	}

?>