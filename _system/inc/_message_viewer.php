<?php
	// all info should be loaded by this point in the $message array
	if($message['subject']!=''){
		$message_subject = $message['subject'] ;
	} else {
		$message_subject = 'No Subject' ;
	}

	if($message['user_level']==3){
		$recipient =  $_SESSION['APP_CLIENT_NAME'].' Administrator' ;
	} elseif($message['user_level']==2){
		$recipient = $message['company_name'] ;
	} elseif($message['user_level']==1){
		$recipient = trim($message['first_name'].' '.$message['surname']) ;
	} elseif($message['user_level']==0){
		$recipient =  $_SESSION['APP_CLIENT_NAME'].' Developer' ;
	}

	if(is_array($converastion)){
		if($converastion[0]['message_id']!=$message['message_id']){
			echo '<p class="notice" >'.draw_icon(ICON_ALERT).'<b>Please Note</b>: This is not the most recent message in this conversation. <a href="?messageid='.$converastion[0]['message_id'].'" >Click here</a> to view the most recent message.</p>' ;
		}
	}

	// echo '<p>'.htmlspecialchars(print_r($message,true)).'</p>' ;

?>
<style type="text/css" >
.message_headers .floater {
	min-width:250px;
}
</style>
<div class="boxshadow" >
<div class="message_subjeect" ><b><?php echo ucwords($message_subject) ; ?></b></div>
<div class="message_container" >
    <div class="message_headers float_container" >
        <div class="floater" >
        	<?php 
				$mvf->getNameById($message['author_id']) ;
			?>
            <b class="Highlight blocklabel" >Sent:</b> <?php echo date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($message['sent_date'])) ; ?><br />
            <b class="Highlight blocklabel" >Read:</b> <?php echo ($message['read_date']=='' ? ($message['user_id']==$_SESSION['user_id'] ? 'Just now' : 'Unread by recipient') : date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($message['read_date']))) ; ?>
        </div>
        <div class="floater" >
            <b class="Highlight blocklabel" >From:</b> <?php echo ($_SESSION['userarray'][$message['author_id']]['user_level']==1 ? '<a href="view_profile.php?profileid='.$message['author_id'].'" target="_blank" >'.$_SESSION['userarray'][$message['author_id']]['full_name'].'</a>' : $_SESSION['userarray'][$message['author_id']]['full_name']) ; ?><br />
            <b class="Highlight blocklabel" >To:</b> <?php echo ($message['user_level']==1 ? '<a href="view_profile.php?profileid='.$message['user_id'].'" target="_blank" >'.$recipient.'</a>' : $recipient) ; ?>
        </div>
    </div>
    <div class="message_content urlpop" >
        <?php echo str_replace('href="','target="_blank" href="',$message['message_content']) ; ?>
    </div>
</div>
</div>
<?php
	if($show_links==true){
		?>
        <div style="clear:both;">
        <ul class="li_buttons" >
        	<?php
				if($message['author_id']==$_SESSION['user_id']){
					$reply_label = 'Follow up this mesage' ;
					$reply_action = 'followupid' ;
				} else {
					$reply_label = 'Reply to this message' ;
					$reply_action = 'replyid' ;
				}
				?>
				<li><a href="message_send.php?<?php echo $reply_action ; ?>=<?php echo $message['message_id'] ; ?>" ><?php echo $reply_label ; ?></a></li>
            <li><a href="home.php?action=messages&subaction=<?php echo $_GET['subaction'] ; ?>&folderid=<?php echo $message['folder_id'] ; ?>" >Return to Mesage Folder</a></li>
        </ul>
        </div>
        <?php
	}


?>
