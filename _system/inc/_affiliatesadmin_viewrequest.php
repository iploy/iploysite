
<h1>Payment Request - Details</h1>
<?php

if(sizeof($requestReferrals)>0){
	// compile the recipient address
	$recipientAddress = '' ;
	if($recipient['payment_address_1']!=''){
		$recipientAddress.= $recipient['payment_address_1'] ;
	}
	if($recipient['payment_address_2']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$recipient['payment_address_2']) ;
	}
	if($recipient['payment_address_town']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$recipient['payment_address_town']) ;
	}
	if($recipient['payment_address_county']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$recipient['payment_address_county']) ;
	}
	if($recipient['payment_address_postcode']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$recipient['payment_address_postcode']) ;
	}
	?>
	<ul>
    	<li><b>Payee Name</b>: <?php echo $recipient['payment_name'] ; ?></li>
    	<li><b>Payee Address</b>: <?php echo $recipientAddress ; ?></li>
        <li><b>Referral Claim Value</b>: &pound;<?php echo number_format($requestReferrals[0]['payment_amount'],2) ; ?></li>
        <li><b>Date Requested</b>: <?php echo date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($requestReferrals[0]['request_date'])) ; ?></li>
        <li><b>Options</b>: <?php echo draw_icon('email.png') ; ?><a href="mailto:<?php echo $recipient['email'] ; ?>" >Email This Affilite</a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo draw_icon('comments.png') ; ?><a href="message_send.php?profileid=<?php echo $recipient['user_id'] ; ?>" target="_blank" >Send Affiliate A Message Using iPloy</a>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo draw_icon('certificate.png') ; ?><a href="view_profile.php?profileid=<?php echo $recipient['user_id'] ; ?>" target="_blank" >View This Affiliate's Profile</a></li>
	</ul>
<?php
	$showMarkPaid = true ;
	foreach($requestReferrals as $referral){
		if($referral['is_declined']==1){
			$showMarkPaid = false ;
		}
	}
?>

	<ul class="li_buttons" >
    <?php
	if($showMarkPaid==true){
		?>
    	<li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&requestid=<?php echo $_GET['requestid'] ; ?>&process=paid" onclick="return confirm('Are you sure you wish to mark this request as paid?\n\nThis action can not be undone.');" >Mark This Request as Paid</a></li>
    	<li><a class="grey" href="#" >Decline This Payment Request</a></li>
        <?php
	} else {
		?>
    	<li><a class="grey" href="#" >Mark This Request as Paid</a></li>
    	<li><a onclick="return confirm('Are you sure you wish to mark this decline this payment request?\n\nThis action can not be undone.');" href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&requestid=<?php echo $_GET['requestid'] ; ?>&process=reject" >Decline This Payment Request</a></li>
        <?php
	}
	?>
    </ul>

    <?php
	if($showMarkPaid==false){
		?>
        <p><?php echo draw_icon(ICON_ALERT) ; ?><b>Note</b>: You can not mark this request as paid as there are declined referrals.</p>
        <?php
	}
	?>

	<?php

	echo '<table class="list" cellpadding="0" cellspacing="0" border="0" width="950" style="margin:16px 0;" >'."\n" ;
	echo '<tr class="headrow" >'."\n" ;
		echo '<td>Referred Graduate</td>'."\n" ;
		echo '<td>Signup Date</td>'."\n" ;
		echo '<td>Referral Cost</td>'."\n" ;
		echo '<td width="16" ></td>'."\n" ;
	echo '</tr>'."\n" ;
	foreach($requestReferrals as $referral){
		$userLabel = trim($referral['first_name'].' '.$referral['surname']) ;
		if($userLabel==''){
			$userLabel = $referral['email'] ;
		}
		//
		if($rowClass==''||$rowClass=='red'){
			$rowClass = 'offrow' ;
		} else {
			$rowClass = '' ;
		}
		if($referral['is_declined']==1){
			$rowClass.= 'red' ;
		}
		echo '<tr class="'.$rowClass.'" >'."\n" ;
			echo '<td><b>'.$userLabel.'</b></td>'."\n" ;
			echo '<td>'.date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($referral['date_created'])).'</td>'."\n" ;
			echo '<td>&pound;'.number_format($referral['payment_amount']/sizeof($requestReferrals),2).'</td>'."\n" ;
			echo '<td class="options" >' ;
				echo '<a href="mailto:'.$referral['email'].'" title="Send this graduate an email" >'.draw_icon('email.png').'</a>' ;
				echo '<a href="message_send.php?profileid='.$referral['user_id'].'" target="_blank" title="Send this graduate a message using the iPloy system" >'.draw_icon('comments.png').'</a>' ;
				echo '<a href="view_profile.php?profileid='.$referral['user_id'].'" target="_blank" title="View this graduate\'s graduate profile" >'.draw_icon('certificate.png').'</a>' ;
				echo '<a href="home.php?action='.$_GET['action'].'&subaction=togglerejected&requestid='.$_GET['requestid'].'&referralid='.$referral['referral_id'].'" title="Decline this referral" >'.draw_icon('deny.png').'</a>' ;
			echo '</td>'."\n" ;
		echo '</tr>'."\n" ;
	}
	echo '</table>'."\n" ;

} else {
	echo '<p>'.draw_icon(ICON_ALERT).'An error occured</p>' ;
}

?>