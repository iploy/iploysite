<?php
// Sage Pay Server Payment processing class

class sagePay {
	
	function before_process() {
	
		$arrResponse = array();
		$errors = array();
				
		/* Now to build the Sage Pay Server POST.  For more details see the Sage Pay Server Protocol 2.23
		** NB: Fields potentially containing non ASCII characters are URLEncoded when included in the POST */
		$strPost='VPSProtocol=' . urlencode($this->protocol);
		$tstPost='VPSProtocol=' . $this->protocol;
		
		$strPost .= '&TxType=' . urlencode($this->transactionType); //PAYMENT by default.  You can change this in the includes file
		$tstPost .= '&TxType=' . $this->transactionType;
		
		$strPost.= '&Vendor=' . urlencode($this->vendorName);
		$tstPost.= '&Vendor=' . $this->vendorName;
		
		$strPost.= '&VendorTxCode=' . urlencode($this->vendorTxCode); //As generated above
		$tstPost.= '&VendorTxCode=' . $this->vendorTxCode;
		
		$strPost .= '&Amount=' . urlencode(number_format($this->cartTotal, 2)); //Formatted to 2 decimal places with leading digit but no commas or currency symbols **
		$tstPost .= '&Amount=' . urlencode(number_format($this->cartTotal, 2));
		
		$strPost .= '&Currency=' . urlencode($this->currency);
		$tstPost .= '&Currency=' . $this->currency;
		
		$strPost .= '&Description=' . urlencode($this->cartDescription);
		$tstPost .= '&Description=' . $this->cartDescription;
		
		$strPost .= '&NotificationURL=' . urlencode($this->siteFQDN . 'transaction_process.php?process=check&'.$this->querystringExtra);
		$tstPost .= '&NotificationURL=' . $this->siteFQDN . 'transaction_process.php?process=check&'.$this->querystringExtra;
		
		if (sizeof($this->customerFirstname) == 0) {
			$errors['cutomerFirstname'] = true;
		}
		$strPost .= '&BillingFirstnames=' . urlencode($this->customerFirstname);
		$tstPost .= '&BillingFirstnames=' . $this->customerFirstname;
		
		if (sizeof($this->customerLastname) == 0) {
			$errors['cutomerLastname'] = true;
		}
		$strPost .= '&BillingSurname=' . urlencode($this->customerLastname);
		$tstPost .= '&BillingSurname=' . $this->customerLastname;

		if (sizeof($this->customerAddress1) == 0) {
			$errors['cutomerAddress1'] = true;
		}
		
		$strPost .= '&BillingAddress1=' . urlencode($this->customerAddress1);
		$tstPost .= '&BillingAddress1=' . $this->customerAddress1;
		
		if (strlen($this->customerAddress2) > 0) {
			$strPost .= '&BillingAddress2=' . urlencode($this->customerAddress2);
			$tstPost .= '&BillingAddress2=' . $this->customerAddress2;
		}
		
		if (sizeof($this->customerCity) == 0) {
			$errors['cutomerCity'] = true;
		}
		$strPost .= '&BillingCity=' . urlencode($this->customerCity);
		$tstPost .= '&BillingCity=' . $this->customerCity;
		
		if (sizeof($this->customerPostcode) == 0) {
			$errors['cutomerPostcode'] = true;
		}
		$strPost .= '&BillingPostCode=' . urlencode($this->customerPostcode);
		$tstPost .= '&BillingPostCode=' . $this->customerPostcode;
		
		if (sizeof($this->customerCountry) == 0) {
			$errors['cutomerCountry'] = true;
		}
		$strPost .= '&BillingCountry=' . urlencode($this->customerCountry);
		$tstPost .= '&BillingCountry=' . $this->customerCountry;
		
		if (strlen($this->customerState) > 0) {
			$strPost .= '&BillingState=' . urlencode($this->customerState);
			$tstPost .= '&BillingState=' . $this->customerState;
		}
		
		if (strlen($this->customerTelephone) > 0) {
			$strPost .= '&BillingPhone=' . urlencode($this->customerTelephone);
			$tstPost .= '&BillingPhone=' . $this->customerTelephone;
		}
		
		if (sizeof($this->deliveryFirstname) == 0) {
			$errors['DeliveryFirstname'] = true;
		}
		$strPost .= '&DeliveryFirstnames=' . urlencode($this->deliveryFirstname);
		$tstPost .= '&DeliveryFirstnames=' . $this->deliveryFirstname;
		
		if (sizeof($this->deliveryLastname) == 0) {
			$errors['deliveryLastname'] = true;
		}
		$strPost .= '&DeliverySurname=' . urlencode($this->deliveryLastname);
		$tstPost .= '&DeliverySurname=' . $this->deliveryLastname;
		
		if (sizeof($this->deliveryAddress1) == 0) {
			$errors['deliveryAddress1'] = true;
		}
		$strPost .= '&DeliveryAddress1=' . urlencode($this->deliveryAddress1);
		$tstPost .= '&DeliveryAddress1=' . $this->deliveryAddress1;
		
		if (strlen($this->deliveryAddress2) > 0) {
			$strPost .= '&DeliveryAddress2=' . urlencode($this->deliveryAddress2);
			$tstPost .= '&DeliveryAddress2=' . $this->deliveryAddress2;
		}
		
		if (sizeof($this->deliveryCity) == 0) {
			$errors['deliveryCity'] = true;
		}
		$strPost .= '&DeliveryCity=' . urlencode($this->deliveryCity);
		$tstPost .= '&DeliveryCity=' . $this->deliveryCity;
		
		if (sizeof($this->deliveryPostcode) == 0) {
			$errors['deliveryPostcode'] = true;
		}
		$strPost .= '&DeliveryPostCode=' . urlencode($this->deliveryPostcode);
		$tstPost .= '&DeliveryPostCode=' . $this->deliveryPostcode;
		
		if (sizeof($this->deliveryCountry) == 0) {
			$errors['deliveryCountry'] = true;
		}
		$strPost .= '&DeliveryCountry=' . urlencode($this->deliveryCountry);
		$tstPost .= '&DeliveryCountry=' . $this->deliveryCountry;
		
		if (strlen($this->deliveryState) > 0) {
			$strPost .= '&DeliveryState=' . urlencode($this->deliveryState);
			$tstPost .= '&DeliveryState=' . $this->deliveryState;
		}
		
		if (strlen($this->deliveryTelephone) > 0) {
			$strPost .= '&DeliveryPhone=' . urlencode($this->deliveryTelephone);
			$tstPost .= '&DeliveryPhone=' . $this->deliveryTelephone;
		}
		
		if (sizeof($this->customerEmail) == 0) {
			$errors['cutomerEmail'] = true;
		}
		$strPost .= '&CustomerEMail=' . urlencode($this->customerEmail);
		$tstPost .= '&CustomerEMail=' . $this->customerEmail;
		
		// Basket Description, Quanity, Price Ex VAT, VAT Content, Item Price (for cart systems), Line Total 
		$strPost .= '&Basket=1:' . $this->productDescription . ':' . $this->cartQuantity . ':' . number_format($this->cartTotal/1.2,2) . ':' . number_format($this->cartTotal*1/6,2) . ':' . number_format($this->cartTotal,2) . ':' . number_format($this->cartTotal*$this->cartQuantity,2);
		$tstPost .= '&Basket=1:' .$this->productDescription . ':' . $this->cartQuantity . ':' . number_format($this->cartTotal/1.2,2) . ':' . number_format($this->cartTotal*1/6,2) . ':' . number_format($this->cartTotal,2) . ':' . number_format($this->cartTotal*$this->cartQuantity,2);
		
		$strPost .= '&AllowGiftAid=' . urlencode($this->giftAid);
		$tstPost .= '&AllowGiftAid=' . $this->giftAid;
		
		if ($this->transactionType!=="AUTHENTICATE")
		$strPost .= '&ApplyAVSCV2=' . urlencode($this->avsCv2);
		$tstPost .= '&ApplyAVSCV2=' . $this->avsCv2;
		
		$strPost .= '&Apply3DSecure=' . urlencode($this->threedSecure);
		$tstPost .= '&Apply3DSecure=' . $this->threedSecure;
		
		$strPost .= '&Profile=' . urlencode($this->profileType);
		$tstPost .= '&Profile=' . $this->profileType;
		
		if ($this->debugMode === 1) {
			echo $tstPost;
		}
		
		
		$arrResponse = requestPost($this->paymentURL, $strPost);
		
		
		
		/* Analyse the response from Sage Pay Server to check that everything is okay
		** Registration results come back in the Status and StatusDetail fields */
		$strStatus=$arrResponse["Status"];
		$strStatusDetail=$arrResponse["StatusDetail"];
		if (sizeof($errors) > 0) {
			return $errors;
		} else if (substr($strStatus,0,2)=="OK") {
			return $arrResponse;
		} else {
			return $arrResponse['StatusDetail'];
		}
	
	}
	
	
	function after_process() {
		
			
		// Check Security Key from Original Post to Sage Pay is still set
		if(strlen($this->secKey)==0) {
			
			/** We cannot find a record of this order, so something isn't right **
			** To protect the customer, we should send back an INVALID response.  This will prevent **
			** the Sage Pay Server systems from settling any authorised transactions.  We will also send a **
			** RedirectURL that points to our orderFailure page, passing details of the error **
			** in the Query String so that the page knows how to respond to the customer **/
			$result = 'error001';
		} else {
		
			/** Now we rebuilt the POST message,<ul></ul> including our security key, and use the MD5 Hash **
			** component that is included to create our own signature to compare with **
			** the contents of the VPSSignature field in the POST.  Check the Sage Pay Server protocol **
			** if you need clarification on this process **/
			$strMessage = $this->txId . $this->vendorTxCode . $this->transactionStatus . $this->authCode . $this->vendorName . $this->avsCv2 . $this->secKey . $this->addressResult . $this->postcodeResult . $this->cv2Result . $this->giftAidResult . $this->threedSecureStatus . $this->cavv . $this->AddressStatus . $this->payerStatus . $this->cardType . $this->lastFourDigits ;
		
			$SageSignature = $this->vpsSignature;
			$strMySignature = strtoupper(md5($strMessage));
			
			/*$variables_file = 'variables.txt';
			$variable_FileHandle = fopen($variables_file, 'w') or die("can't open file");
			
			fwrite($variable_FileHandle, $strMessage . "\r\n");
			fwrite($variable_FileHandle, $SageSignature . "\r\n");
			fwrite($variable_FileHandle, $strMySignature . "\r\n");
			fclose($variable_FileHandle);*/
			
			//$_SESSION['myString'] =  '<strong>My String before MD5:</strong> <br />' . $strMessage . '<br /><br/>';
			//$_SESSION['sageString'] =  '<strong>Sage String before MD5:</strong> <br />' . $SageSignature . '<br /><br/>';
			
			
			//$strSageSignature = strtoupper(md5($SageSignature));
			
			// Check that the card being used is allowed by our system
			
			$card_check = explode(',', $this->allowedCards);
			if (in_array($this->cardType, $card_check)) {
				$card_type_allowed = true;
			} else {
				$card_type_allowed = false;
			}
			
			if ($strMySignature!==$SageSignature) {
				$result = 'error002'; // MD5 strings do not match
			} else if ($card_type_allowed === false) {
				$result = 'error003';
			} else {
				$result = 'success'; // all match success.
			}
		}
		return $result;
	}
	
