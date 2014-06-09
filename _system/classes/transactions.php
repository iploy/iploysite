<?php
	include_once('_system/classes/graduate_frontend.php') ;

	class transactions {

		private $dataList = array() ;

		public function generateRequestId($employerId,$graduateId){
			return md5($employerId.'-'.$_SESSION['APP_CLIENT_NAME'].'-'.$graduateId) ;
		}

		// Grant employer access to a profile
		public function grantEmployerToGraduateAccess($employerId,$graduateId,$confirm_string){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Check the DB
			$sql = "INSERT INTO employer_credit_to_graduate(employer_id,user_id,date_purchased) VALUES('".$employerId."','".$graduateId."','".date(DM_PHP_DATE_FORMAT)."') ;" ;
			if(mysql_query($sql) or die( mysql_error())){
				$gdf = new graduate_data ;
				$gd = $gdf->getGraduateByID($_GET['profileid']) ;
				$product_name = trim('Graduate Profile: '.ucwords($gd['first_name']).' '.ucwords($gd['surname'])) ;
				$sql = "INSERT INTO transaction_history(user_id,transaction_date,product_name,product_price,product_price_in_credits,vendortxcode,txauthno,vpstxid,billing_address_id,graduate_id) VALUES('".$employerId."','".date(DM_PHP_DATE_FORMAT)."','".$product_name."','0','1','".$confirm_string."','n/a','n/a','0','".$_GET['profileid']."') ;" ;
				mysql_query($sql) or die( mysql_error()) ;
				$returnval = true ;
			} else {
				$returnval= false ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnval ;
		}

		// Check that an employer has access to a profile
		public function checkEmployerToGraduateAccess($employerId,$graduateId){
			// if the IDs are non-numeric, return false
			$returnval = false ;
			if(is_numeric($employerId)&&$employerId!=''&&is_numeric($graduateId)&&$graduateId!=''){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// Check the DB
				$sql = "SELECT COUNT(1) as has_access FROM employer_credit_to_graduate WHERE employer_id=".$employerId." AND user_id=".$graduateId." ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				// If the row does not exist return false.
				if($row['has_access']>0){
					$returnval = true ;
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $returnval ;
		}

		// get transaction history
		public function getTransactionHistory($userId,$isCount=false,$pageOffest=0,$pageSize=0,$orderBy='',$orderDir=''){
			// if the IDs are non-numeric, return false
			$returnval = false ;
			// switches
			$orderByVal = 'transaction_date' ;
			$orderDirVal = 'DESC' ;
			switch($orderBy){
				case 'date' :
					$orderByVal = 'transaction_date' ;
					$orderDirVal = 'DESC' ;
				break ;
				case 'prodname' :
					$orderByVal = 'product_name' ;
					$orderDirVal = 'ASC' ;
				break ;
			}
			// order dir if not default
			if(strtolower($orderDir)=='desc'){
				$orderDirVal = 'DESC' ;
			} elseif($orderDir!=''&&strtolower($orderDir)!=strtolower($orderDirVal)){
				$orderDirVal = 'ASC' ;
			}
			// get list
			if(is_numeric($userId)&&$userId!=''&&((is_numeric($pageOffest)&&$pageOffest>-1&&is_numeric($pageSize)&&$pageSize>-1)||$isCount==true)){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// sql
				$sql = "SELECT " ;
				// join if not count
				if($isCount!=true){
					$sql.= "* FROM transaction_history LEFT JOIN address_book ON transaction_history.billing_address_id = address_book.id " ;
				} else {
					$sql.= "COUNT(1) AS transactions_total FROM transaction_history " ;
				}
				$sql.= "WHERE transaction_history.user_id = '".$userId."' " ;
				// limit if not count
				if($isCount!=true){
					$sql.= "ORDER BY ".$orderByVal."+0 ".$orderDirVal." " ;
					$sql.= "LIMIT ".($pageOffest*$pageSize).",".$pageSize." " ;
				}
				$sql.= ";" ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				if($isCount!=true){
					//loop
					while($row = mysql_fetch_array($result)){
						array_push($this->dataList, $row);
					}
				} else {
					$row = mysql_fetch_array($result) ;
					$returnval = $row['transactions_total'] ;
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			}
			if($isCount==true){
				return $returnval ;
			} else {
				return $this->dataList ;
			}
		}

		// transaction summary
		public function getSummary($reportType='credits',$listType='basic',$dateBottom=false,$dateTop=false){
			$this->dataList = array() ;
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql for credit purchases
			$sql = "SELECT " ;
			if($listType!='count'){
				$sql.= "th.product_name, th.product_price " ;
				if($listType=='detailed'){
					$sql.= ", th.transaction_date, th.product_price_in_credits, th.user_id, th.graduate_id" ;
					$sql.= ", employers.company_name " ;
				}
			} else {
				$sql.= " COUNT(1) AS count_total " ;
			}
			$sql.= "FROM transaction_history AS th " ;
			$sql.= "LEFT JOIN login ON th.user_id = login.id " ;
			if($listType=='detailed'){
				$sql.= "LEFT JOIN employers ON th.user_id = employers.user_id " ;
			}
			$sql.= "WHERE " ;
			if(!strstr($_SESSION['email'],'@devmac.co.uk')){
				$sql.= "login.email NOT LIKE '%@iploy.co.uk%' AND login.email NOT LIKE '%@devmac.co.uk%' AND " ;
			}
			$sql.= "product_name LIKE '%" ;
			if($reportType=='credits'){
				$sql.= "Employer Credit" ;
			} else {
				$sql.= "Graduate Profile:" ;
			}
			$sql.= "%' " ;
			if($dateBottom!=false&&$dateBottom!=''){
				$sql.= "AND transaction_date >= '".mysql_escape_string($dateBottom)."' " ;
			}
			if($dateTop!=false&&$dateTop!=''){
				$sql.= "AND transaction_date <= '".mysql_escape_string($dateTop)."' " ;
			}
			if($listType=='detailed'){
				$sql.= "ORDER BY transaction_date ASC " ;
			}
			$sql.= "; " ;
			// echo '<p>'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if($listType!='count'){
				//loop
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList, $row);
				}
				$returnval = $this->dataList ;
			} else {
				$row = mysql_fetch_array($result) ;
				$returnval = $row['count_total'] ;
			}
			$result = mysql_query($sql) or die( mysql_error()) ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnval ;
		}

	}

?>
