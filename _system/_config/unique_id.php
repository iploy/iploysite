<?php

	include_once(SITE_PATH.'_system/classes/random_string.php') ;

	class uniqueId{

		private $idLength = '' ;
		private $tableName = '' ;
		private $columnName = '' ;
		private $uniqueId = '' ;

		public function go(){
			$returnVar = false ;
			// ceck the variables have been set
			if($this->idLength!=''&&$this->tableName!=''&&$this->columnName!=''){
				// generate unique user id
				$random_string = new random_string ;
				$random_string->setUseUpperCase(false) ;
				$random_string->stringLength($this->idLength) ;
				// generate the random string and check it can be used, keep looping until a unique ID is found then continue
				$foundUniqueId = false ;
				while($foundUniqueId==false){
					$this->setUniqueId($random_string->generate()) ;
					$sql = "SELECT COUNT(1) as record_count FROM ".mysql_escape_string($this->tableName)." WHERE ".mysql_escape_string($this->columnName)."='".mysql_escape_string($this->clientId)."' ;" ;
					$result = mysql_query($sql) or die ( mysql_error() ) ;
					$row = mysql_fetch_array($result) ;
					if($row['record_count']<1){
						$foundUniqueId = true ;
					}
				}
				$returnVar = $this->uniqueId ;
			} else {
				echo '<p class="error" >You have not set all the variables for the unique ID class</p>' ;
			}
			return $returnVar ;
		}

		// table name
		public function setUniqueId($var){
			$this->uniqueId = $var ;
		}
		// table name
		public function setTableName($var){
			$this->tableName = $var ;
		}
		// column name
		public function setColumnName($var){
			$this->columnName = $var ;
		}
		// column name
		public function setIdLength($var){
			$this->idLength = $var ;
		}

	}


?>