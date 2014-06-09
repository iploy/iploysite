<script language="javascript" type="text/javascript" src="js/noaccess.js" ></script>
<?php
	include_once('_system/classes/vacancies.php') ;
	include_once('_system/classes/shortlists.php') ;
	include_once('_system/classes/bookmark_icons.php') ;

	$vacancies_function = new vacancies ;
	$vacancies_function->setEmployerId($_SESSION['user_id']) ;

	if($_GET['subaction']=='viewshortlist'&&$_GET['vacancyid']!=''&&(is_numeric($_GET['vacancyid'])||$_GET['vacancyid']=='purchased')){

		include_once('_system/functions/censor_word.php') ;
		include_once('_system/functions/graduate_profile_progress.php') ;
		include_once('_system/classes/transactions.php') ;

		$shortlists_function = new shortlists ;
		$shortlists_function->setVacancyId($_GET['vacancyid']) ;
		$shortlists_function->setEmployerId($_SESSION['user_id']) ;

		// class to check if the employer can access this graduate's full info = = = = = = = = = = = = = = = = = = = = = =
		$transaction_function = new transactions ;

		// Process moving = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		if($_GET['moveid']!=''&&is_numeric($_GET['moveid'])&&$_GET['oldpos']!=''&&is_numeric($_GET['oldpos'])&&$_GET['dir']!=''){
			$shortlists_move_result = $shortlists_function->moveShortlistGraduate($_GET['moveid'],$_GET['oldpos'],$_GET['dir'],$_SESSION['user_id']) ;
			if($_GET['dir']=='down'){
				$newpos = $_GET['oldpos']+1 ;
			} else {
				$newpos = $_GET['oldpos']-1 ;
			}
			echo '<p class="success" >'.draw_icon(ICON_GOOD).'The graduate at position '.$_GET['oldpos'].' was moved '.$_GET['dir'].' the list to position '.$newpos.'</p>'."\n" ;
		}

		// Process removing = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		if($_GET['removeid']!=''&&is_numeric($_GET['removeid'])){
			$shortlists_remove_result = $shortlists_function->removeGraduateFromShortlist($_GET['removeid'],$_SESSION['user_id']) ;
			if($shortlists_remove_result=='good'){
				echo '<p class="success" >'.draw_icon(ICON_GOOD).'The graduate (ID: '.$_GET['removeid'].') was removed from this shortlist</p>'."\n" ;
			} else {
				echo '<p class="error" >'.draw_icon(ICON_BAD).$shortlists_remove_result."\n" ;
			}
		}


		// Now list = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
		if($_GET['vacancyid']=='purchased'){
			$shortlist_array = $shortlists_function->getPurchasedGraduatesList(false,true) ;
		} else {
			$shortlist_array = $shortlists_function->getFullShortlistList(false,true) ;
		}

		if($_GET['vacancyid']=='purchased'){
			$vacancy_info = array() ;
			$vacancy_info['id'] = 'purchased' ;
			$vacancy_info['name'] = 'Purchased Graduates' ;
			$this_icon = draw_icon('cash.png') ;
		}elseif($_GET['vacancyid']!='0'){
			$vacancy_info = $vacancies_function->getVacancy($_GET['vacancyid']) ;
		} else {
			$vacancy_info = array() ;
			$vacancy_info['id'] = '0' ;
			$vacancy_info['name'] = 'Wishlist' ;
		}
		// View vacancy Shortlist
		?>
		<h3>Vacancy Shorlist</h3>
        <p><b>Currently Viewing</b>: <?php echo $this_icon.$vacancy_info['name'] ; ?></p>
		<?php

		echo '<p>Graduates in this shortlist '.sizeof($shortlist_array).'</p>'."\n" ;

		if(sizeof($shortlist_array)>0){
			echo '<table cellpadding="0" cellspacing="0" class="list" width="97%" >'."\n" ;
			echo '<tr class="headrow" >'."\n" ;
			echo '<td width="16" ></td>'."\n" ;
			echo '<td>Name</td>'."\n" ;
			echo '<td width="16" class="nopad" ></td>'."\n" ;
			echo '<td>Profile Status</td>'."\n" ;
			echo '<td></td>'."\n" ;
			if($_GET['vacancyid']!='purchased'){
				echo '<td></td>'."\n" ;
			}
			echo '<td width="50" ></td>'."\n" ;
			echo '<td width="50" ></td>'."\n" ;
			echo '</tr>'."\n" ;
			$row_class = '' ;
			$link_all_prefix = '?action='.$_GET['action'].'&amp;subaction='.$_GET['subaction'].'&amp;vacancyid='.$_GET['vacancyid'] ;
			$counter = 0 ;
			foreach($shortlist_array as $shortlist){
				$counter ++ ;
				// fix shortlist order
				if($shortlist['list_order']!=$counter&&$_GET['vacancyid']!='purchased'){
					echo '<!-- ' ;
					$shortlists_fix_result = $shortlists_function->fixShortlistOrder($shortlist['id'],$counter,$_SESSION['user_id']) ;
					if($shortlists_fix_result!='good'){
						echo 'Issue : '.$shortlists_fix_result ;
					} else {
						echo 'List order fixed from '.$shortlist['list_order'].' to '.$counter ;
					}
					echo ' -->'."\n" ;
				}
				// draw the row
				echo '<tr'.$row_class.'>'."\n" ;
				echo '<td>'.$counter.'</td>'."\n" ;
				$transaction_result = $transaction_function->checkEmployerToGraduateAccess($_SESSION['user_id'],$shortlist['user_id']) ;
				$profile_link = 'view_profile.php?profileid='.$shortlist['user_id'] ;
				$profile_percentage = graduate_profile_progress($graduate_required_fields,$shortlist) ;
				if($transaction_result!=false){
					$this_surname = $shortlist['surname'] ;
					$this_icon = 'unlocked_green.png' ;
					$this_profile_link = '<a href="'.$profile_link.'" >View Profile</a>' ;
				} else {
					$this_surname = censor_word(ucfirst($shortlist['surname'])) ;
					$this_icon = 'locked_black.png' ;
					$this_profile_link = '<a href="'.$profile_link.'" >View Sample</a>' ;
				}
				$this_fullname = ucfirst($shortlist['first_name']).' '.$this_surname ;
				echo '<td>'.$this_fullname.'</td>'."\n" ;
				echo '<td class="nopad" >'.draw_icon($this_icon).'</td>'."\n" ;
				echo '<td>'.$profile_percentage.'% Complete</td>'."\n" ;
				echo '<td>'.$this_profile_link.'</td>'."\n" ;
				if($_GET['vacancyid']!='purchased'){
					if($counter!=1){
						$up_link = draw_icon('up.png').'<a href="'.$link_all_prefix.'&amp;moveid='.$shortlist['id'].'&amp;oldpos='.$counter.'&amp;dir=up" >Move Up</a>' ;
					} else {
						$up_link = draw_icon('up_grey.png').'<span class="grey" >Move Up</span>' ;
					}
					if($counter!=sizeof($shortlist_array)){
						$down_link = draw_icon('down.png').'<a href="'.$link_all_prefix.'&amp;moveid='.$shortlist['id'].'&amp;oldpos='.$counter.'&amp;dir=down" >Move Down</a>' ;
					} else {
						$down_link = draw_icon('down_grey.png').'<span class="grey" >Move Down</span>' ;
					}
					echo '<td>'.$up_link.' &nbsp; '.$down_link.'</td>'."\n" ;
				}
				// message
				if($transaction_result==true){
					$message_link = draw_icon('email.png').'<a href="message_send.php?profileid='.$shortlist['user_id'].'" >Message</a>' ;
				} else {
					$message_link = draw_icon('email_grey.png').'<span class="grey" onclick="noaccess(\'message\',\''.str_replace("'","\'",$shortlist['first_name']).'\');" >Message</span>' ;
				}
				echo '<td align="right" nowrap="nowrap" >'.$message_link.'</td>'."\n" ;
				echo '<td align="right" nowrap="nowrap" >'.draw_icon(ICON_DELETE).'<a href="'.$link_all_prefix.'&amp;removeid='.$shortlist['id'].'" onclick="return confirm(\'CONFIRM GRADUATE REMOVAL\nYou are about to remove '.$this_fullname.' from the '.$vacancy_info['name'].' shortlist\n\nClick OK to remove this user from the shortlist or click cancel if this is not as you intended.\');" >Remove</a></td>'."\n" ;
				echo '</tr>'."\n" ;
				// Row class
				if($row_class==''){
					$row_class = ' class="offrow" ' ;
				} else {
					$row_class = '' ;
				}
			}
			echo '</table>'."\n" ;
		} else {
			echo '<p class="error" >'.draw_icon(ICON_BAD).'There are no graduates in this shortlist</p>'."\n" ;
		}

		?>
        <p><?php echo draw_icon(ICON_BACK) ; ?><a href="?action=shortlists" >Return to the vacnacy list</a></p>
        <?php


	} else {
		// Shortlist Homepage
		$shortlist_count_function = new shortlists ;
		$shortlist_count_function->setEmployerId($_SESSION['user_id']) ;
		$shortlist_count_function->setVacancyId('0') ;
		$shortlist_count = $shortlist_count_function->getFullShortlistList(true) ;
		?>
		<h3>Graduate Shortlists</h3>
		<ul class="licon">
			<li><?php echo draw_icon('graduate.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=viewshortlist&amp;vacancyid=purchased" >Show Purchased Graduates</a></li>
			<!-- <li><?php echo draw_icon('plus.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=viewshortlist&amp;vacancyid=0" >Show Wishlist</a> (<?php echo $shortlist_count ; ?>)</li> -->
		</ul>
		<?php
			// View vacancy Shortlists
			$vacancies = $vacancies_function->getVacancyList() ;
		?>
		<h5>View Vacancy Shortlists</h5>
		<ul class="licon" >
		<?php
            foreach($vacancies as $vacancy){
				$shortlist_count_function->setVacancyId($vacancy['id']) ;
				$shortlist_count = $shortlist_count_function->getFullShortlistList(true) ;
				$shortlist_label = 'Graduate' ;
				if($shortlist_count<>1){
					$shortlist_label.= 's' ;
				}
				$shortlist_prefix = '<span class="grey" >' ;
				$shortlist_postfix = '</span>' ;
				$li_class = '' ;
				if($shortlist_count<>0){
					$shortlist_prefix = '<a href="?action='.$_GET['action'].'&amp;subaction=viewshortlist&amp;vacancyid='.$vacancy['id'].'" >' ;
					$shortlist_postfix = '</a>' ;
				} else {
					$li_class = 'class="grey" ' ;
				}
                ?>
                <li <?php echo $li_class ; ?>>
                <?php echo $shortlist_prefix ; ?>
				<?php echo $vacancy['name'] ; ?>
                <?php echo $shortlist_postfix ; ?>
                <span class="small" >(<?php echo $shortlist_count.' '.$shortlist_label ; ?>)</span></li>
                <?php
            }
        ?>
		</ul>
		<?php
	}
?>