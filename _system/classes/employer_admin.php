<?php

	include_once('_system/classes/address_book.php') ;
	include_once('_system/_config/_employer_required_fields_array.php') ;




	// the class

	class employer_admin {

		private $user_id = 0 ;

		public function updateUser($postVars,$requiredFieldsArray,$setSessions = false){
			if(is_array($postVars)&&is_array($requiredFieldsArray)){
				// quickly fix the promo mail checkbox value
				if(strtolower($postVars['send_promo_mails'])=='yes'){
					$postVars['send_promo_mails'] = 'yes' ;
				} else {
					$postVars['send_promo_mails'] = 'no' ;
				}
				// fix the multiple choice array
				$this_industry_sector_var = '' ;
				for($i=0;$i<sizeof($postVars['industry_sector']);$i++){
					if($i>0){
						$this_industry_sector_var.= "," ;
					}
					$this_industry_sector_var.= $postVars['industry_sector'][$i] ;
				}
				// do the update
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// Eployer profile bit
				$sql = "SELECT COUNT(1) as profile_count FROM employers WHERE user_id = '".$this->user_id."' ; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				if($row['profile_count']>0){
					$sql = "UPDATE employers SET company_name='".mysql_escape_string($postVars['company_name'])."', industry_sector='".mysql_escape_string($this_industry_sector_var)."', send_promo_mails='".mysql_escape_string($postVars['send_promo_mails'])."', website='".mysql_escape_string($postVars['website'])."', bio='".mysql_escape_string($postVars['bio'])."' WHERE user_id='".$this->user_id."' ; " ;
				} else {
					$sql = "INSERT INTO employers(user_id, company_name, industry_sector, send_promo_mails, website, bio) VALUES('".$this->user_id."', '".mysql_escape_string($postVars['company_name'])."', '".mysql_escape_string($this_industry_sector_var)."', '".mysql_escape_string($postVars['send_promo_mails'])."', '".mysql_escape_string($postVars['website'])."', '".mysql_escape_string($postVars['bio'])."') ; " ;
				}
				mysql_query($sql) or die( mysql_error()) ;
				// Save the addresses via the address book class
				$address_book_func = new address_book ;
				$address_book_func->setUserId($this->user_id) ;
				include('_system/_config/_address_required_fields_array.php') ;
				// profile side
				$address_book_func->setType('profile') ;
				$this_address_array = array() ;
				foreach($address_all_fields as $address_field){
					$this_address_array[$address_field] = $postVars[$address_field] ;
				}
				$address_book_func->saveAddress($postVars,false,$setSessions) ;
				// billing side
				$address_book_func->setType('default_billing') ;
				$this_address_array = array() ;
				foreach($address_all_fields as $address_field){
					$this_address_array[$address_field] = $postVars['billing_'.$address_field] ;
				}
				$address_book_func->saveAddress($this_address_array,false,$setSessions) ;
				// Update sessions
				if($setSessions==true){
					$_SESSION['company_name'] = $postVars['company_name'] ;
					$_SESSION['industry_sector'] = $this_industry_sector_var ;
					$_SESSION['send_promo_mails'] = $postVars['send_promo_mails'] ;
					$_SESSION['website'] = $postVars['website'] ;
					$_SESSION['bio'] = $postVars['bio'] ;
				}
				// Close
				$conn->disconnect(DM_DB_NAME) ;
				return true ;
			} else {
				// Basic errors
				if(!is_array($requiredFieldsArray)){
					return 'Required fields variable is not an array' ;
				} else {
					return 'Post variable is not an array' ;
				}
			}
		}


		// login employer function
		public function getEmployerByID($id,$setSessions = false){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Eployer profile bit
			$sql = "SELECT * FROM employers WHERE user_id = '".$id."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$returnvar = $row ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// address book details
			$address_book_func = new address_book ;
			$address_book_func->setUserId($id) ;
			$address_book_func->setType('profile') ;
			$address_book_result = $address_book_func->getAddress() ;
			$address_book_func->setType('default_billing') ;
			$address_book_billing_result = $address_book_func->getAddress() ;
			// now convert the returned arrays into the returnvar to be sent back (sessions are already set in the address book class)
			include('_system/_config/_address_required_fields_array.php') ;
			foreach($address_all_fields as $address_field){
				$returnvar[$address_field] = $address_book_result[$address_field] ;
				$returnvar['billing_'.$address_field] = $address_book_billing_result[$address_field] ;
				// do sessions
				if($setSessions==true){
					$_SESSION[$address_field] = $address_book_result[$address_field] ;
					$_SESSION['billing_'.$address_field] = $address_book_billing_result[$address_field] ;
				}
			}
			if($setSessions==true){
				$_SESSION['company_name'] = $returnvar['company_name'] ;
				$_SESSION['industry_sector'] = $returnvar['industry_sector'] ;
				$_SESSION['send_promo_mails'] = $returnvar['send_promo_mails'] ;
				$_SESSION['website'] = $returnvar['website'] ;
				$_SESSION['bio'] = $returnvar['bio'] ;
			}
			// go back
			return $returnvar ;
		}


		// Getters / setters
		public function setUserId($var){
			if($var!=''&&is_numeric($var)){
				$this->user_id = $var ;
			}
		}

	}

?>
