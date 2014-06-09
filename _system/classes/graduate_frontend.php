<?php

	class graduate_data {

		private $dataList = array() ;
		private $list_page_size = 20 ;
		private $list_page = 1 ;
		private $list_max_length = 0 ;
		private $list_type = '' ;
		private $list_order_by = 'id' ;
		private $list_order_dir = 'DESC' ;
		private $list_custom_where = '' ;
		private $is_active = '1' ;
		private $is_confirmed = '1' ;
		private $complete_profiles_only = true ;

		// Get all graduate profile information via the user ID
		public function getGraduateByID($id){
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
			//print_r($row) ;
			return $row ;
		}

		// Get a list of graduates (Basic info only) for search results and features
		public function getGraduatesList($is_count_only = false){
			include('_system/_config/_graduate_required_fields_array.php') ;
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Build the SQL
			$sql = "SELECT " ;
			if($is_count_only==true){
				$sql.= "COUNT(1) as graduate_count " ;
			} else {
				$sql.= "graduates.login_id, graduates.first_name, graduates.availability, graduates.subject, graduates.job_category, graduates.education_field, graduates.education_grade, graduates.education_degree_title, graduates.emploment_location, graduates.has_cv, graduates.has_photo, graduates.has_certificate, graduates.education_has_graduated, graduates.education_location, graduates.has_video, graduates.employment_status, login.date_created " ;
			}
			$sql.= "FROM graduates " ;
			$sql.= "INNER JOIN login ON login.id = graduates.login_id " ;
			$sql.= "WHERE login.is_active='".$this->is_active."' AND login.email_is_confirmed='".$this->is_confirmed."' AND login.id<>'".DEMO_PROFILE_ID."' " ;
			if(IS_LOCALHOST==false&&!strstr($_SESSION['email'],'@devmac.co.uk')&&!strstr($_SESSION['email'],'@iploy.co.uk')){
				$sql.= "AND email NOT LIKE '%devmac.co.uk' AND email NOT LIKE '%iploy.co.uk' AND login.user_level='1' " ;
			}
			// This part will stop incomlete profiles being visible
			if($this->complete_profiles_only==true){
				foreach($graduate_required_fields as $required_field){
					$sql .= "AND ".$required_field."!='' " ;
				}
			}
			// Add custom where for searches
			if($this->list_custom_where!=''){
				$sql.= "AND (".$this->list_custom_where.") " ;
			}
			if($is_count_only!=true){
				$sql.= "ORDER BY ".$this->list_order_by." ".$this->list_order_dir." "  ;
				if($this->list_max_length>0){
					$sql.= "LIMIT ".$this->list_max_length ;
				} else {
					$sql.= "LIMIT ".(($this->list_page-1)*$this->list_page_size).", ".$this->list_page_size ;
				}
			}
			// Get results
			// echo '<p class="notice" >'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			// Now return the variable depending on the requested type
			if($is_count_only==true){
				// close
				$row = mysql_fetch_array($result) ;
				$conn->disconnect(DM_DB_NAME) ;
				return $row['graduate_count'] ;
			} else {
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList, $row);
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
				return $this->dataList ;
			}
		}

		// Getters / Setters
		public function setListPageSize($var){
			$this->list_page_size = $var ;
		}
		public function setListPage($var){
			$this->list_page = $var ;
		}
		public function setListMaxLength($var){
			$this->list_max_length = $var ;
		}
		public function setListType($var){
			$this->list_type = $var ;
		}
		public function setListOrderBy($var){
			$this->list_order_by = $var ;
		}
		public function setListOrderDir($var){
			$this->list_order_dir = $var ;
		}
		public function setIsActive($var){
			$this->is_active = $var ;
		}
		public function setIsConfirmed($var){
			$this->is_confirmed = $var ;
		}
		public function setCustomWhere($var){
			$this->list_custom_where = $var ;
		}
		public function showCompleteOnly($var){
			if($var==false){
				$this->complete_profiles_only = false ;
			} else {
				$this->complete_profiles_only = true ;
			}
		}

	}

?>