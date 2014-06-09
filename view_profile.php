<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;
	include_once('_system/_config/_multiple_choice_arrays.php') ;


	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

	if($_GET['unlock']=='confirmed'){
		$screen_message = draw_icon(ICON_GOOD).'<b>Profile Unlock Request Successful</b>: Your request was completed successfully. Thank you for purchasing this profile.' ;
		$screen_message_type = 'success' ;
	}
	if($_GET['notice']=='unlocked'){
		$screen_message = draw_icon(ICON_ALERT).'<b>Unnecessary Request</b>: Your unlock request was canceled as you already have access to this profile. No credits have been deducted from your account.' ;
		$screen_message_type = 'notice' ;
	}

	$show_user_profile = 'No' ;
	if($_GET['profileid']!=''&&is_numeric($_GET['profileid'])){
		include_once('_system/classes/user_info.php') ;
		include_once('_system/classes/graduate_frontend.php') ;
		include_once('_system/classes/transactions.php') ;
		include_once('_system/functions/censor_word.php') ;
		$user_info_function = new user_info ;
		$user_info = $user_info_function->getInfoByUserId($_GET['profileid'],false) ;
		$graduate_info_function = new graduate_data ;
		$graduate_info = $graduate_info_function->getGraduateByID($_GET['profileid']) ;
		if($user_info['is_active']==1){
			include_once('_system/functions/graduate_profile_progress.php') ;
			include_once('_system/_config/_graduate_required_fields_array.php') ;
			$graduate_profile_percentage = graduate_profile_progress($graduate_required_fields,$graduate_info) ;
			// Options for employers
			if($_SESSION['user_level']==='0'||$_SESSION['user_level']==3||$_GET['profileid']==DEMO_PROFILE_ID){
				// Always show to admins
				$show_user_profile = 'Active' ;
			} elseif($_SESSION['user_level']==''){
				$show_user_profile = 'Preview' ;
			}else {
				if($_SESSION['user_id']==$user_info['id']){
					// Always show to the user
					$show_user_profile = 'Active' ;
				} else {
					// Show to people who have purchased
					$transaction_function = new transactions ;
					$transaction_result = $transaction_function->checkEmployerToGraduateAccess($_SESSION['user_id'],$user_info['id']) ;
					if($transaction_result==true){
						$show_user_profile = 'Active' ;
					} else {
						// $show_user_profile = 'Invalid Permissions' ;
						if($graduate_profile_percentage<100){
							$show_user_profile = 'Incomplete' ;
						} else {
							// Preview of completed profiles to everyone else
							$show_user_profile = 'Preview' ;
						}
					}
				}
			}
		} elseif($user_info['is_active']==0){
			// do nowt
			$show_user_profile = 'Inactive' ;
		} else {
			// do nowt
			$show_user_profile = 'Invalid' ;
		}
	}


	// if employer, get available credit information
	if($_SESSION['user_level']==2){
		include_once('_system/classes/credits.php') ;
		$credits_function = new credits ;
		$employer_credits = $credits_function->checkCredits($_SESSION['user_id']) ;
	}



	// Get the graduate class info if required.  This is done here rather than below so that the page title can be set.
	if($show_user_profile=='Active'||$show_user_profile=='Preview'){ // Active user request
		// Get the graduate info by ID
		include_once('_system/classes/graduate_frontend.php') ;
		$graduate_info_function = new graduate_data ;
		$graduate_info = $graduate_info_function->getGraduateByID($user_info['id'],false) ;
	}


	// process remove from shortlist 
	if($_GET['action']=='shortlistremove'&&$_GET['removeid']!=''&&is_numeric($_GET['removeid'])&&$_GET['vacancyid']!=''&&is_numeric($_GET['vacancyid'])&&$_GET['profileid']!=''&&is_numeric($_GET['profileid'])&&$_SESSION['user_level']==2){
		include_once('_system/classes/shortlists.php') ;
		$slremove = new shortlists ;
		$slremove->setVacancyId($_GET['vacancyid']) ;
		$result = $slremove->removeGraduateFromShortlist($_GET['removeid'],$_SESSION['user_id']) ;
		if($result=='good'){
			$screen_message = draw_icon(ICON_GOOD).'<b>Graduate Removed From Shortlist</b>: Your request to remove this graduate from your shortlist was completed successfully.' ;
			$screen_message_type = 'success' ;
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : <?php echo ucfirst(strtolower($graduate_info['first_name'])) ; ?>'s Profile - Graduate ID:<?php echo $user_info['id'] ; ?></title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
<style type="text/css" >
.minheight2 p + ul {
	margin-top:-10px;
}
.img {
	border:1px solid #CCC;
}

.left {
	float:left;
	margin:16px 0 16px 0; width:<?php echo IMAGE_UPLOAD_LARGE_WIDTH ; ?>px;
}
.rightcontain {
	margin-left:<?php echo IMAGE_UPLOAD_LARGE_WIDTH+18 ; ?>px;
	padding:6px 0;
}
.rightleft p, .rightright p {
	margin:12px 0 12px 28px;
}
.rightleft ul, .rightleft li, .rightright ul, .rightright li {
	margin:0;
	padding:0;
	list-style:circle;
}
.rightleft li, .rightright li {
	margin:0 0 2px 42px;
}
.rightleft .ico, .rightright .ico {
	margin-left:-24px;
	position:absolute;
}
.rightleft {
	width:326px;
	float:left;
}
.rightright {
	width:320px;
	float:right;
}
.rightleft, .rightright {
	min-height:300px;
}

</style>
<link rel="stylesheet" type="text/css" href="css/profile_print.css" media="print" />
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
			<?php
				// Show notice for incomplete profile
				if($graduate_profile_percentage<100){
					?>
                    <p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?><b>Please Note:</b> This user has edited their profile and it is now incomplete, you have been granted access due to your purchase history.</p>
                    <?php
				}

// ============================== [ FORMAT DATA ] =============================================================

                if($show_user_profile=='Active'||$show_user_profile=='Preview'){ // Active user request
                    // Set defaults
                    $this_first_name = 'Anonymous' ;
                    $this_surname = 'Anonymous' ;
                    $this_date_of_birth = 'Unspecified' ;
                    $this_tel_mobile = 'Unspecified' ;
                    $this_social_skype = '' ;
                    $this_will_travel = 'Unspecified' ;
                    $this_has_driving_licence = 'Unspecified' ;
                    $this_will_do_antisocial = 'Unspecified' ;
                    $this_availability = 'Unspecified' ;
					$this_employment_status = 'Unspecified' ;
                    // Set the user variables for this page for ease of use
                    $this_user_id = $user_info['id'] ;
                    $this_email = $user_info['email'] ;
                    $this_date_created = date(DM_PHP_SCREENDATE_FORMAT,strtotime($user_info['date_created'])) ;
                    // Set the graduate variables for this page for ease of use
                    // First Name
                    if($graduate_info['first_name']!=''){
                        $this_first_name = $graduate_info['first_name'] ;
                    }
                    // Surname
                    if($graduate_info['surname']!=''){
                        $this_surname = $graduate_info['surname'] ;
                    }
					if($show_user_profile=='Active'){
						$this_name = ucfirst(strtolower($this_first_name)).' '.ucfirst(strtolower($this_surname)) ; // ' (<a href="mailto:'.$this_email.'" >'.$this_email.'</a>)' ;
					} else {
						$this_name = ucfirst(strtolower($this_first_name)).' '.censor_word($this_surname) ;
					}
                    // Surname
                    if($graduate_info['employment_status']!=''){
                        $this_employment_status = $graduate_info['employment_status'] ;
                    }
                    // Surname
                    if($graduate_info['date_of_birth']!='0000-00-00'&&$graduate_info['date_of_birth']!=''){
                        $this_date_of_birth = date(DM_PHP_DATE_PICK_FORMAT,strtotime($graduate_info['date_of_birth'])) ;
                    }
                    if($graduate_info['tel_mobile']!=''){
                        $this_tel_mobile = $graduate_info['tel_mobile'] ;
                    }
                    if($graduate_info['social_skype']!=''){
                        $this_social_skype = $graduate_info['social_skype'] ;
                    }
                    // Yes No Options 
                    if($graduate_info['will_travel']!=''){
                        $this_will_travel = $graduate_info['will_travel'] ;
                    }
                    if($graduate_info['has_driving_licence']!=''){
                        $this_has_driving_licence = $graduate_info['has_driving_licence'] ;
                    }
                    if($graduate_info['will_do_antisocial']!=''){
                        $this_will_do_antisocial = $graduate_info['will_do_antisocial'] ;
                    }
                    if($graduate_info['availability'] !=''){
                        $this_availability = date(DM_PHP_DATE_PICK_FORMAT,strtotime($graduate_info['availability'])) ;
                    }
					$profile_id = $_GET['profileid'] ;
                    $youtube_id = $graduate_info['youtube_id'] ;

                    // Arrays
                    $this_hours = explode(",",$graduate_info['hours']) ;
                    $this_emploment_location = explode(",",$graduate_info['emploment_location']) ;
                    $this_job_category = explode(",",$graduate_info['job_category']) ;
                    $this_alternate_languages = explode(",",$graduate_info['alternate_languages']) ;

                    // Educaation info (phase 2 additions) ========================================== //
                    $this_education_level = 'Unspecified' ;
					$this_education_degree_title = 'Unspecified' ;
					$this_subject = 'Unspecified' ;
                    $this_education_start = 'Unspecified' ;
                    $this_education_end = 'Unspecified' ;
                    $this_education_has_graduated = 'Yes' ;
                    $this_has_graduated_icon = ICON_GOOD ;
                    $this_education_grade = 'Unspecified' ;
                    $this_education_institution = 'Unspecified' ;
                    $this_education_location = 'Unspecified' ;
                    $this_education_certificate_title = 'Unspecified' ;
                    if($graduate_info['education_level'] !=''){
                        $this_education_level = $graduate_info['education_level'] ;
                    }
                    if($graduate_info['education_degree_title'] !=''){
                        $this_education_degree_title = $graduate_info['education_degree_title'] ;
                    }
                    if($graduate_info['subject'] !=''){
                        $this_subject = $graduate_info['subject'] ;
                    }
                    if($graduate_info['education_start']!='0000-00-00'&&$graduate_info['education_start']!=''){
                        $this_education_start = date(DM_PHP_DATE_PICK_FORMAT,strtotime($graduate_info['education_start'])) ;
                    }
                    if($graduate_info['education_end']!='0000-00-00'&&$graduate_info['education_end']!=''){
                        $this_education_end = date(DM_PHP_DATE_PICK_FORMAT,strtotime($graduate_info['education_end'])) ;
                    }
                    if(strtolower($graduate_info['education_has_graduated'])!='yes'){
                        $this_education_has_graduated = $graduate_info['education_has_graduated'] ;
                        $this_has_graduated_icon = ICON_ALERT ;
                    }
                    if($graduate_info['education_grade']!=''){
                        $this_education_grade = $graduate_info['education_grade'] ;
                    }
                    if($graduate_info['education_institution'] !=''){
                        $this_education_institution = $graduate_info['education_institution'] ;
                    }
                    if($graduate_info['education_location'] !=''){
                        $this_education_location = $graduate_info['education_location'] ;
                    }
                    if($graduate_info['education_certificate_title'] !=''){
                        $this_education_certificate_title = $graduate_info['education_certificate_title'] ;
                    }




                    // Check the status of the image
                    // open
                    $conn = new ConnectionSimple ;
                    $conn->connect(DM_DB_NAME) ;
                    // Get any files waiting for confirmation
                    $screen_message = '' ;
                    $sql = "SELECT upload_type, file_path_full FROM unverified_files WHERE user_id='".$this_user_id."' AND upload_type='photo' ;" ;
                    $result = mysql_query($sql) or die( mysql_error()) ;
                    $needs_approval_cv = false ;
                    $needs_approval_photo = false ;
                    while($row = mysql_fetch_array($result)){
                        if($row['upload_type']=='cv'){
                            $needs_approval_cv = true ;
                        }
                        if($row['upload_type']=='photo'){
                            $needs_approval_photo = true ;
                        }
                    }
                    // close
                    $conn->disconnect(DM_DB_NAME) ;
                    // - - - - - - - - - - - - - - - - - - - -
                    $userfile_photo = 'userfiles/photo/'.$this_user_id.'/' ;
                    $userfile_cv = 'userfiles/cv/'.$this_user_id.'/' ;
                    $userfile_certificate = 'userfiles/certificate/'.$this_user_id.'/' ;

                    ?>

<?php // ============================================================== [ LEFT ] ============================================================== // ?>
<?php // ============================================================== [ LEFT ] ============================================================== // ?>

					<?php
					// set img url and enable image if viewing own profile
					$imgUrl = 'user_image.php?userid='.$this_user_id.'&size=large' ;
					if($_SESSION['user_id']==$this_user_id){
						$imgUrl.= '&admin=true' ;
					}
					?>
                    <div class="left" >
                        <div class="img" style="width:<?php echo IMAGE_UPLOAD_LARGE_WIDTH ; ?>px; height:<?php echo IMAGE_UPLOAD_LARGE_HEIGHT ; ?>px; margin-bottom:20px;" ><img src="<?php echo $imgUrl ; ?>" width="<?php echo IMAGE_UPLOAD_LARGE_WIDTH ; ?>" height="<?php echo IMAGE_UPLOAD_LARGE_HEIGHT ; ?>" border="0" alt="" /></div>
                        <?php
                        if($show_user_profile=='Active'&&$_SESSION['user_id']!=$this_user_id){
							if($_GET['profileid']==DEMO_PROFILE_ID){
								?><p><?php echo draw_icon('email.png') ; ?><span class="pointer Highlight" onclick="alert('DEMO PROFILE\nMessages can not be sent to the demo profile!');" >Send <?php echo $this_first_name ; ?> a message using <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></span></p>
                                <p><?php echo draw_icon('email.png') ; ?><span class="pointer Highlight" onclick="alert('DEMO PROFILE\nMessages can not be sent to the demo profile!');" >Send <?php echo $this_first_name ; ?> an Email</span></p>
								<?php
							} else {
								?><p><?php echo draw_icon('email.png') ; ?><a href="message_send.php?profileid=<?php echo $this_user_id ; ?>" >Send <?php echo $this_first_name ; ?> a message using <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></a></p>
                                <p><?php echo draw_icon('email.png') ; ?><a href="mailto:<?php echo $this_email ; ?>" >Send <?php echo $this_first_name ; ?> an Email</a></p>
								<?php
							}
						}
                        // Photo Notice
                        if(!file_exists($userfile_photo)&&$show_user_profile=='Active'){
                            ?>
                            <p><?php echo draw_icon(ICON_BAD) ; ?>This user has not yet uploaded a photo</a></p>
                            <?php
                        }
                        // CV notice
                        if($_GET['profileid']==DEMO_PROFILE_ID){
							?>
                            <p><?php echo draw_icon(ICON_DOWNLOAD) ; ?><span class="pointer Highlight" onclick="alert('DEMO PROFILE\nCV Download has been disabled on the demo profile!');" >Click here to download <?php echo $this_first_name ; ?>'s CV</span></p>
                            <?php
						}elseif(file_exists($userfile_cv)&&$show_user_profile=='Active'){
                            ?>
                            <p><?php echo draw_icon(ICON_DOWNLOAD) ; ?><a href="download_userfile.php?userid=<?php echo $this_user_id ; ?>&type=cv" >Click here to download <?php echo $this_first_name ; ?>'s CV</a></p>
                            <?php
                        } elseif(!file_exists($userfile_cv)&&$show_user_profile=='Active'){
                            ?>
                            <p><?php echo draw_icon(ICON_BAD) ; ?>This user has not yet uploaded a CV</a></p>
                            <?php
                        }
						// Skype add button
						if($_GET['profileid']==DEMO_PROFILE_ID||$_SESSION['user_id']==$this_user_id){
									?>
									<p><img src="images/icons/social_skype.png" class="ico18" width="18" height="18" border="0" /><b>Skype</b> : <span class="pointer Highlight" onclick="alert('DEMO PROFILE\nSkype links have been disabled on the demo profile!');" >Call Now</span> | <span class="pointer Highlight" onclick="alert('DEMO PROFILE\nSkype links have been disabled on the demo profile!');" >Add Contact</span></p>
									<?php
						} else {
							if($show_user_profile=='Active'){
								if($graduate_info['social_skype']!=''){
									?>
									<p><img src="images/icons/social_skype.png" class="ico18" width="18" height="18" border="0" /><b>Skype</b> : <a href="skype:<?php echo urlencode($graduate_info['social_skype']) ; ?>?call" >Call Now</a> | <a href="skype:<?php echo urlencode($graduate_info['social_skype']) ; ?>?add">Add Contact</a></p>
									<?php
								}
							}
						}

						
						// if user is an employer check if this graduate is in a shrotlist
						if($_SESSION['user_level']==2){
							include_once('_system/classes/shortlist_frontend.php') ;
							$shortlist_function = new shortlist_frontend ;
							$shortlist_function->setEmployerId($_SESSION['user_id']) ;
							$shortlist_function->setGraduateId($_GET['profileid']) ;
							$shortlist_list = $shortlist_function->getShortlistsList() ;
							$shortlist_flag = '' ;
							$sltitle = '' ;
							if(sizeof($shortlist_list)>0){
								?>
                                <script language="javascript" type="text/javascript" >
								function confirmDelete(vname){
									if(confirm('CONFIRMATION REQUIRED\nYou are about to remove <?php echo $this_name ; ?> from the '+vname+' shortlist')){
										return true ;
									} else {
										return false ;
									}
								}
								</script>
                                <h5>Featured in <?php echo sizeof($shortlist_list) ; ?> Shortlists:</h5>
                                <ul class="icolist">
                                <?php
								foreach($shortlist_list as $shortlist){
									?>
                                    <li><?php
										echo '<a href="?profileid='.$_GET['profileid'].'&amp;action=shortlistremove&amp;vacancyid='.$shortlist['vacancy_id'].'&amp;removeid='.$shortlist['id'].'" title="Remove this gradute from the '.htmlspecialchars($shortlist['name']).' shortlist" onclick="return confirmDelete(\''.htmlspecialchars($shortlist['name']).'\');" >'.draw_icon('delete.png').'</a>' ;
									?><a href="home.php?action=shortlists&subaction=viewshortlist&vacancyid=<?php echo $shortlist['vacancy_id'] ; ?>" title="View a full list of graduates added to the <?php echo htmlspecialchars($shortlist['name']) ; ?> shortlist" ><?php echo $shortlist['name'] ; ?></a></li>
                                    <?php
								}
								?>
                                </ul>
                                <?php
							}
						}
                        ?>
                    </div>


<?php // ============================================================== [ RIGHT ] ============================================================== // ?>
<?php // ============================================================== [ RIGHT ] ============================================================== // ?>

                    <div class="rightcontain" > <!-- right div -->
                    <?php

// ============================================= [ DRAW PROFILE ] =======================================================

                    ?>
                    <h2><?php echo $this_name ; ?></h2>
                    <?php
                    // Note at the top about free reduced or need to purchase profile
                    $screen_message = '' ;
					if($_GET['profileid']==DEMO_PROFILE_ID){
						$screen_message = draw_icon(ICON_ALERT).'<b>Demo Profile:</b> Some functionality has been disabled for the purpose of this demo.' ;
						$screen_message_type = 'notice' ;
					} else {
						if($show_user_profile!='Active'){
							if($_SESSION['user_level']==2&&$show_user_profile=='Preview'){
								if($employer_credits>0){
									$screen_message = draw_icon(ICON_ALERT).'<b>Currently Viewing a Reduced Profile:</b><br />You must purchase this profile to view more information or contact the graduate. <a href="#" ><b>Click to Buy Now</b></a>!' ;
									$screen_message_type = 'notice' ;
								} else {
									$screen_message = draw_icon(ICON_ALERT).'<b>Currently Viewing a Reduced Profile:</b><br />You do not have any credits on your account in order to purchase this profile. <a href="#" ><b>Click to Add Credits</b></a>.' ;
									$screen_message_type = 'notice' ;
								}
							} else {
								$screen_message = draw_icon(ICON_ALERT).'<b>Currently Viewing a Free Profile:</b><br />This graduate profile has been reduced for visitors. Please sign up for an employer account in order to download graduates\' CVs, view further detailed information and to contact graduates directly.' ;
								$screen_message_type = 'notice' ;
							}
						}
						if($transaction_result==true){
							$screen_message = draw_icon(ICON_GOOD).'<b>Viewing Full Profile:</b> You have previously unlocked unrestricted access to this profile.' ;
							$screen_message_type = 'success' ;
						}
					}
                    include('_system/inc/_screen_message_handler.php') ;


	// ============================================================== [ DIVS ] ============================================================== //
	// ============================================================== [ DIVS ] ============================================================== //

                    ?>
                    <div style="overflow:auto;" >
                        <div class="rightleft" >
                        	<h3>User Profile</h3>
                            <p><?php echo draw_icon('user.png') ; ?><b class="Highlight" >Name</b> : <?php echo $this_name ; ?></p>
                            <p><?php echo draw_icon('employer.png') ; ?><b class="Highlight" >Employment Status</b> : <?php echo ucfirst($this_employment_status) ; ?></p>
                            <p><?php echo draw_icon('available.png') ; ?><b class="Highlight" >Availability</b> : <?php echo $this_availability ; ?></p>
							<?php
                            // Hours
                            if(sizeof($this_hours)>0){
                                if(sizeof($this_hours)==1){
                                    if($this_hours[0]!=''){
                                        $this_scrn = $this_hours[0] ;
                                    } else {
                                        $this_scrn = 'Unspecified' ;
                                    }
                                    ?>
                                    <p><?php echo draw_icon('time.png') ; ?><b class="Highlight" >Employment Term Desired</b> : <?php echo $this_scrn ; ?></p>
                                    <?php
                                } else {
                                    ?>
                                    <p><?php echo draw_icon('time.png') ; ?><b class="Highlight" >Employment Term Desired</b> :</p>
                                    <ul>
                                    <?php
                                        foreach($this_hours as $hours){
                                            ?><li><?php echo $hours ; ?></li>
                                            <?php
                                        }
                                    ?>
                                    </ul>
                                    <?php
                                }
                            }
							
							// show location and desired location on preview
							?>
                            <p><?php echo draw_icon('location.png') ; ?><b class="Highlight" >Location</b> : <?php echo $this_education_location ; ?></p>
                            <?php								// Locations 
							if(sizeof($this_emploment_location)>0){
								if(sizeof($this_emploment_location)==1){
									if($this_hours[0]!=''){
										$this_scrn = $this_emploment_location[0] ;
									} else {
										$this_scrn = 'Unspecified' ;
									}
									?>
									<p><?php echo draw_icon('location.png') ; ?><b class="Highlight" >Desired Location</b> : <?php echo $this_scrn ; ?></p>
									<?php
								} else {
									?>
									<p><?php echo draw_icon('location.png') ; ?><b class="Highlight" >Desired Locations</b> :</p>
									<ul>
									<?php
										foreach($this_emploment_location as $emploment_location){
											?><li><?php echo $emploment_location ; ?></li>
											<?php
										}
									?>
									</ul>
									<?php
								}
							}
							
                            // Full Profile only info
                            if($show_user_profile=='Active'){
								?>
								<p><?php echo draw_icon('phone.png') ; ?><b class="Highlight" >Telephone</b> : <?php echo $this_tel_mobile ; ?></p>
								<p><?php echo draw_icon('calendar.png') ; ?><b class="Highlight" >Date of Birth</b> : <?php echo $this_date_of_birth ; ?></p>
								<?php
                            }
                            ?>
                            <p><?php echo draw_icon('car.png') ; ?><b class="Highlight" >Has a Driving Licence</b> : <?php echo ucfirst($this_has_driving_licence) ; ?></p>
                            <p><?php echo draw_icon('moon.png') ; ?><b class="Highlight" >Will do Unsociable Hours</b> : <?php echo ucfirst($this_will_do_antisocial) ; ?></p>
                            <p><?php echo draw_icon('travel.png') ; ?><b class="Highlight" >Willing to Travel</b> : <?php echo ucfirst($this_will_travel) ; ?></p>
                            <?php
							// Catagories 
							if(sizeof($this_job_category)>0){
								if(sizeof($this_job_category)==1){
									if($this_job_category[0]!=''){
										$this_scrn = $this_job_category[0] ;
									} else {
										$this_scrn = 'Unspecified' ;
									}
									?>
									<p><?php echo draw_icon('shirt.png') ; ?><b class="Highlight" >Desired Job Catagory</b> : <?php echo $this_scrn ; ?></p>
									<?php
								} elseif(sizeof($this_job_category)==sizeof($category_array)){
									?>
									<p><?php echo draw_icon('shirt.png') ; ?><b class="Highlight" >Desired Job Catagory</b> : Any</p>
									<?php
								} else {
									?>
									<p><?php echo draw_icon('shirt.png') ; ?><b class="Highlight" >Desired Job Catagories</b> :</p>
									<ul>
									<?php
										foreach($this_job_category as $job_category){
											?><li><?php echo $job_category ; ?></li>
											<?php
										}
									?>
									</ul>
									<?php
								}
							}
                            // alternate languages
                            if(sizeof($this_alternate_languages)>0){
                                if(sizeof($this_alternate_languages)==1){
                                    if($this_alternate_languages[0]!=''){
                                        $this_scrn = $this_alternate_languages[0] ;
                                    } else {
                                        $this_scrn = 'Unspecified' ;
                                    }
                                    ?>
                                    <p><?php echo draw_icon('comments.png') ; ?><b class="Highlight" >Alternate Languages</b> : <?php echo $this_scrn ; ?></p>
                                    <?php
                                } else {
                                    ?>
                                    <p><?php echo draw_icon('comments.png') ; ?><b class="Highlight" >Alternate Languages</b> :</p>
                                    <ul>
                                    <?php
                                        foreach($this_alternate_languages as $alternate_language){
                                            ?><li><?php echo $alternate_language ; ?></li>
                                            <?php
                                        }
                                    ?>
                                    </ul>
                                    <?php
                                }
                            }
							?>
                            <?php
                            if($show_user_profile!='Active'){
								?>
                                <p><?php echo draw_icon(ICON_ALERT) ; ?>Unlock this profile to view more information.</p>
                                <?php
							}
							?>
                        </div>
                        <div class="rightright" >
                        	<!-- Image for print only -->
                       		<div id="printimg" class="img" style="width:<?php echo IMAGE_UPLOAD_LARGE_WIDTH ; ?>px; height:<?php echo IMAGE_UPLOAD_LARGE_HEIGHT ; ?>px; margin-bottom:20px;" ><img src="<?php echo $imgUrl ; ?>" width="<?php echo IMAGE_UPLOAD_LARGE_WIDTH ; ?>" height="<?php echo IMAGE_UPLOAD_LARGE_HEIGHT ; ?>" border="0" alt="" /></div>
                            <!-- video -->
                            <div class="videoprofile">
                        	<h3>Video Profiling</h3>
                            <?php
                            if($this_education_level!='Unspecified'&&$show_user_profile!='Preview'){
								include('_system/inc/_youtube_graduate_profile.php') ;
							} else {
								?>
                                <p><?php echo draw_icon(ICON_ALERT) ; ?>Unlock this profile to view videos</p>
                                <?php
							}
							?>
                            </div>
                            <!-- education -->
                        	<h3>Education</h3>
                            <p><?php echo draw_icon('books.png') ; ?><b class="Highlight" >Subject Studied</b> : <?php echo $this_subject ; ?></p>
                            <p><?php echo draw_icon($this_has_graduated_icon) ; ?><b class="Highlight" >Has Graduated</b> : <?php echo $this_education_has_graduated ; ?></p>
                            <?php
                            if($this_education_level!='Unspecified'&&$show_user_profile!='Preview'){
                                ?>
                                <p><?php echo draw_icon('education_level.png') ; ?><b class="Highlight" >Level</b> : <?php echo $this_education_level ; ?></p>
                                <p><?php echo draw_icon('education_level.png') ; ?><b class="Highlight" >Degree Title</b> : <?php echo $this_education_degree_title ; ?></p>
                                <p><?php echo draw_icon('calendar.png') ; ?><b class="Highlight" >Start</b> : <?php echo $this_education_start ; ?></p>
                                <p><?php echo draw_icon('calendar.png') ; ?><b class="Highlight" >End</b> : <?php echo $this_education_end ; ?></p>
                                <p><?php echo draw_icon('grade.png') ; ?><b class="Highlight" >Grade</b> : <?php echo $this_education_grade ; ?></p>
                                <p><?php echo draw_icon('building.png') ; ?><b class="Highlight" >Institution</b> : <?php echo $this_education_institution ; ?></p>
                                <?php
                            
                                if(file_exists($userfile_certificate)){
                                    ?>
                                    <p><?php echo draw_icon('certificate.png') ; ?><b class="Highlight" >Certificate</b> : <?php echo $this_education_certificate_title ; ?><br />
                                    <span class="small" >(<?php
									include_once('_system/functions/user_file_is_an_image.php') ;
                                    if(user_file_is_an_image('userfiles/certificate/'.$user_info['id'].'/')){
										?><a href="user_image.php?userid=<?php echo $user_info['id'] ; ?>&amp;size=original&type=certificate&admin=true&.jpg" class="lytebox" target="_blank" >View</a> / <?php
									}
                                    ?><a href="download_userfile.php?userid=<?php echo $user_info['id'] ; ?>&amp;type=certificate" >Download</a>)</span></p>
                                    <?php
                                }
                            } else {
								?>
                                <p><?php echo draw_icon(ICON_ALERT) ; ?>Unlock this profile to view more information.</p>
                                <?php
							}
                            ?>
                        </div>
                    </div>
                    <?php


                    // Education Info
                    // education_level, education_start, education_end, education_has_graduated, education_grade, education_institution, education_location, education_certificate_title


// ============================== Non-active profile notice
					$resume_search_available = false ;
					foreach($search_fld_array as $search_fld){
						if($_SESSION['search_'.$search_fld]!=''){
							$resume_search_available = true ;
						}
					}
                    if($_SESSION['user_level']==''||((!$_SESSION['user_level']==='0')&&$_SESSION['user_level']!=2&&$_SESSION['user_level']!=3)){
                        ?>
                        <h3>Employer Accounts</h3>
                        <p>Employers can view more information, download CVs and contact graduates.</p>
                        <ul class="li_buttons" >
                            <li><a href="employers.php" >More Information</a></li>
                            <li><a href="employer_signup.php" >Signup for an Employer Account</a></li>
                            <?php
                            if($resume_search_available==true){
                                ?>
                                <li><a href="search.php?resume=true" >Back to Search</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                    }elseif($_SESSION['user_level']==='0'||$_SESSION['user_level']==='3'){
                        ?>
                        <p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?>You are viewing this profile as an administrator</p>
                        <?php
						if($resume_search_available==true){
							?>
							<ul class="li_buttons" >
								<li><a href="search.php?resume=true" >Back to Search</a></li>
							</ul>
							<?php
						}
                    }elseif($show_user_profile!='Active'){
                        ?>
                        <div align="left" >
                        <ul class="li_buttons" >
                            <li><a href="unlock_profile.php?profileid=<?php echo $_GET['profileid'] ; ?>" >Unlock Profile</a></li>
                            <li><a href="home.php?action=vacancies&subaction=shortlistadd&profileid=<?php echo $_GET['profileid'] ; ?>" >Add To Shortlist</a></li>
                            <li><a href="home.php?action=credits" >Manage Credits</a></li>
                            <?php
                                if($_GET['unlock']==''&&$resume_search_available==true){
                                    ?>
                                    <li><a href="search.php?resume=true" >Back to Search</a></li>
                                    <?php
                                }
                            ?>
                        </ul>
                        </div>
                        <?php
                    } else {
						?>
						<ul class="li_buttons" >
                        <?php
						if($_GET['profileid']==DEMO_PROFILE_ID){
							?>
							<li><a class="pointer" onclick="alert('DEMO PROFILE\nAn employer account is required in order to use the shortlist manager!');"  >Add To Shortlist</a></li>
							<?php
						}elseif($show_user_profile=='Active'&&$_GET['profileid']!=$_SESSION['user_id']){
							?>
							<li><a href="home.php?action=vacancies&subaction=shortlistadd&profileid=<?php echo $_GET['profileid'] ; ?>" >Add To Shortlist</a></li>
							<?php
						} elseif($_GET['profileid']!=$_SESSION['user_id']){
							?>
							<li><a href="home.php?action=" >Edit Profile</a></li>
							<?php
						}
						if($resume_search_available==true){
							?>
							<li><a href="search.php?resume=true" >Back to Search</a></li>
							<?php
						}
						?>
						</ul>
                        <?php
					}
                    ?>
                    </div> <!-- right div -->
                    <?php
                } else { // Inactive or invalid user request
                    ?>
                    <h3><?php echo $show_user_profile ; ?> <?php if($show_user_profile!='Invalid Permissions'){ echo 'User Requested' ; } ?></h3>
                    <?php
                    // Errors
                    if($show_user_profile=='Incomplete'){
                        ?>
                        <p>This user's profile is incomplete and can not be viewed at this time.</p>
                        <?php
                    }elseif($show_user_profile=='Inactive'){
                        ?>
                        <p>The user profile requested may no longer exist on our system.</p>
                        <?php
                    }elseif($show_user_profile=='Invalid Permissions'){
                        ?>
                        <p>You do not have permission to view this user profiles.</p>
                        <ul>
                            <li>Guest or graduates can currently only view their own profile.</li>
                        </ul>
                        <?php
                    }else{
                        ?>
                        <p>The user profile request was invalid or the user no longer exists on our system.</p>
                        <?php
                    }
                    // Show the back / Search link
                    if($show_user_profile!='Invalid Permissions'){
                        ?>
                        <p>Please <a href="search.php" >click here</a> to find a graduate profile.</p>
                        <?php
                    }
                }
				// Show notice for incomplete profile
				if($graduate_profile_percentage<100){
                    // <p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ? ><b>Please Note:</b> This user has edited their profile and it is now incomplete, you have been granted access due to your purchase history.</p>
					?>
                    <?php
				}
            ?>
            </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->

<?php include('_global_body_end.php') ; ?>

</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
