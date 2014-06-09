<?php
class ConnectionSimple {
	public function connect($dbname){
		$conn = mysql_connect(DM_DB_HOST, DM_DB_USER, DM_DB_PASSWORD) or die(mysql_error()) ;
		mysql_select_db($dbname,$conn) ;
	}
	public function disConnect(){
		if($conn){
			mysql_close();
		}
	}
}
?>
