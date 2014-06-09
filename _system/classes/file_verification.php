<?php

	class fileVerification {
		private $userId = 0 ;
		private $type = false ;
		// Is image verified
		public function check(){
			if(is_numeric($this->userId)&&$this->type!=false){
				// Check if the image is confimred
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				$sql = "SELECT id FROM unverified_files WHERE user_id='".$this->userId."' AND upload_type='".$this->type."' ;" ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$conn->disconnect(DM_DB_NAME) ;
				if(mysql_num_rows($result)<1){
					return true ;
				} else {
					return false ;
				}
			} else {
				return false ;
			}
		}
		// Getters / Setters
		public function setUserId($var){
			if(is_numeric($var)){
				$this->userId = $var ;
			}
		}
		public function setType($var){
			if($var=='cv'||$var=='photo'){
				$this->type = $var ;
			} elseif($var=='img'||$var=='image'){
				$this->type = 'photo' ;
			} elseif($var=='file'){
				$this->type = 'cv' ;
			} elseif($var=='certificate'||$var=='certification'){
				$this->type = 'certificate' ;
			} else {
				$this->type = false ;
			}
		}
	}

?>
