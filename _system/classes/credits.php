<?php

	class credits{

		// Check credits
		public function checkCredits($id){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// SQL
			$sql = "SELECT balance FROM credit_balance WHERE user_id='".mysql_escape_string($id)."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if(mysql_num_rows($result)<1){
				// if no credit record found, create one and return 0
				$sql = "INSERT INTO credit_balance(user_id,balance) VALUES('".mysql_escape_string($id)."','0') ;" ;
				mysql_query($sql) or die( mysql_error()) ;
				$returnval = 0 ;
			} else {
				// else return the current number of credits
				$row = mysql_fetch_array($result) ;
				$returnval = $row['balance'] ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			//return
			return $returnval ;
		}

		// Add credits and return new balance (and no... before you ask... not a pair of chav trainers)
		public function addCredits($id,$number){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// SQL
			$sql = "SELECT balance FROM credit_balance WHERE user_id='".mysql_escape_string($id)."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			// prepare follw up SQL
			if(mysql_num_rows($result)<1){
				// if no record found create one and return the new addtion as the balance
				$returnval = $number ;
				$sql = "INSERT INTO credit_balance(user_id,balance) VALUES('".mysql_escape_string($id)."','".mysql_escape_string($number)."') ;" ;
			} else {
				// if update the existing and return the new total
				$row = mysql_fetch_array($result) ;
				$sql = "UPDATE credit_balance SET balance='".mysql_escape_string($row['balance']+$number)."' WHERE user_id='".mysql_escape_string($id)."' ; " ;
				$returnval = $row['balance'] + $number ;
			}
			mysql_query($sql) or die( mysql_error()) ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
			//return
			return $returnval ;
		}

		// Spend credits and return new balance false = not enough credits to spend
		public function spendCredits($id,$number){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// SQL
			$sql = "SELECT balance FROM credit_balance WHERE user_id='".mysql_escape_string($id)."' ; " ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			// prepare follw up SQL
			if(mysql_num_rows($result)<1){
				// if no record found create one and return the new addtion as the balance
				$returnval = false ;
				$sql = "INSERT INTO credit_balance(user_id,balance) VALUES('".mysql_escape_string($id)."','0') ;" ;
			} else {
				// if update the existing and return the new total
				$row = mysql_fetch_array($result) ;
				$newbalance = $row['balance']-$number ;
				if($newbalance<0){ $newbalance = 0 ; }
				if($row['balance']>0){
					$sql = "UPDATE credit_balance SET balance='".mysql_escape_string($newbalance)."' WHERE user_id='".mysql_escape_string($id)."' ; " ;
					$returnval = $newbalance ;
				} else {
					$returnval = false ;
				}
			}
			mysql_query($sql) or die( mysql_error()) ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
			//return
			return $returnval ;
		}

	}

?>
