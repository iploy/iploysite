<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
	include_once('_system/functions/string_elipsis.php') ;
	include_once('_system/classes/session_killer.php') ;
	include('_system/_config/_multiple_choice_arrays.php') ;

	if($_SESSION['user_level']==2){
		include_once('_system/classes/shortlist_frontend.php') ;
		$shortlist_function = new shortlist_frontend ;
		$shortlist_function->setEmployerId($_SESSION['user_id']) ;
	}

	// Error handling from purchases
	if($_GET['error']=='badrequest'){
		$screen_message = draw_icon(ICON_BAD).'<b>Request ID Error</b>: The request ID specified was incorrect. Please do not manually modify the address in your browser window.' ;
		$screen_message_type = 'error' ;
	}
	if($_GET['error']=='nocredits'){
		$screen_message = draw_icon(ICON_BAD).'<b>No Credits</b>: There are no credits available on your account to unlock this profile. Please do not manually modify the address in your browser window.' ;
		$screen_message_type = 'error' ;
	}
	if($_GET['error']=='noprofileid'){
		$screen_message = draw_icon(ICON_BAD).'<b>No User Requested</b>: Your unlock request will not be processed as no user ID was specified.' ;
		$screen_message_type = 'error' ;
	}
	if($_GET['error']=='nouser'){
		$screen_message = draw_icon(ICON_BAD).'<b>No User Found</b>: The specified user ID does not exist on our system.' ;
		$screen_message_type = 'error' ;
	}
	if($_GET['error']=='inactive'){
		$screen_message = draw_icon(ICON_BAD).'<b>Inactive User Found</b>: The specified user\'s account has been disabled.' ;
		$screen_message_type = 'error' ;
	}



	// This will hide the search results if no search was specified...
	$is_a_search = false ;
	// ...and this will show it again if a browse all was requested
	if($_GET['browse']=='true'){
		$is_a_search = true ;
	}

	// Process the paging requeststststs
	$current_page = 1 ;
	$page_size = SEARCH_RESULTS_PAGE_SIZE ;
	if($_SESSION['search_page']!=''&&$_GET['resume']=='true'){
		$current_page = $_SESSION['search_page'] ;
	}
	if($_SESSION['search_pagesize']!=''){
		$page_size = $_SESSION['search_pagesize'] ;
	}
	if($_GET['page']!=''&&is_numeric($_GET['page'])){
		$current_page = $_GET['page'] ;
		$_SESSION['search_page'] = $current_page ;
	}
	if($_GET['pagesize']!=''&&is_numeric($_GET['pagesize'])){
		$page_size = $_GET['pagesize'] ;
		$_SESSION['search_pagesize'] = $page_size ;
	}
	// Add the querystring variables to the search where clause
	$custom_where = ''  ;
	$is_first_clause = true ;

	foreach($search_fld_array as $search_fld){
		if($_GET[$search_fld]!=''||($_SESSION['search_'.$search_fld]!=''&&$_GET['resume']=='true')){
			// echo $search_fld.' - '.$_SESSION['search_'.$search_fld].' - '.$_GET[$search_fld].'<br />' ;
			$is_a_search = true ;
			$search_value = '' ;
			// check if the get is empty, if so use the session
			if($_GET[$search_fld]!=''){
				$_SESSION['search_'.$search_fld] = $_GET[$search_fld] ;
				$search_value = $_GET[$search_fld] ;
			} else {
				$search_value = $_SESSION['search_'.$search_fld] ;
			}
			// use AND if not the first one.
			if($is_first_clause!=true){
				$custom_where.= "AND " ;
			}
			if($search_fld=='keyword'){
				$custom_where.= "(
					graduates.first_name LIKE '%".mysql_escape_string($search_value)."%' OR 
					graduates.subject LIKE '%".mysql_escape_string($search_value)."%' OR 
					graduates.job_category LIKE '%".mysql_escape_string($search_value)."%'
				)" ;

			} elseif($search_fld=='subject'){
				$custom_where.= "(" ;
				for($s=0;$s<sizeof($search_value);$s++){
					if($s>0){
						$custom_where.= " OR " ;
					}
					$custom_where.= "graduates.subject LIKE '%".mysql_escape_string($search_value[$s])."%'" ;
					// echo $value."<br />" ;
				}
				$custom_where.= ")" ;

			} elseif($search_fld=='availability'){
				// Make sure that the selected availability and all after are included from the multiple choice array
				$custom_where.= "graduates.availability >= '".date(strtotime($availability_array[$i]))."'" ;

			} elseif($search_fld=='alternate_languages'){
				$custom_where.= "(" ;
				for($s=0;$s<sizeof($search_value);$s++){
					if($s>0){
						$custom_where.= " AND " ;
					}
					$custom_where.= "graduates.alternate_languages LIKE '%".mysql_escape_string($search_value[$s])."%'" ;
					// echo $value."<br />" ;
				}
				$custom_where.= ")" ;

			} else {
				// Else perform a basic search
				$custom_where.= "graduates.".$search_fld." LIKE '%".mysql_escape_string($search_value)."%' " ;
			}
			$is_first_clause = false ;
		} else {
			// else blank the session for the following search
			$_SESSION['search_'.$search_fld] = '' ;
		}
	}

	/*
	if($_GET['catagory']!=''){
		$is_a_search = true ;
		if($is_first_clause!=true){ $custom_where.= "AND " ; }
		$custom_where.= "" ;
	}
	*/
	// Do the search
	if($is_a_search==true){
		include_once('_system/classes/graduate_frontend.php') ;
		$graduate_data = new graduate_data ;
		$graduate_data->setListPage($current_page) ;
		$graduate_data->setListPageSize($page_size) ;
		$graduate_data->setListType('search') ;
		$graduate_data->setListOrderBy('login.date_created') ;
		$graduate_data->setListOrderDir('DESC') ;
		$graduate_data->showCompleteOnly(true) ;
		// Create an array of the querystring varaibles so that we can quickly loop through this for QS maintainance (paging, etc)
		$QS_fixer = '&amp;resume='.$_GET['resume'] ;
		foreach($search_fld_array as $search_fld_array){
			// if matching, this is an array type selector and needs to be built as such
			if(is_array($_GET[$search_fld_array])){
				foreach($_GET[$search_fld_array] as $value){
					$QS_fixer.='&amp;'.$search_fld_array.'[]='.$value ;
				}
			} else {
				$QS_fixer.='&amp;'.$search_fld_array.'='.$_GET[$search_fld_array] ;
			}
		}
		$graduate_data->setCustomWhere($custom_where) ;
		// Get the data
		$graduate_list = $graduate_data->getGraduatesList() ;
		$graduate_list_count = $graduate_data->getGraduatesList(true) ;
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
		// Build the Paging String
		$paging_string = '' ;
		$page_counter = 0 ;
		$page_range = 5 ;
		$showing_count = 0 ;
		$first_link = true ;
		if($graduate_list_count>$page_size){
			// This will only run if there is more than 1 page full of results
			$first_number = 1 ;
			$last_number = $page_size ;
			if($current_page!=1){
				$paging_string.= '<a href="?page='.($current_page-1).'&amp;pagesize='.$_GET['pagesize'].$QS_fixer.'" >&lt; Prev</a> ' ;
			} else {
				$paging_string.= '&lt; Prev ' ;
			}
			while($first_number<=$graduate_list_count){
				$page_counter ++ ;
				if($last_number>$graduate_list_count){
					$last_number = $graduate_list_count ;
				}
				if($first_number!=$last_number){
					$this_label = $first_number.'-'.$last_number ;
				} else {
					$this_label = $first_number ;
				}
				if($page_counter!=$current_page){
					$label_prefix = '<a href="?page='.$page_counter.'&amp;pagesize='.$_GET['pagesize'].$QS_fixer.'" >' ;
					$label_postfix = '</a>' ;
				} else {
					$label_prefix = '<b title="Current Page" >' ;
					$label_postfix = '</b>' ;
				}
				//$current_page-($page_counter+1)<($page_range)
				if((($page_counter+1)>($current_page-$page_range)&&($page_counter-1)<($current_page+$page_range))){
					$paging_string.= ' | ' ;
					$paging_string.= $label_prefix.$this_label.$label_postfix ;
					$showing_count ++ ;
				}
				$first_number = $first_number + $page_size ;
				$last_number = $last_number + $page_size ;
			}
			if($page_counter!=$current_page){
				$paging_string.= ' | <a href="?page='.($current_page+1).'&amp;pagesize='.$_GET['pagesize'].$QS_fixer.'" >Next &gt;</a>' ;
			} else {
				$paging_string.= ' | Next &gt;' ;
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['APP_SITE_NAME'] ; ?> : Graduate Search</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
<style type="text/css" >
.search_results, .search_results li {
	margin:0;
	padding:0;
	list-style:none;
}
.search_results {
	margin:8px 0 8px 6px;
	overflow:auto;
}
.search_results li {
	margin:7px;
	float:left;
	line-height:16px;
}
.search_results a {
	width:455px;
	height:<?php echo IMAGE_UPLOAD_LRGMEDIUM_HEIGHT+45 ; ?>px;
	background:#F4F4F4;
	border:1px solid #777;
	display:block;
	overflow:hidden;
	color:#555;
	border-radius:6px;
}
.search_results .img {
	width:<?php echo IMAGE_UPLOAD_LRGMEDIUM_WIDTH ; ?>px;
	height:<?php echo IMAGE_UPLOAD_LRGMEDIUM_HEIGHT ; ?>px;
	background-color:#111;
	margin:9px 9px 4px;
	border:1px solid #999;
	float:left;
	overflow:hidden;
}
.search_results .name, .search_results .subject, .search_results .availability {
}
.search_results .contain {
	display:block;
	margin:10px 6px 0 <?php echo IMAGE_UPLOAD_LRGMEDIUM_WIDTH+22 ; ?>px;
}
.search_results .name {
	font-weight:bold;
	background:#777;
	color:#FFF;
	line-height:24px;
	text-transform:uppercase;
	padding:0 6px 1px;
	display:block;
}
.search_results .subject {
	display:block;
	margin-top:4px;
}
.search_results a:hover {
	text-decoration:none;
	background:#FCFCFC;
}


.search_results .field {
	display:block;
	line-height:18px;
	height:18px;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
}
.search_results .ticks {
	display:block;
	line-height:18px;
}
.search_results .ticks .ico {
	margin:0 4px -3px 0 ;
}
.search_results .tickrow {
	display:block;
	font-size:13px;
	line-height:13px;
	height:13px;
	margin-bottom:8px;
}
.search_results .tickrow b {
	margin-right:18px;
}


.search_results .tickbox {
	border-bottom:1px solid #CCC;
	display:block;
	margin-bottom:2px;
	margin-right:4px;
}

.float_left {
	float:left;
	margin-right:50px;
}


.search_results .unlocked a {
	border:1px solid #679E21;
}
.search_results .unlocked a .name {
	background:#679E21;
}
.search_results .unlocked a:hover {
	border:1px solid #71C107;
}
.search_results .unlocked a:hover .name {
	background:#71C107;
}


</style>

<?php include_once('_system/inc/_include_mootools_head.php') ; ?>
<script src="js/mootools_datepicker/languages/Locale.en-US.DatePicker.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.Attach.js" type="text/javascript" language="javascript" ></script>
<script src="js/mootools_datepicker/js/Picker.Date.js" type="text/javascript" language="javascript" ></script>
<link href="js/mootools_datepicker/themes/iploy/iploy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" >
window.addEvent('domready', function(){
    new DatePicker($$('.date'), {
		format: '<?php echo DM_JS_DATE_PICK_FORMAT ; ?>',
		pickerClass: 'datepicker_carmemo',
		timePicker: false,
		startView: 'years',
		toggleElements: '.datetoggle',
		minDate:'<?php echo date(DM_PHP_DATE_FORMAT) ; ?>'
    });
});
</script>

<!-- Stuff for auto population -->
<script language="javascript" type="text/javascript" src="js/ajaxAutoComplete/ajaxAutoComplete.js" ></script>
<link rel="stylesheet" type="text/css" href="js/ajaxAutoComplete/ajaxAutoComplete.css" />


<script language="javascript" type="text/javascript" src="js/mooZipList/mooZipList.js" ></script>
<link rel="stylesheet" type="text/css" href="js/mooZipList/mooZipList.css" />

</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
              <?php
				// if is a search start --------------------------------------------
				if($is_a_search==true){
					if($graduate_list_count>0){
						?>
						<h2>Search Results</h2>
							<?php
								$end_number = $current_page*$page_size ;
								if($end_number>$graduate_list_count){
									$end_number = $graduate_list_count ;
								}
							?>
							<p style="overflow:auto; margin-bottom:0px;" ><span style="float:right; display:block;"><?php echo 'Showing '.((($current_page-1)*$page_size)+1).' to '.$end_number.' of '.$graduate_list_count.' results' ; ?></span>
							<?php
								if($page_counter!=0){
									echo 'Page '.$current_page.' of '.$page_counter ;
								} else {
									echo 'Page 1 of 1' ;
								}
							?>
						</p>
					  <ul class="search_results" >
						<?php
							include_once('_system/classes/transactions.php') ;
							$transactions_function = new transactions ;
							$counter = 0 ;
							$has_unlocked_results = false ;
							foreach($graduate_list as $graduate){
								$available_class = '' ;
								$available_icon = 'locked.png' ;
								if($transactions_function->checkEmployerToGraduateAccess($_SESSION['user_id'],$graduate['login_id'])==true){
									$available_class = ' class="unlocked" ' ;
									$has_unlocked_results = true ;
									$available_icon = 'unlocked.png' ;
								}
								// if user is an employer check if this graduate is in a shrotlist
								if($_SESSION['user_level']==2){
									$shortlist_function->setGraduateId($graduate['login_id']) ;
									$shortlist_list = $shortlist_function->getShortlistsList() ;
									$shortlist_flag = '' ;
									$sltitle = '' ;
									if(sizeof($shortlist_list)>0){
										$sltitle = 'Shortlists : ' ;
										$slcount = 0 ;
										foreach($shortlist_list as $shortlist){
											$slcount ++ ;
											if($slcount>1){
												$sltitle.= ', ' ;
											}
											if($shortlist['name']==''){
												$sltitle.= 'Wishlist' ;
											} else {
												$sltitle.= $shortlist['name'] ;
											}
										}
										$shortlist_flag.= '<span title="'.$sltitle.'" >(Shortlisted)</span>' ;
									}
								}
								echo '<li'.$available_class.' title="'.$sltitle.'" ><a href="view_profile.php?profileid='.$graduate['login_id'].'" >' ;
								echo '<span class="name" >'.draw_icon($available_icon).$graduate['first_name'].' '.$shortlist_flag ;
								if($graduate['availability']!='0000-00-00'&&!is_null($graduate['availability'])&&$graduate['availability']!=''){
									echo ' - Available '.date(DM_PHP_DATE_PICK_FORMAT,strtotime($graduate['availability'])) ;
								}
								$thisJobCat = str_replace(",",", ",$graduate['job_category']) ;
								$thisEmpLoc = str_replace(",",", ",$graduate['emploment_location']) ;
								echo '</span>' ;
								echo '<span class="img" >' ;
								if($graduate['employment_status']=='employed'){
									echo '<img src="images/graphics/employed_mask.png" width="160" height="134" class="absolutepos" border="0" alt="" />' ;
								}
								echo '<img src="user_image.php?userid='.$graduate['login_id'].'&amp;size=lrgmed" width="'.IMAGE_UPLOAD_LRGMEDIUM_WIDTH.'" height="'.IMAGE_UPLOAD_LRGMEDIUM_HEIGHT.'" border="0" alt="" />' ;
								echo '</span>' ;
								echo '<span class="contain" >' ;
								echo '<span class="ticks" >' ;
								echo '<span class="ticks" >' ;
								
								echo '<span class="tickbox" >' ;
								echo '<span class="tickrow" >' ;
									echo draw_icon(($graduate['has_cv']==1 ? 'accept.png' : 'deny.png')).'<b class="'.($graduate['has_cv']==1 ? 'green' : 'red').'" >CV</b>' ;
									echo draw_icon(($graduate['has_photo']==1 ? 'accept.png' : 'deny.png')).'<b class="'.($graduate['has_photo']==1 ? 'green' : 'red').'" >Picture</b>' ;
									// just this
									echo draw_icon(($graduate['has_video']==1 ? 'accept.png' : 'deny.png')).'<b class="'.($graduate['has_video']==1 ? 'green' : 'red').'" >Video</b>' ;
								echo '</span>' ;
								echo '<span class="tickrow" >' ;
									echo draw_icon(($graduate['has_certificate']==1 ? 'accept.png' : 'deny.png')).'<b class="'.($graduate['has_certificate']==1 ? 'green' : 'red').'" >Certificate</b>' ;
									echo draw_icon(($graduate['education_has_graduated']=='yes' ? 'accept.png' : 'deny.png')).'<b class="'.($graduate['education_has_graduated']=='yes' ? 'green' : 'red').'" >Has '.($graduate['education_has_graduated']=='yes' ? '' : 'not ').'Graduated</b>' ;
								echo '</span>' ;
								echo '</span>' ;
								
								echo '</span>' ;
								echo '</span>' ;
								echo '<span class="field" ><b>Subject</b>: '.$graduate['subject'].'</span> ' ;
								echo '<span class="field" ><b>Grade</b>: '.$graduate['education_grade'].'</span> ' ;
								echo '<span class="field" ><b>Degree Title</b>: '.$graduate['education_degree_title'].'</span> ' ;
								echo '<span class="field help" title="'.$thisJobCat.'" ><b>Job Catagory</b>: '.$thisJobCat.'</span> ' ;
								echo '<span class="field help" title="'.$thisEmpLoc.'" ><b>Desired Locaion</b>: '.$thisEmpLoc.'</span> ' ;
								// echo '<span class="availability" ><b>Job Category:</b> '.string_elipsis(str_replace(",",", ",$graduate['job_category']),130).'</span><br />' ;
								// echo '<span class="subject" ><b>Studying:</b> '.string_elipsis(str_replace(",",", ",$graduate['subject']),130).'</span>' ;
								echo '</span>' ;
								echo '</a></li>'."\n" ;
							}
						?>
						</ul>
						<!-- Paging START -->
					  <p style="overflow:auto;" >
							<span style="float:right;" >Show
							<?php
								$page_size_array = explode(",",SEARCH_RESULTS_AVAILABLE_PAGE_SIZES) ;
								foreach($page_size_array as $this_page_size){
									if($page_size!=$this_page_size){
										echo '&nbsp;<a href="?page=1&amp;pagesize='.$this_page_size.$QS_fixer.'" >'.$this_page_size.'</a> ' ;
									} else {
										echo '&nbsp;<b>'.$this_page_size.'</b> ' ;
									}
								}
							?>
							</span> 
							<?php echo $paging_string ; ?>
					  </p>
                      <p align="center" ><?php echo 'Showing '.((($current_page-1)*$page_size)+1).' to '.$end_number.' of '.$graduate_list_count.' results' ; ?></p>
					  <?php
							if($has_unlocked_results==true){
								?><p><?php echo draw_icon('unlocked_green.png') ; ?>Results with a green border have been purchased and are unlocked.</p>
								<?php
							}
                      ?>
						<!-- Paging END -->
					  <div class="greydivider"></div>
						<?php
					} else {
						// No search results found bit
						?>
						<h2>No Search Results</h2>
						<p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?>No results could be found for the search criteria used. Please modify the search terms used and try again.</p>
						<?php
					}
				}
				// if is a search end --------------------------------------------
				
				?>
                
                <h2>Graduate Search</h2>
                <?php include_once('_system/inc/_screen_message_handler.php') ; ?>
                <script language="javascript" type="text/javascript" src="js/check_me.js" ></script>
                <form name="searchform" id="searchform" action="search.php?" method="get" enctype="application/x-www-form-urlencoded" >


                <div class="float_left" style="clear:both;" >
                    <label for="srch_catagory" >Job Category</label>
                    <select name="job_category" id="srch_catagory" >
                        <option value="" >Please select a job category&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        <optgroup label="Options" >
                            <?php
                                foreach($category_array as $category){
                                    if($_SESSION['search_job_category']==$category){
                                        $this_selected = 'selected="selected" ' ;
                                    } else {
                                        $this_selected = '' ;
                                    }
                                    ?><option value="<?php echo $category ; ?>" title="<?php echo $category ; ?>" <?php echo $this_selected ; ?> ><?php echo string_elipsis($category,25) ; ?></option>
                                    <?php
                                }
                            ?>
                        </optgroup>
                    </select>

                    <label for="srch_availability" >Available From</label>
            		<div class="datetoggle" >&nbsp;</div>
                    <input class="text date" type="text" readonly="readonly" name="availability" id="srch_availability" value="<?php echo $_SESSION['search_availability'] ; ?>" />&nbsp;&nbsp;&nbsp;<?php echo draw_icon('calendar.png') ; ?>&nbsp;<span onclick="document.getElementById('srch_availability').value='';" class="pointer" ><?php echo draw_icon('delete.png') ; ?></span>

                    <label for="srch_hours" >Current Location</label>
                    <select name="education_location" id="srch_education_location" >
                        <option value="" >Select if required&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        <?php
                            foreach($locations_array as $education_location){
                                if($_SESSION['search_education_location']==$education_location){
                                    $this_selected = 'selected="selected" ' ;
                                } else {
                                    $this_selected = '' ;
                                }
                                ?>
                                <option value="<?php echo $education_location ; ?>" <?php echo $this_selected ; ?> ><?php echo $education_location ; ?></option>
                                <?php
                            }
                        ?>
                    </select>

                </div>

                <div class="float_left" >
                    <label for="srch_location" >Intended Location</label>
                    <select name="emploment_location" id="srch_location" >
                        <option value="" >Please select a location&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        <optgroup label="Options" >
                        <?php
                            // $ul_split_at = ceil(sizeof($locations_array)/2) ;
                            foreach($locations_array as $location){
                                if($_SESSION['search_emploment_location']==$location){
                                    $this_selected = 'selected="selected" ' ;
                                } else {
                                    $this_selected = '' ;
                                }
                                ?>
                                <option value="<?php echo $location ; ?>" <?php echo $this_selected ; ?> ><?php echo $location ; ?></option>
                                <?php
                            }
                        ?>
                        </optgroup>
                    </select>

                    <label for="srch_education_degree_title" >Degree Title</label>
                    <input class="text aac textsmall" type="text" name="education_degree_title" id="srch_education_degree_title" value="<?php echo $_SESSION['search_education_degree_title'] ; ?>" />


                    <label for="srch_hours" >Grade</label>
                    <select name="education_grade" id="srch_education_grade" >
                        <option value="" >Select if required&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        <?php
                            foreach($education_grade_array as $education_grade){
                                if($_SESSION['search_education_grade']==$education_grade){
                                    $this_selected = 'selected="selected" ' ;
                                } else {
                                    $this_selected = '' ;
                                }
                                ?>
                                <option value="<?php echo $education_grade ; ?>" <?php echo $this_selected ; ?> ><?php echo $education_grade ; ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>

                <div class="float_left" >
                    <label for="srch_hours" >Desired Hours</label>
                    <select name="hours" id="srch_hours" >
                        <option value="" >Select if required&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        <?php
                            foreach($hours_array as $hours){
                                if($_SESSION['search_hours']==$hours){
                                    $this_selected = 'selected="selected" ' ;
                                } else {
                                    $this_selected = '' ;
                                }
                                ?>
                                <option value="<?php echo $hours ; ?>" <?php echo $this_selected ; ?> ><?php echo $hours ; ?></option>
                                <?php
                            }
                        ?>
                    </select>

                    <label for="srch_education_institution" >Institution</label>
                    <input class="text aac textsmall" type="text" name="education_institution" id="srch_education_institution" value="<?php echo $_SESSION['search_education_institution'] ; ?>" />
                </div>





				<!-- -- -- -- -- -- -- -- -- -- -- CLEAR -- -- -- -- -- -- -- -- -- -- -->
                <div class="float_left" style="clear:both;" >
                    
                    <label for="srch_catagory" >Options</label>
                    <div class="lister" >
                        <ul>
                            <li><input type="checkbox" name="education_has_graduated" id="education_has_graduated" value="Yes" <?php if($_SESSION['search_education_has_graduated']=='Yes'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('education_has_graduated','multi');" >Has Graduated</span></li>
                            <li><input type="checkbox" name="will_travel" id="will_travel" value="Yes" <?php if($_SESSION['search_will_travel']=='Yes'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('will_travel','multi');" >Willing to Travel</span></li>
                            <li><input type="checkbox" name="has_driving_licence" id="has_driving_licence" value="Yes" <?php if($_SESSION['search_has_driving_licence']=='Yes'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('has_driving_licence','multi');" >Has driving Licence</span></li>
                            <li><input type="checkbox" name="will_do_antisocial" id="will_do_antisocial" value="Yes" <?php if($_SESSION['search_will_do_antisocial']=='Yes'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('will_do_antisocial','multi');" >Willing to do Anti-Social Hours</span></li>
                        </ul>
                    </div>
                    
                </div>

                <div class="float_left" >

                    <label for="srch_catagory" >User Uploads</label>
                    <div class="lister" >
                        <ul>
                            <li><input type="checkbox" name="has_cv" id="has_cv" value="1" <?php if($_SESSION['search_has_cv']=='1'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('has_cv','multi');" >CV Uploaded</span></li>
                            <li><input type="checkbox" name="has_photo" id="has_photo" value="1" <?php if($_SESSION['search_has_photo']=='1'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('has_photo','multi');" >Photo Uploaded</span></li>
                            <li><input type="checkbox" name="has_certificate" id="has_certificate" value="1" <?php if($_SESSION['search_has_certificate']=='1'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('has_certificate','multi');" >Certificate Uploaded</span></li>
                            <li><input type="checkbox" name="has_video" id="has_video" value="1" <?php if($_SESSION['search_has_video']=='1'){ echo 'checked="checked" ' ; } ?>/> <span onclick="check_me('has_video','multi');" >Videos Available</span></li>
                        </ul>
                    </div>
                </div>

				<div class="float_left" style="clear:both;" >
                    <label for="srch_subject" >Subject Studied</label>
                    <div class="float_container lister" >
                    <?php
                        $ul_split_at = ceil(sizeof($subjects_array)/3) ;
                        $subjects_session_array = $_SESSION['search_subject'] ;
						if(!is_array($subjects_session_array)){ $subjects_session_array = explode(",",$subjects_session_array) ; }
                        for($i=0;$i<sizeof($subjects_array);$i++){
                            // Check if selected
                            if(in_array($subjects_array[$i],$subjects_session_array)){
                                $is_selected = 'checked="checked" ' ;
                            } else {
                                $is_selected = '' ;
                            }
                            if($i==0||($i+1)==($ul_split_at+1)||($i+1)==(($ul_split_at*2)+1)){
                                echo '<div class="floater" ><ul>'."\n" ;
                            }
                            ?>
                            <li><input id="srch_subject_<?php echo $i ; ?>" type="checkbox" value="<?php echo $subjects_array[$i] ; ?>" name="subject[]" <?php echo $is_selected ; ?>/><span onclick="check_me('srch_subject_<?php echo $i ; ?>','multi');" ><?php echo $subjects_array[$i] ; ?></span></li>
                            <?php
                            if(($i+1)==$ul_split_at||($i+1)==($ul_split_at*2)||($i+1)==sizeof($subjects_array)){
                                echo '</ul></div>'."\n" ;
                            }
                        }
                    ?>
                    </div>
                </div>

                <div class="float_left" style="clear:both;" >
                    <label for="srch_catagory" >Alternate Languages</label>
                    <div class="float_container lister mooZipList" >
                    <?php
						$ul_split_at = ceil(sizeof($languages_array)/5) ;
						$languages_session_array = $_SESSION['search_alternate_languages'] ;
						if(!is_array($languages_session_array)){ $languages_session_array = explode(",",$languages_session_array) ; }
						for($i=0;$i<sizeof($languages_array);$i++){
							// Check if selected
							if(in_array($languages_array[$i],$languages_session_array)){
								$is_selected = 'checked="checked" ' ;
							} else {
								$is_selected = '' ;
							}
							if($i==0||($i)==($ul_split_at*2)||($i)==($ul_split_at*3)||($i)==($ul_split_at*4)||($i+1)==($ul_split_at+1)){
								echo '<div class="floater mediumfloater" ><ul>'."\n" ;
							}
							?>
							<li><input id="srch_alternate_languages_<?php echo $i ; ?>" type="checkbox" value="<?php echo $languages_array[$i] ; ?>" name="alternate_languages[]" <?php echo $is_selected ; ?>/><span onclick="check_me('srch_alternate_languages_<?php echo $i ; ?>','multi');" ><?php echo $languages_array[$i] ; ?></span></li>
							<?php
							if(($i+1)==$ul_split_at||($i+1)==($ul_split_at*2)||($i+1)==($ul_split_at*3)||($i+1)==($ul_split_at*4)||($i+1)==sizeof($languages_array)){
								echo '</ul></div>'."\n" ;
							}
						}
                    ?>
                    </div>
                </div>

				<!--
                <div align="left" style="clear:both; padding-top:16px;" >
                <label for="srch_keyword" >Keyword</label>
                <input type="text" name="keyword" id="srch_keyword" value="<?php echo $_SESSION['search_keyword'] ; ?>" >
                </div>
				-->

                <div align="left" style="clear:both; padding-top:16px;" >
                <input style="margin-top:0;" type="submit" name="submit" value="Search Graduates" />
                </div>

                </form>
            </div>
		</div>
	</div><!-- Container Div End -->
	<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
