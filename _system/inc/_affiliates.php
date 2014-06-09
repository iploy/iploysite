<?php

	// display stats homepage for current affiliates
	if($_SESSION['affiliate_id']){

		if($_GET['subaction']=='edit'){
			?>
			<h2>Modify Account Info</h2>
			<?php
			$formAction = 'edit' ;
			include_once(SITE_PATH.'_system/inc/_affiliates_form.php') ;

		} elseif($_GET['subaction']=='history'){
			include_once(SITE_PATH.'_system/inc/_affiliates_history.php') ;

		} else {
		
			?>
            <?php
            if($affiliateTotal>0){
                ?>
                <div style="float:right;" >
                    <ul class="li_buttons" >
                        <li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=history" >View Payment Schedule and History</a>	</li>
                    </ul>
                </div>
                <?php
            }
            ?>
			<h1>iPloy Affiliate Home</h1>
			<ul>
				<li><b class="Highlight" >Your Unique Signup URL</b>: <b><?php echo SITE_DOMAIN.'signup/'.$_SESSION['affiliate_username'].'/' ; ?></b></li>
				<li>Graduates you recommend should use this exact web address to start the signup process to be recognised as your referral.</li>
				<li>We recommend copying the URL above and sending it as a hyperlink via email to the graduate to ensure it is entered accurately.</li>
				<li>Remember, you will only be eligible for payment once the graduate completes their profile and uploads a photo and CV</li>
				<li>Please note: uploads only register on the referrer after confirmation by our administrators. This can take up to 72 hours at peak times.</li>
                <li>Payments will be eligible after at least 5 graduates have completed their profile and uploaded a photograph and CV.</li>
			</ul>



			<h2>Referrals Summary</h2>
			<?php
			// start if main
			if($affiliateTotal>0){
				?>
				<div class="float_container" >
					<div class="floater" style="margin-right:0;" >
					<?php
					// Unconfimred Files
						$referralsGraph[] = array('Unconfirmed',sizeof($affiliateUnconfirmed)) ;
						$referralsGraph[] = array('Incomplete',sizeof($affiliateConfirmed)) ;
						$referralsGraph[] = array('Eligible',sizeof($affiliateEligible)) ;
						$referralsGraph[] = array('Requeted',sizeof($affiliateRequested)) ;
						$referralsGraph[] = array('Paid',sizeof($affiliatePaid)) ;
						$_SESSION['referrals'] = array() ;
						$_SESSION['referrals']['width'] = 380 ;
						$_SESSION['referrals']['height'] = 250 ;
						$_SESSION['referrals']['data'] = $referralsGraph ;
						$_SESSION['referrals']['colours'] = array('#D10513', '#E5AA09', '#06CC3A', '#004C8D', '#555555');
						echo '<img src="'.SITE_FOLDER.'images/phplot/phplot_img.php?session=referrals" width="'.$_SESSION['filesGraph']['width'].'" height="'.$_SESSION['filesGraph']['height'].'" alt="" title="'.$_SESSION['filesGraph']['title'].'" />' ;
					?>
					</div>
					<div class="floater" >
						<p><b class="Highlight" >Unconfirmed Graduate Accounts</b>: - <?php echo sizeof($affiliateUnconfirmed).' Graduate'.(sizeof($affiliateUnconfirmed)!=1 ? 's' : '') ; ?><br />
						These users followed the affiliate link, but have not yet confirmed their email address.</p>
						<p><b class="Highlight" >Confirmed But Incomplete</b>: - <?php echo sizeof($affiliateConfirmed).' Graduate'.(sizeof($affiliateConfirmed)!=1 ? 's' : '') ; ?><br />
						These users have confirmed their email, but have not completed their profile.</p>
						<p><b class="Highlight" >Unpaid Eligible</b>: <?php echo sizeof($affiliateEligible).' Graduate'.(sizeof($affiliateEligible)!=1 ? 's' : '') ; ?><br />
						These users have completed their profile and you are eligible for payment.</p>
						<p><b class="Highlight" >Payment Requested</b>: <?php echo sizeof($affiliateRequested).' Graduate'.(sizeof($affiliateRequested)!=1 ? 's' : '') ; ?><br />
						These are eligible referrals for which payment has been requested.<br />
						<a href="?action=<?php echo $_GET['action'] ; ?>&subaction=history" >Click here to view your payment history</a></p>
						<p><b class="Highlight" >Paid</b>: <?php echo sizeof($affiliatePaid).' Graduate'.(sizeof($affiliatePaid)!=1 ? 's' : '') ; ?><br />
						These are eligible referrals for which payment has been sent.<br />
						<a href="?action=<?php echo $_GET['action'] ; ?>&subaction=history" >Click here to view your payment history</a></p>
					</div>
				</div>
				<?php
				// Claim Payment Links --------------------------------------------------------------------
				if(sizeof($affiliateEligible)>=reset($payouts)){ // we use reset() to get the value of the first member of an assolciative array
					echo '<h2>Claim Payment</h2>'."\n" ;
					echo '<ul class="li_buttons" >'."\n" ;
					foreach($payouts as $cash => $graduates){
						echo '<li>' ;
						if(sizeof($affiliateEligible)>=$graduates){
							echo '<a href="?action='.$_GET['action'].'&subaction=claim&graduates='.$graduates.'" onclick="return confirm(\'You are about to request a payment of &pound;'.$cash.' for '.$graduates.' graduates.\\n\\nAre you sure you wish to do this?\');" >' ; 
						} else {
							echo '<a class="grey" >' ;
						}
						echo 'Claim &pound;'.$cash.' for '.$graduates.' Graduates' ;
						echo '</a>' ;
						echo '</li>'."\n" ;
					}
					echo '</ul>'."\n" ;
				}
				// Claim Payment Links --------------------------------------------------------------------
	
	
				function doAffiliateList($data){
					$lastPaymentId = '' ;
					// open table
					echo '<table width="946" class="list" cellpadding="0" cellspacing="0" border="0" style="margin:0px; font-size:12px;" >'."\n" ;
					// Head Row
					echo '<tr class="headrow redrow" >'."\n" ;
					echo '<td>Name</td>'."\n" ;
					echo '<td>Email</td>'."\n" ;
					echo '<td>Telephone</td>'."\n" ;
					echo '<td>Date Created</td>'."\n" ;
					echo '</tr>'."\n" ;
					// do the data loop
					foreach($data as $row){
						if($lastPaymentId==''||$lastPaymentId!=$row['payment_id']){
							if($rowClass==''){
								$rowClass = 'offrow' ;
							} else {
								$rowClass = '' ;
							}
						}
						$name = trim($row['first_name'].' '.$row['surname']) ;
						if($name==''){
							$name = '<em class="grey" >Profile Incomplete</em>' ;
						}
						$tel = trim($row['tel_mobile']) ;
						if($tel==''){
							$tel = '<em class="grey" >- - - - - - - - - - - - -</em>' ;
						}
						echo '<tr class="'.$rowClass.'" '.($row['payment_id']!='' ? 'title="Payment ID:'.$row['payment_id'].'"' : '').' >'."\n" ;
						echo '<td><b>'.draw_icon('graduate.png').$name.'</b></td>'."\n" ;
						echo '<td><a href="mailto:'.$row['email'].'" >'.$row['email'].'</a></td>'."\n" ;
						echo '<td>'.$tel.'</td>'."\n" ;
						echo '<td>'.date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($row['date_created'])).'</td>'."\n" ;
						echo '</tr>'."\n" ;
						$lastPaymentId = $row['payment_id'] ;
					}
					// close table
					echo '</table>'."\n" ;
				}
				?>
				<h2>Referral Payments</h2>
				<div class="mooZip" >
					<!-- Unpaid Eligible -->
					<?php
					if(sizeof($affiliateEligible)>0){
						?>
						<div class="zip" >
							<h4>Unpaid Eligible Referrals (<?php echo sizeof($affiliateEligible) ; ?>)</h4>
							<?php doAffiliateList($affiliateEligible) ; ?>
						</div>
						<?php
					}
					?>
					<!-- Confirmed But Not Eligible -->
					<?php
					if(sizeof($affiliateConfirmed)>0){
						?>
						<div class="zip off" >
							<h4>Confirmed But Incomplete (<?php echo sizeof($affiliateConfirmed) ; ?>)</h4>
							<?php doAffiliateList($affiliateConfirmed) ; ?>
						</div>
						<?php
					}
					?>
					<!-- Unconfirmed Graduates -->
					<?php
					if(sizeof($affiliateUnconfirmed)>0){
						?>
						<div class="zip" >
							<h4>Unconfirmed Graduates (<?php echo sizeof($affiliateUnconfirmed) ; ?>)</h4>
							<?php doAffiliateList($affiliateUnconfirmed) ; ?>
						</div>
						<?php
					}
					?>
                    <!-- Requested Eligible -->
                    <?php
                    if(sizeof($affiliateRequested)>0){
                        ?>
                        <div class="zip off" >
                            <h4>Payment Requested Referrals (<?php echo sizeof($affiliateRequested) ; ?>)</h4>
                            <?php doAffiliateList($affiliateRequested) ; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <!-- Unpaid Eligible -->
                    <?php
                    if(sizeof($affiliatePaid)>0){
                        ?>
                        <div class="zip" >
                            <h4>Paid Referrals (<?php echo sizeof($affiliatePaid) ; ?>)</h4>
                            <?php doAffiliateList($affiliatePaid) ; ?>
                        </div>
                        <?php
                    }
                    ?>
				</div>
				<?php
			} else {
				?>
                <p><?php echo draw_icon(ICON_ALERT) ; ?>You do not yet have any referrals on your account. More information will be displayed here when you do.</p>
                <?php
			}
			// end if main
			?>
			<div class="greydivider" ></div>
            <p><?php echo draw_icon('cash.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=edit" >Click here to modfy account and payment information</a>.</p>
			<?php
		}

	} else { // =========================================================[ SIGNUP FORM ]================================================================
		// display signup now message for non-affiliates
		if(sizeof($screen_message_array)==0){
			?>
       		<h1>iPloy Affiliate Home</h1>
        	<p>Please complete the information below to sign up as an iPloy affiliate and receive your unique signup URL.</p>
			<?php
			$formAction = 'signup' ;
			include_once(SITE_PATH.'_system/inc/_affiliates_form.php') ;
		}
		?>
		<h2>Enable Affiliate Account</h2>
        <?php

	}

?>