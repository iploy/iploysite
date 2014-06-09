<?php

	$mvf->getNameById($message['author_id']) ;
	$mvf->getNameById($message['user_id']) ;

	if($show_conversation==true&&sizeof($converastion)>1){
		echo '<a name="mooZip" ></a><p>There '.(sizeof($converastion)==1 ? 'is' : 'are').' currently '.sizeof($converastion).' message'.(sizeof($converastion)==1 ? '' : 's').' in this conversation:</p>'."\n" ;
		// IF REPLY, LIST HISTORY
		echo '<div class="mooZip" >'."\n" ;
		foreach($converastion as $message){
			if($_GET['messageid']!=$message['message_id']){
				$rowclass=='off' ? $rowclass='' : $rowclass='off' ;
				echo '<div class="zip '.$rowclass.'" >'."\n" ;
				echo '<h4><span class="date" >'.date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($message['sent_date'])).'</span> '.draw_icon('email.png').$message['subject'].'</h4>'."\n" ;
				echo '<div class="message_headers float_container" >'."\n" ;
				echo '<div class="floater" >'."\n" ;
				echo '<b class="Highlight blocklabel" >Sent:</b> '.date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($message['sent_date'])).'<br />'."\n" ;
				echo '<b class="Highlight blocklabel" >Read:</b> '.($message['read_date']=='' ? ($message['user_id']==$_SESSION['user_id'] ? 'Just now' : 'Unread by recipient') : date(DM_PHP_SCREENSHORTDATETIME_FORMAT,strtotime($message['read_date'])))."\n" ;
				echo '</div>'."\n" ;
				echo '<div class="floater" >'."\n" ;
				echo '<b class="Highlight blocklabel" >From:</b> '.($_SESSION['userarray'][$message['author_id']]['user_level']==1 ? '<a href="view_profile.php?profileid='.$message['author_id'].'" target="_blank" >'.$_SESSION['userarray'][$message['author_id']]['full_name'].'</a>' : $_SESSION['userarray'][$message['author_id']]['full_name']).'<br />'."\n" ;
				echo '<b class="Highlight blocklabel" >To:</b> '.($_SESSION['userarray'][$message['user_id']]['user_level']==1 ? '<a href="view_profile.php?profileid='.$message['user_id'].'" target="_blank" >'.$_SESSION['userarray'][$message['user_id']]['full_name'].'</a>' : $_SESSION['userarray'][$message['user_id']]['full_name'])."\n" ;
				echo '</div>'."\n" ;
				echo '</div>'."\n" ;
				echo '<div class="content urlpop" >'.$message['message_content'].'</div>'."\n" ;
				echo '</div>'."\n" ;
			}
		}
		echo '</div>'."\n" ;
	} else {
		echo '<p>'.draw_icon(ICON_ALERT).'This is the only message in this conversation'."\n" ;
	}

?>
