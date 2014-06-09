<script language="javascript" type="text/javascript" src="js/mooConfirm/mooConfirm.js" ></script>
<?php


	include_once('_system/classes/graduate_frontend.php') ;
	include_once('_system/functions/censor_word.php') ;
	include_once('_system/classes/transactions.php') ;
	include('_system/_config/_multiple_choice_arrays.php') ;

	// Check if search variables exist
	$resume_search_available = false ;
	foreach($search_fld_array as $search_fld){
		if($_SESSION['search_'.$search_fld]!=''){
			$resume_search_available = true ;
		}
	}

	// Show to people who have purchased
	$transaction_function = new transactions ;
	$transaction_result = $transaction_function->checkEmployerToGraduateAccess($_SESSION['user_id'],$_GET['profileid']) ;
	// Greaduate info
	$graduate_data_function = new graduate_data ;
	$graduate_data = $graduate_data_function->getGraduateByID($_GET['profileid']) ;
	if(print_r($graduate_data,true)==''){
		?>
        <p class="error" ><?php echo draw_icon(ICON_BAD) ; ?><b>Invalid Profile ID</b> : You can not unlock a profile which does not exist</p>
        <?php
	} else {
		// else update the database and give the continue link (with java, maybe)
		$graduate_name = ucfirst($graduate_data['first_name']) ;
		if(trim($graduate_data['surname'])!=''){
			if($transaction_result==false){
				$graduate_name.=' '.censor_word(ucfirst($graduate_data['surname'])) ;
			} else {
				$graduate_name.=' '.ucfirst($graduate_data['surname']) ;
			}
		}
		// Check for vacancy ID, if not rpesent, ask to choose a vacancy
		if($_GET['vacancyid']!=''&&is_numeric($_GET['vacancyid'])){
			// Get the vacancy information / $vacancies_function defined on _employer_shortlist_add.php
			if($_GET['vacancyid']!='0'){
				$this_vacancy = $vacancies_function->getVacancy($_GET['vacancyid']) ;
			} else {
				$this_vacancy = array() ;
				$this_vacancy['name'] = 'Wishlist' ;
				$this_vacancy['id'] = '0' ;
			}
			?>
			<h3>Add <?php echo $graduate_name ; ?> To <?php echo $this_vacancy['name'] ; ?></h3>
            <?php
			// try to perform the insert
			include_once('_system/classes/shortlists.php') ;
			$shortlists_function = new shortlists ;
			$shortlists_function->setVacancyId($_GET['vacancyid']) ;
			$shortlists_function->setEmployerId($_SESSION['user_id']) ;
			$shortlists_function->setGraduateId($_GET['profileid']) ;
			$shortlists_function_result = $shortlists_function->addGraduateToShortlist() ;
			// Now check if the user was added successfully
			if($shortlists_function_result==true){
				// user added to shortlist
				?>
                <p class="success" ><?php echo draw_icon(ICON_GOOD) ; ?><?php echo $graduate_name ; ?> was successfully added to <?php echo $this_vacancy['name'] ; ?></p>
                <ul class="li_buttons" >
                    <li><a href="view_profile.php?profileid=<?php echo $_GET['profileid'] ; ?>" >Return to Graduate Profile</a></li>
                    <li><a href="home.php?action=shortlists" >My Shortlists</a></li>
                    <?php
                        if($resume_search_available==true){
                            ?>
                            <li><a href="search.php?resume=true" >Return to Search</a></li>
                            <?php
                        }
                    ?>
                </ul>
                <?php
			} else {
				// user not added please choose another vacancy
				?>
                <p class="error" ><?php echo draw_icon(ICON_BAD) ; ?><?php echo $graduate_name ; ?> already exists in <?php echo $this_vacancy['name'] ; ?></p>
            	<?php
				// Get vacancies list / $vacancies_function defined on _employer_shortlist_add.php
				$vacancies = $vacancies_function->getVacancyList() ;
				?>
                <p>Please select another vacancy or return to <a href="view_profile.php?profileid=<?php echo $_GET['profileid'] ; ?>" ><?php echo $graduate_name ; ?>'s profile</a>.</p>
                <?php
				include('_system/inc/_shortlist_add_to_list_list.php') ;
				?>
				<h5>Cancel This Request</h5>
           		<p>If you do not wish to add <?php echo $graduate_name ; ?> to a shortlist at this time, please select an option:</p>
                <ul class="li_buttons" >
                    <li><a href="view_profile.php?profileid=<?php echo $_GET['profileid'] ; ?>" >Cancel and Return to Profile</a></li>
                    <li><a href="home.php?action=shortlists" >View My Shortlists</a></li>
                    <?php
                        if($resume_search_available==true){
                            ?>
                            <li><a href="search.php?resume=true" >Cancel and Return to Search</a></li>
                            <?php
                        }
                    ?>
                </ul>
                <?php
			}
		} else {
			// Get vacancies list / $vacancies_function defined on _employer_shortlist_add.php
			$vacancies = $vacancies_function->getVacancyList() ;
			?>
			<h3>Add User To Shortlist</h3>
			<p>You have chosen to add <b><?php echo $graduate_name ; ?></b> to a shortlist.</p>
			<h5>Add to a Shortlist:</h5>
            <?php include('_system/inc/_shortlist_add_to_list_list.php') ; ?>		
			<p><a href="search.php?resume=true" >Back to search results</a></p>
			<h5>Cancel This Request</h5>
            <p>If you do not wish to add <?php echo $graduate_name ; ?> to a shortlist at this time, please select an option:</p>
            <ul class="li_buttons" >
                <li><a href="view_profile.php?profileid=<?php echo $_GET['profileid'] ; ?>" class="mooConfirm" title="You have not yet added this graduate to a new shortlist." >Return to Profile</a></li>
                <li><a href="home.php?action=shortlists" class="mooConfirm" title="You have not yet added this graduate to a new shortlist." >View My Shortlists</a></li>
                <?php
                    if($resume_search_available==true){
                        ?>
                        <li><a href="search.php?resume=true" class="mooConfirm" title="You have not yet added this graduate to a new shortlist." >Return to Search</a></li>
                        <?php
                    }
                ?>
            </ul>
            <?php
		}
    }
?>