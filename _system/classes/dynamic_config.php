<?php

	class dynamic_config {

		public function setDynamicConfig(){
			// Open connection
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			// build the SQL
			$this_sql = "SELECT " ;
			$this_sql.= "app_client_name, app_site_name, email_from_name, allowed_files_cv, allowed_files_images " ;
			$this_sql.= "FROM app_config " ;
			$this_sql.= "WHERE id='1' ; " ;
			$result = mysql_query($this_sql) or die( mysql_error()) ;
			$config = mysql_fetch_array($result) ;
			// set sessions
			$_SESSION['APP_CLIENT_NAME'] = $config['app_client_name'] ;
			$_SESSION['APP_SITE_NAME'] = $config['app_site_name'] ;
			$_SESSION['EMAIL_FROM_NAME']  = $config['email_from_name'] ;
			$_SESSION['ALLOWED_FILES_CV']  = explode("|",$config['allowed_files_cv']) ;
			$_SESSION['ALLOWED_FILES_IMAGES']  = explode("|",$config['allowed_files_images']) ;
			$_SESSION['LAST_LOAD'] = date(DM_PHP_DATE_FORMAT) ;
			// Close
			$conn->disconnect(DM_DB_NAME) ;
		}

		public function forceDynamicConfigUpdate(){
			$ourFileName = SITE_PATH."";
			$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
			fclose($ourFileHandle);
		}

	}

?>