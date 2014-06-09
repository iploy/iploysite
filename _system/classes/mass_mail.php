<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/classes/connection.php') ;

	class mass_mail {

		// function for fetching the graduates
		public function getGraduates($values){
			$returnVal = array() ;
			$returnVal['id_string'] = '' ;
			$returnVal['found_count'] = 0 ;
			$returnVal['total_count'] = 0 ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// build the query
			$sql = "SELECT " ;
			$sql.= "graduates.login_id " ;
			$sql.= "FROM graduates " ;
			$sql.= "INNER JOIN login ON login.id = graduates.login_id " ;
			$sql.= "WHERE " ;
			$sqlWhere = "" ;
			// graduate_files
			$filesTypes = array('photo','cv','certificate') ;
			foreach($filesTypes as $filesType){
				if(sizeof($values['graduate_files_'.$filesType])>0){
					if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
					$sqlWhere.= "(" ;
					foreach($values['graduate_files_'.$filesType] as $i => $value){
						if($i>0){ $sqlWhere.= " OR " ; }
						$sqlWhere.= "graduates.has_".$filesType."='".$value."'" ;
					}
					$sqlWhere.= ")" ;
				}
			}
			// current location
			if(sizeof($values['graduate_current_location'])>0){
				if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
				$sqlWhere.= "(" ;
				foreach($values['graduate_current_location'] as $i => $value){
					if($i>0){ $sqlWhere.= " OR " ; }
					$sqlWhere.= "graduates.education_location='".$value."'" ;
				}
				$sqlWhere.= ")" ;
			}
			// desired location
			if(sizeof($values['graduate_desired_location'])>0){
				if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
				$sqlWhere.= "(" ;
				foreach($values['graduate_desired_location'] as $i => $value){
					if($i>0){ $sqlWhere.= " OR " ; }
					$sqlWhere.= "graduates.emploment_location LIKE '%".$value."%'" ;
				}
				$sqlWhere.= ")" ;
			}
			// subject studied
			if(sizeof($values['graduate_subject_studied'])>0){
				if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
				$sqlWhere.= "(" ;
				foreach($values['graduate_subject_studied'] as $i => $value){
					if($i>0){ $sqlWhere.= " OR " ; }
					$sqlWhere.= "graduates.subject='".$value."'" ;
				}
				$sqlWhere.= ")" ;
			}
			// job category
			if(sizeof($values['graduate_job_category'])>0){
				if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
				$sqlWhere.= "(" ;
				foreach($values['graduate_job_category'] as $i => $value){
					if($i>0){ $sqlWhere.= " OR " ; }
					$sqlWhere.= "graduates.job_category LIKE '%".$value."%'" ;
				}
				$sqlWhere.= ")" ;
			}
			// education level
			if(sizeof($values['graduate_education_level'])>0){
				if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
				$sqlWhere.= "(" ;
				foreach($values['graduate_education_level'] as $i => $value){
					if($i>0){ $sqlWhere.= " OR " ; }
					$sqlWhere.= "graduates.education_level='".$value."'" ;
				}
				$sqlWhere.= ")" ;
			}
			// education level
			if(sizeof($values['graduate_education_grade'])>0){
				if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
				$sqlWhere.= "(" ;
				foreach($values['graduate_education_grade'] as $i => $value){
					if($i>0){ $sqlWhere.= " OR " ; }
					$sqlWhere.= "graduates.education_grade='".$value."'" ;
				}
				$sqlWhere.= ")" ;
			}

			// add a clause to ensure only confirmed accounts are included
			if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
			$sqlWhere.= "login.email_is_confirmed='1' ; " ;
			//echo '<p class="notice" >'.$sql.$sqlWhere.'</p>' ;

			$result = mysql_query($sql.$sqlWhere) or die( mysql_error()) ;
			$returnVal['found_count'] = mysql_num_rows($result) ;
			while($row = mysql_fetch_array($result)){
				if($returnVal['id_string']!=''){ $returnVal['id_string'].= ',' ; }
				$returnVal['id_string'].= $row['login_id'] ;
			}
			// echo '<p class="notice" >'.$returnVal['id_string'].'</p>' ;

			// get the full graduate count if there are results
			$sql = "SELECT COUNT(1) as total_count FROM graduates INNER JOIN login ON login.id = graduates.login_id WHERE login.email_is_confirmed='1' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$returnVal['total_count'] = $row['total_count'] ;

			// Close
			$conn->disconnect(DM_DB_NAME) ;
			return $returnVal ;
		}


		public function getEmployers($values){
			$returnVal = array() ;
			$returnVal['id_string'] = '' ;
			$returnVal['found_count'] = 0 ;
			$returnVal['total_count'] = 0 ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// build the query
			$sql = "SELECT " ;
			$sql.= "employers.user_id " ;
			$sql.= "FROM employers " ;
			$sql.= "INNER JOIN login ON login.id = employers.user_id " ;
			$sql.= "WHERE " ;
			$sqlWhere = "" ;
			// industry sector
			if(sizeof($values['employer_industry_sector'])>0){
				if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
				$sqlWhere.= "(" ;
				foreach($values['employer_industry_sector'] as $i => $value){
					if($i>0){ $sqlWhere.= " OR " ; }
					$sqlWhere.= "employers.industry_sector LIKE '%".$value."%'" ;
				}
				$sqlWhere.= ")" ;
			}
			// add a clause to ensure only confirmed accounts are included
			if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
			$sqlWhere.= "login.email_is_confirmed='1' ; " ;
			// echo '<p class="notice" >'.$sql.$sqlWhere.'</p>' ;
			$result = mysql_query($sql.$sqlWhere) or die( mysql_error()) ;
			$returnVal['found_count'] = mysql_num_rows($result) ;
			while($row = mysql_fetch_array($result)){
				if($returnVal['id_string']!=''){ $returnVal['id_string'].= ',' ; }
				$returnVal['id_string'].= $row['user_id'] ;
			}
			// get the full employer count if there are results
			$sql = "SELECT COUNT(1) as total_count FROM employers INNER JOIN login ON login.id = employers.user_id WHERE login.email_is_confirmed='1' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$returnVal['total_count'] = $row['total_count'] ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			return $returnVal ;
		}

		public function getAdmins($values){
			$returnVal = array() ;
			$returnVal['id_string'] = '' ;
			$returnVal['found_count'] = 0 ;
			$returnVal['total_count'] = 0 ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// build the query
			$sql = "SELECT " ;
			$sql.= "id " ;
			$sql.= "FROM login " ;
			$sql.= "WHERE " ;
			// clauses
			$sqlWhere = "" ;
			// add a clause to ensure only confirmed accounts are included
			if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
			$sqlWhere.= "user_level='3' AND email_is_confirmed='1' ; " ;
			// echo '<p class="notice" >'.$sql.$sqlWhere.'</p>' ;
			$result = mysql_query($sql.$sqlWhere) or die( mysql_error()) ;
			$returnVal['found_count'] = mysql_num_rows($result) ;
			while($row = mysql_fetch_array($result)){
				if($returnVal['id_string']!=''){ $returnVal['id_string'].= ',' ; }
				$returnVal['id_string'].= $row['id'] ;
			}
			// get the full employer count if there are results
			$sql = "SELECT COUNT(1) as total_count FROM login WHERE user_level='3' AND email_is_confirmed='1' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$returnVal['total_count'] = $row['total_count'] ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			return $returnVal ;
		}

		public function getSu($values){
			$returnVal = array() ;
			$returnVal['id_string'] = '' ;
			$returnVal['found_count'] = 0 ;
			$returnVal['total_count'] = 0 ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// build the query
			$sql = "SELECT " ;
			$sql.= "id " ;
			$sql.= "FROM login " ;
			$sql.= "WHERE " ;
			// clauses
			$sqlWhere = "" ;
			// add a clause to ensure only confirmed accounts are included
			if($sqlWhere!=""){ $sqlWhere.= " AND " ; }
			$sqlWhere.= "user_level='0' AND email_is_confirmed='1' ; " ;
			// echo '<p class="notice" >'.$sql.$sqlWhere.'</p>' ;
			$result = mysql_query($sql.$sqlWhere) or die( mysql_error()) ;
			$returnVal['found_count'] = mysql_num_rows($result) ;
			while($row = mysql_fetch_array($result)){
				if($returnVal['id_string']!=''){ $returnVal['id_string'].= ',' ; }
				$returnVal['id_string'].= $row['id'] ;
			}
			// get the full employer count if there are results
			$sql = "SELECT COUNT(1) as total_count FROM login WHERE user_level='0' AND email_is_confirmed='1' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$returnVal['total_count'] = $row['total_count'] ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			return $returnVal ;
		}


		function getData($type,$values){
			if($type=='graduate'){
				return $this->getGraduates($values) ;
			}elseif($type=='employer'){
				return $this->getEmployers($values) ;
			}elseif($type=='admin'){
				return $this->getAdmins($values) ;
			}elseif($type=='superuser'){
				return $this->getSu($values) ;
			}
		}

	}

?>