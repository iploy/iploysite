<?php

	include_once('_system/classes/session_killer.php') ;
	include_once('_system/classes/file_verification.php') ;
	include_once('_system/classes/transactions.php') ;

	class download {

		private $userId = 0 ;
		private $size = false ;
		private $adminMode = false ;
		private $type = false ;

		private $test_mode = false ;


		// the download function
		public function startDownload(){
			// TEST STUFF
			if($this->test_mode==true){
				$report = '<li><b>Test Mode</b></li>'."\r\n" ;
				$report.= '<li><b>Type:</b> '.$this->type.'</li>'."\r\n" ;
				if($this->type=='photo'){
					if($this->size===''){
						$this_size = 'Original' ;
					} else {
						$this_size = $this->size ;
					}
					$report.= '<li><b>Size:</b> '.$this_size.'</li>'."\r\n" ;
				}
			}
			// Do the permissions check
			$permission_granted = false ;
			$explicit_permission = false ;
			// Allow admins to download
			if($_SESSION['user_level']==0||$_SESSION['user_level']==3){
				$permission_granted = true ;
				$report.= '<li><b>User</b>: is admin level '.$_SESSION['user_level'].'</li>'."\r\n" ;
			} else {
				$report.= '<li><b>User</b>: is not admin</li>'."\r\n" ;
			}
			// Alow the user top download their own file
			if($_SESSION['user_id']==$this->userId){
				$permission_granted = true ;
				$report.= '<li><b>User ID</b>: matches</li>'."\r\n" ;
			} else {
				$report.= '<li><b>User ID</b>: does not match</li>'."\r\n" ;
			}
			// Allow employers who have purchased the file to download
			$transactions_function = new transactions ;
			$employer_has_access = $transactions_function->checkEmployerToGraduateAccess($_SESSION['user_id'],$this->userId) ;
			if($employer_has_access==true&&$_SESSION['user_level']==2){
				$permission_granted = true ;
				$report.= '<li><b>Transacion</b>: profile purchased</li>'."\r\n" ;
			} else {
				$report.= '<li><b>Transacion</b>: profile not purchased</li>'."\r\n" ;
			}
			// do the check and kill if permission denied
			if($permission_granted==false){
				if($this->test_mode==false){
					$kill_function = new sessionKiller ;
					$kill_function->killAll() ;
					header('Location: login.php?download=permissionerror') ;
					exit() ;
				} else  {
					$report.= '<li><b>Permission</b>: failed, user redirected</li>'."\r\n" ;
				}
			} else {
				$report.= '<li><b>Permission</b>: granted</li>'."\r\n" ;
			}
			// Check for hotlinking
			if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])||$_SESSION['user_id']==''){
				// declare classes and kill sessions
				if($this->test_mode==false){
					$kill_function = new sessionKiller ;
					$kill_function->killAll() ;
					header('Location: login.php?download=prohibited') ;
					exit() ;
				} else {
					$report.= '<li><b>Hotlink Detected</b>: Sessions will be killed</li>'."\r\n" ;
				}
			} elseif($this->test_mode==true){
				$report.= '<li><b>Hotlink Protection</b>: OK</li>'."\r\n" ;
			}
			// Check the querystring for errors
			if($this->type==false||($this->type=='image'&&$this->size!==false)){
				// declare classes and kill sessions
				if($this->test_mode==false){
					$kill_function = new sessionKiller ;
					$kill_function->killAll() ;
					header('Location: login.php?download=qserror') ;
					exit() ;
				} else {
					$report.= '<li><b>Querystring</b>: Sessions will be killed</li>'."\r\n" ;
				}
			} elseif($this->test_mode==true){
				$report.= '<li><b>Querystring</b>: OK</li>'."\r\n" ;
			}
			// check if the file is unverified
			$file_varification = new fileVerification ;
			$file_varification->setUserId($this->userId) ;
			$file_varification->setType($this->type) ;
			$file_is_verified = $file_varification->check() ;
			if($file_is_verified==false){
				// declare classes and kill sessions
				if($this->test_mode==false&&$permission_granted==false){
					$kill_function = new sessionKiller ;
					$kill_function->killAll() ;
					header('Location: login.php?download=unverified') ;
					exit() ;
				} else {
					$report.= '<li><b>File</b>: Is not Verified</li>'."\r\n" ;
				}
			} elseif($this->test_mode==true){
				$report.= '<li><b>File</b>: Is Verified</li>'."\r\n" ;
			}
			// Start to build the URL
			$file_path = 'userfiles/'.$this->type.'/'.$this->userId.'/' ;
			// Check the user file exists
			$file_name = $this->findFile($file_path) ;
			if($file_name==false){
				// declare classes and kill sessions
				if($this->test_mode==false){
					$kill_function = new sessionKiller ;
					$kill_function->killAll() ;
					header('Location: login.php?download=qserror') ;
					exit() ;
				} else {
					$report.= '<li><b>File</b>: Does not exist</li>'."\r\n" ;
				}
			} elseif($this->test_mode==true){
				$report.= '<li><b>File</b>: Exists as '.$file_name.'</li>'."\r\n" ;
			}
			// At this point, all checks are complete. Download the file or report
			if($this->test_mode==true){
				$report.= '<li><b>File Path</b>: '.$file_path.$file_name.'</li>'."\r\n" ;
				echo '<ul>'.$report.'</ul>' ;
			} else {
				// push the download
				$this->pushDownload($file_path.$file_name) ;
			}
		}

		public function pushDownload($download_file){
			if($this->type=='cv'){
				$allowed_array = $_SESSION['ALLOWED_FILES_CV'] ;
			} elseif($this->type=='certificate'){
				$allowed_array = array_merge($_SESSION['ALLOWED_FILES_CV'],$_SESSION['ALLOWED_FILES_IMAGES']) ;
			} elseif ($this->type=='photo'){
				$allowed_array = $_SESSION['ALLOWED_FILES_IMAGES'] ;
			}
			include_once('_system/inc/_download_file.php') ;
		}

		// Find and return the filename based on a specific directory / Returns false if the file does not exist
		public function findFile($folder){
			// Build the image path
			$returnstr = false ;
			// If by this point a real image is requested, check if one even exists
			if(is_dir($folder)){
				$this_dir = opendir($folder) ;
				while(($file=readdir($this_dir)) != false){
					if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){
						$returnstr = $file ;
					}
				}
				closedir($this_dir) ;
			}
			return $returnstr ;
		}


		// Getters / Setterss
		public function setSize($var){
			if($var=='thumb'||$var=='icon'){ // Thumbnail / Icon
				$this->size = 'thumb' ;
			} elseif($var=='med'||$var=='medium'){ // medium / med
				$this->size = 'med' ;
			} elseif($var=='lrgmed'||$var=='largemed'||$var=='largemedium'){ // medium / med
				$this->size = 'lrgmed' ;
			} elseif($var=='large'||$var=='lrg'){ // medium / med
				$this->size = 'large' ;
			} elseif($var=='original'){ // medium / med
				$this->size = '' ;
			}
		}
		public function setUserId($var){
			if(is_numeric($var)){
				$this->userId = $var ;
			}
		}
		public function setAdminMode($var){
			if($var==true||$var=='true'){
				$this->adminMode = true ;
			} else {
				$this->adminMode = false ;
			}
		}
		public function setType($var){
			if($var=='cv'||$var=='photo'){
				$this->type = $var ;
			} elseif($var=='img'||$var=='image'){
				$this->type = 'photo' ;
			} elseif($var=='file'){
				$this->type = 'cv' ;
			} elseif($var=='certificate'||$var=='certification'){
				$this->type = 'certificate' ;
			} else {
				$this->type = false ;
			}
		}

	}

?>
