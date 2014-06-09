<?php
	include_once(SITE_PATH.'_system/classes/unique_id.php') ;

	class affiliate {

		private $affiliateId = '' ;
		private $affiliateUserId = '' ;
		private $affiliateUserName = '' ;
		private $paymentId = '' ;
		private $referralId = '' ;

		// functions
		public function getaffiliate($setSessions=false){
			$returnVar = '' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT " ;
			$sql.= "affiliates.*, " ;
			$sql.= "login.email, " ;
			$sql.= "login.user_level " ;
			$sql.= "FROM affiliates " ;
			$sql.= "LEFT JOIN login ON affiliates.user_id = login.id " ;
			$sqlWhere = "WHERE " ;
			if($this->affiliateId!=''){
				$sql.= $sqlWhere."affiliate_id='".$this->affiliateId."' " ;
				$sqlWhere = "AND " ;
			}
			if($this->affiliateUserId!=''){
				$sql.= $sqlWhere."user_id='".$this->affiliateUserId."' " ;
				$sqlWhere = "AND " ;
			}
			if($this->affiliateUserName!=''){
				$sql.= $sqlWhere."username='".$this->affiliateUserName."' " ;
				$sqlWhere = "AND " ;
			}
			$sql.= "; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$returnVar = mysql_fetch_array($result) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// set sessions
			if($setSessions==true){
				$_SESSION['affiliate_id'] = $returnVar['affiliate_id'] ;
				$_SESSION['affiliate_username'] = $returnVar['username'] ;
				$_SESSION['affiliate_signup_date'] = $returnVar['signup_date'] ;
				$_SESSION['affiliate_payment_name'] = $returnVar['payment_name'] ;
				$_SESSION['affiliate_payment_address_1'] = $returnVar['payment_address_1'] ;
				$_SESSION['affiliate_payment_address_2'] = $returnVar['payment_address_2'] ;
				$_SESSION['affiliate_payment_address_town'] = $returnVar['payment_address_town'] ;
				$_SESSION['affiliate_payment_address_county'] = $returnVar['payment_address_county'] ;
				$_SESSION['affiliate_payment_address_postcode'] = $returnVar['payment_address_postcode'] ;
			}
			// return
			return $returnVar ;
		}



		public function usernameIsUnique($username,$current=''){
			$returnVar = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT COUNT(1) as record_count FROM affiliates WHERE username='".mysql_escape_string($username)."' ".($current!='' ? "AND username!='".$current."'" : "" )." ;" ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			if($row['record_count']==0){
				$returnVar = true ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}

		// signup affiliate
		public function signup($setSessions=false){
			$returnVar = '' ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// get a unique ID
			$uniqueId = new uniqueId ;
			$uniqueId->setTableName('affiliates') ;
			$uniqueId->setColumnName('affiliate_id') ;
			$uniqueId->setIdLength(15) ;
			$this->affiliateId = $uniqueId->go() ;
			// sql
			$sql = "INSERT INTO affiliates SET " ;
			$sql.= "affiliate_id='".mysql_escape_string($this->affiliateId)."', " ;
			$sql.= "user_id='".mysql_escape_string($this->affiliateUserId)."', " ;
			$sql.= "username='".mysql_escape_string(strtolower($_POST['username']))."', " ;
			$sql.= "signup_date='".date(DM_PHP_DATE_FORMAT)."', " ;
			$sql.= "payment_name='".mysql_escape_string($_POST['payment_name'])."', " ;
			$sql.= "payment_address_1='".mysql_escape_string($_POST['payment_address_1'])."', " ;
			$sql.= "payment_address_2='".mysql_escape_string($_POST['payment_address_2'])."', " ;
			$sql.= "payment_address_town='".mysql_escape_string($_POST['payment_address_town'])."', " ;
			$sql.= "payment_address_county='".mysql_escape_string($_POST['payment_address_county'])."', " ;
			$sql.= "payment_address_postcode='".mysql_escape_string($_POST['payment_address_postcode'])."' " ;
			$sql.= " ;" ;
			// echo '<p>'.$sql.'</p>' ;
			if(mysql_query($sql)){
				$returnVar = $this->affiliateId ;
				// set sessions on request
				if($setSessions==true){
					$_SESSION['affiliate_id'] = $this->affiliateId ;
					$_SESSION['affiliate_username'] = $_POST['username'] ;
					$_SESSION['affiliate_signup_date'] = $_POST['signup_date'] ;
					$_SESSION['affiliate_payment_name'] = $_POST['payment_name'] ;
					$_SESSION['affiliate_payment_address_1'] = $_POST['payment_address_1'] ;
					$_SESSION['affiliate_payment_address_2'] = $_POST['payment_address_2'] ;
					$_SESSION['affiliate_payment_address_town'] = $_POST['payment_address_town'] ;
					$_SESSION['affiliate_payment_address_county'] = $_POST['payment_address_county'] ;
					$_SESSION['affiliate_payment_address_postcode'] = $_POST['payment_address_postcode'] ;
				}
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}

 		public function updateAffiliate($setSessions=false){
			$returnVar = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "UPDATE affiliates SET " ;
			$sql.= "username='".mysql_escape_string($_POST['username'])."', " ;
			$sql.= "signup_date='".date(DM_PHP_DATE_FORMAT)."', " ;
			$sql.= "payment_name='".mysql_escape_string($_POST['payment_name'])."', " ;
			$sql.= "payment_address_1='".mysql_escape_string($_POST['payment_address_1'])."', " ;
			$sql.= "payment_address_2='".mysql_escape_string($_POST['payment_address_2'])."', " ;
			$sql.= "payment_address_town='".mysql_escape_string($_POST['payment_address_town'])."', " ;
			$sql.= "payment_address_county='".mysql_escape_string($_POST['payment_address_county'])."', " ;
			$sql.= "payment_address_postcode='".mysql_escape_string($_POST['payment_address_postcode'])."' " ;
			$sql.= "WHERE user_id='".mysql_escape_string($this->affiliateUserId)."'" ;
			$sql.= " ;" ;
			// do the update
			if(mysql_query($sql)){
				// set sessions on request
				if($setSessions==true){
					$_SESSION['affiliate_id'] = $this->affiliateId ;
					$_SESSION['affiliate_username'] = $_POST['username'] ;
					$_SESSION['affiliate_signup_date'] = $_POST['signup_date'] ;
					$_SESSION['affiliate_payment_name'] = $_POST['payment_name'] ;
					$_SESSION['affiliate_payment_address_1'] = $_POST['payment_address_1'] ;
					$_SESSION['affiliate_payment_address_2'] = $_POST['payment_address_2'] ;
					$_SESSION['affiliate_payment_address_town'] = $_POST['payment_address_town'] ;
					$_SESSION['affiliate_payment_address_county'] = $_POST['payment_address_county'] ;
					$_SESSION['affiliate_payment_address_postcode'] = $_POST['payment_address_postcode'] ;
				}
				$returnVar = true ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}

		// function to claim payments // keep this secure to avoid abuse
		public function claimPayment($graduates,$paymentsArray){
			$returnVar = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// now check this affiliate actually has that many unclaimed graduates
			$sql = "SELECT " ;
			$sql.= "affiliates_referrals.referral_id, " ;
			$sql.= "login.date_created " ;
			$sql.= "FROM affiliates_referrals " ;
			$sql.= "LEFT JOIN login ON affiliates_referrals.user_id = login.id " ;
			$sql.= "WHERE affiliate_id = '".mysql_escape_string($this->affiliateId)."' " ;
			$sql.= "AND payment_id = '' " ;
			$sql.= "AND is_eligible = '1' " ;
			$sql.= "AND is_declined = '0' " ;
			$sql.= "ORDER BY login.date_created ASC " ;
			$sql.= "; " ;
			// echo '<p>'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)>=$graduates){
				// first find out how much the reward is
				$reward = 0 ;
				foreach($paymentsArray as $cash => $requirement){
					if($graduates==$requirement){
						$reward = $cash ;
					}
				}
				if($reward!=0){
					// make an array of the referral IDs for processing in a sec...
					$referrals = array() ;
					while($row = mysql_fetch_array($result)){
						if(sizeof($referrals)<$graduates){
							$referrals[] = $row['referral_id'] ;
						}
					}
					// generate the string for the referral_ids field of the affiliates_payments table
					$referralsStr = '' ;
					foreach($referrals as $referral){
						$referralsStr.= ($referralsStr=='' ? '' : ',').$referral ;
					}
					// get a unique ID for the payment request
					$uniqueId = new uniqueId ;
					$uniqueId->setTableName('affiliates_payments') ;
					$uniqueId->setColumnName('payment_id') ;
					$uniqueId->setIdLength(15) ;
					$this->paymentId = $uniqueId->go() ;
					// now make the affiliate payment request
					$sql = "INSERT INTO affiliates_payments SET " ;
					$sql.= "payment_id = '".$this->paymentId."', " ;
					$sql.= "payment_amount = '".$reward."', " ;
					$sql.= "referral_ids = '".$referralsStr."', " ;
					$sql.= "request_date = '".date(DM_PHP_DATE_FORMAT)."', " ;
					$sql.= "number_of_graduates = '".$graduates."' " ;
					$sql.= "; " ;
					// echo '<p>'.$sql.'</p>' ;
					if(mysql_query($sql)){
						// now update the referrals to show this payment ID
						$sql = "UPDATE affiliates_referrals SET payment_id = '".$this->paymentId."' " ;
						$sqlWhere = "WHERE " ;
						foreach($referrals as $referral){
							$sql.= $sqlWhere."referral_id = '".$referral."' " ;
							if($sqlWhere=="WHERE "){
								$sqlWhere = "OR " ;
							}
						}
						$sql.= " ; " ;
						// echo '<p>'.$sql.'</p>' ;
						if(mysql_query($sql)){
							$returnVar = 'success' ;
						} else {
							// if it failed to update, remove the payment request
							$sql = "DELETE FROM affiliates_payments WHERE payment_id = '".$this->paymentId."' ; " ;
							mysql_query($sql) ;
							$returnVar = 'error151' ;
						}
					} else {
						$returnVar = 'error191' ;
					}
				} else {
					$returnVar = 'error161' ;
				}
			} else {
				// not enough unclaimed graduates
				$returnVar = 'notEnoughGraduates' ;
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}


		// toggle referral declined status
		public function toggleDeclinedStatus(){
			$returnVar = false ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "SELECT is_declined " ;
			$sql.= "FROM affiliates_referrals " ;
			$sql.= "WHERE referral_id = '".mysql_escape_string($this->referralId)."' AND payment_id = '".mysql_escape_string($this->paymentId)."' " ;
			$sql.= "; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// build the update
			$returnVar = ($row['is_declined']==0 ? '1' : '0') ;
			$sql = "UPDATE affiliates_referrals " ;
			$sql.= "SET is_declined = '".$returnVar."' " ;
			$sql.= "WHERE referral_id = '".mysql_escape_string($this->referralId)."' AND payment_id = '".mysql_escape_string($this->paymentId)."' " ;
			$sql.= "; " ;
			mysql_query($sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// return
			return $returnVar ;
		}


		// getters setters
		public function setAffiliateId($var){
			$this->affiliateId = $var ;
		}
		public function setAffiliateUserId($var){
			if(is_numeric($var)){
				$this->affiliateUserId = $var ;
			}
		}
		public function setAffiliateUserName($var){
			$this->affiliateUserName = $var ;
		}
		public function setPaymentId($var){
			$this->paymentId = $var ;
		}
		public function setReferralId($var){
			$this->referralId = $var ;
		}

	}

?>