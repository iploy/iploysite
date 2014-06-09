<?php

include_once('_system/_config/configure.php');
include('_system/classes/sagepay_class/includes.php');
include('_system/classes/sagepay_class/sagepay_server.php'); 



///////////////////////////  Process Final Response from Sage Pay ////////////////////////////
if (isset($_GET['transaction'])) {

	// Get the transaction response and decide where to redirect for final message to user.
	switch($_GET['transaction']) {
		case 'success';
		$noscript_text = '<p align="center" class="main">The transaction is being finalized. Please click continue to finalize your order.</p>';
		$urlredirect = $success_redirect;
		$url_query = '';
		break;
		
		case 'error001';
		$noscript_text = '<p align="center" class="main">There has been an error processing your transaction.  Error Code 001.  Please continue to try again</p>';
		$urlredirect = $error_redirect;
		$url_query = '&error=001' ;
		break;
		
		case 'error002';
		$noscript_text = '<p align="center" class="main">There has been an error processing your transaction.  Error Code 002.  Please continue to try again</p>';
		$urlredirect = $error_redirect;
		$url_query = '&error=002';
		break;
		
		case 'error003';
		$noscript_text = '<p align="center" class="main">The card type that you have entered is not accepted by our system.  Please try another card to continue</p>';
		$urlredirect = $card_not_allowed_redirect;
		$url_query = '';
		break;
		
		case 'abort';
		$noscript_text = '<p align="center" class="main">The transaction has been aborted.  Please continue to try again</p>';
		$urlredirect = $abort_redirect;
		$url_query = '&error=cancelled';
		break;
		
		case 'notauthed';
		$noscript_text = '<p align="center" class="main">The transaction has been declined.  Please continue to try again</p>';
		$urlredirect = $notauthed_redirect;
		$url_query = '&error=declined';
		break;
		
		case 'rejected';
		$noscript_text = '<p align="center" class="main">The transaction has been rejected.  Please continue to try again</p>';
		$urlredirect = $notauthed_redirect;
		$url_query = '&error=rejected';
		break;
		
		case 'error';
		$noscript_text = '<p align="center" class="main">The transaction has encountered an unkown error.  Please continue to try again</p>';
		$urlredirect = $notauthed_redirect;
		$url_query = '&error=unknown';
		break;
		
	}
		//  Redirect to final confirmation, error or not Authed Page, breaking out of iframe.
		//  $urlredirect is set up inside includes for the 3 options.  Edit there for your own site.
		echo '<form id="rdform" name="redirect" action="' . $noscript_try_again_link.'" method="post" target="_top">' . "\n" . 
		'<noscript>' . "\n" . 
		$noscript_text . 
		'<p align="center" class="main"><input type="submit" value="Continue" /></p>' . "\n" . 
		'</noscript>' . "\n" . 
		'</form>' . "\n" . 
		'<script language="javascript">' . "\n" . 
		'document.getElementById(\'rdform\').action=\''.$urlredirect . $url_query.'\' ;'."\n".
		'document.redirect.submit();' . "\n" . 
		'</script>';
		exit();
}

