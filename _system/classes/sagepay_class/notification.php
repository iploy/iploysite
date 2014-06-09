<?php
include('includes.php');
		
		$eoln = chr(13) . chr(10);
		header("Content-type: text/plain");
		echo "Status=INVALID" . $eoln;
		echo "RedirectURL=" . $strYourSiteFQDN . "complete.php" . $eoln;
		exit();
		
?>