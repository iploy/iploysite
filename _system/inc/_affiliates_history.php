
<div style="float:right;" >
    <ul class="li_buttons" >
        <li><a href="?action=<?php echo $_GET['action'] ; ?>" >Back to the Affililates Homepage</a></li>
    </ul>
</div>

<h1>Affiliate Payments</h1>
<p>Transaction summaries for affiliate payments</p>


<h2>Awaiting Payment (<?php echo sizeof($affiliatesUnpaid) ; ?>)</h2>
<?php

if(sizeof($affiliatesUnpaid)>0){
	echo '<table class="list" cellpadding="0" cellspacing="0" border="0" width="950" style="margin:0 0 34px;" >'."\n" ;
	echo '<tr class="headrow" >'."\n" ;
		echo '<td>Date Requested</td>'."\n" ;
		echo '<td>Payment Value</td>'."\n" ;
		echo '<td>Number of Graduates</td>'."\n" ;
	echo '</tr>'."\n" ;
	$totalValue = 0 ;
	$totalGraduates = 0 ;
	foreach($affiliatesUnpaid as $affiliate){
		$totalValue = $totalValue + $affiliate['payment_amount'] ;
		$totalGraduates = $totalGraduates + $affiliate['number_of_graduates'] ;
		if($rowClass==''){
			$rowClass = ' class="offrow" ' ;
		} else {
			$rowClass = '' ;
		}
		echo '<tr'.$rowClass.'>'."\n" ;
			echo '<td>'.date(DM_PHP_SCREENDATETIME_FORMAT,strtotime($affiliate['request_date'])).'</td>'."\n" ;
			echo '<td>&pound;'.number_format($affiliate['payment_amount'],2).'</td>'."\n" ;
			echo '<td>'.$affiliate['number_of_graduates'].'</td>'."\n" ;
		echo '</tr>'."\n" ;
	}
	// total row
	echo '<tr class="red" >'."\n" ;
		echo '<td></td>'."\n" ;
		echo '<td>&gt; &pound;'.number_format($totalValue,2).' Total</td>'."\n" ;
		echo '<td>&gt; '.$totalGraduates.' Graduates</td>'."\n" ;
	echo '</tr>'."\n" ;
	
	echo '</table>'."\n" ;
} else {
	echo '<p>'.draw_icon(ICON_BAD).'There are no pending payments. If you have more than '.reset($payouts).' graduate referrals <a href="?action='.$_GET['action'].'" >click here</a> to make a payment request.</p>'."\n" ;
}

?>


<h2>Payment Processed (<?php echo sizeof($affiliatesPaid) ; ?>)</h2>
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
			echo '<td>'.date(DM_PHP_SCREENDATETIME_FORMAT,strtotime($affiliate['request_date'])).'</td>'."\n" ;
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
	echo '<p>'.draw_icon(ICON_BAD).'There are no processed payments at this time.</p>'."\n" ;
}

?>