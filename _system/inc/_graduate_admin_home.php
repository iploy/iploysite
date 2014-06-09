<?php include_once('_system/inc/_graduate_progress_bar.php') ; ?>
<?php include_once('_system/inc/_graduate_profile_progress.php') ; ?>
<h1>Graduate Admin</h1>
<p>Please select an action:</p>
<div class="nav_basic lister" style="margin-left:20px;" >

	<ul>
    	<li><a href="home.php?action=affiliates" ><?php echo draw_icon('cash.png').'NEW: '.($_SESSION['affiliate_id']==''? 'Become an affiliate and earn cash for referrals' : 'Affiliate Account Information and Referrals') ; ?></a></li>
    </ul>
    <br />
    <ul>
    	<?php
			if($_SESSION['user_level']==0&&$_SESSION['su_level_mask']==1){
				?>
                <li class="su_link" ><a href="home.php?action=su0" >SU</a></li>
                <?php
			} else {
				?><li><a href="home.php?" ><?php echo draw_icon('home.png') ; ?>Home</a></li>
                <?php
			}
		?> 
        <li><a href="home.php?action=messages" ><?php echo draw_icon('email.png') ; ?>Messages (<span class="messages" ><?php echo $_SESSION['unread_messages'] ; ?></span>)</a></li>
        <!-- <li class="messages_inbox" ><a href="#" >Messages Inbox</a></li> -->
        <li><?php echo draw_icon(ICON_ACCOUNT) ; ?>Profile Options:
        	<ul>
        		<li><a href="home.php?action=personal" ><?php echo draw_icon('user.png') ; ?>Edit Personal Information</a></li>
        		<li><a href="home.php?action=education" ><?php echo draw_icon('books.png') ; ?>Edit Education Information</a></li>
        		<li><a href="home.php?action=employment" ><?php echo draw_icon('building.png') ; ?>Edit Employment Preferences</a></li>
                <li><a href="home.php?action=upload" ><?php echo draw_icon('up.png') ; ?>Upload Files</a>
                    <ul>
                        <li><a href="home.php?action=upload&subaction=photo" ><?php echo draw_icon('images.png') ; ?>Upload Photograph</a></li>
                        <li><a href="home.php?action=upload&subaction=cv" ><?php echo draw_icon('profile.png') ; ?>Upload CV</a></li>
                        <li><a href="home.php?action=upload&subaction=certificate" ><?php echo draw_icon('certificate.png') ; ?>Upload Certificate</a></li>
                    </ul>
                </li>
        		<li><a href="home.php?action=youtube" ><?php echo draw_icon('youtube.gif') ; ?>Youtube Video Administration</a></li>
                <li><a href="view_profile.php?profileid=<?php echo $_SESSION['user_id'] ; ?>" ><?php echo draw_icon('inspect.png') ; ?>View Profile (beta)</a></li>
        	</ul>
        </li>
        <li><a href="home.php?action=changepass" ><?php echo draw_icon('key.png') ; ?>Change Password</a></li>
    </ul>
</div>