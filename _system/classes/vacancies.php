<?php

	class vacancies {

		private $vacancyId = 0 ;
		private $employerId = 0 ;
		private $userId = 0 ;

		// Add a Vacancy
		public function addVacancy($vacancy_name,$vacancy_icon){
			$returnval = false ;
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Get any files waiting for confirmation
			$sql = "SELECT COUNT(1) as list_count FROM vacancies WHERE name='".mysql_escape_string($vacancy_name)."' AND employer_id='".mysql_escape_string($this->employerId)."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// add if possible
			if($row['list_count']<1){
				// Get any files waiting for confirmation
				$sql = "SELECT id FROM vacancies WHERE employer_id='".$this->employerId."' ; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				// Add the vacancy
				$sql = "INSERT INTO vacancies(name,list_order,employer_id,date_added) VALUES('".mysql_escape_string($vacancy_name)."','".(mysql_num_rows($result)+1)."','".$this->employerId."','".date(DM_PHP_DATE_FORMAT)."') ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				$returnval = true ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// Return or add
			return $returnval ;
		}

		// Add a Vacancy
		public function editVacancy($id,$vacancy_name,$vacancy_icon){
			$returnval = false ;
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Get any files waiting for confirmation
			$sql = "SELECT id FROM vacancies WHERE id='".mysql_escape_string($id)."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			// add if possible
			if(mysql_num_rows($result)>0){
				// Add the vacancy
				$sql = "UPDATE vacancies SET name='".mysql_escape_string($vacancy_name)."' WHERE id='".$id."' AND employer_id = '".$this->employerId."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				$returnval = true ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// Return or add
			return $returnval ;
		}

		// List of current Vacancies
		public function getVacancy($id){
			if(is_numeric($id)){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// Get any files waiting for confirmation
				$sql = "SELECT id,name,list_order,employer_id,date_added FROM vacancies WHERE id='".mysql_escape_string($id)."' AND employer_id='".$this->employerId."' ; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				// close
				$conn->disconnect(DM_DB_NAME) ;
				// Return or add
				return $row ;
			} else {
				return false ;
			}
		}

		// Delete Vacancies
		public function deleteVacancy($employerId,$vacancyId){
			if(is_numeric($vacancyId)){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// Get any files waiting for confirmation
				$sql = "DELETE FROM vacancies WHERE id='".mysql_escape_string($vacancyId)."' AND employer_id='".mysql_escape_string($employerId)."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				$sql = "DELETE FROM vacancy_candidates WHERE vacancy_id='".mysql_escape_string($vacancyId)."' AND employer_id='".mysql_escape_string($employerId)."' ; " ;
				mysql_query($sql) or die( mysql_error()) ;
				// close
				$conn->disconnect(DM_DB_NAME) ;
				// Return or add
				return true ;
			}
		}

		// List of current Vacancies
		public function getVacancyList($is_count=false){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Get the list
			$sql = "SELECT id , name, list_order, employer_id, date_added FROM vacancies WHERE employer_id='".$this->employerId."' ORDER BY name ASC ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			// Return val
			$returnval = array() ;
			while($row = mysql_fetch_array($result)){
				$returnval[] = $row ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// Return or add
			return $returnval ;
		}



		// Getters & Setters
		public function setVacancyId($var){
			if(is_numeric($var)){
				$this->vacancyId = $var ;
			} else {
				$this->vacancyId = 0 ;
			}
		}
		public function setEmployerId($var){
			if(is_numeric($var)){
				$this->employerId = $var ;
			} else {
				$this->employerId = 0 ;
			}
		}
		public function setUserId($var){
			if(is_numeric($var)){
				$this->userId = $var ;
			} else {
				$this->userId = 0 ;
			}
		}

	}

?>