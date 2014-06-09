<?php

	include_once('_system/classes/session_killer.php') ;
	include_once('_system/classes/unique_id.php') ;

	class user_info {

		// Get all base user information via the email address. Used for login, confirmation and other functions
		public function getInfoByEmail($email,$setsessions){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Sql here
			$this_sql = "SELECT id, email, password, date_created, is_active, user_level, email_is_confirmed " ;
			$this_sql.= "FROM login WHERE email='".mysql_escape_string(strtolower($email))."'" ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// Set sessions
			if($setsessions==true){
				$_SESSION['user_id'] = $row['id'] ;
				$_SESSION['email'] = $row['email'] ;
				$_SESSION['date_created'] = $row['date_created'] ;
				$_SESSION['is_active'] = $row['is_active'] ;
				$_SESSION['user_level'] = $row['user_level'] ;
				$_SESSION['email_is_confirmed'] = $row['email_is_confirmed'] ;
				$_SESSION['su_level_mask'] = 0 ;
			}
			if(mysql_num_rows($result)>0){
				// return the data row
				return $row ;
			} else {
				return false ;
			}
		}

		// Get all base user information via the userid. Used for lists, contact and other functions
		public function getInfoByUserId($userID,$setsessions){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Sql here
			$this_sql = "SELECT id, email, password, date_created, is_active, user_level, email_is_confirmed " ;
			$this_sql.= "FROM login WHERE id='".$userID."'" ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// Set sessions
			if($setsessions==true){
				$_SESSION['user_id'] = $row['id'] ;
				$_SESSION['email'] = $row['email'] ;
				$_SESSION['date_created'] = $row['date_created'] ;
				$_SESSION['is_active'] = $row['is_active'] ;
				$_SESSION['user_level'] = $row['user_level'] ;
				$_SESSION['email_is_confirmed'] = $row['email_is_confirmed'] ;
				$_SESSION['su_level_mask'] = 0 ;
			}
			// return the data row
			return $row ;
		}


		// Create a new user and return the ID of the new user
		public function createUserAndReturnId($email,$password,$is_active,$user_level,$is_confirmed,$setsessions=false,$affiliateId=''){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// check the new ID and keep checking until we get a unique one
			$foundUnique = false ;
			while($foundUnique==false){
				$newId = mt_rand(100,999).date('dmyHis') ;
				// Now check the ID doesnt exist
				$this_sql = "SELECT COUNT(1) AS existing_count FROM login WHERE id='".$newId."' " ;
				$result = mysql_query($this_sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				if($row['existing_count']<1){
					$foundUnique = true ;
				}
			}
			// Sql here
			$this_sql = "INSERT INTO login(id, email, password, date_created, is_active, user_level, email_is_confirmed)" ;
			$this_sql.= "values(" ;
			$this_sql.= "'".$newId."'," ; // email
			$this_sql.= "'".mysql_escape_string(strtolower($email))."'," ; // email
			$this_sql.= "'".md5($password)."'," ; // password
			$this_sql.= "'".DATE_TIME_NOW."'," ; // date_created
			$this_sql.= "'".$is_active."'," ; // is_active
			$this_sql.= "'".$user_level."'," ; // user_level
			$this_sql.= "'".$is_confirmed."'" ; // email_is_confirmed
			$this_sql.= ")" ;
			mysql_query($this_sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			// Set sessions
			if($setsessions==true){
				$_SESSION['user_id'] = $newId ;
				$_SESSION['email'] = $email ;
				$_SESSION['date_created'] = DATE_TIME_NOW ;
				$_SESSION['is_active'] = $is_active ;
				$_SESSION['user_level'] = $user_level ;
				$_SESSION['email_is_confirmed'] = $is_confirmed ;
				$_SESSION['new_signup'] = true ;
			}
			// deal with the referrer
			if($affiliateId!=''){
				// check if the referralId exists
				$sql = "SELECT user_id FROM affiliates WHERE affiliate_id='".mysql_escape_string($affiliateId)."' ; " ;
				$result = mysql_query($sql) or die(mysql_error()) ;
				if(mysql_num_rows($result)>0){
					// get a unique ID
					$uniqueId = new uniqueId ;
					$uniqueId->setTableName('affiliates') ;
					$uniqueId->setColumnName('affiliate_id') ;
					$uniqueId->setIdLength(15) ;
					$referralId = $uniqueId->go() ;
					// insert the referral
					$sql = "INSERT INTO affiliates_referrals SET " ;
					$sql.= "referral_id='".mysql_escape_string($referralId)."', " ;
					$sql.= "affiliate_id='".mysql_escape_string($affiliateId)."', " ;
					$sql.= "user_id='".mysql_escape_string($newId)."', " ;
					$sql.= "payment_id='', " ;
					$sql.= "is_eligible='0' " ;
					$sql.= "; " ;
					mysql_query($sql) or die(mysql_error()) ;
				}
			}
			// return the ID
			return $newId ;
		}

		// Get all base user information via the email address. Used for login, confirmation and other functions
		public function confirmEmail($email){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Sql here
			$this_sql = "UPDATE login SET email_is_confirmed='1' WHERE email='".mysql_escape_string(strtolower($email))."' " ;
			mysql_query($this_sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}

		// Get all base user information via the email address. Used for login, confirmation and other functions
		public function updatePassword($id,$newpass){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Sql here
			$this_sql = "UPDATE login SET password='".md5($newpass)."' WHERE id='".$id."' " ;
			mysql_query($this_sql) or die( mysql_error()) ;
			// Delete any pass change requests
			$this_sql = "DELETE FROM password_reset_requests WHERE user_id='".$id."' ; " ;
			mysql_query($this_sql) or die( mysql_error() ) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}

		// Get all base user information via the email address. Used for login, confirmation and other functions
		public function userLevelStats($user_level){
			$this_stat = array() ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Sql here
			$this_sql = "SELECT COUNT(1) AS user_count FROM login WHERE user_level='".$user_level."' ; " ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$this_stat['total_users'] = $row['user_count'] ;
			// Sql here
			$this_sql = "SELECT COUNT(1) AS user_count FROM login WHERE user_level='".$user_level."' AND is_active='1' AND email_is_confirmed='1' ; " ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$this_stat['total_active'] = $row['user_count'] ;
			// Generate Stats
			if($this_stat['total_active']>0||$this_stat['total_users']>0){
				$this_stat['percent_active'] = round(($this_stat['total_active']/$this_stat['total_users'])*100,0) ; 
			} else {
				$this_stat['percent_active'] = 0 ;
			}
			// Sql here
			$this_sql = "SELECT COUNT(1) AS user_count FROM login WHERE user_level='".$user_level."' AND is_active='0' AND email_is_confirmed='1'  ; " ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$this_stat['total_suspended'] = $row['user_count'] ;
			// Generate Stats
			if($this_stat['total_suspended']>0||$this_stat['total_users']>0){
				$this_stat['percent_suspended'] = round(($this_stat['total_suspended']/$this_stat['total_users'])*100,0) ; 
			} else {
				$this_stat['percent_suspended'] = 0 ;
			}
			// Sql here
			$this_sql = "SELECT COUNT(1) AS user_count FROM login WHERE user_level='".$user_level."' AND email_is_confirmed='0' ; " ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$row = mysql_fetch_array($result) ;
			$this_stat['total_unconfirmed'] = $row['user_count'] ;
			// Generate Stats
			if($this_stat['total_unconfirmed']>0||$this_stat['total_users']>0){
				$this_stat['percent_unconfirmed'] = round(($this_stat['total_unconfirmed']/$this_stat['total_users'])*100,0) ; 
			} else {
				$this_stat['percent_unconfirmed'] = 0 ;
			}
			return $this_stat ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}

		// Get all base user information via the email address. Used for login, confirmation and other functions
		public function userIsActiveChange($id,$newStatus){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Sql here
			$this_sql = "UPDATE login SET is_active='".$newStatus."' WHERE id='".$id."' ; " ;
			mysql_query($this_sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}

		// Create a password reset request
		public function createPasswordResetRequest($id,$email){
			include_once('_system/functions/random_string_generator.php') ;
			$this_random_string = random_string_generator(30) ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Delete any old ones first
			$this_sql = "DELETE FROM password_reset_requests WHERE user_id='".$id."' AND user_email='".$email."' ; " ;
			mysql_query($this_sql) or die( mysql_error()) ;
			// Create a new one
			$this_sql = "INSERT INTO password_reset_requests(user_id,reset_string,date_requested,user_email) VALUES('".$id."','".$this_random_string."','".DATE_TIME_NOW."','".$email."') ; " ;
			mysql_query($this_sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			return $this_random_string ;
		}
		// Create a password reset request
		public function confirmPasswordResetRequest($resetstr,$email){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// Confirm the request exists with the random string
			$this_sql = "SELECT user_id FROM password_reset_requests WHERE user_email='".mysql_escape_string($email)."' AND reset_string='".mysql_escape_string($resetstr)."' ; " ;
			mysql_query($this_sql) or die( mysql_error()) ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)>0){
				$row = mysql_fetch_array($result) ;
				$this_result = $row['user_id'] ; 
			} else {
				$this_result = 0 ; 
			}
			// Close
			$conn->disconnect(DM_DB_NAME) ;
			return $this_result ;
		}

		// Kill all sessions used by a logged in user, used for signup confirmation and logout
		public function killSessions(){
			$kill_function = new sessionKiller ;
			$kill_function->killLoginSessions() ;
		}

	}

?>