///////////////////////////  Process notification Response from Sage Pay ////////////////////////////
if ($_GET['process'] == 'check') {
	
	//Define end of line character used to correctly format response to Sage Pay Server
	$eoln = chr(13) . chr(10);
	
	// Check if transaction has been accepted
	if (strtolower(cleaninput($_POST["Status"], 'Text')) == 'abort') {
		// Transaction Cancelled by user
		ob_flush();
		header("Content-type: text/html");
		echo "Status=OK" . $eoln;
		echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=abort&".$maintained_getvars . $eoln;
		exit();
	} else if (strtolower(cleaninput($_POST["Status"], 'Text')) == 'notauthed') {
		// Transaction declined by bank
		ob_flush();
		header("Content-type: text/html");
		echo "Status=OK" . $eoln;
		echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=notauthed&".$maintained_getvars . $eoln;
		exit();
	} else if (strtolower(cleaninput($_POST["Status"], 'Text')) == 'rejected') {
		// Transaction rejected by Sage Pay
		ob_flush();
		header("Content-type: text/html");
		echo "Status=OK" . $eoln;
		echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=rejected&".$maintained_getvars . $eoln;
		exit();
	} else if (strtolower(cleaninput($_POST["Status"], 'Text')) == 'error') {
		// Unknown error.  Normally caused by no response from Bank.
		ob_flush();
		header("Content-type: text/html");
		echo "Status=ERROR" . $eoln;
		echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=error&".$maintained_getvars . $eoln;
		exit();
	} else if (strtolower(cleaninput($_POST["Status"], 'Text')) == 'ok') {
		// Transaction has been accepted.  Process check to ensure that the response from Sage Pay has not been altered
		// You will need to get the Security Key from the database now.  Select it using the VendorTxCode set up in previous step to get the security code for this order.
		
		$atp = new sagePay();
		$sp_response2 = '';
		
		
		if ($runfromdatabase === false) {
			$seckey_txtfile = 'secKey.txt';
			$seckey_file = fopen($seckey_txtfile, 'r') or die("can't open file");
			$seckey = fread($seckey_file, 10);
			fclose($seckey_file);
		} else {
			// Put database query here to get your security key from your database. Pull value against current TxCode ($_POST["VendorTxCode"]) to ensure that you are working with the correct security key.
			include_once('_system/classes/connection.php') ;
			// Open
			$conn = new ConnectionSimple ;
			$conn->connect(DM_DB_NAME) ;
			$sql = "SELECT * FROM transaction_process WHERE transaction_code='".mysql_escape_string(cleaninput($_POST["VendorTxCode"],"VendorTxCode"))."' ; " ;
			$result = mysql_query($sql) ;
			$transaction = mysql_fetch_array($result) ;
			// set the key
			$seckey = $transaction['security_key'] ;
			// close
			$conn->disconnect(DM_DB_NAME) ;
		}
		
		$atp->setTxId(cleaninput($_POST["VPSTxId"],"Text"));
		$atp->setVendorTxCode(cleaninput($_POST["VendorTxCode"],"VendorTxCode"));
		$atp->setTransactionStatus(cleaninput($_POST["Status"],"Text"));
	
		if (strlen($_POST["TxAuthNo"]>0)) {
			$atp->setAuthCode(cleaninput($_POST["TxAuthNo"],"Number"));
		}
	
		$atp->setVPSSignature(cleaninput($_POST["VPSSignature"],"Text"));
		$atp->setStatusDetail(cleaninput($_POST["StatusDetail"],"Text"));
		$atp->setVendorName($strVendorName);
		$atp->setSecKey($seckey);
		
		$atp->setAvsCv2(cleaninput($_POST["AVSCV2"],"Text"));
		$atp->setAddressResult(cleaninput($_POST["AddressResult"],"Text"));
		$atp->setPostCodeResult(cleaninput($_POST["PostCodeResult"],"Text"));
		$atp->setCv2Result(cleaninput($_POST["CV2Result"],"Text"));
		$atp->setGiftAidResult(cleaninput($_POST["GiftAid"],"Number"));
		$atp->setThreedSecureStatus(cleaninput($_POST["3DSecureStatus"],"Text"));
		$atp->setCavv(cleaninput($_POST["CAVV"],"Text"));
		$atp->setAddressStatus(cleaninput($_POST["AddressStatus"],"Text"));
		$atp->setPayerStatus(cleaninput($_POST["PayerStatus"],"Text"));
		$atp->setCardType(cleaninput($_POST["CardType"],"Text"));
		$atp->setLastFourDigits(cleaninput($_POST["Last4Digits"],"Text"));
		$atp->setSiteFQDN($strYourSiteFQDN);
		$atp->setAllowedCards($allowedcards);
	
		$sp_response2 = $atp->after_process();	
		
		if ($sp_response2 == 'error001') {
			// available variables $_GET['userid']
			ob_flush();
			header("Content-type: text/html");
			echo "Status=INVALID" . $eoln;
			echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=error001&".$maintained_getvars . $eoln;
			exit();
		} else if ($sp_response2 == 'error002') {
			// available variables $_GET['userid']
			ob_flush();
			header("Content-type: text/plain");
			echo "Status=INVALID" . $eoln;
			echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=error002&".$maintained_getvars . $eoln;
			exit();
		} else if ($sp_response2 == 'error003') {
			// available variables $_GET['userid']
			ob_flush();
			header("Content-type: text/plain");
			echo "Status=OK" . $eoln;
			echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=error003&".$maintained_getvars . $eoln;
			exit();
		} else if ($sp_response2 == 'success') {
			// if run from database, do the insert into the history table
			if ($runfromdatabase===true) {
				// available variables $_GET['userid']
				// we now need to update transation table to say transaction complete and move into history.
				// We need to update the transaction details with $_POST["TxAuthNo"] sent from sage pay to record the Auth No.
				include_once('_system/classes/connection.php') ;
				include_once('_system/classes/products.php') ;
				include_once('_system/classes/credits.php') ;
				$pf = new products ;
				$pf->setProductId($transaction['product_id']) ;
				$product = $pf->getProduct() ;
				// Open
				$conn = new ConnectionSimple ;
				$conn->connect(DM_DB_NAME) ;
				$sql = "INSERT INTO transaction_history(user_id,transaction_date,product_name,product_price,product_price_in_credits,vendortxcode,txauthno,vpstxid,billing_address_id) VALUES(" ;
				$sql.= "'".mysql_escape_string($transaction['user_id'])."'," ;
				$sql.= "'".date(DM_PHP_DATE_FORMAT,strtotime($transaction['transaction_date']))."'," ;
				$sql.= "'".mysql_escape_string($product['name'])."'," ;
				$sql.= "'".mysql_escape_string($product['price'])."'," ;
				$sql.= "'".mysql_escape_string($product['price_in_credits'])."'," ;
				$sql.= "'".mysql_escape_string($transaction["transaction_code"])."'," ;
				$sql.= "'".mysql_escape_string(cleaninput($_POST["TxAuthNo"],"Number"))."',"  ;
				$sql.= "'".mysql_escape_string(cleaninput($_POST["VPSTxId"],"Text"))."'," ;
				$sql.= "'".mysql_escape_string($transaction["billing_address_id"])."'" ;
				$sql.= ") ; " ;
				if(mysql_query($sql)){
					$sql = "DELETE FROM transaction_process WHERE transaction_id='".$transaction['transaction_id']."' ; " ;
					mysql_query($sql) ;
					$credit_function = new credits ;
					$credits_total = $credit_function->addCredits($transaction['user_id'],$product['number_of_credits']) ;
				}
				// close
				$conn->disconnect(DM_DB_NAME) ;
			}

			ob_flush();
			header("Content-type: text/plain");
			echo "Status=OK" . $eoln; 
			$strResponse="Status=OK" . $eoln;
			echo "RedirectURL=" . $strYourSiteFQDN . "transaction_process.php?transaction=success" . $eoln;
			exit();
		}
		exit();
	} // end Check if transaction accepted
	
} // End if process = check

