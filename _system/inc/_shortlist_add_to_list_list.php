<?php

	if($graduate_name==''){
		$graduate_name = 'The selected graduate' ;
	}

?>
<ul>
<?php
	include_once('_system/classes/shortlists.php') ;
	$shortlists_list_function = new shortlists ;
	$shortlists_list_function->setEmployerId($_SESSION['user_id']) ;
	$shortlists_list_function->setGraduateId($_GET['profileid']) ;
	$exists_is_shortlist_array = array() ;
	foreach($vacancies as $vacancy){
		$shortlists_list_function->setVacancyId($vacancy['id']) ;
		$shortlists_list_result = $shortlists_list_function->checkShortlistForGraduate() ;
		if($shortlists_list_result!=true){
			?>
			<li><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&profileid=<?php echo $_GET['profileid'] ; ?>&vacancyid=<?php echo $vacancy['id'] ; ?>" ><?php echo $vacancy['name'] ; ?></a></li>
			<?php
		} else {
			$exists_is_shortlist_array[] = $vacancy ;
		}
	}

?>
</ul>

<?php
	//list existing shortlist entries

	if(sizeof($exists_is_shortlist_array)>0){
		?>
        <div class="greydivider" ></div>
        <h5><?php echo $graduate_name ; ?> already exists in the following shortlists:</h5>
        <ul>
        <?php
		foreach($exists_is_shortlist_array as $exists_is_shortlist){
			?>
			<li><?php echo $exists_is_shortlist['name'] ; ?></li>
			<?php
		}
		?>
        </ul>
        <?php
	}

	/*
	$shortlists_list_function->setVacancyId('0') ;
	$shortlists_list_result = $shortlists_list_function->checkShortlistForGraduate() ;
	if($shortlists_list_result==false){
		?>	
		<h5>Add to Wishlist</h5>
		<p>Alternatively, you can add <?php echo $graduate_name ; ?> to your Wishlist to be viewed again later.</p>
		<p><a href="?action=<?php echo $_GET['action'] ; ?>&subaction=<?php echo $_GET['subaction'] ; ?>&profileid=<?php echo $_GET['profileid'] ; ?>&vacancyid=0" class="wishlist ico" >Add to Wishlist</a></p>
		<?php
	} else {
		$prefered_term = 'already' ;
		if(sizeof($exists_is_shortlist_array)>0){
			$prefered_term = 'also' ;
		}
		?>	
		<p><?php echo draw_icon(ICON_GOOD) ; ?>The selected graudate <?php echo $prefered_term ; ?> exists in your wishlist</p>
		<?php
	}
	*/
?>
<div class="greydivider" ></div>