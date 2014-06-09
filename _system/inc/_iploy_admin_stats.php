
<style type="text/css" >
.stats {
    margin:0 0 16px 16px;
}
.stats tr {
    background:#E1E1E1;
}
.stats tr.onrow {
    background:#F4F4F4;
}
.stats td {
    padding:4px ;
    padding:4px 30px 4px 10px;
}
.stats .headrow td {
    font-weight:bold;
    text-transform:uppercase;
    background:#999;
    color:#FFF;
}
.stats .summary td {
    border-top:1px solid #999;
}
.stats .label {
    font-weight:bold;
}
</style>
<?php
    $user_level_stats_info = new user_info ;
    $total_users = 0 ;
    $total_active_users = 0 ;
	$total_unconfirmed_users = 0 ;
	$total_suspended_users = 0 ;
	$total_inactive_users = 0 ;

	$total_photos = $confirm_count_function->countAllFiles('photo') ;
	$total_cvs = $confirm_count_function->countAllFiles('cv') ;
	$total_certificate = $confirm_count_function->countAllFiles('certificate') ;

	$graduatesGraph = array() ;
?>
<h4>User Accounts</h4>
<table cellpadding="0" cellspacing="0" border="0" class="stats" width="918" >
    <tr class="headrow" >
        <td>User Type</td>
        <td>Total Users</td>
        <td>Unconfirmed Accounts</td>
        <td>Active Users</td>
        <td>Suspended Users</td>
        <td>Inactive Users *</td>
    </tr>
    <?php
        $user_level_stats = $user_level_stats_info->userLevelStats(1) ;
        $total_users = $total_users + $user_level_stats['total_users'] ;
        $total_active_users = $total_active_users + $user_level_stats['total_active'] ;
        $total_unconfirmed_users = $total_unconfirmed_users + $user_level_stats['total_unconfirmed'] ;
        $total_suspended_users = $total_suspended_users + $user_level_stats['total_suspended'] ;
		$total_inactive_users = $total_inactive_users + $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
		$inactive_users = $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
		// Graduates Graph Data
		$graduatesGraph[] = array('Active',$user_level_stats['total_active']) ;
		$graduatesGraph[] = array('Unconfirmed',$user_level_stats['total_unconfirmed']) ;
		$graduatesGraph[] = array('Suspended',$user_level_stats['total_suspended']) ;
    ?>
    <tr class="onrow" >
        <td class="label" >Graduates:</td>
        <td><?php echo $user_level_stats['total_users'] ; ?></td>
        <td><?php echo $user_level_stats['total_unconfirmed'].' ('.$user_level_stats['percent_unconfirmed'].'%)' ; ?></td>
        <td><?php echo $user_level_stats['total_active'].' ('.$user_level_stats['percent_active'].'%)' ; ?></td>
        <td><?php echo $user_level_stats['total_suspended'].' ('.$user_level_stats['percent_suspended'].'%)' ; ?></td>
       	<td><?php echo $inactive_users.' ('.round(($inactive_users/$user_level_stats['total_users'])*100,0).'%)' ; ?></td>
    </tr>
    <?php
        $user_level_stats = $user_level_stats_info->userLevelStats(2) ;
        $total_users = $total_users + $user_level_stats['total_users'] ;
        $total_active_users = $total_active_users + $user_level_stats['total_active'] ;
        $total_unconfirmed_users = $total_unconfirmed_users + $user_level_stats['total_unconfirmed'] ;
        $total_suspended_users = $total_suspended_users + $user_level_stats['total_suspended'] ;
		$total_inactive_users = $total_inactive_users + $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
		$inactive_users = $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
		// Employers Graph Data
		$employerGraph[] = array('Active',$user_level_stats['total_active']) ;
		$employerGraph[] = array('Unconfirmed',$user_level_stats['total_unconfirmed']) ;
		$employerGraph[] = array('Suspended',$user_level_stats['total_suspended']) ;
    ?>
    <tr>
        <td class="label" >Employers:</td>
        <td><?php echo $user_level_stats['total_users'] ; ?></td>
        <td><?php echo $user_level_stats['total_unconfirmed'].' ('.$user_level_stats['percent_unconfirmed'].'%)' ; ?></td>
        <td><?php echo $user_level_stats['total_active'].' ('.$user_level_stats['percent_active'].'%)' ; ?></td>
        <td><?php echo $user_level_stats['total_suspended'].' ('.$user_level_stats['percent_suspended'].'%)' ; ?></td>
       	<td><?php echo $inactive_users.' ('.round(($inactive_users/$user_level_stats['total_users'])*100,0).'%)' ; ?></td>
    </tr>
    <?php
        $user_level_stats = $user_level_stats_info->userLevelStats(3) ;
        $total_users = $total_users + $user_level_stats['total_users'] ;
        $total_active_users = $total_active_users + $user_level_stats['total_active'] ;
        $total_unconfirmed_users = $total_unconfirmed_users + $user_level_stats['total_unconfirmed'] ;
        $total_suspended_users = $total_suspended_users + $user_level_stats['total_suspended'] ;
		$total_inactive_users = $total_inactive_users + $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
		$inactive_users = $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
    ?>
    <tr class="onrow" >
        <td class="label" ><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> Administrators:</td>
        <td><?php echo $user_level_stats['total_users'] ; ?></td>
        <td><?php echo $user_level_stats['total_unconfirmed'].' ('.$user_level_stats['percent_unconfirmed'].'%)' ; ?></td>
        <td><?php echo $user_level_stats['total_active'].' ('.$user_level_stats['percent_active'].'%)' ; ?></td>
        <td><?php echo $user_level_stats['total_suspended'].' ('.$user_level_stats['percent_suspended'].'%)' ; ?></td>
       	<td><?php echo $inactive_users.' ('.round(($inactive_users/$user_level_stats['total_users'])*100,0).'%)' ; ?></td>
    </tr>
    <?php
		if(strstr($_SESSION['email'],'@devmac.co.uk')){
			$user_level_stats = $user_level_stats_info->userLevelStats(0) ;
			$total_users = $total_users + $user_level_stats['total_users'] ;
			$total_active_users = $total_active_users + $user_level_stats['total_active'] ;
			$total_unconfirmed_users = $total_unconfirmed_users + $user_level_stats['total_unconfirmed'] ;
			$total_suspended_users = $total_suspended_users + $user_level_stats['total_suspended'] ;
			$total_inactive_users = $total_inactive_users + $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
			$inactive_users = $user_level_stats['total_unconfirmed'] + $user_level_stats['total_suspended'] ;
			?>
			<tr>
				<td class="label" >Super Users:</td>
				<td><?php echo $user_level_stats['total_users'] ; ?></td>
				<td><?php echo $user_level_stats['total_unconfirmed'].' ('.$user_level_stats['percent_unconfirmed'].'%)' ; ?></td>
				<td><?php echo $user_level_stats['total_active'].' ('.$user_level_stats['percent_active'].'%)' ; ?></td>
				<td><?php echo $user_level_stats['total_suspended'].' ('.$user_level_stats['percent_suspended'].'%)' ; ?></td>
       			<td><?php echo $inactive_users.' ('.round(($inactive_users/$user_level_stats['total_users'])*100,0).'%)' ; ?></td>
			</tr>
			<?php
		}
	?>
    <tr class="onrow summary" >
        <td class="label" >Total Users:</td>
        <td><?php echo $total_users ;?></td>
        <td><?php echo $total_unconfirmed_users.' ('.round(($total_unconfirmed_users/$total_users)*100,0).'%)' ; ?></td>
        <td><?php echo $total_active_users.' ('.round(($total_active_users/$total_users)*100,0).'%)' ; ?></td>
        <td><?php echo $total_suspended_users.' ('.round(($total_suspended_users/$total_users)*100,0).'%)' ; ?></td>
        <td><?php echo $total_inactive_users.' ('.round(($total_inactive_users/$total_users)*100,0).'%)' ; ?></td>
    </tr>
