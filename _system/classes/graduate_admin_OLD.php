<?php
		/*
		SYSTEM : login_id
		STRAIGHT TEXT: first_name surname tel_mobile 
		DATE : date_of_birth 
		MULTIPLE CHOICE : hours subject emploment_location job_category 
		YES/NO : will_travel has_driving_licence will_do_antisocial 
		OPTION LIST : availability 
		UPLOADS : upload_cv upload_photo 
		*/
	include('_system/classes/image_manipulation.php') ;
	include_once('_system/functions/delete_directory.php') ;
	include_once('_system/classes/session_killer.php') ;

	class graduate_info {

		// Get all graduate profile information via the user ID
		// Get all graduate profile information via the user ID
		public function getGraduateByID($id,$setsessions=false){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Sql here
			$this_sql = "SELECT * FROM graduates WHERE login_id='".$id."'" ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			// echo mysql_num_rows($result) ;
			$row = mysql_fetch_array($result) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// Set sessions
			if($setsessions==true){
				$_SESSION['first_name'] = $row['first_name'] ;
				$_SESSION['surname'] = $row['surname'] ;
				$_SESSION['tel_mobile'] = $row['tel_mobile'] ;
				$_SESSION['social_skype'] = $row['social_skype'] ;
				if($row['date_of_birth']!='0000-00-00'){
					$_SESSION['date_of_birth'] = $row['date_of_birth'] ;
				} else {
					$_SESSION['date_of_birth'] = '' ;
				}
				$_SESSION['hours'] = $row['hours'] ;
				$_SESSION['subject'] = $row['subject'] ;
				$_SESSION['emploment_location'] = $row['emploment_location'] ;
				$_SESSION['job_category'] = $row['job_category'] ;
				$_SESSION['will_travel'] = $row['will_travel'] ;
				$_SESSION['has_driving_licence'] = $row['has_driving_licence'] ;
				$_SESSION['will_do_antisocial'] = $row['will_do_antisocial'] ;
				if($row['availability']!='0000-00-00'){
					$_SESSION['availability'] = $row['availability'] ;
				} else {
					$_SESSION['availability'] = '' ;
				}
				// Education
				$_SESSION['education_level'] = $row['education_level'] ;
				$_SESSION['education_degree_title'] = $row['education_degree_title'] ;
				if($row['education_start']!='0000-00-00'){
					$_SESSION['education_start'] = $row['education_start'] ;
				} else {
					$_SESSION['education_start'] = '' ;
				}
				if($row['education_end']!='0000-00-00'){
					$_SESSION['education_end'] = $row['education_end'] ;
				} else {
					$_SESSION['education_end'] = '' ;
				}
				$_SESSION['education_has_graduated'] = $row['education_has_graduated'] ;
				$_SESSION['education_grade'] = $row['education_grade'] ;
				$_SESSION['education_institution'] = $row['education_institution'] ;
				$_SESSION['education_location'] = $row['education_location'] ;
				$_SESSION['education_certificate_title'] = $row['education_certificate_title'] ;
				$_SESSION['youtube_id'] = $row['youtube_id'] ;
			}
			return $row ;
		}


		// Personal info = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		public function savePersonalInfo($id,$setsessions){
			include('_system/_config/_multiple_choice_arrays.php') ;
			$error = '' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Check for existing user
			// Sql here
			$this_sql = "SELECT login_id FROM graduates WHERE login_id='".$id."'" ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// UPDATE THE DATABASE ----------------------------------------------
			// format telephone number
			$this_tel_mobile = '' ;
			if($_POST['tel_mobile']!=''){
				$this_tel_mobile = preg_replace('/[^0-9+]/','',$_POST['tel_mobile']) ;
			}
			// format date of birth
			$this_date_of_birth = '' ;
			if($_POST['date_of_birth']!=''){
				$this_date_of_birth = date(substr(DM_PHP_DATE_FORMAT,0,5),strtotime($_POST['date_of_birth'])) ;
			}
			// Format string for has_driving_licence yes/no
			$this_has_driving_licence = '' ;
			if($_POST['has_driving_licence']=='yes'||$_POST['has_driving_licence']=='no'){
				$this_has_driving_licence = $_POST['has_driving_licence'] ;
			}
			// Format string for education_location
			$this_education_location = '' ;
			if(in_array($_POST['education_location'],$locations_array)){
				$this_education_location = $_POST['education_location'] ;
			}
			// Create the SQL statement
			if($row['login_id']==''){
				// if empty create new
				$sql = "INSERT INTO graduates(login_id, first_name, surname, tel_mobile, social_skype, date_of_birth, has_driving_licence, education_location) VALUES(" ;
				$sql.= "'".mysql_escape_string($id)."', " ;
				$sql.= "'".mysql_escape_string($_POST['first_name'])."', " ;
				$sql.= "'".mysql_escape_string($_POST['surname'])."', " ;
				$sql.= "'".mysql_escape_string($this_tel_mobile)."', " ;
				$sql.= "'".mysql_escape_string($_POST['social_skype'])."', " ;
				$sql.= "'".mysql_escape_string($this_date_of_birth)."', " ;
				$sql.= "'".mysql_escape_string($this_has_driving_licence)."', " ;
				$sql.= "'".mysql_escape_string($this_education_location)."' " ;
				// close
				$sql.= ") ;" ;
			} else {
				// update
				$sql = "UPDATE graduates SET " ;
				$sql.= "first_name='".mysql_escape_string($_POST['first_name'])."', " ;
				$sql.= "surname='".mysql_escape_string($_POST['surname'])."', " ;
				$sql.= "tel_mobile='".mysql_escape_string($this_tel_mobile)."', " ;
				$sql.= "social_skype='".mysql_escape_string($_POST['social_skype'])."', " ;
				$sql.= "date_of_birth='".mysql_escape_string($this_date_of_birth)."', " ;
				$sql.= "has_driving_licence='".mysql_escape_string($this_has_driving_licence)."', " ;
				$sql.= "education_location='".mysql_escape_string($this_education_location)."' " ;
				// close
				$sql.= "WHERE login_id='".$id."' ; " ;
			}
			// echo '<p>'.$sql.'</p>' ;
			mysql_query($sql) ;
			if(mysql_error()!=''){
				$error = mysql_error() ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// Set sessions
			if($setsessions==true&&$error==''){
				$_SESSION['first_name'] = $_POST['first_name'] ;
				$_SESSION['surname'] = $_POST['surname'] ;
				$_SESSION['tel_mobile'] = $this_tel_mobile ;
				$_SESSION['social_skype'] = $_POST['social_skype'] ;
				$_SESSION['date_of_birth'] = $this_date_of_birth ;
				$_SESSION['has_driving_licence'] = $this_has_driving_licence ;
				$_SESSION['education_location'] = $this_education_location ;
			}
			return $error ;
		}


		// Education info = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		public function saveEducationInfo($id,$setsessions){
			include('_system/_config/_multiple_choice_arrays.php') ;
			$error = '' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Check for existing user
			// Sql here
			$this_sql = "SELECT login_id FROM graduates WHERE login_id='".$id."'" ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// UPDATE THE DATABASE ----------------------------------------------
			// Format string for education_level
			$this_education_level = '' ;
			if(in_array($_POST['education_level'],$education_level_array)){
				$this_education_level = $_POST['education_level'] ;
			}
			// Format string for education_start
			$this_education_start = '' ;
			if($_POST['education_start']!=''){
				$this_education_start = date(substr(DM_PHP_DATE_FORMAT,0,5),strtotime($_POST['education_start'])) ;
			}
			// Format string for education_end
			$this_education_end = '' ;
			if($_POST['education_end']!=''){
				$this_education_end = date(substr(DM_PHP_DATE_FORMAT,0,5),strtotime($_POST['education_end'])) ;
			}
			// Format string for education_has_graduated
			$this_education_has_graduated = '' ;
			if($_POST['education_has_graduated']=='no'){
				$this_education_has_graduated = 'no' ;
			} else {
				$this_education_has_graduated = 'yes' ;
			}
			// Format string for education_grade
			$this_education_grade = '' ;
			if(in_array($_POST['education_grade'],$education_grade_array)){
				$this_education_grade = $_POST['education_grade'] ;
			}
			// Create the SQL statement
			if($row['login_id']==''){
				// if empty create new
				$sql = "INSERT INTO graduates(id, subject, education_level, education_degree_title, education_start, education_end, education_has_graduated, education_grade, education_institution) VALUES(" ;
				$sql.= "'".mysql_escape_string($id)."', " ;
				$sql.= "'".mysql_escape_string($_POST['subject'])."', " ;
				$sql.= "'".mysql_escape_string($this_education_level)."', " ;
				$sql.= "'".mysql_escape_string($_POST['education_degree_title'])."', " ;
				$sql.= "'".mysql_escape_string($this_education_start)."', " ;
				$sql.= "'".mysql_escape_string($this_education_end)."', " ;
				$sql.= "'".mysql_escape_string($this_education_has_graduated)."', " ;
				$sql.= "'".mysql_escape_string($this_education_grade)."', " ;
				$sql.= "'".mysql_escape_string($_POST['education_institution'])."' " ;
				// close
				$sql.= ") ;" ;
			} else {
				// update
				$sql = "UPDATE graduates SET " ;
				$sql.= "subject='".mysql_escape_string($_POST['subject'])."', " ;
				$sql.= "education_level='".mysql_escape_string($this_education_level)."', " ;
				$sql.= "education_degree_title='".mysql_escape_string($_POST['education_degree_title'])."', " ;
				$sql.= "education_start='".mysql_escape_string($this_education_start)."', " ;
				$sql.= "education_end='".mysql_escape_string($this_education_end)."', " ;
				$sql.= "education_has_graduated='".mysql_escape_string($this_education_has_graduated)."', " ;
				$sql.= "education_grade='".mysql_escape_string($this_education_grade)."', " ;
				$sql.= "education_institution='".mysql_escape_string($_POST['education_institution'])."' " ;
				// close
				$sql.= "WHERE login_id='".$id."' ; " ;
			}
			// echo '<p>'.$sql.'</p>' ;
			mysql_query($sql) ;
			if(mysql_error()!=''){
				$error = mysql_error() ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// Set sessions
			if($setsessions==true&&$error==''){
				// Education
				$_SESSION['subject'] = $_POST['subject'] ;
				$_SESSION['education_level'] = $this_education_level ;
				$_SESSION['education_degree_title'] = $_POST['education_degree_title'] ;
				$_SESSION['education_start'] = $this_education_start ;
				$_SESSION['education_end'] = $this_education_end ;
				$_SESSION['education_has_graduated'] = $this_education_has_graduated ;
				$_SESSION['education_grade'] = $this_education_grade ;
				$_SESSION['education_institution'] = $_POST['education_institution'] ;
			}
			return $error ;
		}


		// Employment info = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		public function saveEmploymentInfo($id,$setsessions){
			include('_system/_config/_multiple_choice_arrays.php') ;
			$error = '' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Check for existing user
			// Sql here
			$this_sql = "SELECT login_id FROM graduates WHERE login_id='".$id."'" ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// UPDATE THE DATABASE ----------------------------------------------
			// Format string for hours multiple choice
			$this_hours = '' ;
			if(is_array($_POST['hours'])){
				foreach($_POST['hours'] as $hours){
					if(in_array($hours,$hours_array)){
						if($this_hours==''){
							$this_hours.= $hours ;
						} else {
							$this_hours.= ','.$hours ;
						}
					}
				}
			}
			// Format string for emploment_location multiple choice
			$this_emploment_location = '' ;
			if(is_array($_POST['emploment_location'])){
				foreach($_POST['emploment_location'] as $emploment_location){
					if(in_array($emploment_location,$locations_array)){
						if($this_emploment_location==''){
							$this_emploment_location.= $emploment_location ;
						} else {
							$this_emploment_location.= ','.$emploment_location ;
						}
					}
				}
			}
			// Format string for job_category multiple choice
			$this_job_category = '' ;
			if(is_array($_POST['job_category'])){
				foreach($_POST['job_category'] as $job_category){
					if(in_array($job_category,$category_array)){
						if($this_job_category==''){
							$this_job_category.= $job_category ;
						} else {
							$this_job_category.= ','.$job_category ;
						}
					}
				}
			}
			// Format string for availability radio array
			$this_availability = '' ;
			if($_POST['availability']!=''){
				$this_availability = date(substr(DM_PHP_DATE_FORMAT,0,5),strtotime($_POST['availability'])) ;
			}
			// Format string for will_travel yes/no
			$this_will_travel = '' ;
			if($_POST['will_travel']=='yes'||$_POST['will_travel']=='no'){
				$this_will_travel = $_POST['will_travel'] ;
			}
			// Format string for will_do_antisocial yes/no
			$this_will_do_antisocial = '' ;
			if($_POST['will_do_antisocial']=='yes'||$_POST['will_do_antisocial']=='no'){
				$this_will_do_antisocial = $_POST['will_do_antisocial'] ;
			}
			// Create the SQL statement
			if($row['login_id']==''){
				// if empty create new
				$sql = "INSERT INTO graduates(id, availability, will_do_antisocial, will_travel, hours, emploment_location, job_category) VALUES(" ;
				$sql.= "'".mysql_escape_string($id)."', " ;
				$sql.= "'".mysql_escape_string($this_availability)."', " ;
				$sql.= "'".mysql_escape_string($this_will_do_antisocial)."', " ;
				$sql.= "'".mysql_escape_string($this_will_travel)."', " ;
				$sql.= "'".mysql_escape_string($this_hours)."', " ;
				$sql.= "'".mysql_escape_string($this_emploment_location)."', " ;
				$sql.= "'".mysql_escape_string($this_job_category)."' " ;
				// close
				$sql.= ") ;" ;
			} else {
				// update
				$sql = "UPDATE graduates SET " ;
				$sql.= "availability='".mysql_escape_string($this_availability)."', " ;
				$sql.= "will_do_antisocial='".mysql_escape_string($this_will_do_antisocial)."', " ;
				$sql.= "will_travel='".mysql_escape_string($this_will_travel)."', " ;
				$sql.= "hours='".mysql_escape_string($this_hours)."', " ;
				$sql.= "emploment_location='".mysql_escape_string($this_emploment_location)."', " ;
				$sql.= "job_category='".mysql_escape_string($this_job_category)."' " ;
				// close
				$sql.= "WHERE login_id='".$id."' ; " ;
			}
			// echo '<p>'.$sql.'</p>' ;
			mysql_query($sql) ;
			if(mysql_error()!=''){
				$error = mysql_error() ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// Set sessions
			if($setsessions==true&&$error==''){
				$_SESSION['availability'] = $this_availability ;
				$_SESSION['will_do_antisocial'] = $this_will_do_antisocial ;
				$_SESSION['will_travel'] = $this_will_travel ;
				$_SESSION['hours'] = $this_hours ;
				$_SESSION['emploment_location'] = $this_emploment_location ;
				$_SESSION['job_category'] = $this_job_category ;
			}
			return $error ;
		}


		// Uploads = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		public function uploadFile($id,$fileType,$setsessions){
			include('_system/_config/_multiple_choice_arrays.php') ;
			$error = '' ;
			if($fileType=='photo'||$fileType=='cv'||$fileType=='certificate'){
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// Check for existing user
				// Sql here
				$this_sql = "SELECT login_id FROM graduates WHERE login_id='".$id."'" ;
				$result = mysql_query($this_sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				// UPLOAD THE FILE -------------------------------------------------
				$uploadSuccessful = false ;
				switch($fileType){
					case 'photo' :
						$allowedFiles = $_SESSION['ALLOWED_FILES_IMAGES'] ;
					break ;
					case 'cv' :
						$allowedFiles = $_SESSION['ALLOWED_FILES_CV'] ;
					break ;
					case 'certificate' :
						$allowedFiles = array_merge($_SESSION['ALLOWED_FILES_IMAGES'],$_SESSION['ALLOWED_FILES_CV']) ;
					break ;
				}
				$disallowed_filetypes = explode("|",DISALLOWED_FILETYPES) ;
				// do it!
				$this_file_extension_array = explode(".",$_FILES['upload']['name']) ;
				$this_file_extension = strtolower($this_file_extension_array[sizeof($this_file_extension_array)-1]) ;
				// ensure file extension is allowed
				if(sizeof($this_file_extension_array)>1){
					if(in_array($this_file_extension,$allowedFiles)&&!in_array($this_file_extension,$disallowed_filetypes)){
						// After confirming the file extension, delete the old directory then upload
						$target_dir = "userfiles/".$fileType."/".$id.'/' ;
						if(is_dir($target_dir)){
							delete_directory($target_dir) ;
						}
						mkdir($target_dir) ;
						$target_path = $target_dir.$fileType.'.'.$this_file_extension ; 
						if(!move_uploaded_file($_FILES['upload']['tmp_name'], $target_path)) {
							$error = "There was an error uploading the file, please try again.";
						} else {
							// check if the file type is an image and pump out the resized versions if required
							if($fileType=='photo'&&in_array($this_file_extension,$_SESSION['ALLOWED_FILES_IMAGES'])){
								// Get the upload size
								$image_info = getimagesize($target_path);
								$upload_width = $image_info[0] ;
								$upload_height = $image_info[1] ;
								// Work out which side of the LARGE image to maintain
								if(($upload_width/$upload_height)>($upload_height/$upload_width)&&($upload_width/$upload_height)>(IMAGE_UPLOAD_LARGE_WIDTH/IMAGE_UPLOAD_LARGE_HEIGHT)){
									$large_scale_type = 'height_max' ;
								} else {
									$large_scale_type = 'width_max' ;
								}
								// Work out which side of the MEDIUM image to maintain
								if(($upload_width/$upload_height)>($upload_height/$upload_width)&&($upload_width/$upload_height)>(IMAGE_UPLOAD_MEDIUM_WIDTH/IMAGE_UPLOAD_MEDIUM_HEIGHT)){
									$med_scale_type = 'height_max' ;
								} else {
									$med_scale_type = 'width_max' ;
								}
								// Work out which side of the THUMBNAIL image to maintain
								if(($upload_width/$upload_height)>($upload_height/$upload_width)&&($upload_width/$upload_height)>(IMAGE_UPLOAD_THUMBNAIL_WIDTH/IMAGE_UPLOAD_THUMBNAIL_HEIGHT)){
									$thumb_scale_type = 'height_max' ;
								} else {
									$thumb_scale_type = 'width_max' ;
								}
								// Create the images
								$image = new SimpleImage();
								$image->load($target_path);
								// large
								if($large_scale_type=='width_max'){
									$image->resizeToWidth(IMAGE_UPLOAD_LARGE_WIDTH);
								} else {
									$image->resizeToHeight(IMAGE_UPLOAD_LARGE_HEIGHT);
								}
								mkdir(str_replace('/'.$id.'/','/'.$id.'/_large/',$target_dir)) ;
								$image->save(str_replace('/'.$id.'/','/'.$id.'/_large/',$target_path));
								// largemedium (Search Results)
								if($large_scale_type=='width_max'){
									$image->resizeToWidth(IMAGE_UPLOAD_LRGMEDIUM_WIDTH);
								} else {
									$image->resizeToHeight(IMAGE_UPLOAD_LRGMEDIUM_HEIGHT);
								}
								mkdir(str_replace('/'.$id.'/','/'.$id.'/_lrgmed/',$target_dir)) ;
								$image->save(str_replace('/'.$id.'/','/'.$id.'/_lrgmed/',$target_path));
								// meduim
								if($med_scale_type=='width_max'){
									$image->resizeToWidth(IMAGE_UPLOAD_MEDIUM_WIDTH);
								} else {
									$image->resizeToHeight(IMAGE_UPLOAD_MEDIUM_HEIGHT);
								}
								mkdir(str_replace('/'.$id.'/','/'.$id.'/_med/',$target_dir)) ;
								$image->save(str_replace('/'.$id.'/','/'.$id.'/_med/',$target_path));
								// Thumb
								if($thumb_scale_type=='width_max'){
									$image->resizeToWidth(IMAGE_UPLOAD_THUMBNAIL_WIDTH);
								} else {
									$image->resizeToHeight(IMAGE_UPLOAD_THUMBNAIL_HEIGHT);
								}
								mkdir(str_replace('/'.$id.'/','/'.$id.'/_thumb/',$target_dir)) ;
								$image->save(str_replace('/'.$id.'/','/'.$id.'/_thumb/',$target_path));
							}
							// update the database asking for verification - Deleting any old requests first
							$sql = "DELETE FROM unverified_files WHERE upload_type='".$fileType."' AND user_id='".$id."' ;" ;
							mysql_query($sql) or die( mysql_error()) ;
							$sql = "INSERT INTO unverified_files(upload_type,file_path_full,user_id,date_added) " ;
							$sql.= "VALUES('".$fileType."','".$target_path."','".$id."','".DATE_TIME_NOW."') ;" ;
							mysql_query($sql) or die( mysql_error()) ;
							// do the extra bit for the certificate
							if($fileType=='certificate'){
								// update the certificate title
								if($row['login_id']==''){
									// if empty create new
									$sql = "INSERT INTO graduates(login_id, education_certificate_title) VALUES('".$id."','".mysql_escape_string($_POST['education_certificate_title'])."') ;" ;
								} else {
									$sql = "UPDATE graduates SET education_certificate_title='".mysql_escape_string($_POST['education_certificate_title'])."' ; " ;
								}
								mysql_query($sql) or die( mysql_error()) ;
								// Set sessions
								if($setsessions==true){
									$_SESSION['education_certificate_title'] = $_POST['education_certificate_title'] ;
								}
							}
						}
					} else {
						$error = "The file type you selected is not allowed on our system.";
					}
				} else {
					$error = "That kind of upload is prohibited";
				}
				// Close
				$conn->disconnect(DM_DB_NAME) ;
			} else {
				$error = 'Unrecognised Request' ;
			}
			return $error ;
		}


		// delete files and clean up directories, used for removing CV and images
		public function deleteFile($id,$fileType){
			if($fileType=='photo'||$fileType=='cv'||$fileType=='certificate'){
				// update the database asking for verification - Deleting any old requests first
				$sql = "DELETE FROM unverified_files WHERE upload_type='".$fileType."' AND user_id='".$id."' ;" ;
				mysql_query($sql) or die( mysql_error()) ;
				$target_dir = 'userfiles/'.$fileType.'/'.$id.'/' ;
				if(is_dir($target_dir)){
					delete_directory($target_dir) ;
				}
			}
		}

		// Kill all sessions used by a logged in graduate, for logout purposes only
		public function killSessions(){
			$kill_function = new sessionKiller ;
			$kill_function->killGraduateSessions() ;
		}

	} // End Class

?>
