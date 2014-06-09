<?php

	include('_system/inc/_messages_user_inbox_count.php') ;
	include_once('_system/classes/file_confirmation.php') ;

	// Images

	$confirm_count_function = new fileConfirmation ;

	// Check for confirmations or deletions
	if($_GET['action']=='adminimg'||$_GET['action']=='admincv'||$_GET['action']=='admincertificate'){
		if($_GET['subaction']=='confirm'&&$_GET['requestid']!=''&&is_numeric($_GET['requestid'])){
			$confirm_count_function->confirmFileByRequestId($_GET['requestid']) ;
			$screen_message = draw_icon(ICON_GOOD).'File confirmed for Request ID: '.$_GET['requestid'] ;
			$screen_message_type = 'success' ;
		}elseif($_GET['subaction']=='remove'&&$_GET['requestid']!=''&&is_numeric($_GET['requestid'])){
			$confirm_count_function->deleteFileByRequestId($_GET['requestid']) ;
			$screen_message = draw_icon(ICON_DELETE).'File removed for Request ID: '.$_GET['requestid'] ;
			$screen_message_type = 'success' ;
		}
	}

	// Relist
	$unconfirmed_photos = $confirm_count_function->countFilesAwaitingConfirmation('photo') ;
	$unconfirmed_cvs = $confirm_count_function->countFilesAwaitingConfirmation('cv') ;
	$unconfirmed_certificates = $confirm_count_function->countFilesAwaitingConfirmation('certificate') ;

?>
<div>
    <ul>
    	<?php
			if($_SESSION['user_level']==0&&$_SESSION['su_level_mask']==3){
				?>
                <li class="su_link" ><a href="home.php?action=su0" >SU</a></li>
                <?php
			}
		?> 
        <li><a href="home.php?action=" ><?php echo draw_icon('stats.png') ; ?>Statistics</a>
        	<ul>
            	<li><a href="home.php?action=" ><?php echo draw_icon('stats.png') ; ?>Stats Home</a></li>
            	<li><a href="home.php?action=purchasereport" ><?php echo draw_icon('cash_register.png') ; ?>Purchase Report</a></li>
            </ul>
        </li>
        <li><a href="#" ><?php echo draw_icon('inspect.png') ; ?>Confirm Files (<?php echo $unconfirmed_photos+$unconfirmed_cvs+$unconfirmed_certificates ; ?>)</a>
            <ul>
            <li><a href="home.php?action=adminimg" ><?php echo draw_icon('images.png') ; ?>Confirm Photographs (<?php echo $unconfirmed_photos ; ?>)</a></li>
            <li><a href="home.php?action=admincv" ><?php echo draw_icon('profile.png') ; ?>Confirm CVs (<?php echo $unconfirmed_cvs ; ?>)</a></li>
            <li><a href="home.php?action=admincertificate" ><?php echo draw_icon('certificate.png') ; ?>Confirm Certificates (<?php echo $unconfirmed_certificates ; ?>)</a></li>
        </ul>
        </li>
        <li><a href="#" ><?php echo draw_icon('user.png') ; ?>Website Users</a>
        	<ul>
            	<li><a href="home.php?action=adminusers" ><?php echo draw_icon('user_admin.png') ; ?>Member Admin</a></li>
            	<li><a href="home.php?action=adduser" ><?php echo draw_icon('user_add.png') ; ?>Add a User</a></li>
            	<li><a href="home.php?action=affiliatesadmin" ><?php echo draw_icon('cash.png') ; ?>Affiliates Admin</a>
				<?php
                    $subClass = '' ;
                    include(SITE_PATH.'_system/inc/_nav_sub_affliates.php') ;
                ?>
                </li>
            	<li><a href="search.php" ><?php echo draw_icon('search.png') ; ?>Graduate Search Engine</a></li>
            </ul>
        </li>
        <li><a href="home.php?action=messages" ><?php echo draw_icon('email.png') ; ?>Messages (<span class="messages" ><?php echo $_SESSION['unread_messages'] ; ?></span>)</a>
        	<ul>
            	<li><a href="home.php?action=messages" ><?php echo draw_icon('inbox.png') ; ?>Inbox (<span class="messages" ><?php echo $_SESSION['unread_messages'] ; ?></span>)</a></li>
            	<li><a href="home.php?action=messages&folderid=sent" ><?php echo draw_icon('inbox_out.png') ; ?>Sent Items</a></li>
            	<li><a href="home.php?action=massmailing" ><?php echo draw_icon('mail_mass.png') ; ?>Mass Mailing</a></li>
            </ul>
        </li>
        <li><a href="home.php?action=changepass" ><?php echo draw_icon('key.png') ; ?>Change Password</a></li>
    </ul>
</div>