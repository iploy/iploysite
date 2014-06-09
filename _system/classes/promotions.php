<?php

	class promotion {

		private $userId = 0 ;
		private $promoId = 0 ;

		// function for marking a user as entered into a competition
		public function userHasEntered(){
			$returnvar = false ;
			if($this->userId>0&&$this->promoId>0){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// SQL
				$sql = "INSERT INTO promotion_user_to_promo(user_id, promo_id) VALUES('".$this->userId."','".$this->promoId."') ; " ;
				if(mysql_query($sql) or die( mysql_error())){
					$returnvar = true ;
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			}
			// return
			return $returnvar ;
		}

		// function for checking if a user as entered into a competition
		public function userCanEnter(){
			$returnvar = 'yes' ;
			if($this->userId>0&&$this->promoId>0){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				// SQL
				$sql = "SELECT id FROM promotion_user_to_promo WHERE user_id='".$this->userId."' AND promo_id='".$this->promoId."' ; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				// if there is a row, the user can't enter, so return false
				if(mysql_num_rows($result)>0){
					$returnvar = 'no' ;
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			}
			// return
			return $returnvar ;
		}

		// Set User Id
		public function setUserId($var){
			if(is_numeric($var)){
				$this->userId = $var ;
			}
		}
		// Set Promo Id
		public function setPromoId($var){
			if(is_numeric($var)){
				$this->promoId = $var ;
			}
		}

	}

?>
