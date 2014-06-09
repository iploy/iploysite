<?php

	// header("Location: ipad_competition_info.php") ;

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	include('_system/inc/_authentication.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	$is_ipad_form_page = true ;

	include('_system/classes/promotions.php') ;
	$promotion_function = new promotion ;
	$promotion_function->setUserId($_SESSION['user_id']) ;
	$promotion_function->setPromoId(IPAD_PROMO_ID) ;
	$_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID] = $promotion_function->userCanEnter() ;
	if($_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID]!='yes'){
		header('Location: ipad_competition_info.php?promo=submited') ;
		exit() ;
	}

	/*
	if($_GET['post']=='true'){
		$field_array = array() ; 
		$field_array[] = 'user_id' ; 
		$field_array[] = 'first_name' ; 
		$field_array[] = 'surname' ; 
		$field_array[] = 'email' ; 
		$field_array[] = 'referal' ; 
		$field_array[] = 'rate_navigation' ; 
		$field_array[] = 'rate_design' ; 
		$field_array[] = 'rate_profile_creation' ; 
		$field_array[] = 'problems_experienced' ; 
		$field_array[] = 'market_to_students' ; 
		$field_array[] = 'improvement_suggestions' ; 
		$required_field_array = array() ; 
		$required_field_errortxt = array() ; 
		$required_field_array[] = 'first_name' ; 
		$required_field_errortxt[] = 'Your <b>First Name</b> is required, please set this in your <a href="home.php" >profile admin</a> screen' ; 
		$required_field_array[] = 'surname' ; 
		$required_field_errortxt[] = 'Your <b>Surname</b> is required, please set this in your <a href="home.php" >profile admin</a> screen' ; 
		$required_field_array[] = 'email' ; 
		$required_field_errortxt[] = 'Your <b>Email</b> is required, please set this in your <a href="home.php" >profile admin</a> screen' ; 
		$required_field_array[] = 'referal' ; 
		$required_field_errortxt[] = 'Please let us know how you <b>heard about the site</b>.' ; 
		$required_field_array[] = 'rate_navigation' ; 
		$required_field_errortxt[] = 'Please rate the sites <b>navigation</b>.' ; 
		$required_field_array[] = 'rate_design' ; 
		$required_field_errortxt[] = 'Please rate the sites <b>design</b>.' ; 
		$required_field_array[] = 'rate_profile_creation' ; 
		$required_field_errortxt[] = 'Please rate how easy you found the <b>profile creation</b>.' ; 
		// check required fields
		$error_msg = '' ;
		for($i=0;$i<sizeof($required_field_array);$i++){
			if($_POST[$required_field_array[$i]]==''){
				$error_msg.= '<span class="errorlist" >'.$required_field_errortxt[$i].'</span> ' ;
			}
		}
		if($error_msg!=''){
			$screen_message = draw_icon(ICON_BAD).'<b>Your form could not be submitted as the following errors occured:</b>'.$error_msg ;
			$screen_message_type = 'error' ;
		} else {
			// Build the message contents
			$msg = '' ;
			foreach($field_array as $field){
				$msg.='<p><b>'.strtoupper(str_replace("_"," ",$field)).'</b><br />'.$_POST[$field].'</p>' ;
			}
			// Try to send the email
			include('_system/classes/email.php') ;
			$email_function = new email ;
			$email_function->setToAddress(IPAD_PROMO_EMAIL) ;
			$email_function->setSubject('iPad Givaway 2 Entry Form') ;
			$email_function->setContents($msg) ;
			$email_send_result = $email_function->send() ;
			if(!$email_send_result===true){
				// email sending error
				$screen_message = draw_icon(ICON_BAD).$email_send_result ;
				$screen_message_type = 'error' ;
			} else {
				// Success, mark as done
				$promotion_function_result = $promotion_function->userHasEntered() ;
				// report to screen
				if($promotion_function_result===true){
					$screen_message = draw_icon(ICON_GOOD).'Your entry into the iPad Givaway promotion was successfully recieved.' ;
					$screen_message_type = 'success' 
					;
					$_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID]='no' ;
				} else {
					$screen_message = draw_icon(ICON_BAD).'An error occured when flagging your promotion entry. Please try again.' ;
					$screen_message_type = 'error' ;
				}
			}
		}
	}
	*/



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : iPad Giveaway</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
<script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
                <h1>The <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> iPad Giveaway!</h1>
                <?php
					if($email_send_result==true||!$_SESSION['user_can_enter_promo_'.IPAD_PROMO_ID]=='yes'){
						?>
                        <p><b>Thank you for your entry in our iPad Giveaway promotion</b>.</p>
                        <p>Winners will be contacted directly by an <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> representative.</p>
                        <p>Please check back regularly for news of upcoming featrures such as Youtube integration, peer-to-peer messaging and our powerful search facilities.</p>
                        <p>We wish you the best of luck with your employment search.</p>
                        <?php
					} else {
						?>
						<p>Please complete the form below for your chance to win a <b>16GB iPad 2 with Wi-Fi</b>.</p>
						<p>Fields marked with a star <b class="red" >(*)</b> are required.</p>
						<form action="?post=true" method="post" name="ipadform" >
						<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ;?>" />
						<label for="first_name" >Name (Set by your profile info)</label>
						<input readonly="readonly" class="text grey" type="text" name="first_name" id="first_name" value="<?php echo $_SESSION['first_name'] ;?>" style="width:120px;" />&nbsp;<input readonly="readonly" class="text grey" type="text" name="surname" id="surname" value="<?php echo $_SESSION['surname'] ;?>" style="width:170px;" />
						<label for="email" >Email Address (Set by your login info)</label>
						<input onchange="isModified();" class="text grey" readonly="readonly" type="text" name="email" id="email" value="<?php echo $_SESSION['email'] ;?>" />
		
						<!-- editable fields -->
						<label for="referal" >How Did You Hear About Us? *</label>
						<div class="lister" >
						<ul>
						<?php
							$referal = array() ;
							$referal[] = 'Search Engine' ;
							$referal[] = 'Link From Another Site' ;
							$referal[] = 'Through University' ;
							$referal[] = 'Word of Mouth' ;
							for($i=0;$i<sizeof($referal);$i++){
								if($referal[$i]==$_POST['referal']){
									$is_selected = 'checked="checked" ' ;
								} else {
									$is_selected = '' ;
								}
								?>
						  <li><span onclick="check_me('referal_<?php echo $i ; ?>','radio');" ><input id="referal_<?php echo $i ; ?>" type="radio" value="<?php echo $referal[$i] ; ?>" name="referal" <?php echo $is_selected ; ?>/><?php echo $referal[$i] ; ?></span></li>
								<?php
							}
						?>
						</ul>
						</div>
		
						<label for="rate_navigation" >Please rate the ease of our navigation system. (10=Best 1=Worst) *</label>
						<div class="lister" >
						<ul class="inline">
						<?php
							for($i=1;$i<=10;$i++){
								if($i==$_POST['rate_navigation']){
									$is_selected = 'checked="checked" ' ;
								} else {
									$is_selected = '' ;
								}
								?>
								<li><span onclick="check_me('rate_navigation_<?php echo $i ; ?>','radio');" ><input id="rate_navigation_<?php echo $i ; ?>" type="radio" value="<?php echo $i ; ?>" name="rate_navigation" <?php echo $is_selected ; ?>/><?php echo $i ; ?></span></li>
								<?php
							}
						?>
						</ul>
						</div>
		
						<div class="lister" >
						<label for="rate_design" >Please the quality of our site design. (10=Best 1=Worst) *</label>
						<ul class="inline">
						<?php
							for($i=1;$i<=10;$i++){
								if($i==$_POST['rate_design']){
									$is_selected = 'checked="checked" ' ;
								} else {
									$is_selected = '' ;
								}
								?>
								<li><span onclick="check_me('rate_design_<?php echo $i ; ?>','radio');" ><input id="rate_design_<?php echo $i ; ?>" type="radio" value="<?php echo $i ; ?>" name="rate_design" <?php echo $is_selected ; ?>/><?php echo $i ; ?></span></li>
								<?php
							}
						?>
						</ul>
						</div>
		
						<div class="lister" >
						<label for="rate_profile_creation" >Please the ease of profile creation. (10=Best 1=Worst) *</label>
						<ul class="inline">
						<?php
							for($i=1;$i<=10;$i++){
								if($i==$_POST['rate_profile_creation']){
									$is_selected = 'checked="checked" ' ;
								} else {
									$is_selected = '' ;
								}
								?>
								<li><span onclick="check_me('rate_profile_creation_<?php echo $i ; ?>','radio');" ><input id="rate_profile_creation_<?php echo $i ; ?>" type="radio" value="<?php echo $i ; ?>" name="rate_profile_creation" <?php echo $is_selected ; ?>/><?php echo $i ; ?></span></li>
								<?php
							}
						?>
						</ul>
						</div>
		
		
						<label for="problems_experienced" >Did you experience any problems with the site?</label>
						<textarea name="problems_experienced" id="problems_experienced" class="text" style="width:500px; height:140px;" ></textarea>
		
		
						<label for="market_to_students" >If you were to market this site to students and graduates, how would you do so?<br />
						<span class="black" >(The more creative the better)</span></label>
						<textarea name="market_to_students" id="market_to_students" class="text" style="width:500px; height:140px;" ></textarea>
		
		
						<label for="improvement_suggestions" >Do you have any suggestions or improvements we could make to the website?<br />
						<span class="black" >e.g. Operation of the site, services the site offers, etc</span></label>
						<textarea name="improvement_suggestions" id="improvement_suggestions" class="text" style="width:500px; height:140px;" ></textarea>
		
						<div><input type="submit" name="gradsubmit" id="gradsubmit" value="Submit" /></div>
		
		
						</form>
                    <?php
					}
				?>
		  </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
