<?php

	class email {

		// Private Vars
		private $toAddress = 'rob@devmac.co.uk' ;
		private $fromName = '' ;
		private $fromAddress = EMAIL_NOREPLY_ADDRESS ;
		private $subject = EMAIL_NOSUBJECT ;
		private $contents = 'No Email Content Found.' ;

		// The send mail function
		public function send(){
			//mail('rob@devmac.co.uk','Mail Start '.$date_var,'Mail Start - '.$email_result) ;
			$headers = "MIME-Version: 1.0\r\n" ;
			$headers.= "Content-type: text/html;" ;
			$headers.= " charset=UTF-8\r\n" ;
			// set the from name
			if($this->fromName!=''){
				$headers.= "Reply-To: ".$this->fromName." <".$this->fromAddress.">\n" ;
				$headers.= "From: ".$this->fromName." <".$this->fromAddress.">\n" ;
			} else {
				$headers.= "Reply-To: ".$this->fromAddress."\n" ;
				$headers.= "From: ".$this->fromAddress."\n" ;
			}
			// Messaage topper & CSS
			$msg_topper = '' ;
			$msg_topper.= '<style type="text/css" >' ;
			$msg_topper.= 'p, a, h1, h2, h3, h4, h5, h6 { font-family:Arial, Helvetica, sans-serif; } h1, h2, h3, h4, h5, h6 { color:#FF1D24; } p, a { font-size:13px; color:#333; } h1 { font-size:18px; } ' ;
			$msg_topper.= '</style>' ;
			//mail('rob@devmac.co.uk','Mail Middle '.$date_var,'Mail Middle - '.$email_result) ;
			// Send the mail
			if (mail($this->toAddress, $this->subject, $msg_topper.$this->contents, $headers)) {
				//mail('rob@devmac.co.uk','Mail Good '.$date_var,'Mail Good - '.$email_result) ;
				return true ;
			} else {
				//mail('rob@devmac.co.uk','Mail Bad '.$date_var,'Mail Bad - '.$email_result) ;
				return 'An error prevented the server sending the welcome email. Please contact us immediately for further assistance.' ;
			}
		}
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			
		// Set the to address
		public function setToAddress($var){
			$this->toAddress = $var ;
		}
		// Set the from name
		public function setFromName($var){
			$this->fromName = $var ;
		}
		// Set the from address
		public function setFromAddress($var){
			$this->fromAddress = $var ;
		}
		// Set the email content
		public function setSubject($var){
			$this->subject = $var ;
		}
		// Set the email content
		public function setContents($var){
			$this->contents = $var ;
		}
	}

?>