	////////////////////////////////////  Start Getters & Setters  ///////////////////////////////////////////////////
		
		
		
		////// Settings from Config Start ///////
		
		public function setStrMessage($var) {
			$this->strMessage = $var;
		}
		
		public function setDebugMode($var) {
		   $this->debugMode = $var;
		 }
		
		public function setVendorName($var) {
		   $this->vendorName = $var;
		 }
		
		public function setProtocol($var) {
		   $this->protocol = $var;
		 }
		
		public function setTransactionType($var) {
		   $this->transactionType = $var;
		 }
		
		public function setCurrency($var) {
		   $this->currency = $var;
		 }
		
		public function setSiteFQDN($var) {
		   $this->siteFQDN = $var;
		 }
		
		public function setInternalFQDN($var) {
		   $this->internalFQDN = $var;
		 }
		
		public function setProfileType($var) {
		   $this->profileType = $var;
		 }
		
		public function setPaymentURL($var) {
		   $this->paymentURL = $var;
		 }
		
		public function setGiftAid($var) {
		   $this->giftAid = $var;
		 }

		public function setAvsCv2($var) {
		   $this->avsCv2 = $var;
		 }
		
		public function setThreedSecure($var) {
		   $this->threedSecure = $var;
		 }
		
		////// Settings from Config End ///////
		
		
		
