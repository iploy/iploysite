<?php
	include_once('_system/classes/vacancies.php') ;
	include_once('_system/classes/bookmark_icons.php') ;

	$vacancies_function = new vacancies ;
	$vacancies_function->setEmployerId($_SESSION['user_id']) ;

	// Process Add Vacancy
	if($_GET['subaction']=='addpost'&&$_POST['vacancy_name']!=''){
		if($vacancies_function->addVacancy($_POST['vacancy_name'],$_POST['vacancy_icon'])){
			$screen_message = draw_icon(ICON_GOOD).'Vacancy added successfully' ;
			$screen_message_type = 'success' ;
		}else{
			$screen_message = draw_icon(ICON_BAD).'A vacancy alreay exists with that name. Please try again.' ;
			$screen_message_type = 'error' ;
		}
	}

	// Process Add Vacancy
	if($_GET['subaction']=='editpost'&&$_POST['vacancy_name']!=''&&$_GET['vid']!=''&&is_numeric($_GET['vid'])){
		if($vacancies_function->editVacancy($_GET['vid'],$_POST['vacancy_name'],$_POST['vacancy_icon'])){
			$screen_message = draw_icon(ICON_GOOD).'Vacancy \''.$_POST['vacancy_name'].'\' updated successfully' ;
			$screen_message_type = 'success' ;
		}else{
			$screen_message = draw_icon(ICON_BAD).'That vacancy does not exist, the requested changes were discarded.' ;
			$screen_message_type = 'error' ;
		}
	}

	// Process Add Vacancy
	if($_GET['subaction']=='delete'&&$_GET['vid']!=''&&is_numeric($_GET['vid'])){
		if($vacancies_function->deleteVacancy($_SESSION['user_id'],$_GET['vid'])){
			$screen_message = draw_icon(ICON_GOOD).'Vacancy id:'.$_GET['vid'].' successfully removed' ;
			$screen_message_type = 'success' ;
		}
	}


?>
<?php include('_screen_message_handler.php') ; ?>
<?php

	// EDIT A VACANCY
	if(($_GET['subaction']=='edit'||($_GET['subaction']=='editpost'&&$screen_message_type=='error'))&&is_numeric($_GET['vid'])&&$_GET['vid']!=''){
		$vacancy_data = $vacancies_function->getVacancy($_GET['vid']) ;
		?>
        <h3>Edit Vacancy</h3>
        <ul>
        	<li>Currently editing '<?php echo $vacancy_data['name'] ; ?>'</li>
        </ul>
        <?php
			$subaction = 'edit' ;
			$prepop_name = $vacancy_data['name'] ;
			include('_admin_vacancy_form.php') ;

		?>
		<p><?php echo draw_icon(ICON_BAD) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>" >Click here to cancel these changes</a></p>	
		<?php

	// VIEW SHORTLIST
	} elseif($_GET['subaction']=='shortlist'&&is_numeric($_GET['vid'])&&$_GET['vid']!=''){
		$vacancy_data = $vacancies_function->getVacancy($_GET['vid']) ;
		?>
        <h3>Vacancy Shortlist</h3>
        <ul>
        	<li>This section is unfinished and should be completed after the transaction development</li>
        </ul>
		<?php
	// ADD TO SHORTLIST
	} elseif($_GET['subaction']=='shortlistadd'&&is_numeric($_GET['profileid'])&&$_GET['profileid']!=''){
		include_once('_system/inc/_employer_sortlist_add.php') ;

	} else {
	// VACANCY ADMIN HOME
		?>
        <h3>Add a Vacancy</h3>
        <?php
			if($screen_message_type=='error'){
				$prepop_name = $_POST['vacancy_name'] ;
				$prepop_icon = $_POST['vacancy_icon'] ;
			} else {
				$prepop_name = '' ;
				$prepop_icon = '' ;
			}
			$subaction = 'add' ;
			include('_admin_vacancy_form.php') ;
		?>


        <h3>Current Vacancies</h3>
        <table cellpadding="0" cellspacing="0" border="0" class="list" >
        <tr class="headrow" >
        	<td>ID</td>
        	<td>Name</td>
        	<td>Date Added</td>
        	<td>Options</td>
        </tr>
        <?php
			include_once('_system/classes/shortlists.php') ;
			$row_class = '' ;
			$vacancies = $vacancies_function->getVacancyList(false) ;
			$graduates_shortlists_function = new shortlists ;
			$graduates_shortlists_function->setEmployerId($_SESSION['user_id']) ;
			foreach($vacancies as $vacancy){
				$view_link = '?action=shortlists&subaction=viewshortlist&vacancyid='.$vacancy['id'] ;
				$edit_link = '?action='.$_GET['action'].'&subaction=edit&vid='.$vacancy['id'] ;
				$delete_link = '?action='.$_GET['action'].'&subaction=delete&vid='.$vacancy['id'] ;
				// get graduate count
				$graduates_shortlists_function->setVacancyId($vacancy['id']) ;
				$graduates_in_shortlist = $graduates_shortlists_function->getShortlistListGraduateCount() ;
				$this_class = 'small' ;
				if($graduates_in_shortlist==0){
					$this_class.= ' grey' ;
				}
				?>
	        	<tr<?php echo $row_class ; ?>>
                    <td><?php echo $vacancy['id'] ; ?></td>
                    <td><a href="<?php echo $view_link ; ?>" ><?php echo $vacancy['name'] ; ?></a> <span class="<?php echo $this_class ; ?>" >(<?php echo $graduates_in_shortlist ; ?> Graduates)</span></td>
                    <td><span class="grey"><?php echo date(DM_PHP_SCREENSHORTDATE_FORMAT,strtotime($vacancy['date_added'])) ; ?></span></td>
                    <td align="right" >
                    	<a href="<?php echo $view_link ; ?>" title="View the shortlist for this vacancy" ><?php echo draw_icon('list.png') ; ?></a>
                    	<a href="<?php echo $edit_link ; ?>" title="Edit this vacancy" ><?php echo draw_icon('edit.png') ; ?></a>
						<a href="<?php echo $delete_link ; ?>" title="Delete this vacancy" onclick="return confirm('CONFIRM DELETE VACANCY\nYou are about to delete \'<?php echo $vacancy['name'] ; ?>\'.\n\nPress OK to remove this vacancy or press cancel if this is incorrect');" ><?php echo draw_icon(ICON_DELETE) ; ?></a>
                    </td>
                </tr>
				<?php
				if($row_class==''){
					$row_class = ' class="offrow" ' ;
				} else {
					$row_class = '' ;
				}
			}
		?>
        </table>
        <?php
	}
?>

