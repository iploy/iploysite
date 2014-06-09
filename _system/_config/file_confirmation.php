<?php

	include_once('_system/functions/delete_directory.php') ;

	class fileConfirmation {

   		private $dataList = array();

		public function listUnconfirmedFiles($filetype){

			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Get any files waiting for confirmation
			$sql = "SELECT id, upload_type, file_path_full, user_id, date_added FROM unverified_files WHERE upload_type='".$filetype."' ORDER BY date_added ASC ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			while($row = mysql_fetch_array($result)){
            	array_push($this->dataList, $row);
				// echo $row['id'].' - '.$row['file_path_full'].' - '.$row['date_added']."<br />" ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			return $this->dataList ;

		}

		public function confirmFileByRequestId($request_id){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Get the request info
			$sql = "SELECT upload_type, user_id FROM unverified_files WHERE id='".$request_id."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// perform the DB update
			if($row['upload_type']!=''&&$row['user_id']!=''){
				// check graduate exists first to either create or update
				$sql = "SELECT COUNT(1) AS user_count FROM graduates WHERE login_id='".$row['user_id']."' ; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row2 = mysql_fetch_array($result) ;
				if($row2['user_count']>0){
					// update
					$sql = "UPDATE graduates SET has_".$row['upload_type']."='1' WHERE login_id='".$row['user_id']."' ;" ;
				} else {
					// insert
					$sql = "INSERT INTO graduates(login_id,has_".$row['upload_type'].") VALUES('".$row['user_id']."','1') ;" ;
				}
				mysql_query($sql) or die( mysql_error()) ;
				// update the user's affiliate progress
				include_once(SITE_PATH.'_system/classes/graduate_admin.php') ;
				$graduate_info = new graduate_info ;
				$graduate_info->updateAfilliateEligibility($row['user_id']) ;
				// remove the request info
				$sql = "DELETE FROM unverified_files WHERE id='".$request_id."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
		}

		public function deleteFileByRequestId($request_id){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Get the request info
			$sql = "SELECT upload_type, user_id FROM unverified_files WHERE id='".$request_id."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// delete the file
			if($row['upload_type']!=''&&$row['user_id']!=''){
				$this_path = 'userfiles/'.$row['upload_type'].'/'.$row['user_id'].'/' ;
				delete_directory($this_path) ;
			}
			// echo $this_path."<br />" ;
			// delete the request
			$sql = "DELETE FROM unverified_files WHERE id='".$request_id."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
		}

		public function countFilesAwaitingConfirmation($filetype){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Get the request info
			$sql = "SELECT COUNT(1) as list_count FROM unverified_files WHERE upload_type='".$filetype."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return the count
			return $row['list_count'] ;
		}

		public function countAllFiles($filetype){
			$filecount = 0 ;
			$filepath = 'userfiles/'.$filetype.'/' ;
			if(is_dir($filepath)){
				$this_dir = opendir($filepath) ;
				while(($file=readdir($this_dir)) != false){
					if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
						$filecount++ ;
					}
				}
				closedir($this_dir) ;
			}
			return $filecount ;
		}

	}

?>