<?php
	include('_system/inc/_messages_user_inbox_count.php') ;
?>
<div>
	<div style="float:right; padding-right:10px;" class="Highlight" ><?php echo draw_icon('user.png') ; ?><?php echo $_SESSION['email'] ; ?></div>
    <ul>
    	<?php
			if($_SESSION['user_level']==0&&$_SESSION['su_level_mask']==2){
				?>
                <li class="su_link" ><a href="home.php?action=su0" >SU</a></li>
                <?php
			} else {
				?><li><a href="home.php?" ><?php echo draw_icon('home.png') ; ?>Home</a></li>
                <?php
			}
		?> 
        <li><a href="#" ><?php echo draw_icon('vacancies.png') ; ?>Search</a>
            <ul>
       			<li><a href="search.php" ><?php echo draw_icon('search.png') ; ?>Find Graduates</a></li>
                <li><a href="home.php?action=shortlists&subaction=viewshortlist&vacancyid=purchased" ><?php echo draw_icon('graduate.png') ; ?>Recently Purchased</a></li>
                <li><a href="home.php?action=vacancies" ><?php echo draw_icon('briefcase.png') ; ?>Shortlists</a></li>
            </ul>
        </li>
        <li><a href="#" ><?php echo draw_icon(ICON_ACCOUNT) ; ?>My Account</a>
        	<ul>
        		<li><a href="home.php?action=credits" ><?php echo draw_icon('cash.png') ; ?>Buy Credits</a></li>
            	<li><a href="home.php?action=purchasehistory" ><?php echo draw_icon('calendar.png') ; ?>Purchase History</a></li>
        		<li><a href="home.php?action=addresses" ><?php echo draw_icon('building.png') ; ?>Address Book</a></li>
            	<li><a href="home.php?action=profile" ><?php echo draw_icon('user.png') ; ?>Employer Profile</a></li>
        		<li><a href="home.php?action=changepass" ><?php echo draw_icon('key.png') ; ?>Change Password</a></li>
            </ul>
        </li>
        <li><a href="home.php?action=messages" ><?php echo draw_icon('email.png') ; ?>Messages (<span class="messages" ><?php echo $_SESSION['unread_messages'] ; ?></span>)</a></li>
    </ul>
</div>
