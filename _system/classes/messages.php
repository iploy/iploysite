<?php
	include_once('_system/classes/email.php') ;

	class messages {

		private $recipients = false ; // potentially a string array
		private $authorId = false ;
		private $messageId = false ;
		private $userLevel = false ;
		private $messageSubject = false ;
		private $messageContent = false ;
		private $messagePriority = 0 ;
		private $folderId = 0 ;
		private $conversationIndex = 0 ;
		private $messageList = array() ;
		private $foldersList = array() ;
		private $list_custom_where = false ;
		private $pageSize = false ;
		private $pageOffset = false ;


		public function getNameById($userId,$doSessions = true){
			if(!$_SESSION['namesarray']&&$doSessions==true){
				$_SESSION['namesarray'] = array() ;
			}
			$returnvar = array() ;
			$returnvar['full_name'] = 'Unknown User' ;
			$returnvar['user_level'] = 'Unknown' ;
			// check session first
			if($_SESSION['namesarray'][$userId]==''||$_SESSION['userlevelarray'][$userId]==''){
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// do connection
				$sql = "SELECT login.user_level, graduates.first_name, graduates.surname, employers.company_name FROM login " ;
				$sql.= "LEFT JOIN graduates ON login.id = graduates.login_id " ;
				$sql.= "LEFT JOIN employers ON login.id = employers.user_id " ;
				$sql.= "WHERE login.id='".$userId."' " ;
				$sql.= "LIMIT 0,1 ; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				if(mysql_num_rows($result)>0){
					$row = mysql_fetch_array($result) ;
					$returnvar['user_level'] = $row['user_level'] ;
					if($row['user_level']==0){
						$returnvar['full_name'] = $_SESSION['APP_CLIENT_NAME'].' Developer' ;
					} elseif($row['user_level']==1){
						$returnvar['full_name'] = trim($row['first_name'].' '.$row['surname']) ;
					} elseif($row['user_level']==2){
						$returnvar['full_name'] = trim($row['company_name']) ;
					} elseif($row['user_level']==3){
						$returnvar['full_name'] = $_SESSION['APP_CLIENT_NAME'].' Administrator' ;
					}
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
				if($doSessions==true){
					$_SESSION['userarray'][$userId]['full_name'] = $returnvar['full_name'] ;
					$_SESSION['userarray'][$userId]['user_level'] = $returnvar['user_level'] ;
				}
			} else {
				$returnvar = $_SESSION['userarray'][$userId] ;
			}
			// return
			return $returnvar ;
		}

		public function deleteFolder($delete_action){
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = '<b>Fatal Error</b>: Delete action no recognised' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			//
			$sql = "SELECT COUNT(1) as message_count FROM message_status WHERE folder_id='".$this->folderId."' AND user_id='".$this->recipients."' ;" ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			if($delete_action=='empty'&&$row['message_count']==0){
				$sql = "DELETE FROM message_folders WHERE folder_id='".$this->folderId."' AND user_id='".$this->recipients."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				$returnvar['status'] = true ;
				$returnvar['statustxt'] = 'The selected folder has been successfully removed.' ;
			} elseif($delete_action=='empty'&&$row['message_count']>0){
				$returnvar['status'] = false ;
				$returnvar['statustxt'] = '<b>Fatal Error</b>: That folder is not empty' ;
			} else {
				if($row['message_count']>0){
					// build
					if($delete_action=='inbox'){
						$sql = "UPDATE message_status SET folder_id='0' WHERE folder_id='".$this->folderId."' AND user_id='".$this->recipients."' ; " ;
						mysql_query($sql) or die( mysql_error()) ;
						$sql = "DELETE FROM message_folders WHERE folder_id='".$this->folderId."' AND user_id='".$this->recipients."' ; " ;
						mysql_query($sql) or die( mysql_error()) ;
						$returnvar['status'] = true ;
						$returnvar['statustxt'] = 'The selected folder has been successfully removed and it\' messages move to your Inbox.' ;
					}elseif($delete_action=='remove'){
						$sql = "SELECT message_id FROM message_status WHERE folder_id='".$this->folderId."' AND user_id='".$this->recipients."' ; " ;
						$result = mysql_query($sql) or die( mysql_error()) ;
						while($row = mysql_fetch_array($result)){
							$this->deleteMessage($row['message_id']) ;
						}
						$returnvar['status'] = true ;
						$returnvar['statustxt'] = 'The selected folder has been successfully removed' ;
					}
				} else {
					$returnvar['status'] = false ;
					$returnvar['statustxt'] = '<b>Permission Error</b>: That folder does not belong to you' ;
				}
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnvar ;
		}

		public function deleteMessage($messageId,$is_author=false){
			// echo '<h3>DELETE: '.$messageId.'</h3>' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			/*
			// check if the logged in user is the author
			$is_author = false ;
			$sql = "SELECT author_id FROM messages_main WHERE message_id='".mysql_escape_string($messageId)."' ; " ;
			echo '<p class="notice" >'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			if($row['author_id']==$this->recipients){
				$is_author = true ;
			}
			*/
			// check if there are any other recipients
			$multiple_unread = false ;
			$sql = "SELECT COUNT(1) as mtu_count FROM messages_to_users WHERE message_id='".mysql_escape_string($messageId)."' AND deleted_by_recipient='0' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			if($row['mtu_count']>1){
				// echo '<p>Has multiple recpients</p>' ;
				$multiple_unread = true ;
			};
			// now the actions differ for authors
			$delete_all = false ;
			if($is_author==false){
				// THIS IS A RECIPIENT
				// check if the author has deleted it
				$deleted_by_author = false ;
				$sql = "SELECT deleted_by_author FROM messages_main WHERE message_id='".mysql_escape_string($messageId)."' ; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				// if deleted by the author and does not have multiple recipients, select the complete delete option.
				if($row['deleted_by_author']==1&&$multiple_unread==false){
					// echo '<p>deleted by author</p>' ;
					$delete_all = true ;
				} else {
					// otherwise just mark it deleted by the user for now
					$sql = "UPDATE messages_to_users SET deleted_by_recipient='1' WHERE user_id='".$this->recipients."' AND message_id='".mysql_escape_string($messageId)."' ; " ;
					mysql_query($sql) or die( mysql_error()) ;
					// echo '<p class="notice" >'.$sql.'</p>' ;
					// Always delete the message status for none authors
					$sql = "DELETE FROM message_status WHERE user_id='".$this->recipients."' AND message_id='".mysql_escape_string($messageId)."' ; " ;
					mysql_query($sql) or die( mysql_error()) ;
					// echo '<p class="notice" >'.$sql.'</p>' ;
				}
			} else {
				// THIS IS AN AUTHOR
				// check if it was deleted by all recipient(s)
				$sql = "SELECT COUNT(1) as mtu_count FROM messages_to_users WHERE message_id='".mysql_escape_string($messageId)."' AND deleted_by_recipient='0' ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				if($row['mtu_count']>0){ 
					// there are other recipients with this message, flag as deleted by the author
					$sql = "UPDATE messages_main SET deleted_by_author='1' WHERE message_id='".mysql_escape_string($messageId)."' ;" ;
					mysql_query($sql) or die( mysql_error()) ;
					// echo '<p class="notice" >'.$sql.'</p>' ;
				} else {
					// delete all
					$delete_all = true ;
				}
			}
			if($delete_all==true){
				// delete the content
				$sql = "DELETE FROM messages_content WHERE message_id='".mysql_escape_string($messageId)."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				// message main
				$sql = "DELETE FROM messages_main WHERE message_id='".mysql_escape_string($messageId)."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				// message to user records 
				$sql = "DELETE FROM messages_to_users WHERE message_id='".mysql_escape_string($messageId)."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				// message reports
				$sql = "DELETE FROM message_report WHERE message_id='".mysql_escape_string($messageId)."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				// delete all message statuses
				$sql = "DELETE FROM message_status WHERE message_id='".mysql_escape_string($messageId)."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
		}


		public function deleteMessagesByArray($msgArray,$folderType='generic'){
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = '<b>Fatal Error</b>: Failed due to lack of variables' ;
			if(!is_array($msgArray)){
				$returnvar['status'] = false ;
				$returnvar['statustxt'] = '<b>Fatal Error</b>: Invalid message identifier' ;
			} else {
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// loop thru array checking or ownership first if none admin
				$permissionErrors = false ;
					foreach($msgArray as $messageId){
						if($folderType=='sent'){
							$sql = "SELECT COUNT(1) as message_count FROM messages_main WHERE message_id='".$messageId."'" ;
							if($_SESSION['user_level']!=0&&$_SESSION['user_level']!=3){
								$sql.= " AND author_id='".$this->recipients."' " ;
							}
							$sql.= " ;" ;
							$is_author = true ;
						} else {
							$sql = "SELECT COUNT(1) as message_count FROM messages_to_users WHERE message_id='".$messageId."'" ;
							if($_SESSION['user_level']!=0&&$_SESSION['user_level']!=3){
								$sql.= " AND user_id='".$this->recipients."'" ;
							}
							$sql.= " ;" ;
							$is_author = false ;
						}
						// echo '<p class="notice" >'.$sql.'</p>' ;
						$result = mysql_query($sql) or die( mysql_error()) ;
						$row = mysql_fetch_array($result) ;
						if($row['message_count']!=1){
							$permissionErrors = true ;
						}
					}
				// if the user owns the messages, allow deletion
				if($permissionErrors==false){
					foreach($msgArray as $messageId){
						$this->deleteMessage($messageId,$is_author) ;
						// echo 'delete '.$messageId.'<br />' ;
					}
					$returnvar['status'] = true ;
					$returnvar['statustxt'] = '<b>Messages Deleted Successfully</b>: The '.sizeof($msgArray).' message'.(sizeof($msgArray)==1 ? '' : 's').' you selected have been removed' ;
				} else {
					$returnvar['status'] = false ;
					$returnvar['statustxt'] = '<b>Permission Error</b>: You can only delete mesages that exist in your message folders, sent items or inbox' ;
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
				return $returnvar ;
			}
		}


		public function messagesInUserFolderCount(){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			//sql
			$sql = "SELECT COUNT(1) as message_count FROM message_status WHERE folder_id='".$this->folderId."' AND user_id='".$this->recipients."' ;" ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
			return $row['message_count'] ;
		}


		public function editFolder($folderName){
			$folderName = ucwords(strtolower($folderName)) ;
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = 'Form action not processed due to lack of variables' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			//sql
			$sql = "SELECT COUNT(1) as folder_count FROM message_folders WHERE folder_id='".$this->folderId."' AND user_id='".$this->recipients."' ;" ;
			// echo '<p class="notice" >'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			if($row['folder_count']<1){
				$returnvar['status'] = false ;
				$returnvar['statustxt'] = '<b>Permission Error</b>: That folder does not belong to you' ;
			} else {
				//sql
				$sql = "SELECT COUNT(1) as folder_count FROM message_folders WHERE folder_name='".mysql_escape_string($folderName)."' AND user_id='".$this->recipients."' AND folder_id<>'".$this->folderId."' ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				if($row['folder_count']>0){
					$returnvar['status'] = false ;
					$returnvar['statustxt'] = 'A folder with that name already exists, please try again' ;
				} else {
					$sql = "UPDATE message_folders SET folder_name='".mysql_escape_string($folderName)."' WHERE folder_id='".$this->folderId."' ;" ;
					mysql_query($sql) or die( mysql_error()) ;
					$returnvar['status'] = true ;
					$returnvar['statustxt'] = 'The requested folder was renamed successfully' ;
				}
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnvar ;
		}


		public function addFolder($folderName){
			$folderName = ucwords(strtolower($folderName)) ;
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = 'Form action not processed due to lack of variables' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			//sql
			$sql = "SELECT COUNT(1) as folder_count FROM message_folders WHERE folder_name='".mysql_escape_string($folderName)."' AND user_id='".$this->recipients."' ;" ;
			// echo '<p class="notice" >'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			if($row['folder_count']>0){
				$returnvar['status'] = false ;
				$returnvar['statustxt'] = 'A folder with that name already exists, please try again' ;
			} else {
				$sql = "INSERT INTO message_folders(folder_name,user_id) VALUES('".mysql_escape_string($folderName)."','".$this->recipients."') ;" ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				mysql_query($sql) or die( mysql_error()) ;
				$returnvar['status'] = true ;
				$returnvar['statustxt'] = 'The folder \''.$folderName.'\' has been added successfully' ;
				unset($_SESSION['folders_list']) ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnvar ;
		}


		// function for moving folders and such
		public function markMessages($msgAction,$msgIdArray){
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = 'Form action not processed due to lack of variables' ;
			if(is_array($msgIdArray)){
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				if(is_numeric($msgAction)&&$msgAction>-1){
					$allow_mark = false ;
					if($msgAction>0){
						// check if the folder exists
						$sql = "SELECT folder_name FROM message_folders WHERE user_id='".mysql_escape_string($this->recipients)."' AND folder_id='".mysql_escape_string($msgAction)."' ; " ;
						$result = mysql_query($sql) or die( mysql_error()) ;
						if(mysql_num_rows($result)>0){
							$row = mysql_fetch_array($result) ;
							$allow_mark = true ;
							$folder_name = "'".$row['folder_name']."'" ;
						}
					} else {
						$allow_mark = true ;
						$folder_name = 'your inbox' ;
					}
					if($allow_mark==true){
						foreach($msgIdArray as $msgId){
							// now we know this folder belongs to the user try to move the messages
							$sql = "SELECT COUNT(1) as status_count FROM message_status WHERE user_id='".mysql_escape_string($this->recipients)."' AND message_id='".mysql_escape_string($msgId)."' ; " ;
							$result = mysql_query($sql) or die( mysql_error()) ;
							$row = mysql_fetch_array($result) ;
							if($row['status_count']>0){
								// update
								$sql = "UPDATE message_status SET folder_id='".mysql_escape_string($msgAction)."' WHERE user_id='".mysql_escape_string($this->recipients)."' AND message_id='".mysql_escape_string($msgId)."' ; " ;
								$result = mysql_query($sql) or die( mysql_error()) ;
							} else {
								// insert
								$sql = "INSERT INTO message_status(folder_id,is_read,read_date,message_id,user_id) VALUES('".mysql_escape_string($msgAction)."','0',NULL,'".mysql_escape_string($msgId)."','".mysql_escape_string($this->recipients)."') ; " ;
								$result = mysql_query($sql) or die( mysql_error()) ;
							}
							$returnvar['status'] = true ;
							$returnvar['statustxt'] = sizeof($msgIdArray).' message'.((sizeof($msgIdArray)==1) ? ' was' : 's were').' moved to '.$folder_name ;
							unset($_SESSION['unread_count_'.$msgAction]) ;
						}
					} else {
						$returnvar['status'] = false ;
						$returnvar['statustxt'] = 'That folder does not belog to you. Possible hacking attempt.' ;
					}
				} elseif($msgAction=='mark_read'||$msgAction=='mark_unread'){
					if($msgAction=='mark_read'){
						$mark_value = 1 ;
						$mark_text = 'read' ;
					} else {
						$mark_value = 0 ;
						$mark_text = 'unread' ;
					}
					foreach($msgIdArray as $msgId){
						$sql = "SELECT folder_id, is_read FROM message_status WHERE user_id='".mysql_escape_string($this->recipients)."' AND message_id='".mysql_escape_string($msgId)."' ; " ;
						$result = mysql_query($sql) or die( mysql_error()) ;
						if(mysql_num_rows($result)>0){
							// update
							$row = mysql_fetch_array($result) ;
							// no need to update if already set as request
							if($row['is_read']!=$mark_value){
								unset($_SESSION['unread_count_'.$row['folder_id']]) ;
								$sql = "UPDATE message_status SET is_read='".$mark_value."' WHERE user_id='".mysql_escape_string($this->recipients)."' AND message_id='".mysql_escape_string($msgId)."' ; " ;
								$result = mysql_query($sql) or die( mysql_error()) ;
							}
						} else {
							// insert
							// no need make new records for mark as undread if they don't already exist
							if($mark_value!=0){
								// if it doesnt exisst, it's obviously in the inbox
								$sql = "INSERT INTO message_status(folder_id,is_read,read_date,message_id,user_id) VALUES('0','1',NULL,'".mysql_escape_string($msgId)."','".mysql_escape_string($this->recipients)."') ; " ;
								$result = mysql_query($sql) or die( mysql_error()) ;
								unset($_SESSION['unread_count_0']) ;
							}
						}
						$returnvar['status'] = true ;
						$returnvar['statustxt'] = sizeof($msgIdArray).' message'.((sizeof($msgIdArray)==1) ? ' was' : 's were').' marked as '.$mark_text ;
					}
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			} else {
				$returnvar['status'] = false ;
				$returnvar['statustxt'] = 'Message list is not an array.  Possible hacking attempt.' ;
			}
			return $returnvar ;
		}


		// get available folders list
		public function getFolderInfo() {
			$returnval = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// see if it exists
			$sql = "SELECT folder_id, folder_name FROM message_folders WHERE user_id='".$this->recipients."' AND folder_id='".$this->folderId."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)>0){
				$returnval = mysql_fetch_array($result) ;
			}
			$conn->connect(DM_DB_NAME) ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
			return $returnval ;
		}


		// get available folders list
		public function getFoldersList($isCount=false) {
			$returnval = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// see if it exists
			$sql = "SELECT " ;
			if($isCount==true){
				$sql.= "COUNT(1) as folder_count " ;
			} else {
				$sql.= "folder_id, folder_name " ;
			}
			$sql.= "FROM message_folders WHERE user_id='".$this->recipients."' " ;
			if($isCount!=true){
				$sql.= "ORDER BY folder_name ASC " ;
			}
			$sql.= ";" ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if($isCount==true){
				$row = mysql_fetch_array($result) ;
				$returnval = $row['folder_count'] ;
			} else {
				$this->foldersList = array() ;
				while($row = mysql_fetch_array($result)){
					array_push($this->foldersList, $row);
				}
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			if($isCount==true){
				return $returnval ;
			} else {
				return $this->foldersList ;
			}
		}


		public function markAsRead(){
			$returnval = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// see if it exists
			$sql = "SELECT ms_id FROM message_status WHERE message_id='".$this->messageId."' AND user_id='".$this->recipients."' LIMIT 0,1 ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)==0){
				// if not, insert
				$sql = "INSERT INTO message_status(folder_id,is_read,read_date,message_id,user_id) VALUES('0','1','".date(DM_PHP_DATE_FORMAT)."','".$this->messageId."','".$this->recipients."') ; " ;
			} else {
				// if so, update
				$sql = "UPDATE message_status SET is_read='1' WHERE message_id='".$this->messageId."' AND user_id='".$this->recipients."' ; " ;
			}			
			// echo '<p class="notice" >'.$sql.'</p>' ;
			if(mysql_query($sql)){
				$returnval = true ;
			} else {
				$returnval = 'SQL Error: '.mysql_error() ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			return $returnval ;
		}


		public function getMessageById($messageId,$viewerId,$getConversation=false,$author_id=0,$getConversationOrder='DESC'){
			$returnval = array() ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			$sql = "SELECT ";
			$sql.= "messages_main.message_id, messages_main.subject, messages_main.priority, messages_main.sent_date, messages_main.author_id, messages_main.conversation_index, " ;
			$sql.= "login.user_level, " ;
			$sql.= "graduates.first_name, graduates.surname, " ;
			$sql.= "employers.company_name, " ;
			$sql.= "messages_to_users.user_id, " ;
			$sql.= "message_status.folder_id, message_status.is_read, message_status.read_date, " ;
			$sql.= "messages_content.message_content " ;
			$sql.= "FROM messages_main " ;
			$sql.= "LEFT JOIN messages_to_users ON messages_main.message_id = messages_to_users.message_id " ;
			$sql.= "LEFT JOIN login ON messages_to_users.user_id = login.id " ;
			$sql.= "LEFT JOIN graduates ON messages_to_users.user_id = graduates.login_id " ;
			$sql.= "LEFT JOIN employers ON messages_to_users.user_id = employers.user_id " ;
			$sql.= "LEFT JOIN message_status ON (messages_to_users.message_id = message_status.message_id AND messages_to_users.user_id = message_status.user_id) " ;
			$sql.= "LEFT JOIN messages_content ON messages_main.message_id = messages_content.message_id " ;
			$sql.= "WHERE (messages_main.author_id = '".mysql_escape_string($viewerId)."' OR messages_to_users.user_id = '".mysql_escape_string($viewerId)."') AND (messages_main.message_id = '".mysql_escape_string($messageId)."'" ;
			// add conversation index for conversation
			if($getConversation==true&&$this->conversationIndex>1){
			// messages_main.author_id = '".mysql_escape_string($author_id)."' OR messages_to_users.user_id = '".mysql_escape_string($author_id)."'
				$sql.= " OR messages_main.message_id = '".mysql_escape_string($this->conversationIndex)."' OR messages_main.conversation_index = '".mysql_escape_string($this->conversationIndex)."' AND ((messages_main.author_id = '".mysql_escape_string($author_id)."' AND messages_to_users.user_id = '".mysql_escape_string($viewerId)."') OR (messages_main.author_id = '".mysql_escape_string($viewerId)."'  AND messages_to_users.user_id = '".mysql_escape_string($author_id)."')) " ;
			}
			$sql.= ") GROUP BY messages_main.message_id " ;
			// order for conversation
			if($getConversation==true){
				if(strtolower($getConversationOrder)=='asc'){
					$getConversationOrder = 'ASC' ;
				}
				$sql.= "ORDER BY messages_main.sent_date ".$getConversationOrder." " ;
			} else {
				$sql.= "LIMIT 0,1 " ;
			}
			$sql.= "; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)>0){
				// prepare list for conversations
				if($getConversation==true){
					$this->messageList = array() ;
					while($row = mysql_fetch_array($result)){
						array_push($this->messageList, $row);
					}
				} else {
					// return 1 row for non=conversation
					$returnval = mysql_fetch_array($result) ;
					if($_SESSION['user_id']==$viewerId){
						$this->setMessageId($messageId) ;
						$this->setRecipients($viewerId) ;
						$this->markAsRead() ;
						unset($_SESSION['unread_count_'.$returnval['folder_id']]) ;
					}
				}
			}
			// echo '<p class="notice" >'.$sql.'</p>' ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
			if($getConversation==true&&sizeof($this->messageList)>0){
				return $this->messageList ;
			} else {
				return $returnval ;
			}
		}


		public function getMessageList($isCount=false,$isType=false){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT " ;
			if($isCount==true){
				$sql.= "COUNT(1) as message_count " ;
				if($isType==false){
					$sql.= "FROM messages_to_users " ;
					$sql.= "WHERE messages_to_users.user_id='".mysql_escape_string($this->recipients)."' " ;
				} else {
					$sql.= "FROM messages_to_users " ;
					$sql.= "LEFT JOIN message_status ON (messages_to_users.message_id = message_status.message_id AND messages_to_users.user_id = message_status.user_id) " ;
					$sql.= "LEFT JOIN messages_main ON messages_to_users.message_id = messages_main.message_id " ;
					if($isType=='sent'){
						$sql.= "WHERE (messages_main.author_id='".mysql_escape_string($this->recipients)."' AND messages_main.deleted_by_author='0') " ;
					} else {
						$sql.= "WHERE (messages_to_users.user_id='".mysql_escape_string($this->recipients)."' AND messages_to_users.deleted_by_recipient='0') " ;
						if($isType=='unread'){
							$sql.= " AND (message_status.is_read IS NULL OR message_status.is_read=0) " ;
						}
						if($this->folderId>0){
							$sql.= "AND message_status.folder_id='".mysql_escape_string($this->folderId)."' " ;
						} else {
							$sql.= "AND (message_status.folder_id IS NULL OR message_status.folder_id='".mysql_escape_string($this->folderId)."') " ;
						}
					}
				}
				// echo '<p class="notice" >'.$sql.'</p>' ;
			} else {
				// fields
				$sql.= "messages_to_users.user_id, messages_to_users.message_id, messages_to_users.deleted_by_recipient, " ;
				$sql.= "messages_main.subject, messages_main.priority, messages_main.sent_date, messages_main.author_id, messages_main.author_user_level, messages_main.deleted_by_author, " ;
				$sql.= "message_status.is_read, message_status.read_date, " ;
				$sql.= "login.user_level, " ; // THIS
				$sql.= "graduates.first_name, graduates.surname, " ; // THIS
				$sql.= "employers.company_name " ; // THIS
				// from table... 
				$sql.= "FROM messages_to_users " ;
				// joins = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
				$sql.= "LEFT JOIN message_status ON (messages_to_users.message_id = message_status.message_id AND messages_to_users.user_id = message_status.user_id) " ;
				$sql.= "LEFT JOIN messages_main ON messages_to_users.message_id = messages_main.message_id " ;
				// Get the sender / recipient info dependednt on the folder type
				if($isType=='sent'){
					$sql.= "LEFT JOIN login ON messages_to_users.user_id = login.id " ;
					$sql.= "LEFT JOIN graduates ON messages_to_users.user_id = graduates.login_id " ;
					$sql.= "LEFT JOIN employers ON messages_to_users.user_id = employers.user_id " ;
				} else {
					$sql.= "LEFT JOIN login ON messages_main.author_id = login.id " ;
					$sql.= "LEFT JOIN graduates ON messages_main.author_id = graduates.login_id " ;
					$sql.= "LEFT JOIN employers ON messages_main.author_id = employers.user_id " ;
				}
				// where clause
				if($isType=='sent'){
					$sql.= "WHERE (messages_main.author_id='".mysql_escape_string($this->recipients)."' AND messages_main.deleted_by_author='0') " ;
				} else {
					$sql.= "WHERE (messages_to_users.user_id='".mysql_escape_string($this->recipients)."' AND messages_to_users.deleted_by_recipient='0') " ;
				}
				if($isType!='sent'){
					if($this->folderId>0){
						$sql.= "AND message_status.folder_id='".mysql_escape_string($this->folderId)."' " ;
					} else {
						$sql.= "AND (message_status.folder_id IS NULL OR message_status.folder_id='".mysql_escape_string($this->folderId)."') " ;
					}
				}
				// order
				$sql.= "ORDER BY messages_main.sent_date DESC " ;
				// page
				if((is_numeric($this->pageSize)&&$this->pageSize>0)&&(is_numeric($this->pageOffset)&&$this->pageOffset>=0)){
					$sql.= "LIMIT ".mysql_escape_string(($this->pageSize*$this->pageOffset)).','.mysql_escape_string($this->pageSize) ;
				}
			}
			// custom where
			if($this->list_custom_where!=false){
				$sql.= "AND (".$this->list_custom_where.") " ;
			}
			$sql.= ";" ;
			// echo '<p class="notice" >'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			// make list
			if($isCount==true){
				// close
				$row = mysql_fetch_array($result) ;
				$conn->disconnect(DM_DB_NAME) ;
				return $row['message_count'] ;
			} else {
				while($row = mysql_fetch_array($result)){
					array_push($this->messageList, $row);
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
				return $this->messageList ;
			}
		}


		public function getRecipientsString(){
			$returnstr = array() ;
			if($this->recipients==false||!is_array($this->recipients)){
				$returnstr['status'] = 'recipienterror' ;
				$returnstr['statustxt'] = 'Recipients not in array format' ;
			} else {
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// sql
				$sql = "SELECT login.id, login.user_level, employers.company_name, graduates.first_name, graduates.surname, employers.company_name " ;
				$sql.= "FROM login " ;
				$sql.= "LEFT JOIN employers ON login.id = employers.user_id " ;
				$sql.= "LEFT JOIN graduates ON login.id = graduates.login_id " ;
				$sql.= "WHERE " ;
				for($i=0;$i<sizeof($this->recipients);$i++){
					if($i>0){
						$sql.= "OR " ;
					}
					$sql.= "login.id='".mysql_escape_string($this->recipients[$i])."' " ;
				}
				$sql.= "LIMIT 0,3 ; " ;
				// echo '<p>'.$sql.'</p>' ;
				$returnstr['statustxt'] = '' ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$keepcounter = 1 ;
				while($row = mysql_fetch_array($result)){
					if($keepcounter>1){// sizeof($this->recipients)
						if($keepcounter!=mysql_num_rows($result)||sizeof($this->recipients)>mysql_num_rows($result)){
							$returnstr['statustxt'].= ', ' ;
						} else {
							$returnstr['statustxt'].= ' and ' ;
						}
					}
					if($row['user_level']==0){
						$returnstr['statustxt'].= $_SESSION['APP_CLIENT_NAME'].' Developer' ;
					} elseif($row['user_level']==3){
						$returnstr['statustxt'].= $_SESSION['APP_CLIENT_NAME'].' Administrator' ;
					} elseif($row['user_level']==2){
						if($row['company_name']!=''){
							$returnstr['statustxt'].= $row['company_name'] ;
						} else {
							$returnstr['statustxt'].= 'Employer ID '.$row['id'] ;
						}
					} elseif($row['user_level']==1){
						if($row['first_name']!=''||$row['surname']!=''){
							$returnstr['statustxt'].= trim($row['first_name'].' '.$row['surname']) ;
						} else {
							$returnstr['statustxt'].= 'Graduate ID '.$row['id'] ;
						}
					}
					$keepcounter++ ;
				}
				if(sizeof($this->recipients)>mysql_num_rows($result)){
					$returnstr['statustxt'].= ' and '.(sizeof($this->recipients)-mysql_num_rows($result)).' other recipient'.((sizeof($this->recipients)-mysql_num_rows($result))>1 ? 's' : '') ;
				}
				/*
				echo '<p>Num Rows: '.mysql_num_rows($result).'</p>' ;
				echo '<p>User Level: '.$row['user_level'].'</p>' ;
				echo '<p>Company Name: '.$row['company_name'].'</p>' ;
				echo '<p>Real Name: '.$row['first_name'].' '.$row['surname'].'</p>' ;
				*/
				// set the returnstr
				if(mysql_num_rows($result)>0){
					$returnstr['status'] = true ;
				} else {
					$returnstr['status'] = 'nousers' ;
					$returnstr['statustxt'] = 'No users could be found' ;
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $returnstr ;
		}


		public function sendMessage(){
			$returnstr = array() ;
			if($this->recipients===false||!is_array($this->recipients)){
				$returnstr['status'] = 'norecipient' ;
				$returnstr['statustxt'] = 'No recipient ID was specified' ;
			} elseif($this->authorId===false){
				$returnstr['status'] = 'noauthor' ;
				$returnstr['statustxt'] = 'No author ID was specified' ;
			} elseif($this->userLevel===false){
				$returnstr['status'] = 'noauserlevel' ;
				$returnstr['statustxt'] = 'No user level was specified fo the author' ;
			} elseif($this->messageSubject===false){
				$returnstr['status'] = 'nosubject' ;
				$returnstr['statustxt'] = 'No subject was specified' ;
			} elseif($this->messageContent===false){
				$returnstr['status'] = 'nocontent' ;
				$returnstr['statustxt'] = 'No message content was specified' ;
			} else {
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// Create the message main
				$sql = "INSERT INTO messages_main(subject, priority, author_id, author_user_level, sent_date, conversation_index) VALUES('".mysql_escape_string($this->messageSubject)."', '".mysql_escape_string($this->messagePriority)."', '".mysql_escape_string($this->authorId)."', '".mysql_escape_string($this->userLevel)."', '".mysql_escape_string(DATE_TIME_NOW)."', '".mysql_escape_string($this->conversationIndex)."') ;" ;
				// echo '<h4>messages_main</h4><p>'.$sql.'</p>' ;
				if(!mysql_query($sql)){
					$returnstr['status'] = 'sqlerror_main' ;
					$returnstr['statustxt'] = mysql_error() ;
				} else {
					// store the row ID
					$this->setMessageId(mysql_insert_id()) ;
					// Create the message content
					$sql = "INSERT INTO messages_content(message_id, message_content) VALUES('".mysql_escape_string($this->messageId)."', '".mysql_escape_string($this->messageContent)."') ; " ;
					// echo '<h4>messages_content</h4><p>'.$sql.'</p>' ;
					if(!mysql_query($sql)){
						$returnstr['status'] = 'sqlerror_content' ;
						$returnstr['statustxt'] = mysql_error() ;
					} else {
						// loop through recipients here
						foreach($this->recipients as $recipeint){
							$sql = "INSERT INTO messages_to_users(user_id, message_id) VALUES('".mysql_escape_string($recipeint)."', '".mysql_escape_string($this->messageId)."') ;" ;
							// echo '<h4>messages_to_users</h4><p>'.$sql.'</p>' ;
							if(!mysql_query($sql)){
								$returnstr['status'] = 'sqlerror_user_'.$recipeint ;
								$returnstr['statustxt'] = mysql_error() ;
							} else {
								$this->messageNotification($recipeint,$this->authorId,$this->messageId,$this->messageSubject) ;
							}
						}
						// echo '<p>Returnstr Size: '.sizeof($returnstr).'</p>' ;
						if(sizeof($returnstr)<1){
							$returnstr['status'] = true ;
							$returnstr['statustxt'] = 'Message Sent' ;
						}
					}
				}
				// Close
				$conn->disconnect(DM_DB_NAME) ;
			}
			// return
			return $returnstr ;
		}


		public function messageNotification($to,$from,$messageId,$messageSubject){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT email FROM login WHERE id='".$to."' LIMIT 0,1 ;" ;
			$result = mysql_query($sql) ;
			if(mysql_num_rows($result)>0){
				/// set recipient variables
				$recipientRow = mysql_fetch_array($result) ;
				$recipientEmail = $recipientRow['email'] ; 
				// procede to get the sender details // first find out the type of user
				$sql = "SELECT user_level FROM login WHERE id='".$from."' LIMIT 0,1 ;" ;
				$result = mysql_query($sql) ;
				if(mysql_num_rows($result)>0){
					$userLevelRow = mysql_fetch_array($result) ;
					$userLevel = $userLevelRow['user_level'] ;
					if($userLevel===0){
						$senderName = $_SESSION['APP_CLIENT_NAME'].' Developer' ;
					} elseif($userLevel==2){
						//employer - get info
						$sql = "SELECT company_name FROM employers WHERE user_id='".$from."' LIMIT 0,1 ;" ;
						$result = mysql_query($sql) ;
						if(mysql_num_rows($result)>0){
							$senderNameRow = mysql_fetch_array($result) ;
							$senderName = trim($senderNameRow['company_name']) ;
						} else {
							$senderName = $_SESSION['APP_CLIENT_NAME'].' Employer - ID:'.$from ;
						}
					} elseif($userLevel==3){
						$senderName = $_SESSION['APP_CLIENT_NAME'].' Administrator' ;
					} elseif($userLevel==1){
						//graduate - get info
						$sql = "SELECT first_name, surname FROM graduates WHERE login_id='".$from."' LIMIT 0,1 ;" ;
						$result = mysql_query($sql) ;
						if(mysql_num_rows($result)>0){
							$senderNameRow = mysql_fetch_array($result) ;
							$senderName = trim($senderNameRow['first_name'].' '.$senderNameRow['surname']) ;
						} else {
							$senderName = $_SESSION['APP_CLIENT_NAME'].' Graduate - ID:'.$from ;
						}
					}
				}
				// compose the mail and send it
				$msg ='<h1>'.$_SESSION['APP_CLIENT_NAME'].' - New Message Recieved</h1>'."\n";
				$msg.='<p>You were sent a new message via the '.$_SESSION['APP_CLIENT_NAME'].' website on '.date(DM_PHP_SCREENSHORTDATETIME_FORMAT).'.<br /><br />'."\n" ;
				$msg.='<b>From</b>: '.$senderName.'.<br /><br />'."\n" ;
				$msg.='<b>Subject</b>: '.$messageSubject.'.<br /><br />'."\n" ;
				$msg.='<a href="'.SITE_DOMAIN.'login.php?linkstr='.md5(date(DM_PHP_DATE_FORMAT)).'" >Click here and login to '.$_SESSION['APP_CLIENT_NAME'].' and select your inbox to view this message</a></p>'."\n" ;
				$email = new email ;
				$email->setSubject($_SESSION['APP_CLIENT_NAME'].' - New message from '.$senderName) ;
				$email->setFromName($_SESSION['APP_SITE_NAME']) ;
				$email->setContents($msg) ;
				$email->setToAddress($recipientEmail) ;
				$mailResult = $email->send() ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}



		public function getConversationRoot($messageid){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// check this message
			$sql = "SELECT conversation_index FROM messages_main WHERE message_id='".$messageid."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// echo '<p class="notice" >'.$sql.'</p>' ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			return $row['conversation_index'] ;
		}




		// GETTERS / SETTERS
		public function setRecipients($var){
			if((is_array($var)&&sizeof($var)>0)||(is_numeric($var)&&$var>=0)){
				$this->recipients = $var ;
			}
		}
		public function setAuthorId($var){
			if($var!=''&&is_numeric($var)){
				$this->authorId = $var ;
			}
		}
		public function setFolderId($var){
			if($var!=''&&is_numeric($var)){
				$this->folderId = $var ;
			}
		}
		public function setMessageId($var){
			if($var!=''&&is_numeric($var)){
				$this->messageId = $var ;
			}
		}
		public function setUserLevel($var){
			if($var!=''&&is_numeric($var)){
				$this->userLevel = $var ;
			}
		}
		public function setMessagePriority($var){
			if($var!=''&&is_numeric($var)){
				$this->messagePriority = $var ;
			}
		}
		public function setMessageSubject($var){
			if($var!=''){
				$this->messageSubject = $var ;
			}
		}
		public function setMessageContent($var){
			if($var!=''){
				$this->messageContent = $var ;
			}
		}
		public function setConversationIndex($var){
			if($var!=''&&is_numeric($var)&&$var>0){
				$this->conversationIndex = $var ;
			}
		}
		public function setPageSize($var){
			if($var!=''&&is_numeric($var)&&$var>0){
				$this->pageSize = $var ;
			}
		}
		public function setPageOffset($var){
			if($var!=''&&is_numeric($var)&&$var>=0){
				$this->pageOffset = $var ;
			}
		}

	}

?>