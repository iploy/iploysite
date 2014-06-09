<?php

	include_once('_system/classes/messages.php') ;
	$mvf = new messages ;
	$mvf->setConversationIndex($conversationId) ;
	$msgIndexId = $_GET['messageid'] ;
	if($_GET['replyid']!=''||$_GET['followupid']!=''){
		if($_GET['replyid']!=''){
			$msgIndexId = $_GET['replyid'] ;
		} else {
			$msgIndexId = $_GET['followupid'] ;
		}
	}
	if($_SESSION['user_id']!=$message['author_id']){
		$authorId = $message['author_id'] ;
	} elseif($message['user_id']<>'') {
		$authorId = $message['user_id'] ;
	} else {
		$authorId = 'arse' ;
	}
	$converastion = $mvf->getMessageById($msgIndexId,$_SESSION['user_id'],true,$authorId) ; // set to tru to get full conversation
	$message_array = array() ;
	if($_GET['replyid']!=''||$_GET['followupid']!=''){
		if($_GET['replyid']!=''){
			$msgIndexId = $_GET['replyid'] ;
		} else {
			$msgIndexId = $_GET['followupid'] ;
		}
		$message_array['msg_subject'] = 'Message ID: '.$msgIndexId ;
		foreach($converastion as $message){
			if($message['message_id']==$msgIndexId){
				if($message['conversation_index']==0){
					$message_array['conversation_index'] = $message['message_id'] ;
				} else {
					$message_array['conversation_index'] = $message['conversation_index'] ;
				}
				if(substr($message['subject'],0,4)!='RE: '){
					$message_array['msg_subject'] = 'RE: '.$message['subject'] ;
				} else {
					$message_array['msg_subject'] = $message['subject'] ;
				}
				if($_SESSION['user_id']!=$message['author_id']){
					$message_array['profileid'] = $message['author_id'] ;
				} else {
					$message_array['profileid'] = $message['user_id'] ;
				}
				$mvf->setRecipients(explode(",",$message_array['profileid'])) ;
				$msg_recipient = $mvf->getRecipientsString() ;
				$msg_recipient = $msg_recipient['statustxt'] ;
			}
		}
	}

?>