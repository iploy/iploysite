<?php

	include_once('_system/_config/configure.php') ;

	class ajaxSearchComm {
		// define variables
		private $column = '' ;
		private $value = '' ;
		private $type = 'mid' ;
		private $dataList = array() ;
		// the lister function
		public function getList(){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// do the check
			if($this->column=='education_degree_title'||$this->column=='education_institution'){
				$sql = "SELECT DISTINCT ".$this->column." FROM graduates WHERE " ;
				$sql.= $this->column." LIKE '".($this->type=='mid' ? '%' : '').mysql_escape_string($this->value)."%' ORDER BY ".$this->column." ASC ;" ;
			}
			$result = mysql_query($sql) or die( mysql_error()) ;
			while($row = mysql_fetch_array($result)){
				array_push($this->dataList, $row[$this->column]);
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			return $this->dataList ;
		}
		// getters setters
		public function setColumn($var){
			if($var!=''){
				$this->column = $var ;
			}
		}
		public function setValue($var){
			if($var!=''){
				$this->value = $var ;
			}
		}
		public function setType($var){
			if($var!=''){
				$this->type = $var ;
			}
		}
	}

	if(strstr($_SERVER['HTTP_REFERER'],SITE_DOMAIN.'search.php')){
		// setup the class
		$results = new ajaxSearchComm ;
		$results->setColumn($_POST['fldName']) ;
		$results->setValue($_POST['fldValue']) ;
		// $results->setType('start') ;
		$results = $results->getList() ;
		// loop and draw
		for($i=0;$i<sizeof($results);$i++){
			echo ($i>0 ? $_POST['aacDivider'] : '').$results[$i] ;
		}
		exit() ;
	} else {
		header('Location: '.SITE_DOMAIN) ;
		exit() ;
	}

?>