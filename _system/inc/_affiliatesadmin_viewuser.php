
<?php
if($affiliateTotal>0){
	?>
	<div style="float:right;" >
		<ul class="li_buttons" >
	    	<li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=history&affiliateId=<?php echo $_GET['affiliateId'] ; ?>" >View Payment Schedule and History</a>	</li>
	    </ul>
	</div>
	<?php
}
?>

<h1>Referrals Summary  for <?php echo $user_name ; ?></h1>
<ul>
	<li><b class="Highlight" >Referrer Name</b>: <?php echo trim($affiliateInfo['payment_name']) ; ?> (<?php echo $user_level_labels[$affiliateInfo['user_level']] ; ?>)</li>
	<li><b class="Highlight" >Referrer Email</b>: <a href="mailto:<?php echo $affiliateInfo['email'] ; ?>" ><?php echo $affiliateInfo['email'] ; ?></a></li>
</ul>
<?php
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
            These users have not yet completed their profile and you are not eligible for payment.</p>
            <p><b class="Highlight" >Unpaid Eligible</b>: <?php echo sizeof($affiliateEligible).' Graduate'.(sizeof($affiliateEligible)!=1 ? 's' : '') ; ?><br />
            These users have completed their profile and the affiliate is eligible for payment.</p>
            <p><b class="Highlight" >Payment Requested</b>: <?php echo sizeof($affiliateRequested).' Graduate'.(sizeof($affiliateRequested)!=1 ? 's' : '') ; ?><br />
            These are eligible referrals for which payment has been requested.<br />
            <a href="?action=<?php echo $_GET['action'] ; ?>&subaction=history&affiliateId=<?php echo $_GET['affiliateId'] ; ?>" >Click here to view this user's payment history</a></p>
            <p><b class="Highlight" >Paid</b>: <?php echo sizeof($affiliatePaid).' Graduate'.(sizeof($affiliatePaid)!=1 ? 's' : '') ; ?><br />
            These are eligible referrals for which payment has been sent.<br />
            <a href="?action=<?php echo $_GET['action'] ; ?>&subaction=history&affiliateId=<?php echo $_GET['affiliateId'] ; ?>" >Click here to view this user's payment history</a></p>
		</div>
	</div>
	
	
	<?php
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
    <p><?php echo draw_icon(ICON_BAD) ; ?>This user does not yet have any referrals.</p>
    <?php
}
?>