<?php

	class affiliate_list {

		private $affiliateId = '' ;
		private $isEligible = false ;
		private $isPaid = false ;
		private $isRequested = false ;
		private $emailIsConfirmed = false ;
		private $dataList = array() ;
		private $orderBy = 'name' ;
		private $orderDir = 'ASC' ;
		private $paymentId = '' ;
		private $isDeclined = false ;
		private $groupList = 1 ;

		// functions
		public function getReferrals(){
			$this->dataList = array() ;
			if($this->affiliateId!=''||$this->isEligible!==false||$this->isPaid!==false||$this->emailIsConfirmed!==false){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// SQL
				$sql = "SELECT " ;
				$sql.= "affiliates_referrals.*, " ;
				$sql.= "login.email, " ;
				$sql.= "login.date_created, " ;
				$sql.= "graduates.first_name, " ;
				$sql.= "graduates.surname, " ;
				$sql.= "graduates.tel_mobile " ;
				// joins 
				$sql.= "FROM affiliates_referrals " ;
				$sql.= "LEFT JOIN login ON affiliates_referrals.user_id = login.id " ;
				$sql.= "LEFT JOIN graduates ON affiliates_referrals.user_id = graduates.login_id " ;
				$sql.= "LEFT JOIN affiliates_payments ON affiliates_referrals.payment_id = affiliates_payments.payment_id " ;
				// where
				$sqlWhere = "WHERE " ;
				// specific affiliate
				if($this->affiliateId!=''){
					$sql.= $sqlWhere."affiliate_id='".mysql_escape_string($this->affiliateId)."' " ;
					$sqlWhere = "AND " ;
				}
				// is eligible
				if($this->isEligible!==false){
					$sql.= $sqlWhere."is_eligible='".$this->isEligible."' " ;
					$sqlWhere = "AND " ;
				}
				// is requested
				if($this->isRequested!==false){
					$sql.= $sqlWhere."affiliates_referrals.payment_id".($this->isRequested===0 ? "=''" : "!=''")." " ;
					$sqlWhere = "AND " ;
				}
				// is paid
				if($this->isPaid!==false){
					if($this->isPaid==0){
						$sql.= "AND affiliates_payments.payment_date IS NULL " ;
					} else {
						$sql.= "AND affiliates_payments.payment_date IS NOT NULL " ;
					}
				}
				// email is confrimed
				if($this->emailIsConfirmed!==false){
					$sql.= $sqlWhere."login.email_is_confirmed='".$this->emailIsConfirmed."' " ;
					$sqlWhere = "AND " ;
				}
				// referral was declined
				if($this->isDeclined!==false){
					$sql.= $sqlWhere."affiliates_referrals.is_declined='".$this->isDeclined."' " ;
					$sqlWhere = "AND " ;
				}
				// order
				$sql.= "ORDER BY affiliates_referrals.payment_id ASC, graduates.first_name ASC, graduates.surname ASC, login.email ASC " ;
				//close it
				$sql.= "; " ;
				// echo '<p>'.$sql.'</p>' ;
				// do the thang
				$result = mysql_query($sql) or die( mysql_error()) ;
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList, $row);
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			}
			return $this->dataList ;
		}



		// get users
		public function getUsers(){
			$this->dataList = array() ;
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// SQL
				$sql = "SELECT " ;
				$sql.= "affiliates.*, " ;
				$sql.= "login.email, " ;
				$sql.= "login.date_created, " ;
				$sql.= "login.is_active, " ;
				$sql.= "login.user_level, " ;
				$sql.= "login.email_is_confirmed, " ;
				$sql.= "graduates.first_name, " ;
				$sql.= "graduates.surname, " ;
				$sql.= "graduates.tel_mobile, " ;
				$sql.= "(SELECT COUNT(0) FROM affiliates_referrals WHERE affiliates_referrals.affiliate_id = affiliates.affiliate_id AND is_declined='0') AS affiliates_count " ;
				$sql.= "FROM affiliates " ;
				$sql.= "LEFT JOIN login ON affiliates.user_id = login.id " ;
				$sql.= "LEFT JOIN graduates ON affiliates.user_id = graduates.login_id " ;
				$sql.= "ORDER BY " ;
				if($this->orderBy=='id'){
					$sql.= "affiliates.user_id ".$this->orderDir." " ;
				}elseif($this->orderBy=='name'){
					$sql.= "graduates.first_name ".$this->orderDir.", graduates.surname ".$this->orderDir." " ;
				}elseif($this->orderBy=='username'){
					$sql.= "affiliates.username ".$this->orderDir." " ;
				}elseif($this->orderBy=='referrals'){
					$sql.= "affiliates_count ".$this->orderDir." " ;
				}
				// echo '<p>'.$sql.'</p>' ;
				// do the thang
				$result = mysql_query($sql) or die( mysql_error()) ;
				while($row = mysql_fetch_array($result)){
					array_push($this->dataList, $row);
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			return $this->dataList ;
		}


		// get a list of payments pending or complete
		public function getTransactions(){
			$this->dataList = array() ;
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// SQL
			$sql = "SELECT " ;
			$sql.= "affiliates_payments.payment_id, " ;
			$sql.= "affiliates_payments.payment_amount, " ;
			$sql.= "affiliates_payments.payment_date, " ;
			$sql.= "affiliates_payments.request_date, " ;
			$sql.= "affiliates_payments.number_of_graduates, " ;
			$sql.= "affiliates_payments.referral_ids, " ;
			$sql.= "affiliates.username, " ;
			$sql.= "affiliates.payment_name, " ;
			$sql.= "affiliates.payment_address_1, " ;
			$sql.= "affiliates.payment_address_2, " ;
			$sql.= "affiliates.payment_address_town, " ;
			$sql.= "affiliates.payment_address_county, " ;
			$sql.= "affiliates.payment_address_postcode, " ;
			$sql.= "login.email, " ;
			$sql.= "login.date_created, " ;
			$sql.= "graduates.first_name, " ;
			$sql.= "graduates.surname, " ;
			$sql.= "graduates.tel_mobile, " ;
			$sql.= "affiliates_referrals.affiliate_id, " ;
			$sql.= "affiliates_referrals.user_id, " ;
			$sql.= "affiliates_referrals.is_eligible " ; // last 
			$sql.= "FROM affiliates_referrals " ;
			$sql.= "LEFT JOIN affiliates_payments ON affiliates_referrals.payment_id = affiliates_payments.payment_id " ;
			$sql.= "LEFT JOIN graduates ON affiliates_referrals.user_id = graduates.login_id " ;
			$sql.= "LEFT JOIN login ON affiliates_referrals.user_id = login.id " ;
			$sql.= "LEFT JOIN affiliates ON affiliates_referrals.affiliate_id = affiliates.affiliate_id " ;
			$sqlWhere = "WHERE " ;
			// affiliate ID
			if($this->affiliateId!=''){
				$sql.= $sqlWhere."affiliates_referrals.affiliate_id='".mysql_escape_string($this->affiliateId)."' " ;
				$sqlWhere = "AND " ;
			}
			// payment ID
			if($this->paymentId!=''){
				$sql.= $sqlWhere." affiliates_payments.payment_id = '".mysql_escape_string($this->paymentId)."' " ;
				$sqlWhere = "AND " ;
			} else {
				$sql.= $sqlWhere." affiliates_payments.payment_id != '' " ;
				$sqlWhere = "AND " ;
			}
			// is declined
			if($this->isDeclined==0){
				$sql.= $sqlWhere." affiliates_payments.is_declined = '0' " ;
				$sqlWhere = "AND " ;
			} elseif($this->isDeclined==1){
				$sql.= $sqlWhere." affiliates_payments.is_declined = '1' " ;
				$sqlWhere = "AND " ;
			}
			// is paid or not
			if($this->isPaid==0){
				$sql.= $sqlWhere." affiliates_payments.payment_date IS NULL " ;
				$sqlWhere = "AND " ;
			} elseif($this->isPaid==1){
				$sql.= $sqlWhere." affiliates_payments.payment_date IS NOT NULL " ;
				$sqlWhere = "AND " ;
			}
			// group
			if($this->groupList==1){
				$sql.= "GROUP BY affiliates_referrals.payment_id " ;
			}
			$sql.= "ORDER BY " ;
			$sql.= "affiliates_payments.payment_date ASC, " ;
			$sql.= "affiliates_payments.payment_id ASC, " ;
			$sql.= "affiliates_payments.request_date ASC, " ;
			$sql.= "graduates.first_name ASC " ;
			$sql.= "; " ;
			// echo '<p>'.$sql.'</p>' ;
			// do the thang
			$result = mysql_query($sql) or die( mysql_error()) ;
			while($row = mysql_fetch_array($result)){
				// echo $row['first_name']."<br />" ;
				array_push($this->dataList, $row);
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $this->dataList ;
		}


		// list of payments, either requested or filled
		public function getPayments(){
			$this->dataList = array() ;
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// SQL
			$sql = "SELECT " ;
			$sql.= "affiliates_payments.*, " ;
			$sql.= "affiliates_referrals.user_id, " ;
			$sql.= "affiliates_referrals.affiliate_id, " ;
			$sql.= "affiliates.payment_name, " ;
			$sql.= "affiliates.user_id AS affiliate_user_id, " ;
			//$sql.= "graduates.first_name, " ;
			//$sql.= "graduates.surname, " ;
			$sql.= "login.email " ;
			$sql.= "FROM affiliates_payments " ;
			$sql.= "LEFT JOIN affiliates_referrals ON affiliates_payments.payment_id = affiliates_referrals.payment_id " ;
			$sql.= "LEFT JOIN affiliates ON affiliates_referrals.affiliate_id = affiliates.affiliate_id " ;
			//$sql.= "LEFT JOIN graduates ON affiliates.user_id = graduates.login_id " ;
			$sql.= "LEFT JOIN login ON affiliates_referrals.user_id = login.id " ;
			if($this->isPaid==0){
				$sql.= "WHERE payment_date IS NULL " ;
			} else {
				$sql.= "WHERE payment_date IS NOT NULL " ;
			}
			// referral was declined
			if($this->isDeclined!==false){
				$sql.= "AND affiliates_referrals.is_declined='".$this->isDeclined."' " ;
			}
			$sql.= "GROUP BY affiliates_payments.payment_id " ;
			$sql.= "ORDER BY request_date ASC ;" ;
			// echo '<p>'.$sql.'</p>' ;
			// do the thang
			$result = mysql_query($sql) or die( mysql_error()) ;
			while($row = mysql_fetch_array($result)){
				// echo $row['first_name']."<br />" ;
				array_push($this->dataList, $row);
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $this->dataList ;
		}



		// get payment request details for summary
		public function getPaymentDetails(){
			$this->dataList = array() ;
			$returnVar = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT " ;
			$sql.= "affiliates_payments.*, " ;
			$sql.= "affiliates_referrals.referral_id, " ;
			$sql.= "affiliates_referrals.affiliate_id, " ;
			$sql.= "affiliates_referrals.user_id, " ;
			$sql.= "affiliates_referrals.is_declined, " ;
			$sql.= "login.email, " ;
			$sql.= "login.date_created, " ;
			$sql.= "graduates.first_name, " ;
			$sql.= "graduates.surname " ;
			$sql.= "FROM affiliates_payments " ;
			$sql.= "LEFT JOIN affiliates_referrals ON affiliates_payments.payment_id = affiliates_referrals.payment_id " ;
			$sql.= "LEFT JOIN login ON affiliates_referrals.user_id = login.id " ;
			$sql.= "LEFT JOIN graduates ON affiliates_referrals.user_id = graduates.login_id " ;
			$sql.= "WHERE affiliates_payments.payment_id = '".mysql_escape_string($this->paymentId)."' " ;
			$sql.= "; " ;
			// echo '<p>'.$sql.'</p>' ;
			// do the thang
			$result = mysql_query($sql) or die( mysql_error()) ;
			while($row = mysql_fetch_array($result)){
				// echo $row['first_name']."<br />" ;
				array_push($this->dataList, $row);
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $this->dataList ;
		}


		// mark a request as paid
		public function markPaid(){
			$returnVar = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "UPDATE affiliates_payments " ;
			$sql.= "SET payment_date = '".date(DM_PHP_DATE_FORMAT)."' " ;
			$sql.= "WHERE payment_id = '".mysql_escape_string($this->paymentId)."' AND payment_date IS NULL ; " ;
			if(mysql_query($sql) or die( mysql_error())){
				$returnVar = true ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}


		// decline a request
		public function decline(){
			$returnVar = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// mark the payment it'self as declined
			$sql = "UPDATE affiliates_payments " ;
			$sql.= "SET is_declined = '1' " ;
			$sql.= "WHERE payment_id = '".mysql_escape_string($this->paymentId)."' " ;
			//echo '<p>'.$sql.'</p>' ;
			if(mysql_query($sql)){
				// remove the payment id from the referrals
				$sql = "UPDATE affiliates_referrals " ;
				$sql.= "SET payment_id = '' " ;
				$sql.= "WHERE payment_id = '".mysql_escape_string($this->paymentId)."' " ;
				// echo '<p>'.$sql.'</p>' ;
				if(mysql_query($sql)){
					$returnVar = true ;
				}
			} else {
				// undo the previous SQL
				$sql = "UPDATE affiliates_payments " ;
				$sql.= "SET is_declined = '0' " ;
				$sql.= "WHERE payment_id = '".mysql_escape_string($this->paymentId)."' " ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}



		// getters setters
		public function setAffiliateId($var){
			$this->affiliateId = $var ;
		}
		public function setIsEligible($var){
			if($var==1||$var===true){
				$this->isEligible = 1 ;
			} elseif($var===0||$var===false){
				$this->isEligible = 0 ;
			} else {
				$this->isEligible = false ;
			}
		}
		public function setIsPaid($var){
			if($var===1||$var===true){
				$this->isPaid = 1 ;
			} elseif($var===0||$var===false){
				$this->isPaid = 0 ;
			} else {
				$this->isPaid = false ;
			}
		}
		public function setIsRequested($var){
			if($var===1||$var===true){
				$this->isRequested = 1 ;
			} elseif($var===0||$var===false){
				$this->isRequested = 0 ;
			} else {
				$this->isRequested = false ;
			}
		}
		public function setEmailIsConfirmed($var){
			if($var===1||$var===true){
				$this->emailIsConfirmed = 1 ;
			} elseif($var===0||$var===false){
				$this->emailIsConfirmed = 0 ;
			} else {
				$this->emailIsConfirmed = false ;
			}
		}
		public function setIsDeclined($var){
			if($var===1||$var===true){
				$this->isDeclined = 1 ;
			} elseif($var===0||$var===false){
				$this->isDeclined = 0 ;
			} else {
				$this->isDeclined = false ;
			}
		}
		public function setGroupList($var){
			if($var===0||$var===false){
				$this->groupList = 0 ;
			} else {
				$this->groupList = 1 ;
			}
		}
		public function setOrderBy($var){
			if($var=='id'||$var=='username'||$var=='name'||$var=='referrals'){
				$this->orderBy = strtolower($var) ;
			}
		}
		public function setOrderDir($var){
			if($var=='asc'||$var=='desc'){
				$this->orderDir = strtolower($var) ;
			}
		}
		public function setPaymentId($var){
			$this->paymentId = $var ;
		}
	}

?>