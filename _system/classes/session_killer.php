<?php

	class sessionKiller {
		public function killAll(){
			$this->killLoginSessions() ;
			$this->killGraduateSessions() ;
			$this->killSearchSessions() ;
			$this->killAddressSessions() ;
			$this->killEmployerSessions() ;
			$this->killMessageSessions() ;
			$this->killAffiliateSessions() ;
		}
		public function killLoginSessions(){
			unset($_SESSION['user_id']) ;
			unset($_SESSION['email']) ;
			unset($_SESSION['date_created']) ;
			unset($_SESSION['is_active']) ;
			unset($_SESSION['user_level']) ;
			unset($_SESSION['email_is_confirmed']) ;
			unset($_SESSION['new_signup']) ;
			unset($_SESSION['su_level_mask']) ;
			unset($_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID]) ;
		}
		public function killGraduateSessions(){
			unset($_SESSION['first_name']) ;
			unset($_SESSION['surname']) ;
			unset($_SESSION['tel_mobile']) ;
			unset($_SESSION['social_skype']) ;
			unset($_SESSION['date_of_birth']) ;
			unset($_SESSION['hours']) ;
			unset($_SESSION['subject']) ;
			unset($_SESSION['emploment_location']) ;
			unset($_SESSION['job_category']) ;
			unset($_SESSION['will_travel']) ;
			unset($_SESSION['has_driving_licence']) ;
			unset($_SESSION['alternate_languages']) ;
			unset($_SESSION['will_do_antisocial']) ;
			unset($_SESSION['availability']) ;
			unset($_SESSION['employment_status']) ;
			// Education
			unset($_SESSION['education_level']) ;
			unset($_SESSION['education_degree_title']) ;
			unset($_SESSION['education_start']) ;
			unset($_SESSION['education_end']) ;
			unset($_SESSION['education_has_graduated']) ;
			unset($_SESSION['education_grade']) ;
			unset($_SESSION['education_institution']) ;
			unset($_SESSION['education_location']) ;
			unset($_SESSION['education_certificate_title']) ;
			// youtube
			unset($_SESSION['youtube_id']) ;
		}
		public function killEmployerSessions(){
			unset($_SESSION['company_name']) ;
			unset($_SESSION['industry_sector']) ;
			unset($_SESSION['send_promo_mails']) ;
		}
		public function killSearchSessions(){
			include('_system/_config/_multiple_choice_arrays.php') ;
			foreach($search_fld_array as $search_fld){
				unset($_SESSION['search_'.$search_fld]) ;
			}
		}
		public function killAddressSessions(){
			include('_system/_config/_address_required_fields_array.php') ;
			foreach($address_all_fields as $address_field){
				unset($_SESSION[$address_field]) ;
				unset($_SESSION['billing_'.$address_field]) ;
			}
		}
		public function killMessageSessions(){
			unset($_SESSION['inbox_message_count']) ;
			unset($_SESSION['inbox_message_last_count']) ;
			unset($_SESSION['unread_messages']) ;
			unset($_SESSION['unread_count_0']) ;
			if(isset($_SESSION['folders_list'])){
				foreach($_SESSION['folders_list'] as $userFolder){
					unset($_SESSION['unread_count_'.$userFolder['folder_id']]) ;
				}
			}
			unset($_SESSION['folders_list']) ;
			unset($_SESSION['namesarray']) ;
			unset($_SESSION['userarray']) ;
		}
		public function killAffiliateSessions(){
			unset($_SESSION['affiliate_id']) ;
			unset($_SESSION['affiliate_username']) ;
			unset($_SESSION['affiliate_signup_date']) ;
			unset($_SESSION['affiliate_payment_name']) ;
			unset($_SESSION['affiliate_payment_address_1']) ;
			unset($_SESSION['affiliate_payment_address_2']) ;
			unset($_SESSION['affiliate_payment_address_town']) ;
			unset($_SESSION['affiliate_payment_address_county']) ;
			unset($_SESSION['affiliate_payment_address_postcode']) ;
			        
		}
	}

?>
