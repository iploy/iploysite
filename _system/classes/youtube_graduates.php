<?php

	class graduate_youtube {

		private $userId = 0 ;
		private $youtubeUserName = false ;
		private $dataList = array() ;
		private $videoId = false ;
		private $isEnabled = false ;

		public function addProfileVideo(){
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = 'An unspecified error occured' ;
			if($this->userId>0&&$this->youtubeUserName!=false&&$this->videoId!=false){
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				$sql = "SELECT COUNT(1) AS video_count FROM youtube_videos WHERE youtube_username='".$this->youtubeUserName."' AND user_id='".$this->userId."' AND video_id='".mysql_escape_string($this->videoId)."' ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				if($row['video_count']>0){
					$returnvar['status'] = false ;
					$returnvar['statustxt'] = 'The selected video already exists in your '.$_SESSION['APP_CLIENT_NAME'].' profile' ;
				} else {
					$sql = "SELECT list_order FROM youtube_videos WHERE youtube_username='".$this->youtubeUserName."' AND user_id='".$this->userId."' ORDER BY list_order DESC LIMIT 0,1 ;" ;
					$result = mysql_query($sql) or die( mysql_error()) ;
					$row = mysql_fetch_array($result) ;
					if($row['list_order']>0&&is_numeric($row['list_order'])){
						$list_order = $row['list_order'] + 1 ;
					} else {
						$list_order = 1 ;
					}
					$sql = "INSERT INTO youtube_videos(video_id,list_order,date_added,youtube_username,user_id,is_enabled) VALUES('".mysql_escape_string($this->videoId)."','".$list_order."','".date(DM_PHP_DATE_FORMAT)."','".mysql_escape_string($this->youtubeUserName)."','".$this->userId."','1') ;" ;
					if(mysql_query($sql)){
						$returnvar['status'] = true ;
						$returnvar['statustxt'] = 'The requested video was successfully added to your '.$_SESSION['APP_CLIENT_NAME'].' profile' ;
					} else {
						$returnvar['status'] = false ;
						$returnvar['statustxt'] = 'An error occured when updating the '.$_SESSION['APP_CLIENT_NAME'].' database. Please contact is for further assistance' ;
					}
				}
				// Close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $returnvar ;
		}

		public function removeProfileVideo(){
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = 'An unspecified error occured' ;
			if($this->userId>0&&$this->youtubeUserName!=false&&$this->videoId!=false){
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// sql
				$sql = "DELETE FROM youtube_videos WHERE youtube_username='".$this->youtubeUserName."' AND user_id='".$this->userId."' AND video_id='".mysql_escape_string($this->videoId)."' ; " ;
				if(mysql_query($sql)){
					$returnvar['status'] = true ;
					$returnvar['statustxt'] = 'The requested video was successfully removed from your '.$_SESSION['APP_CLIENT_NAME'].' profile' ;
				} else {
					$returnvar['status'] = false ;
					$returnvar['statustxt'] = 'An error occured when updating the '.$_SESSION['APP_CLIENT_NAME'].' database. Please contact is for further assistance' ;
				}
				// Close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $returnvar ;
		}

		public function getSelectedVideos(){
			$this->dataList = array() ;
			if($this->userId>0&&$this->youtubeUserName!=false){
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				$sql = "SELECT id, video_id, list_order FROM youtube_videos WHERE youtube_username='".$this->youtubeUserName."' AND user_id='".$this->userId."' " ;
				if(!$this->isEnabled===false){
					$sql.= "AND is_enabled='".$this->isEnabled."' " ;
				}
				$sql.= "ORDER BY list_order ASC ; " ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList,$row) ;
				}
				// Close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $this->dataList ;
		}

		public function updateYoutubeUserName($setSeession=true){
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = 'No User ID supplied' ;
			if($this->userId!=0){
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				$sql = "UPDATE graduates SET youtube_id='".$this->youtubeUserName."' WHERE login_id='".$this->userId."' ; " ;
				if(mysql_query($sql)){
					$returnvar['status'] = true ;
					if($this->youtubeUserName==''){
						include('_system/functions/delete_directory.php') ;
						delete_directory('userfiles/youtube_cache/'.$this->userId) ;
						$returnvar['statustxt'] = "Your Youtube account was successfully detatched from your ".$_SESSION['APP_CLIENT_NAME']." profile" ;
					} else {
						$returnvar['statustxt'] = "Youtube user ID successfully updated to '".$this->youtubeUserName."'" ;
					}
					// now remove all videos by the previous username
					$sql = "DELETE FROM youtube_videos WHERE user_id='".$this->userId."' ; " ;
					mysql_query($sql) or die( mysql_error()) ;
					// update the session variable
					if($setSeession==true){
						$_SESSION['youtube_id'] = $this->youtubeUserName ;
					}
				} else {
					// mysql_error()
					$returnvar['status'] = false ;
					$returnvar['statustxt'] = 'An unspecified database error occured. Please contact us for further assistance' ;
				}
				// Close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $returnvar ;
		}
		
		public function fixOrder($id,$newListOrder){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			$sql = "UPDATE youtube_videos SET list_order='".$newListOrder."' WHERE id='".$id."' ; " ;
			mysql_query($sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}

		public function changeOrder($newLocation,$direction){
			$returnvar = array() ;
			$returnvar['status'] = false ;
			$returnvar['statustxt'] = 'User ID or Video ID error' ;
			if($this->userId!=0&&$this->videoId!=false){
				if($direction=='up'){
					$newNew = $newLocation+1 ;
				} else {
					$newNew = $newLocation-1 ;
				}
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				$sql = "UPDATE youtube_videos SET list_order='".$newNew."' WHERE list_order='".$newLocation."' AND user_id='".$this->userId."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				//echo '<p>'.$sql.'</p>' ;
				$sql = "UPDATE youtube_videos SET list_order='".$newLocation."' WHERE video_id='".$this->videoId."' AND user_id='".$this->userId."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				//echo '<p>'.$sql.'</p>' ;
				$returnvar['status'] = true ;
				$returnvar['statustxt'] = 'The order of the selected video was successfully updated' ;
				// Close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $returnvar ;
		}


		// getters / setters
		public function setUserId($var){
			if($var!=''&&is_numeric($var)&&$var>0){
				$this->userId = $var ;
			}
		}
		public function setYoutbeUserName($var){
			$this->youtubeUserName = $var ;
		}
		public function setVideoId($var){
			$this->videoId = $var ;
		}
		public function setIsEnabled($var){
			if($var===1||$var===0){
				$this->isEnabled = $var ;
			}
		}

	}

?>
