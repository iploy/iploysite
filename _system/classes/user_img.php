<?php

	include_once('_system/classes/transactions.php') ;
	include_once('_system/classes/SimpleImage/SimpleImage.php') ;
	include_once('_system/classes/create_directory.php') ;

	class userImg {

		private $userId = 0 ;
		private $width = '' ;
		private $height = '' ;
		private $dateString = '' ;
		private $test_mode = false ;


		public function getUserImage(){
			if($this->width!=''||$this->height!=''){
				$img = '' ;
				$img_path = '' ;
				// Perform basic checks
				if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])){
					// Redirect to hotlink protected notice
					if($this->test_mode!=true){
						header('Location: login.php?img=prohibited') ;
						exit() ;
					}
				} elseif($this->userId===0||($this->width===false&&$this->height===false)){
					// Redirect to damaged image request notice
					if($this->test_mode!=true){
						header('Location: login.php?img=qserror') ;
						exit() ;
					}
				}
				// Build the image path
				$found_an_image = false ;
				$img_path = 'userfiles/photo/'.$this->userId.'/' ;
				// If by this point a real image is requested, check if one even exists
				if(is_dir($img_path)){
					$this_dir = opendir($img_path) ;
					while(($file=readdir($this_dir)) != false){
						if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'&&!is_dir($img_path.$file)){
							$img = $file ;
							$found_an_image = true ;
						}
					}
					closedir($this_dir) ;
				}
				// now check if the image has been verified
				if(!$this->isImageVerified()){
					$found_an_image = false ;
				}
				if($found_an_image==false){
					// show the backup image if none found
					$img = 'photo.jpg' ;
					$img_path = 'userfiles/photo/___default/' ;
					$wimg_path = $img_path ;
				} else {
					$wimg_path = $img_path.'_working/' ;
				}
				// fall back for images added pre-resize script
				if(!file_exists($wimg_path.$img)){
					$wimg_path = $img_path ;
				}
				// get actual size for editable image
				if($this->width=='editable'){
					$resizedPath = 'userfiles/photo/'.$this->userId.'/_editable/'.$img ;
					list($width, $height, $type, $attr)= getimagesize($resizedPath); 
					$this->width = $width ;
					$this->height = $height ;
				} elseif($this->width=='original'){
					$resizedPath = 'userfiles/photo/'.$this->userId.'/'.$img ;
					list($width, $height, $type, $attr)= getimagesize($resizedPath); 
					$this->width = $width ;
					$this->height = $height ;
				} else {
					// setup the custom image folder
					$cimg_path = $img_path.'_custom/' ;
					if(!is_dir($cimg_path)){
						$cd = new create_directory ;
						$cd->go($cimg_path);
					}
					// get the file extension
					$fileExt = explode('.',$img) ;
					$fileExt = $fileExt[sizeof($fileExt)-1] ;
					// figure out the new size
					if($this->width==''&&$this->height!=''){
						// no width found
						$this->width = round((IMAGE_UPLOAD_LARGE_WIDTH/IMAGE_UPLOAD_LARGE_HEIGHT)*$this->height,0) ;
					}elseif($this->width!=''&&$this->height==''){
						$this->height = round((IMAGE_UPLOAD_LARGE_HEIGHT/IMAGE_UPLOAD_LARGE_WIDTH)*$this->width,0) ;
					}
					// make the full path
					$resizedPath = $cimg_path.$this->width.'x'.$this->height.'.'.$fileExt ;
					// echo $resizedPath.'<br />' ;
					if(!file_exists($resizedPath)){
						if($this->test_mode!=true){
							// make the new image
							$image = new SimpleImage() ;
							$image->load($wimg_path.$img) ;
							$image->resize($this->width,$this->height) ;
							$image->save($resizedPath) ;
						} else {
							echo '<p>File to be resized: '.$wimg_path.$img.'<br />File to be created: '.$resizedPath.'</p>' ;
						}
					} else {
						if($this->test_mode==true){
							echo '<p>File exists: '.$resizedPath.'</p>' ;
						}
					}
				}
				// draw it
				if($this->test_mode!=true){
					$img_info = $this->getImageInfo($resizedPath) ;
					header( "Content-type: image/".$img_info['file_type'] );
					@readfile( $resizedPath );
				} else {
					echo '<ul>'."\r\n" ;
					echo '<li><b>USER:</b> '.$this->userId."</li>\r\n" ;
					echo '<li><b>SIZE:</b> '.$this->width.'x'.$this->height."</li>\r\n" ;
					echo '<li><b>PATH:</b> '.$resizedPath."</li>\r\n" ;
					echo '</ul>'."\r\n" ;
				}
			} else {
				echo 'Physical size or datestring not specified<br />' ;
			}
		}


		// Is image verified
		public function isImageVerified(){
			if(is_numeric($this->userId)){
				// Check if the image is confimred
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				$sql = "SELECT * FROM unverified_files WHERE user_id='".mysql_escape_string($this->userId)."' AND upload_type='photo' ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$conn->disconnect(DM_DB_NAME) ;
				if(mysql_num_rows($result)<1){
					return true ;
				} else {
					return false ;
				}
			} else {
				return false ;
			}
		}

		public function getImageInfo($filepath){
			$returnvar = array() ;
			$temp_path_array = explode("/",$filepath) ;
			$temp_type_array = explode(".",$filepath) ;
			// Get filename
			$returnvar['file_name'] = $temp_path_array[sizeof($temp_path_array)-1] ;
			// get the file extension and image header type
			$returnvar['file_extension'] = strtolower($temp_type_array[sizeof($temp_type_array)-1]);
			if($returnvar['file_extension']=='jpg'||$returnvar['file_extension']='jpeg'){
				$returnvar['file_type'] = 'jpg' ;
			}elseif($returnvar['file_extension']=='gif'){
				$returnvar['file_type'] = 'gif' ;
			}elseif($returnvar['file_extension']=='png'){
				$returnvar['file_type'] = 'png' ;
			}elseif($returnvar['file_extension']=='bmp'){
				$returnvar['file_type'] = 'bmp' ;
			}elseif($returnvar['file_extension']=='tif'||$returnvar['file_extension']=='tiff'){
				$returnvar['file_type'] = 'tiff' ;
			}
			return $returnvar ;
		}

		// check the user had an imaage from their profile
		public function userHasImage($userId){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// get the img
			$returnVar = false ;
			$sql = "SELECT has_photo FROM graduates WHERE login_id='".$userId."' ;" ;
			$result = mysql_query($sql) ;
			if(mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result) ;
				if($row['has_photo']==1){
					$returnVar = 1 ;
				}
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}

		// Getters / Setterss
		public function setSize($var){
			if($var=='thumb'||$var=='icon'||$var=='small'){ // Thumbnail / Icon
				$this->width = IMAGE_UPLOAD_THUMBNAIL_WIDTH ;
				$this->height = IMAGE_UPLOAD_THUMBNAIL_HEIGHT ;
			} elseif($var=='med'||$var=='medium'){ // medium / med
				$this->width = IMAGE_UPLOAD_MEDIUM_WIDTH ;
				$this->height = IMAGE_UPLOAD_MEDIUM_HEIGHT ;
			} elseif($var=='lrgmed'||$var=='largemed'||$var=='largemedium'){ // medium / med
				$this->width = IMAGE_UPLOAD_LRGMEDIUM_WIDTH ;
				$this->height = IMAGE_UPLOAD_LRGMEDIUM_HEIGHT ;
			} elseif($var=='large'||$var=='lrg'){ // medium / med
				$this->width = IMAGE_UPLOAD_LARGE_WIDTH ;
				$this->height = IMAGE_UPLOAD_LARGE_HEIGHT ;
			} elseif($var=='editable'){ // medium / med
				$this->width = 'editable' ;
				$this->height = 'editable' ;
			} elseif($var=='original'){ // medium / med
				$this->width = 'original' ;
				$this->height = 'original' ;
			}
			
		}
		public function setWidth($var){
			if(is_numeric($var)){
				$this->width = $var ;
			}
		}
		public function setHeight($var){
			if(is_numeric($var)){
				$this->height = $var ;
			}
		}
		public function setUserId($var){
			$this->userId = $var ;
		}
		public function setDateString($var){
			if(is_numeric($var)){
				$this->dateString = $var ;
			}
		}

	}

?>