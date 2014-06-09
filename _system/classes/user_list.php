<?php

	class user_list {

		private $dataList = array() ;
		private $email = '' ;
		private $date_created_lower_limit = '' ;
		private $date_created_upper_limit = '' ;
		private $is_active = '' ;
		private $user_level = '' ;
		private $email_is_confirmed = '' ;
		private $order_by = 'id' ;
		private $order_dir = 'ASC' ;
		private $page_offset = 0 ;
		private $page_size = DEFAULT_PAGE_SIZE ;
		private $show_SU = false ;

		// Get all base user information via the email address. Used for login, confirmation and other functions
		public function getUserList($listtype){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Build the SQL
			if($listtype=='count'){
				$sql = "SELECT COUNT(1) as list_count ";
			} else {
				$sql = "SELECT id, email, date_created, is_active, user_level, email_is_confirmed ";
			}
			$sql.= "FROM login " ;
			$where_divider = 'WHERE ' ;
			if(IS_LOCALHOST==false&&!strstr($_SESSION['email'],'@devmac.co.uk')){
				$sql.= $where_divider."email NOT LIKE '%@devmac.co.uk' " ;
				$where_divider = ' AND ' ;
			}
			if($this->email!=''){
				$sql.= $where_divider."email='%".$this->email."%'" ;
				$where_divider = ' AND ' ;
			}
			if($this->date_created_lower_limit!=''||$this->date_created_upper_limit!=''){
				$sql.= $where_divider."(" ;
				// bit
				if($this->date_created_lower_limit!=''){
					$sql.= "date_created>'".$this->date_created_lower_limit."'" ;
				}
				if($this->date_created_lower_limit!=''&&$this->date_created_upper_limit!=''){
					$sql.= " AND " ;
				}
				if($this->date_created_upper_limit!=''){
					$sql.= "date_created<'".$this->date_created_upper_limit."'" ;
				}
				$sql.= ")" ;
				$where_divider = ' AND ' ;
			}
			if($this->is_active!=''){
				$sql.= $where_divider."is_active='".$this->is_active."'" ;
				$where_divider = ' AND ' ;
			}
			if($this->user_level!=''){
				$sql.= $where_divider."user_level='".$this->user_level."'" ;
				$where_divider = ' AND ' ;
			}
			if($this->email_is_confirmed!=''){
				$sql.= $where_divider."email_is_confirmed='".$this->email_is_confirmed."'" ;
				$where_divider = ' AND ' ;
			}
			if($this->show_SU!=true){
				$sql.= $where_divider."user_level!='0'" ;
				$where_divider = ' AND ' ;
			}
	
			if($listtype=='count'){
				// Get results
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				// close
				$conn->disconnect(DM_DB_NAME) ;
				// return the count
				return $row['list_count'] ;
			} else {
				// Add the order by clause
				$sql.= " ORDER BY ".$this->order_by." ".$this->order_dir." LIMIT ".($this->page_offset*$this->page_size).", ".$this->page_size ;
				// Get results
				// echo '<p class="notice" >'.$sql.'</p>' ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList, $row);
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
				return $this->dataList ;
			}
		}

		public function setEmail($val){
			$this->email = $val ;
		}

		public function setDateCreatedLowerLimit($val){
			$this->date_created_lower_limit = $val ;
		}

		public function setDateCreatedUpperLimit($val){
			$this->date_created_upper_limit = $val ;
		}

		public function setIsActive($val){
			$this->is_active = $val ;
		}

		public function setUserLevel($val){
			$this->user_level = $val ;
		}

		public function setEmailIsConfrimed($val){
			$this->email_is_confirmed = $val ;
		}

		public function setOrderBy($val){
			$this->order_by = $val ;
		}

		public function setOrderDir($val){
			$this->order_dir = $val ;
		}

		public function setPageOffset($val){
			$this->page_offset = $val ;
		}

		public function setPageSize($val){
			$this->page_size = $val ;
		}

		public function setShowSu($val){
			if($val==true){
				$this->show_SU = true ;
			} else {
				$this->show_SU = false ;
			}
		}

	}

?>