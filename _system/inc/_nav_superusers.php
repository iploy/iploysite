<?php
	include('_system/inc/_messages_user_inbox_count.php') ;
?>
<div>
    <ul>
        <li class="graduate_permissions" ><a href="home.php?action=su0" ><?php echo draw_icon('superuser.png') ; ?>Home</a></li>
        <li class="graduate_permissions" ><a href="#" ><?php echo draw_icon('mask.png') ; ?>Emulate User Level</a>
            <ul>
                <li class="graduate_permissions" ><a href="home.php?action=su1" ><?php echo draw_icon('graduate_sm.png') ; ?>Graduate Permissions</a></li>
                <li class="employer_permissions" ><a href="home.php?action=su2" ><?php echo draw_icon('employer.png') ; ?>Employer Permissions</a></li>
                <li class="iployadmin_permissions" ><a href="home.php?action=su3" ><?php echo draw_icon('admin.png') ; ?><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> Admin Permissions</a></li>
            </ul>
        </li>
        <li><a href="home.php?action=messages" ><?php echo draw_icon('email.png') ; ?>Messages (<span class="messages" ><?php echo $_SESSION['unread_messages'] ; ?></span>)</a>
        	<ul>
            	<li><a href="home.php?action=messages" ><?php echo draw_icon('inbox.png') ; ?>Inbox (<span class="messages" ><?php echo $_SESSION['unread_messages'] ; ?></span>)</a></li>
            	<li><a href="home.php?action=massmailing" ><?php echo draw_icon('mail_mass.png') ; ?>Mass Mailing</a></li>
            	<li><a href="home.php?action=messages&folderid=sent" ><?php echo draw_icon('inbox_out.png') ; ?>Sent Items</a></li>
            </ul>
        </li>
    </ul>
</div>