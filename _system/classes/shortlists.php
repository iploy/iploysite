<?php

	class shortlists {

		private $vacancyId = 0 ;
		private $employerId = 0 ;
		private $graduateId = 0 ;
		private $dataList = array() ;

		// functions 

		public function addGraduateToShortlist(){
			// return false if already exists
			if($this->checkShortlistForGraduate()!=false){
				return false ;
			} else {
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// 
				$sql = "SELECT * FROM vacancy_candidates WHERE vacancy_id='".$this->vacancyId."' AND employer_id='".$this->employerId."' ORDER BY list_order DESC LIMIT 0, 1 ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				if($row['list_order']==''){
					$new_order = 1 ;
				} else {
					$new_order = $row['list_order'] + 1 ;
				}
				// 
				$sql = "INSERT INTO vacancy_candidates(vacancy_id,employer_id,user_id,list_order) values('".$this->vacancyId."','".$this->employerId."','".$this->graduateId."','".$new_order."') ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				// echo '<p>'.$sql.'</p>' ;
				// 
				$conn->disconnect(DM_DB_NAME) ;
				return true ;
			}
		}

		public function checkShortlistForGraduate(){
			$return_var = false ;
			// return count if already exists
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			$sql = "SELECT id FROM vacancy_candidates WHERE vacancy_id='".$this->vacancyId."' AND employer_id='".$this->employerId."' AND user_id='".$this->graduateId."' ;" ;
			// echo '<p>'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)>0){
				$return_var = true ;
			}
			$conn->disconnect(DM_DB_NAME) ;
			return $return_var ;
		}


		public function getShortlistListGraduateCount(){
			// return count if already exists
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT COUNT(1) as graduates FROM vacancy_candidates " ;
			// Where
			$sql_prefix = "WHERE " ;
			if($this->employerId!=0){
				$sql.= $sql_prefix."employer_id='".$this->employerId."' " ;
				$sql_prefix = "AND " ;
			}
			if($this->vacancyId!=0){
				$sql.= $sql_prefix."vacancy_id='".$this->vacancyId."' " ;
				$sql_prefix = "AND " ;
			}
			if($this->graduateId!=0){
				$sql.= $sql_prefix."user_id='".$this->graduateId."' " ;
				$sql_prefix = "AND " ;
			}
			$sql.= "; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			//close and return
			$conn->disconnect(DM_DB_NAME) ;
			return $row['graduates'] ;
		}

		public function getPurchasedGraduatesList($is_count=false,$is_full_user_info=false){
			// return count if already exists
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			$sql = "SELECT " ;
			// is count?
			if($is_count==true){
				$sql.= "COUNT(1) as list_count " ;
			} else {
				$sql.= "employer_credit_to_graduate.employer_id, employer_credit_to_graduate.user_id, employer_credit_to_graduate.date_purchased, " ;
				if($is_full_user_info==true){
					$sql.= "graduates.* " ;
				} else {
					$sql.= "graduates.first_name, graduates.surname " ;
				}
				$sql.= "FROM employer_credit_to_graduate " ;
				if($is_count!=true){
					$sql.= "INNER JOIN graduates ON employer_credit_to_graduate.user_id = graduates.login_id " ;
				}
			}
			$sql.= "WHERE employer_credit_to_graduate.date_purchased > '".date(DM_PHP_DATE_FORMAT,strtotime(date(DM_PHP_DATE_FORMAT)." - ".PROFILE_PURCHASE_EXPIRY))."' AND employer_id='".$this->employerId."' " ;
			$sql.= "ORDER BY graduates.first_name ASC " ;
			$sql.= "; " ;
			// echo '<p class="notice" >'.$sql.'</p>' ;
			// get
			$result = mysql_query($sql) or die( mysql_error()) ;
			if($is_count==true){
				// close
				$conn->disconnect(DM_DB_NAME) ;
				$row = mysql_fetch_array($result) ;
				return $row['list_count'] ;
			} else {
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList, $row);
				}
				$conn->disconnect(DM_DB_NAME) ;
				return $this->dataList ;
			}
		}


		public function getFullShortlistList($is_count=false,$is_full_user_info=false){
			// return count if already exists
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			$sql = "SELECT " ;
			if($is_count==true){
				$sql.= "COUNT(1) as list_count " ;
			} else {
				$sql.= "vacancy_candidates.id, vacancy_candidates.employer_id, vacancy_candidates.user_id, vacancy_candidates.list_order, " ;
				if($is_full_user_info==true){
					$sql.= "graduates.* " ;
				} else {
					$sql.= "graduates.first_name, graduates.surname " ;
				}
			}
			$sql.= "FROM vacancy_candidates " ;
			if($is_count!=true){
				$sql.= "INNER JOIN graduates ON vacancy_candidates.user_id = graduates.login_id " ;
			}
			$sql_prefix = "WHERE " ;
			if($this->employerId!=0){
				$sql.= $sql_prefix."employer_id='".$this->employerId."' " ;
				$sql_prefix = "AND " ;
			}
			if($this->vacancyId!==0){
				$sql.= $sql_prefix."vacancy_id='".$this->vacancyId."' " ;
				$sql_prefix = "AND " ;
			}
			if($this->graduateId!=0){
				$sql.= $sql_prefix."user_id='".$this->graduateId."' " ;
				$sql_prefix = "AND " ;
			}
			if($is_count!=true){
				$sql.= "ORDER BY list_order ASC ;" ;
			}
			$result = mysql_query($sql) or die( mysql_error()) ;
			if($is_count==true){
				// close
				$conn->disconnect(DM_DB_NAME) ;
				$row = mysql_fetch_array($result) ;
				return $row['list_count'] ;
			} else {
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList, $row);
				}
				$conn->disconnect(DM_DB_NAME) ;
				return $this->dataList ;
			}
		}

		public function fixShortlistOrder($id,$number,$employerid){
			if($id!=''&&is_numeric($id)&&$number!=''&&is_numeric($number)&&$employerid!=''&&is_numeric($employerid)){
				$sql = "UPDATE vacancy_candidates SET list_order='".$number."' WHERE id='".$id."' AND employer_id='".$employerid."' ; " ;
				if(mysql_query($sql)){
					$returnvar = 'good' ;
				} else {
					$returnvar = mysql_error() ;
				}
			} else {
				$returnvar = 'ID or Order Number Error' ;
			}
			return $returnvar ;
		}

		public function moveShortlistGraduate($moveid,$oldpos,$dir,$employerid){
			if($dir=='up'){
				$new_order = $oldpos-1 ;
			} else {
				$new_order = $oldpos+1 ;
			}
			$sql_old = "UPDATE vacancy_candidates SET list_order='".$oldpos."' WHERE vacancy_id='".$this->vacancyId."' AND list_order='".$new_order."' AND employer_id='".$employerid."' ; " ;
			$sql_new = "UPDATE vacancy_candidates SET list_order='".$new_order."' WHERE id='".$moveid."' AND employer_id='".$employerid."' ; " ;
			mysql_query($sql_old) or die( mysql_error()) ;
			mysql_query($sql_new) or die( mysql_error()) ;
		}


		public function removeGraduateFromShortlist($removeid,$employerid){
			// return count if already exists
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "DELETE FROM vacancy_candidates WHERE vacancy_id='".$this->vacancyId."' AND id='".$removeid."' AND employer_id='".$employerid."' ; " ;
			if(mysql_query($sql)){
				$returnvar = 'good' ;
			} else {
				$returnvar = mysql_error() ;
			}
			// close and return
			$conn->disconnect(DM_DB_NAME) ;
			return $returnvar ;
		}


		// Getters / Setters

		public function setVacancyId($var){
			if($var!=''&&is_numeric($var)){
				$this->vacancyId = $var ;
			}
		}
		public function setEmployerId($var){
			if($var!=''&&is_numeric($var)){
				$this->employerId = $var ;
			}
		}
		public function setGraduateId($var){
			if($var!=''&&is_numeric($var)){
				$this->graduateId = $var ;
			}
		}

	}

?>
