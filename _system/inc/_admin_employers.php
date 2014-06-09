<?php

	include_once('_system/functions/employer_profile_progress.php') ;

	// Process the submission
	if($_GET['subaction']=='saveprofile'){
		include_once('_system/classes/employer_admin.php') ;
		$employer_admin_function = new employer_admin ;
		$employer_admin_function->setUserId($_SESSION['user_id']) ;
		$emploer_profile_save = $employer_admin_function->updateUser($_POST,$employer_required_fields,true) ;
		if($emploer_profile_save===true){
			$screen_message = draw_icon(ICON_GOOD).'The changes to your profile were saved successfully.' ;
			$screen_message_type = 'success' ;
		} else  {
			$screen_message = draw_icon(ICON_BAD).$emploer_profile_save ;
			$screen_message_type = 'error' ;
		}
	}

	include('_system/inc/_screen_message_handler.php') ;

	// set the profile progress after update
	$profile_progress = employer_profile_progress($employer_required_fields,$_SESSION) ;

	if(($profile_progress==100||$_GET['action']=='profile')||$_SESSION['user_level']==0){

		include_once('_system/classes/credits.php') ;
		$credit_function = new credits ;


		/* THIS CODE SHOULD BE REMOVED SOON */
		if($_GET['action']=='credits'&&($_GET['subaction']!='add1'||$_GET['subaction']!='add5'||$_GET['subaction']!='spend1'||$_GET['subaction']!='spend5')&&(strstr($_SESSION['email'],'@devmac.co.uk')||strstr($_SESSION['email'],'@iploy.co.uk'))){
			if(is_numeric(str_replace('add','',$_GET['subaction']))){
				$credits_total = $credit_function->addCredits($_SESSION['user_id'],str_replace('add','',$_GET['subaction'])) ;
			}
			if(is_numeric(str_replace('spend','',$_GET['subaction']))){
				$credits_total = $credit_function->spendCredits($_SESSION['user_id'],str_replace('spend','',$_GET['subaction'])) ;
			}
		}
		/* THIS CODE SHOULD BE REMOVED SOON */
	

	
		// check current balance
		$credits_total = $credit_function->checkCredits($_SESSION['user_id']) ;
		if($_GET['action']=='changepass'){
			$screen_message = '' ;
			if($_GET['subaction']=='changepass'){
				include('_system/inc/_change_password_process.php') ;
			}
			// Write messages for image admin sub actions
			include('_screen_message_handler.php') ;
			?>
			<h1>Change Password</h1>
			<p>Please fill out the information below to change your password</p>
			<?php include('_system/inc/_change_password_form.php') ; ?>
			<?php 
		} elseif($_GET['action']=='credits'){
			include_once('_system/inc/_admin_employers_credits.php') ;
	
		} elseif($_GET['action']=='purchasehistory'){
			include_once('_system/inc/_admin_employers_purchasehistory.php') ;
	
		} elseif($_GET['action']=='shortlists'){
			include_once('_system/inc/_admin_employers_shortlists.php') ;
	
		} elseif($_GET['action']=='vacancies'){
			include_once('_system/inc/_admin_employers_vacancies.php') ;
	
		} elseif($_GET['action']=='profile'){
			include_once('_system/inc/_employer_profile.php') ;
	
		} elseif($_GET['action']=='addresses'){
			include_once('_system/inc/_employer_addresses.php') ;
	
		}elseif($_GET['action']=='messages'){
			if($_GET['subaction']=='read'&&$_GET['messageid']!=''&&is_numeric($_GET['messageid'])){
				include('_system/inc/_message_read.php') ;
			} else {
				include('_system/inc/_messages.php') ;
			}
		
		}else {
	
			// Employer Admin Home
			?>
			<h3>Employer Account Administration</h3>
			<?php
				if($credits_total==0){
					?>
					<p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?><b>WARNING</b>: You will not be able to view full accounts, CV's or Images as you have no credits on your account. <a href="?action=credits" ><b>Click here to buy credits now</b></a>.</p>
					<?php
				}
			?>
			<ul>
				<li><b>Credits:</b> <?php echo $credits_total ; ?></li>
			</ul>
			<h3>Employer Options</h3>
			<ul class="licon limar" >
				<li><?php echo draw_icon('search.png') ; ?><b><a href="search.php" >Find Graduates</a></b><br />
				Use our advanced search engine to find candidates for your vacancies.</li>
				<li><?php echo draw_icon('email.png') ; ?><b><a href="home.php?action=messages" >Messages (<?php echo $_SESSION['unread_messages'] ; ?>)</a></b><br />
				View your inbox, message folders and sent items.</li>
				<li><?php echo draw_icon('plus.png') ; ?><b><a href="?action=shortlists" >Edit Shortlists</a></b><br />
				View graduates on your shortlists and wishlist with messaging and profile options.</li>
				<li><?php echo draw_icon('graduate.png') ; ?><b><a href="?action=shortlists&subaction=viewshortlist&vacancyid=purchased" >View Purchased Graduates</a></b><br />
				View a list of all of your unlocked graduates.</li>
				<li><?php echo draw_icon('briefcase.png') ; ?><b><a href="?action=vacancies" >Vacancy Admin</a></b><br />
				Add, Edit and Remove current vacancies. Vacancies can be used to create shortlists of candidates before purchasing profiles.</li>
				<li><?php echo draw_icon('cash.png') ; ?><b><a href="?action=credits" >Buy Credits</a></b><br />
				Purchase individual graduate credits or save money with our bulk credit options.</li>
				<li><?php echo draw_icon('calendar.png') ; ?><b><a href="?action=purchasehistory" >Purchase History</a></b><br />
				Review your transaction history including credit purchases and graduate profile unlock requests.</li>
				<li><?php echo draw_icon('building.png') ; ?><b><a href="?action=addresses" >Address Book</a></b><br />
				View and edit your default addresses and alternate billing addresses.</li>
				<li><?php echo draw_icon('user.png') ; ?><b><a href="?action=profile" >Employer Profile</a></b><br />
				Modify your employer profile information.</li>
			</ul>
            
			<?php
	
		}

	} else {
		/*include('_system/inc/_employer_profile.php') ;*/

		//header('Location: '.SITE_FOLDER.'home.php?action=profile') ;
		//exit() ;
	}

?>