</table>
<p>* Inactive Users refers the number of unconfirmed accounts plus suspended accounts</p>
<div class="divider" style="margin-bottom:0;" ></div>


<div style="overflow:auto;" >

    <div style="float:left; width:50%;">
    <h4>Uploaded Files</h4>
    <table cellpadding="0" cellspacing="0" border="0" class="stats" width="444" >
        <tr class="headrow" >
            <td>File Type</td>
            <td>Unconfirmed</td>
            <td>Confirmed</td>
        </tr>
        <tr >
            <td class="label" >Photographs</td>
            <td><?php echo $unconfirmed_photos ; ?></td>
            <td><?php echo $total_photos-$unconfirmed_photos ; ?></td>
        </tr>
        <tr class="onrow">
            <td class="label" >Curriculum Vitaes</td>
            <td><?php echo $unconfirmed_cvs ; ?></td>
            <td><?php echo $total_cvs-$unconfirmed_cvs ; ?></td>
        </tr>
        <tr>
            <td class="label" >Certificates</td>
            <td><?php echo $unconfirmed_certificates ; ?></td>
            <td><?php echo $total_certificate-$unconfirmed_certificates ; ?></td>
        </tr>
    </table>
    </div>

	<?php
		include_once(SITE_PATH.'_system/classes/transactions.php') ;
		$purchaseStats = new transactions ;
		$psCreditsThisMonth = $purchaseStats->getSummary('credits','basic',date('Y-m-1 00:00:00'),date('Y-m-t 23:59:59')) ;
		$psCreditsTotal = $purchaseStats->getSummary('credits','basic') ;
		$psProfilesThisMonth = $purchaseStats->getSummary('profiles','count',date('Y-m-1 00:00:00'),date('Y-m-t 23:59:59')) ;
		$psProfilesTotal = $purchaseStats->getSummary('profiles','count') ;
		// this month credits count
		$creditsThisMonth = 0 ;
		$creditsValueThisMonth = 0 ;
		foreach($psCreditsThisMonth as $psCredit){
			$creditsThisMonth = $creditsThisMonth + floatval($psCredit['product_name']) ;
			$creditsValueThisMonth = $creditsValueThisMonth + $psCredit['product_price'] ;
		}
		// total credits count
		$creditsTotal = 0 ;
		$creditsValueTotal = 0 ;
		foreach($psCreditsTotal as $psCredit){
			$creditsTotal = $creditsTotal + floatval($psCredit['product_name']) ;
			$creditsValueTotal = $creditsValueTotal + $psCredit['product_price'] ;
		}
	?>
    <div style="float:left; width:50%;" >
    <h4>Purchases <em>- <a href="home.php?action=purchasereport" >Click here to start a report</a></em></h4>
    <table cellpadding="0" cellspacing="0" border="0" class="stats" width="444" >
        <tr class="headrow" >
            <td>Purchase Type</td>
            <td>This Month</td>
            <td>All Time</td>
        </tr>
        <tr>
            <td class="label" >Credits Purchased</td>
            <td><?php echo $creditsThisMonth ; ?></td>
            <td><?php echo $creditsTotal ; ?></td>
        </tr>
        <tr class="onrow">
            <td class="label" >Purchase Value</td>
            <td>&pound;<?php echo number_format($creditsValueThisMonth,2) ; ?></td>
            <td>&pound;<?php echo number_format($creditsValueTotal,2) ; ?></td>
        </tr>
        <tr >
            <td class="label" >Profiles Unlocked</td>
            <td><?php echo $psProfilesThisMonth ; ?></td>
            <td><?php echo $psProfilesTotal ; ?></td>
        </tr>
    </table>
    </div>