///////////////////////////  Default action - send/process and load payment page ////////////////////////////
else {

	include('_system/functions/get_country_code.php') ;

	/** First we need to generate a unique VendorTxCode for this transaction
	** We're using VendorName, time stamp and a random element.  You can use different methods if you wish
	** but the VendorTxCode MUST be unique for each transaction you send to Sage Pay Server */
	$strTimeStamp = date('y/m/d : H:i:s', time());
	$intRandNum = rand(0,32000)*rand(0,32000);
	$strVendorTxCode=cleanInput($strVendorName . "-" . $strTimeStamp . "-" . $intRandNum,"VendorTxCode");

	$tp = new sagePay();
	$sp_response1 = array();


	$cart_total = $product['price'] ;
	$cart_quantity = '1';
	$product_description = $product['name'] ;
	$cart_description = $_SESSION['APP_CLIENT_NAME'].' Employer Account Payments';

	$tp->setDebugMode($debug);

	$tp->setVendorName($strVendorName);
	$tp->setVendorTxCode($strVendorTxCode);
	$tp->setProtocol($strProtocol);
	$tp->setTransactionType($strTransactionType);
	$tp->setCurrency($strCurrency);

	$tp->setSiteFQDN($strYourSiteFQDN);
	$tp->setInternalFQDN($internal_url);

	// Temp state fix for UK
	if($address['contact_address_2']==''){
		$add2 = $address['contact_state'] ;
	} else {
		$add2 = $address['contact_address_2'].', '.$address['contact_state'] ;
	}

	$tp->setCustomerFirstname($address['contact_first_name']);
	$tp->setCustomerLastname($address['contact_surname']);
	$tp->setCustomerEmail($address['contact_email']);
	$tp->setCustomerAddress1($address['contact_address_1']);
	$tp->setCustomerAddress2($add2);
	$tp->setCustomerCity($address['contact_town']);
	// $tp->setCustomerState($address['contact_state']);
	$tp->setCustomerPostcode($address['contact_postcode']);
	$tp->setCustomerCountry(get_country_code($address['contact_country']));
	$tp->setCustomerTelephone($address['contact_tel']);

	$tp->setDeliveryFirstname($address['contact_first_name']);
	$tp->setDeliveryLastname($address['contact_surname']);
	$tp->setDeliveryAddress1($address['contact_address_1']);
	$tp->setDeliveryAddress2($add2);
	$tp->setDeliveryCity($address['contact_town']);
	// $tp->setDeliveryState($address['contact_state']);
	$tp->setDeliveryPostcode($address['contact_postcode']);
	$tp->setDeliveryCountry(get_country_code($address['contact_country']));
	$tp->setDeliveryTelephone($address['contact_tel']);

	$tp->setCartTotal($cart_total);
	$tp->setCartQuantity($cart_quantity);
	$tp->setProductDescription($product_description);
	$tp->setCartDescription($cart_description);
	$tp->setProfileType($paymentprofile);
	$tp->setPaymentURL($strPurchaseURL);
	$tp->setGiftAid($allowgiftaid);
	$tp->setAvsCv2($avscv2check);
	$tp->setThreedSecure($threedsecure);

	$tp->setQuerystringExtra('product_id='.$_GET['product_id'].'&addressid='.$_GET['addressid']) ;

	$sp_response1 = $tp->before_process();

	if ($debug == 1) {
		echo '<br /><strong>Sage Pay Response Array:</strong><br />';
		print_r($sp_response1);
		echo '<br /><br />';
		echo 'Key in Session: ' . $_SESSION['seckey'] . "<br />"; 
		echo 'Key in Response: ' . $sp_response1['SecurityKey'] . "<br />"; 
		echo 'Session User ID: ' . $_SESSION['user_id'] . "<br />"; 
		echo '<p>Vendor Transaction Code: ' . $strVendorTxCode . '</p>';
	}

	// Security key is temporarily put into text file.  Put this in the database transaction/orders table now for working examples.  
	// You will also need to store VPSTxID to help with order debugging later
	// You need to put $strVendorTxCode into the database at this point as well.  This is required to know which order you are working on.
	
	if ($runfromdatabase === false) {
		$seckey_txtfile = 'secKey.txt';
		$seckey_txtfileHandle = fopen($seckey_txtfile, 'w') or die("can't open file");
		fwrite($seckey_txtfileHandle, $_SESSION['seckey']);
		fclose($seckey_txtfileHandle);
	} else {
		//  Put $sp_response1['SecurityKey'] & $strVendorTxCode into database.  Security key will need to be retrieved from database against the TxCode for this transaction.  You should also now store $sp_response1['VPSTxId'] for transactio debugging later.

		include_once('_system/classes/connection.php') ;
		// Open
		$conn = new ConnectionSimple ;
		$conn->connect(DM_DB_NAME) ;
		// Remove old SQL
		$sql = "DELETE FROM transaction_process WHERE user_id='".$_SESSION['user_id']."' ; " ;
		mysql_query($sql) or die( mysql_error()) ;
		// echo '<p class="notice" >'.$sql.'</p>' ;
		// Create new SQL
		$sql = "INSERT INTO transaction_process(user_id,transaction_code,transaction_date,security_key,vpstxid,product_id,billing_address_id) VALUES('".mysql_escape_string($_SESSION['user_id'])."','".mysql_escape_string(cleaninput($strVendorTxCode,"VendorTxCode"))."','".date(DM_PHP_DATE_FORMAT)."','".mysql_escape_string($sp_response1['SecurityKey'])."','','".mysql_escape_string($_GET['product_id'])."','".$address['id']."') ; " ;
		// echo '<p class="notice" >'.$sql.'</p>' ;
		mysql_query($sql) or die( mysql_error()) ;
		// close
		$conn->disconnect(DM_DB_NAME) ;

	}
	//exit() ;
	
	// next line can be used if not using low profile and the user needs to be redirected to the payment page instead of iframe.
	// redirect($sp_response1['NextURL']);
	
	if (strtolower($sp_response1['Status']) === 'ok') {
		// include('error_codes.php');
		echo '<iframe src="'.$sp_response1['NextURL'].'" width="'.$iframe_width.'" height="'.$iframe_height.'" scrolling="no"  frameborder="0">Your browser does not support ifrmaes.  Please enable iframes in your browser settings in order to complete your transaction</iframe>' . "\n";
	} else {
		include('_system/classes/sagepay_class/error_codes.php');
		$status = explode(':', $sp_response1['StatusDetail']);
		$status = trim($status[0]);
		echo '<h1>An Error has been encountered</h1>' . "\n" . 
		'<p><strong>Error Detail:</strong></p>' . "\n" . 
		'<p>' . $errors[$status] . '</p>' . 
		'<p>Addiitonal Information: ' . $sp_response1['StatusDetail'] . '</p>';
	}
} // End Default action
?>