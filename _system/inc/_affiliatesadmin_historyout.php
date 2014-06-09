<?php

?>

<?php

if(sizeof($transactionData)>0){
	?>
    <div style="float:right;" >
    	<ul class="li_buttons" >
        	<li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>" >Return to Payout History</a></li>
        </ul>
    </div>
	<h1>Transaction Details</h1>
    <?php
	// compile the recipient address 
	$recipientAddress = '' ;
	if($transactionData[0]['payment_address_1']!=''){
		$recipientAddress.= $transactionData[0]['payment_address_1'] ;
	}
	if($transactionData[0]['payment_address_2']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$transactionData[0]['payment_address_2']) ;
	}
	if($transactionData[0]['payment_address_town']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$transactionData[0]['payment_address_town']) ;
	}
	if($transactionData[0]['payment_address_county']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$transactionData[0]['payment_address_county']) ;
	}
	if($transactionData[0]['payment_address_postcode']!=''){
		$recipientAddress.= ($recipientAddress=='' ? '' : ', '.$transactionData[0]['payment_address_postcode']) ;
	}
	?>
    <ul>
    	<li><b>Payee Name</b>: <?php echo $transactionData[0]['payment_name'] ; ?></li>
    	<li><b>Payee Username</b>: <?php echo strtoupper($transactionData[0]['username']) ; ?></li>
    	<li><b>Payee Address</b>: <?php echo $recipientAddress ; ?></li>
    	<li><b>Transaction Value</b>: &pound;<?php echo number_format($transactionData[0]['payment_amount'],2) ; ?></li>
    	<li><b>Number of Graduates</b>: <?php echo $transactionData[0]['number_of_graduates'] ; ?></li>
    	<li><b>Date Requested</b>: <?php echo date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($transactionData[0]['request_date'])) ; ?></li>
    	<li><b>Date Paid</b>: <?php echo date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($transactionData[0]['payment_date'])) ; ?></li>
    </ul>
    <?php
	// list transaction detail
	echo '<table class="list" cellpadding="0" cellspacing="0" border="0" width="950" style="margin:0 0 34px;" >'."\n" ;
	echo '<tr class="headrow" >'."\n" ;
		echo '<td>Graduate Name</td>'."\n" ;
		echo '<td>Signup Date</td>'."\n" ;
		echo '<td>Email</td>'."\n" ;
		echo '<td>Telephone</td>'."\n" ;
		echo '<td width="16" ></td>'."\n" ;
	echo '</tr>'."\n" ;
	// loop the tings
	foreach($transactionData as $transaction){
		$gradName = trim($transaction['first_name'].' '.$transaction['surname']) ;
		if($gradName==''){
			$gradName = '<em class="grey" >Unspecified</em>' ;
		}
		if($rowClass==''){
			$rowClass = ' class="offrow" ' ;
		} else {
			$rowClass = '' ;
		}
		echo '<tr'.$rowClass.'>'."\n" ;
			echo '<td>'.draw_icon('graduate.png').'<b>'.$gradName.'</b></td>'."\n" ;
			echo '<td>'.date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($transaction['date_created'])).'</td>'."\n" ;
			echo '<td><a href="mailto:'.$transaction['email'].'" title="Email This Graduate" >'.$transaction['email'].'</a></td>' ;
			echo '<td>'.$transaction['tel_mobile'].'</td>'."\n" ;
			echo '<td class="options" >' ;
				echo '<a href="message_send.php?profileid='.$transaction['user_id'].'" target="_blank" title="Send Graduate A Message Using iPloy" >'.draw_icon('comments.png').'</a>' ;
				echo '<a href="view_profile.php?profileid='.$transaction['user_id'].'" target="_blank" title="View This Graduate\'s Profile" >'.draw_icon('certificate.png').'</a>' ;
			echo '</td>'."\n" ;
		echo '</tr>'."\n" ;
	}
	echo '</table>'."\n" ;



} else {
	// list payouts =====================================================================================================================
	?>
	<h1>Payout History</h1>
    <?php
	if(sizeof($affiliatesPaid)>0){
	
		echo '<table class="list" cellpadding="0" cellspacing="0" border="0" width="950" style="margin:0 0 34px;" >'."\n" ;
		echo '<tr class="headrow" >'."\n" ;
			echo '<td>Date Requested</td>'."\n" ;
			echo '<td>Date Paid</td>'."\n" ;
			echo '<td>Payment Value</td>'."\n" ;
			echo '<td>Number of Graduates</td>'."\n" ;
		echo '</tr>'."\n" ;
		$totalValue = 0 ;
		$totalGraduates = 0 ;
		foreach($affiliatesPaid as $affiliate){
			$totalValue = $totalValue + $affiliate['payment_amount'] ;
			$totalGraduates = $totalGraduates + $affiliate['number_of_graduates'] ;
			if($rowClass==''){
				$rowClass = ' class="offrow" ' ;
			} else {
				$rowClass = '' ;
			}
			echo '<tr'.$rowClass.'>'."\n" ;
				echo '<td>'.draw_icon('calendar.png').'<a href="?action='.$_GET['action'].'&subaction='.$_GET['subaction'].'&paymentid='.$affiliate['payment_id'].'" >'.date(DM_PHP_SCREENDATETIME_FORMAT,strtotime($affiliate['request_date'])).'</a></td>'."\n" ;
				echo '<td>'.date(DM_PHP_SCREENDATETIME_FORMAT,strtotime($affiliate['payment_date'])).'</td>'."\n" ;
				echo '<td>&pound;'.$affiliate['payment_amount'].'</td>'."\n" ;
				echo '<td>'.$affiliate['number_of_graduates'].'</td>'."\n" ;
			echo '</tr>'."\n" ;
		}
		// total row
		echo '<tr class="red" >'."\n" ;
			echo '<td></td>'."\n" ;
			echo '<td></td>'."\n" ;
			echo '<td>&gt; &pound;'.number_format($totalValue,2).' Total</td>'."\n" ;
			echo '<td>&gt; '.$totalGraduates.' Graduates</td>'."\n" ;
		echo '</tr>'."\n" ;
		echo '</table>'."\n" ;
	
	} else {
		// no payouts
		echo '<p>'.draw_icon(ICON_ALERT).'There are no recorded transactions so far</p>'."\n" ;
	}

}

?>