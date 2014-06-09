<?php

function ftpMyFile($fileLocation,$ftpAddress,$ftpUser,$ftpPassword,$passiveMode=true){
	$returnvar = false ;
	// open some file for reading
	$fileLocArray = explode("/",$fileLocation) ;
	// set up basic connection
	$conn_id = ftp_connect($ftpAddress);
	// login with username and password
	$login_result = ftp_login($conn_id,$ftpUser,$ftpPassword);
	ftp_pasv($conn_id, $passiveMode);
	// try to upload $file
	if (ftp_put($conn_id, $fileLocArray[sizeof($fileLocArray)-1], $fileLocation, FTP_ASCII)) {
		$returnvar = true ;
	}
	// close the connection and the file handler
	ftp_close($conn_id);
	return $returnvar ;
}

if(ftpMyFile('file/testfile.txt','ftp.critforbrains.com','ftpdemo@critforbrains.com','ftpdemo666')){
	echo "All is well" ;
} else {
	echo "Something went wrong" ;
}

?>