		////// Billing info Start ///////
		
		public function setCustomerFirstname($var) {
		   $this->customerFirstname = $var;
		 }
		
		public function setCustomerLastname($var) {
		   $this->customerLastname = $var;
		 }
		
		public function setCustomerEmail($var) {
		   $this->customerEmail = $var;
		 }
		
		public function setCustomerAddress1($var) {
		   $this->customerAddress1 = $var;
		 }
		
		public function setCustomerAddress2($var) {
		   $this->customerAddress2 = $var;
		 }
		
		public function setCustomerCity($var) {
		   $this->customerCity = $var;
		 }
		
		public function setCustomerState($var) {
		   $this->customerState = $var;
		 }
		 
		public function setCustomerpostcode($var) {
		   $this->customerPostcode = $var;
		 }
		
		public function setCustomerCountry($var) {
		   $this->customerCountry = $var;
		 }
		
		public function setCustomerTelephone($var) {
		   $this->customerTelephone = $var;
		 }
		
		////// Billing Info End///////
		
		
		////// Delivery Info Start ///////
		
		public function setDeliveryFirstname($var) {
		   $this->deliveryFirstname = $var;
		 }
		
		public function setDeliveryLastname($var) {
		   $this->deliveryLastname = $var;
		 }
		
		public function setDeliveryAddress1($var) {
		   $this->deliveryAddress1 = $var;
		 }
		
