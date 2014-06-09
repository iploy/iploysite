<?php

	class shortlist_frontend {

		private $employerId = 0 ;
		private $graduateId = 0 ;
		private $dataList = array() ;

		public function isGraduateShortlisted(){
			$return_var = false ;
			// return count if already exists
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			$sql = "SELECT id FROM vacancy_candidates WHERE employer_id='".$this->employerId."' AND user_id='".$this->graduateId."' LIMIT 0,1 ;" ;
			// echo '<p>'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)>0){
				$return_var = true ;
			}
			$conn->disconnect(DM_DB_NAME) ;
			return $return_var ;
		}

		public function getShortlistsList(){
			$this->dataList = array() ;
			$return_var = false ;
			// return count if already exists
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// AT THIS POINT I NEED TO CHECK IF THE USER IS IN THE WISHLIST
			$sql = "SELECT id FROM vacancy_candidates WHERE employer_id='".$this->employerId."' AND user_id='".$this->graduateId."' AND vacancy_id='0' LIMIT 0,1 ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)>0){
				$row = array() ;
				$row['vacancy_id'] = 0 ;
				$row['name'] = 'Wishlist' ;
				array_push($this->dataList, $row);
			}
			// Now do the vacancies
			$sql = "SELECT vacancy_candidates.id, vacancy_candidates.vacancy_id, vacancies.name, vacancies.icon " ;
			$sql.= "FROM vacancy_candidates " ;
			$sql.= "INNER JOIN vacancies ON vacancy_candidates.vacancy_id = vacancies.id " ;
			$sql.= "WHERE vacancy_candidates.employer_id='".$this->employerId."' AND vacancy_candidates.user_id='".$this->graduateId."' " ;
			$sql.= "ORDER BY vacancies.list_order ASC ;" ;
			// echo '<p>'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			while($row = mysql_fetch_array($result)){
				array_push($this->dataList, $row);
			}

			$conn->disconnect(DM_DB_NAME) ;
			return $this->dataList ;
		}


		// GETTERS / SETTERS

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