</div>



<div class="divider" ></div>
<div align="center" >
<?php

// Graduates Graph
	$_SESSION['gradGraph'] = array() ;
	$_SESSION['gradGraph']['title'] = 'Gratuate Statistics' ;
	$_SESSION['gradGraph']['width'] = 460 ;
	$_SESSION['gradGraph']['height'] = 250 ;
	$_SESSION['gradGraph']['data'] = $graduatesGraph ;
	$_SESSION['gradGraph']['colours'] = array('#06CC3A', '#E5AA09', '#D10513');
	echo '<img src="'.SITE_FOLDER.'images/phplot/phplot_img.php?session=gradGraph" width="'.$_SESSION['gradGraph']['width'].'" height="'.$_SESSION['gradGraph']['height'].'" alt="" title="'.$_SESSION['gradGraph']['title'].'" />' ;
	
// Unconfimred Files
	$_SESSION['employerGraph'] = array() ;
	$_SESSION['employerGraph']['title'] = 'Employer Statistics' ;
	$_SESSION['employerGraph']['width'] = 460 ;
	$_SESSION['employerGraph']['height'] = 250 ;
	$_SESSION['employerGraph']['data'] = $employerGraph ;
	$_SESSION['employerGraph']['colours'] = array('#06CC3A', '#E5AA09', '#D10513');
	echo '<img src="'.SITE_FOLDER.'images/phplot/phplot_img.php?session=employerGraph" width="'.$_SESSION['filesGraph']['width'].'" height="'.$_SESSION['filesGraph']['height'].'" alt="" title="'.$_SESSION['filesGraph']['title'].'" />' ;

?>
</div>