		public function setDeliveryAddress2($var) {
		   $this->deliveryAddress2 = $var;
		 }
		
		public function setDeliveryCity($var) {
		   $this->deliveryCity = $var;
		 }
		
		public function setDeliveryState($var) {
		   $this->deliveryState = $var;
		 }
		
		public function setDeliverypostcode($var) {
		   $this->deliveryPostcode = $var;
		 }
		
		public function setDeliveryCountry($var) {
		   $this->deliveryCountry = $var;
		 }
		
		public function setDeliveryTelephone($var) {
		   $this->deliveryTelephone = $var;
		 }
		
		////// Delivery Info End ///////
		
		
		
		////// Cart Info Start ///////
		
		public function setCartTotal($var) {
		   $this->cartTotal = $var;
		 }
		
		public function setCartQuantity($var) {
		   $this->cartQuantity = $var;
		 }
		
		public function setProductDescription($var) {
		   $this->productDescription = $var;
		 }
		
		public function setCartDescription($var) {
		   $this->cartDescription = $var;
		 }
		
		////// Cart Info End ///////
		
		
		
		////// Sage Response Info Start //////////
		
		public function setTxId($var) {
		   $this->txId = $var;
		 }
		
		public function setVendorTxCode ($var) {
			$this->vendorTxCode = $var;
		}
		
		public function setTransactionStatus($var) {
		   $this->transactionStatus = $var;
		 }
		
		public function setAuthCode($var) {
		   $this->authCode = $var;
		 }
		
		public function setSecKey($var) {
		   $this->secKey = $var;
		 }
		
		public function setVPSSignature($var) {
		   $this->vpsSignature = $var;
		 }
		
		public function setStatusDetail($var) {
		   $this->statusDetail = $var;
		 }
		
		public function setAddressResult($var) {
		   $this->addressResult = $var;
		 }
		
		public function setPostcodeResult($var) {
		   $this->postcodeResult = $var;
		 }
		
		public function setCv2Result($var) {
		   $this->cv2Result = $var;
		 }
		
		public function setGiftAidResult($var) {
		   $this->giftAidResult = $var;
		 }
		
		public function setThreedSecureStatus($var) {
		   $this->threedSecureStatus = $var;
		 }
		
		public function setCavv($var) {
		   $this->cavv = $var;
		 }
		
		public function setAddressStatus($var) {
		   $this->addressStatus = $var;
		 }
		
		public function setPayerStatus($var) {
		   $this->payerStatus = $var;
		 }
		
		public function setCardType($var) {
		   $this->cardType = $var;
		 }
		 
		public function setAllowedCards($var) {
			$this->allowedCards = $var;
		}
		
		public function setLastFourDigits($var) {
		   $this->lastFourDigits = $var;
		 }
		
		public function setQuerystringExtra($var) {
		   $this->querystringExtra = $var;
		 }
		
		////// Sage Response Info Start //////////
	
	//////////////////////////////////// End Getters & Setters  ////////////////////////////////////////////////////
	
	
}

?>