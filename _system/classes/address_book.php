<?php

	class address_book {

		private $user_id = 0 ;
		private $type = '' ;
		private $id = 0 ;
		private $notId = 0 ;
		private $addressList = array() ;

		public function getAddress(){
			if($this->user_id!=0){
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				//
				$sql = "SELECT * FROM address_book WHERE user_id='".$this->user_id."' " ;
				if($this->type!=''){
					if($this->type=='default_billing'){
						$sql.=  "AND type='billing' " ;
					} else {
						$sql.=  "AND type='".$this->type."' " ;
					}
				}
				if($this->id!=0){
					 $sql.= "AND id='".$this->id."' " ;
				}
				if($this->type=='default_billing'){
					 $sql.= "AND is_default_billing='yes' " ;
				}
				$sql.= "LIMIT 0,1 ; " ;
				// echo '<p class="notice" >'.$sql.'</p>' ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				if(mysql_num_rows($result)>0){
					$returnval = mysql_fetch_array($result) ;
				} else {
					$returnval = array() ; // declare a new, empty array and set the sessions
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
				//return
				return $returnval ;
			} else {
				return false ;
			}
		}

		public function saveAddress($valuesArray,$is_new_address = false,$setSessions = false){
			if($this->user_id!=0&&is_array($valuesArray)&&$this->type!=''){
				// default vars
				$sessionPrefix = '' ;
				$default_billing_var = 'no' ;
				$this_type_var = 'profile' ;
				// alow the default billing class var to be set from the value array
				if(strtolower($valuesArray['is_default_billing'])=='yes'||$this->type=='default_billing'){
					$default_billing_var = 'yes' ;
					$this->type = 'default_billing' ;
				}
				// open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				//
				$sql = "SELECT id, list_order, is_default_billing FROM address_book WHERE user_id='".$this->user_id."' " ;
				if($this->type=='profile'){
					$sql.= "AND type='profile' " ;
				}
				if($this->type=='billing'||$this->type=='default_billing'){
					$sql.= "AND type='billing' " ;
					$this_type_var = 'billing' ;
					$sessionPrefix = 'billing_' ;
				}
				if($this->type=='default_billing'&&$this->id==0){
					$sql.= "AND is_default_billing='yes' " ;
				}
				if($this->id!=0){
					$sql.= "AND id='".$this->id."' " ;
				}
				$sql.= "; " ;
				$result = mysql_query($sql) or die( mysql_error()) ;
				$row = mysql_fetch_array($result) ;
				// echo '<p>1: '.$sql.'</p>' ;
				// if is default_billing, and is new address, blank the old default billing
				if($default_billing_var=='yes'){
					$sql = "UPDATE address_book SET is_default_billing='no', list_order='0' WHERE user_id='".$this->user_id."' AND is_default_billing='yes' ; " ;
					mysql_query($sql) or die( mysql_error()) ;
					// echo '<p>x: '.$sql.'</p>' ;
				}
				// Check if insert or update
				if(mysql_num_rows($result)>0&&$is_new_address==false){ // Always perform an insert if IS NEW ADDRESS is flagged
					//update
					$sql = "UPDATE address_book SET type='".$this_type_var."', contact_address_1='".mysql_escape_string($valuesArray['contact_address_1'])."', contact_address_2='".mysql_escape_string($valuesArray['contact_address_2'])."', contact_town='".mysql_escape_string($valuesArray['contact_town'])."', contact_state='".mysql_escape_string($valuesArray['contact_state'])."', contact_postcode='".mysql_escape_string($valuesArray['contact_postcode'])."', contact_country='".mysql_escape_string($valuesArray['contact_country'])."', contact_first_name='".mysql_escape_string($valuesArray['contact_first_name'])."', contact_surname='".mysql_escape_string($valuesArray['contact_surname'])."', contact_email='".mysql_escape_string($valuesArray['contact_email'])."', contact_tel='".mysql_escape_string($valuesArray['contact_tel'])."', contact_position='".mysql_escape_string($valuesArray['contact_position'])."', is_default_billing='".$default_billing_var."' WHERE id='".$row['id']."' ; " ;
				} else {
					// set the count
					$sql = "SELECT list_order FROM address_book WHERE user_id='".$this->user_id."' " ;
					if($this->type=='profile'){
						$sql.= "AND type='profile' " ;
					}
					if($this->type=='billing'||$this->type=='default_billing'){
						$sql.= "AND type='billing' " ;
					}
					$sql.= "ORDER BY list_order DESC LIMIT 0,1 ; " ;
					$result = mysql_query($sql) or die( mysql_error()) ;
					$row = mysql_fetch_array($result) ;
					if(mysql_num_rows($result)>0){
						$this_order = $row{'list_order'}+1 ;
					} else {
						$this_order = 1 ;
						// if this type is billing and an insert, and there are no other billing addresses, mark this as the default.
						if($this->type=='billing'){
							$default_billing_var = 'yes' ;
						}
					}
					// insert
					$sql = "INSERT INTO address_book(type, contact_address_1, contact_address_2, contact_town, contact_state, contact_postcode, contact_country, contact_first_name, contact_surname, contact_email, contact_tel, contact_position, user_id, is_default_billing, list_order) VALUES('".$this_type_var."', '".mysql_escape_string($valuesArray['contact_address_1'])."', '".mysql_escape_string($valuesArray['contact_address_2'])."', '".mysql_escape_string($valuesArray['contact_town'])."', '".mysql_escape_string($valuesArray['contact_state'])."', '".mysql_escape_string($valuesArray['contact_postcode'])."', '".mysql_escape_string($valuesArray['contact_country'])."', '".mysql_escape_string($valuesArray['contact_first_name'])."', '".mysql_escape_string($valuesArray['contact_surname'])."', '".mysql_escape_string($valuesArray['contact_email'])."', '".mysql_escape_string($valuesArray['contact_tel'])."', '".mysql_escape_string($valuesArray['contact_position'])."', '".$this->user_id."', '".$default_billing_var."', '".$this_order."') ; " ;
				}
				mysql_query($sql) or die( mysql_error()) ;
				// echo '<p>last: '.$sql.'</p>' ;
				// do sessions
				if($setSessions==true||($default_billing_var=='yes'&&$this->user_id==$_SESSION['user_id'])){
					include('_system/_config/_address_required_fields_array.php') ;
					foreach($address_all_fields as $address_field){
						$_SESSION[$sessionPrefix.$address_field] = $valuesArray[$address_field] ;
					}
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
				//return
				return $returnval ;
			} else {
				return false ;
			}
		}


		public function getAddressesList($countOnly = false,$orderBy = ''){
			// open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// SQL
			$sql = "SELECT " ;
			if($countOnly==true){
				$sql.= "COUNT(1) as address_count " ;
			} else {
				$sql.= "id, type, contact_first_name, contact_surname, contact_address_1, contact_address_2, contact_town, contact_state, contact_postcode, contact_country, contact_email, contact_tel, contact_position, list_order, is_default_billing " ;
			}
			$sql.= "FROM address_book WHERE user_id='".$this->user_id."' " ;
			if($this->type!=''){
				if($this->type=='default_billing'){
					$sql.= "AND type='billing' AND is_default_billing='yes' " ;
				} elseif($this->type=='defaults'){
					$sql.= "AND (is_default_billing='yes' OR type='profile') " ;
				} elseif($this->type=='general_billing'){
					$sql.= "AND is_default_billing='no' AND type='billing' " ;
				} else {
					$sql.= "AND type='".$this->type."' " ;
				}
			}
			if($this->id!=0){
				$sql.= "AND id='".$this->id."' " ;
			}
			if($this->notId!=0){
				$sql.= "AND id!='".$this->notId."' " ;
			}
			if($countOnly!=true){
				if($orderBy!=''){
					$sql.= "ORDER BY ".$orderBy.' ' ;
				} else {
					if($this->type=='defaults'){
						$sql.= "ORDER BY type DESC, list_order ASC " ;
					} else {
						$sql.= "ORDER BY is_default_billing DESC, list_order ASC " ;
					}
				}
			}
			$sql.= ';' ;
			// echo '<p>'.$sql.'</p>' ;
			$result = mysql_query($sql) or die( mysql_error()) ;
			if($countOnly!=true){
				$this->addressList = array() ;
				while($row = mysql_fetch_array($result)){
					array_push($this->addressList, $row);
				}
				$returnvar = $this->addressList ;
			} else {
				$row = mysql_fetch_array($result) ;
				$returnvar = $row['address_count'] ;
			}
			// close
			$conn->disconnect(DM_DB_NAME) ;
			//return
			return $returnvar ;
		}



		public function moveAddress($moveid,$oldpos,$dir,$type){
			if($dir=='up'){
				$new_order = $oldpos-1 ;
			} else {
				$new_order = $oldpos+1 ;
			}
			$sql_old = "UPDATE address_book SET list_order='".$oldpos."' WHERE user_id='".$this->user_id."' AND list_order='".$new_order."' AND type='".$type."' AND is_default_billing='no' ; " ;
			$sql_new = "UPDATE address_book SET list_order='".$new_order."' WHERE id='".$moveid."' AND user_id='".$this->user_id."' ; " ;
			mysql_query($sql_old) or die( mysql_error()) ;
			mysql_query($sql_new) or die( mysql_error()) ;
			// echo '<p>sql_old: '.$sql_old.'</p>' ;
			// echo '<p>sql_new: '.$sql_new.'</p>' ;
		}



		public function fixCount($id,$newOrder){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "UPDATE address_book SET list_order='".$newOrder."' WHERE id='".$id."' AND user_id='".$this->user_id."' ; " ;
			mysql_query($sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}



		public function deleteAddress($id){
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// sql
			$sql = "DELETE FROM address_book WHERE id='".$id."' AND user_id='".$this->user_id."' ; " ;
			mysql_query($sql) or die( mysql_error()) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}



		// GETTERS / SETTERS

		public function setId($var){
			if($var!=''&&is_numeric($var)){
				$this->id = $var ;
			}
		}
		public function setNotId($var){
			if($var!=''&&is_numeric($var)){
				$this->notId = $var ;
			}
		}
		public function setUserId($var){
			if($var!=''&&is_numeric($var)){
				$this->user_id = $var ;
			}
		}
		public function setType($var){
			if($var!=''){
				$this->type = $var ;
			}
		}

	}

?>
