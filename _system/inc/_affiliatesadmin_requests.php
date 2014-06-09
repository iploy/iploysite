
<h1>Pending Payment Requests</h1>
<?php

if(sizeof($payment_requests)>0){
	?>
    <p><?php echo draw_icon(ICON_ALERT) ; ?>Click on a &quot;payment ID&quot; for further information and options for payment confirmation and rejection.</p>
    <?php
	echo '<table class="list" cellpadding="0" cellspacing="0" border="0" width="950" style="margin:16px 0;" >'."\n" ;
	echo '<tr class="headrow" >'."\n" ;
		echo '<td>Payment ID</td>'."\n" ;
		echo '<td>Payee Name</td>'."\n" ;
		echo '<td>Request Date</td>'."\n" ;
		echo '<td>Claim Type</td>'."\n" ;
		echo '<td width="16" ></td>'."\n" ;
	echo '</tr>'."\n" ;
	foreach($payment_requests as $request){
		//
		if($rowClass==''){
			$rowClass = ' class="offrow" ' ;
		} else {
			$rowClass = '' ;
		}
		echo '<tr'.$rowClass.'>'."\n" ;
			echo '<td><a href="?action='.$_GET['action'].'&subaction=viewrequest&requestid='.$request['payment_id'].'" >'.$request['payment_id'].'</a></td>'."\n" ;
			echo '<td>'.$request['payment_name'].'</td>'."\n" ;
			echo '<td>'.date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($request['request_date'])).'</td>'."\n" ;
			echo '<td>'.$request['number_of_graduates'].' graduates @ &pound;'.$request['payment_amount'].'</td>'."\n" ;
			echo '<td class="options" >' ;
				echo '<a href="message_send.php?profileid='.$request['user_id'].'" title="Send this referrer an message using the iPloy system" >'.draw_icon('comments.png').'</a>' ;
				echo '<a href="mailto:'.$request['email'].'" title="Send this referrer an email" >'.draw_icon('email.png').'</a>' ;
				echo '<a href="view_profile.php?profileid='.$request['user_id'].'" target="_blank" title="View this referrer\'s graduate profile" >'.draw_icon('certificate.png').'</a>' ;
			echo '</td>'."\n" ;
		echo '</tr>'."\n" ;
	}
	echo '</table>'."\n" ;

	?>
    <p><?php echo draw_icon(ICON_ALERT) ; ?><b>Please Note</b>: The payee name may not match the name on the referrer's graduate profile.</p>
    <?php

} else {
	?>
    <p><?php echo draw_icon(ICON_BAD) ; ?>There are no pending payment requests</p>
    <?php
}

?>