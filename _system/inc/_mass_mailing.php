<?php
	include_once('_system/_config/_multiple_choice_arrays.php') ;
	include_once('_system/functions/getColumnSplit.php') ;

/*
// FLDS:
	type_graduates, type_employers, type_admin, type_superuser
	keyword
*/	



	$filter_alert = '<b>Note</b>: Only the filters you include will effect search results. Leave entire option sets blank if you wish to exclude a filter type.' ;
	$submit_button_txt = 'Step 2 - Apply Recipient Filter and Generate Link' ;
?>
<h1>Mass Messaging</h1>
<div class="jsrequired" >

	<?php echo draw_icon('mail_mass.png') ; ?><b>Step 1</b> - Select mass mail filters</p>

    <div id="filters"  class="float_container" >
        <label style="margin-top:0;" >Search From</label>
        <div class="lister" ><ul class="inline" >
        <li><input type="checkbox" name="type" class="zipcontroller" id="type_graduate" value="graduate" onchange="checkControlers();" /><span onclick="check_me('type_graduate','multi'); checkControlers();" >Graduates</span></li>
        <li><input type="checkbox" name="type" class="zipcontroller" id="type_employer" value="employer" onchange="checkControlers();" /><span onclick="check_me('type_employer','multi'); checkControlers();" >Employers</span></li>
        <li><input type="checkbox" name="type" class="zipcontroller" id="type_admin" value="admin" onchange="checkControlers();" /><span onclick="check_me('type_admin','multi'); checkControlers();" >Administrators</span></li>
        <?php
        if($_SESSION['user_level']==0){
            ?>
            <li><input type="checkbox" name="type" id="type_superuser" value="superuser" onchange="checkControlers();" /><span onclick="check_me('type_superuser','multi'); checkControlers();" >Super Users</span></li>
            <?php
        }
        ?>
        </ul></div>
    </div>

    <div id="graduate_options" class="zipregion" >
        <div class="boxer">
        	<h6><?php // echo draw_icon(ICON_BAD) ; ?>Add / Edit Graduate Filters</h6>
            <p class="notice" ><?php echo draw_icon(ICON_ALERT).$filter_alert ; ?></p>
            <div class="padder" >
        	<div class="inner" >

            	<label>Files</label>
                <div class="float_container" >
                    <div class="floater lister">
                    	<b>Photo:</b>
                    	<ul class="inline" >
                        	<li><input type="checkbox" name="graduate_files_photo" id="gf_ph1" value="1" /><span onclick="check_me('gf_ph1','multi');" >Has Photo</span></li>
                        	<li><input type="checkbox" name="graduate_files_photo" id="gf_ph0" value="0" /><span onclick="check_me('gf_ph0','multi');" >Does Not Have Photo</span></li>
                        </ul>
                    </div>
                    <div class="floater lister">
                    	<b>CV:</b>
                    	<ul class="inline" >
                        	<li><input type="checkbox" name="graduate_files_cv" id="gf_cv1" value="1" /><span onclick="check_me('gf_cv1','multi');" >Has CV</span></li>
                        	<li><input type="checkbox" name="graduate_files_cv" id="gf_cv0" value="0" /><span onclick="check_me('gf_cv0','multi');" >Does Not Have CV</span></li>
                        </ul>
                    </div>
                    <div class="floater lister">
                    	<b>Certificate:</b>
                    	<ul class="inline" >
                        	<li><input type="checkbox" name="graduate_files_certificate" id="gf_ce1" value="1" /><span onclick="check_me('gf_ce1','multi');" >Has Certificate</span></li>
                        	<li><input type="checkbox" name="graduate_files_certificate" id="gf_ce0" value="0" /><span onclick="check_me('gf_ce0','multi');" >Does Not Have Certificate</span></li>
                        </ul>
                    </div>
                </div>


            	<label>Current Location</label>
                <div class="float_container" >
                <?php
					$columnSplit = getColumnSplit(sizeof($locations_array),4) ;
					for($i=0;$i<sizeof($locations_array);$i++){
						if($i==0||in_array($i,$columnSplit)){
							echo '<div class="floater lister"><ul>'."\n" ;
						}
						?>
	        			<li><input type="checkbox" name="graduate_current_location" id="gcl_<?php echo $locations_array[$i] ; ?>" value="<?php echo $locations_array[$i] ; ?>" /><span onclick="check_me('gcl_<?php echo $locations_array[$i] ; ?>','multi');" ><?php echo $locations_array[$i] ; ?></span></li>
						<?php
						if(in_array($i+1,$columnSplit)){
							echo '</ul></div>'."\n" ;
						}
					}
				?>
                </div>


            	<label>Desired Location</label>
                <div class="float_container" >
                <?php
					$columnSplit = getColumnSplit(sizeof($locations_array),4) ;
					for($i=0;$i<sizeof($locations_array);$i++){
						if($i==0||in_array($i,$columnSplit)){
							echo '<div class="floater lister"><ul>'."\n" ;
						}
						?>
	        			<li><input type="checkbox" name="graduate_desired_location" id="gdl_<?php echo $locations_array[$i] ; ?>" value="<?php echo $locations_array[$i] ; ?>" /><span onclick="check_me('gdl_<?php echo $locations_array[$i] ; ?>','multi');" ><?php echo $locations_array[$i] ; ?></span></li>
						<?php
						if(in_array($i+1,$columnSplit)){
							echo '</ul></div>'."\n" ;
						}
					}
				?>
                </div>


            	<label>Subject Studied</label>
                <div class="float_container" >
                <?php
					$columnSplit = getColumnSplit(sizeof($subjects_array),4) ;
					for($i=0;$i<sizeof($subjects_array);$i++){
						if($i==0||in_array($i,$columnSplit)){
							echo '<div class="floater lister"><ul>'."\n" ;
						}
						?>
	        			<li><input type="checkbox" name="graduate_subject_studied" id="gss_<?php echo $subjects_array[$i] ; ?>" value="<?php echo $subjects_array[$i] ; ?>" /><span onclick="check_me('gss_<?php echo $subjects_array[$i] ; ?>','multi');" ><?php echo $subjects_array[$i] ; ?></span></li>
						<?php
						if(in_array($i+1,$columnSplit)){
							echo '</ul></div>'."\n" ;
						}
					}
				?>
                </div>


            	<label>Job Category</label>
                <div class="float_container" >
                <?php
					$columnSplit = getColumnSplit(sizeof($category_array),4) ;
					for($i=0;$i<sizeof($category_array);$i++){
						if($i==0||in_array($i,$columnSplit)){
							echo '<div class="floater lister"><ul>'."\n" ;
						}
						?>
	        			<li><input type="checkbox" name="graduate_job_category" id="gjc_<?php echo $category_array[$i] ; ?>" value="<?php echo $category_array[$i] ; ?>" /><span onclick="check_me('gjc_<?php echo $category_array[$i] ; ?>','multi');" ><?php echo $category_array[$i] ; ?></span></li>
						<?php
						if(in_array($i+1,$columnSplit)){
							echo '</ul></div>'."\n" ;
						}
					}
				?>
                </div>


            	<label>Education Level</label>
                <div class="float_container" >
                <?php
					$columnSplit = getColumnSplit(sizeof($education_level_array),4) ;
					for($i=0;$i<sizeof($education_level_array);$i++){
						if($i==0||in_array($i,$columnSplit)){
							echo '<div class="floater lister"><ul>'."\n" ;
						}
						?>
	        			<li><input type="checkbox" name="graduate_education_level" id="gel_<?php echo $education_level_array[$i] ; ?>" value="<?php echo $education_level_array[$i] ; ?>" /><span onclick="check_me('gel_<?php echo $education_level_array[$i] ; ?>','multi');" ><?php echo $education_level_array[$i] ; ?></span></li>
						<?php
						if(in_array($i+1,$columnSplit)){
							echo '</ul></div>'."\n" ;
						}
					}
				?>
                </div>

            	<label>Education Grade</label>
                <div class="float_container" >
                <?php
					$columnSplit = getColumnSplit(sizeof($education_grade_array),4) ;
					for($i=0;$i<sizeof($education_grade_array);$i++){
						if($i==0||in_array($i,$columnSplit)){
							echo '<div class="floater lister"><ul>'."\n" ;
						}
						?>
	        			<li><input type="checkbox" name="graduate_education_grade" id="geg_<?php echo $education_grade_array[$i] ; ?>" value="<?php echo $education_grade_array[$i] ; ?>" /><span onclick="check_me('geg_<?php echo $education_grade_array[$i] ; ?>','multi');" ><?php echo $education_grade_array[$i] ; ?></span></li>
						<?php
						if(in_array($i+1,$columnSplit)){
							echo '</ul></div>'."\n" ;
						}
					}
				?>
                </div>





            </div></div>
        </div>
    </div>

    <div id="employer_options" class="zipregion" >
        <div class="boxer">
        	<h6><?php // echo draw_icon(ICON_BAD) ; ?>Add / Edit Employer Filters</h6>
			<p class="notice" ><?php echo draw_icon(ICON_ALERT).$filter_alert ; ?></p>
        	<div class="inner" ><div class="padder" >
            	<label>Industry Sector</label>
                <div class="float_container" >
                <?php
					$columnSplit = getColumnSplit(sizeof($category_array),4) ;
					for($i=0;$i<sizeof($category_array);$i++){
						if($i==0||in_array($i,$columnSplit)){
							echo '<div class="floater lister"><ul>'."\n" ;
						}
						?>
	        			<li><input type="checkbox" name="employer_industry_sector" id="eis_<?php echo $category_array[$i] ; ?>" value="<?php echo $category_array[$i] ; ?>" /><span onclick="check_me('eis_<?php echo $category_array[$i] ; ?>','multi');" ><?php echo $category_array[$i] ; ?></span></li>
						<?php
						if(in_array($i+1,$columnSplit)){
							echo '</ul></div>'."\n" ;
						}
					}
				?>
                </div>
            </div></div>
        </div>
    </div>

	<!--
    <div id="admin_options" class="zipregion" >
        <div class="boxer">
        	<h6><?php // echo draw_icon(ICON_BAD) ; ?>Add Admin Filters</h6>
            <p class="notice" ><?php echo draw_icon(ICON_ALERT).$filter_alert ; ?></p>
        	<div class="inner" ><div class="padder" >
            	<label>Search From</label>
            </div></div>
        </div>
    </div>
	-->

	<div align="center" >
    	<input id="filterSubmit" type="submit" value="<?php echo $submit_button_txt ; ?>" />
    </div>


	<form action="message_send.php" method="post" style="margin:0;" >
        <h5>Search Results</h5>
        <p id="searchstatus" class="error" ><?php echo draw_icon(ICON_BAD) ; ?>No search has been made</p>
        <div id="zipper" ><div id="searchresults" >
        </div></div>
        <input type="hidden" id="recipients" name="profileid" value="" />
    </form>

</div>



<style type="text/css" >
.zipregion {
	overflow:hidden;
	margin:5px 0;
	border:1px solid #888;
	background:#F7F7F7;
	border-radius:6px;
}
.boxer {
}
.boxer .padder {
	margin:6px;
}
.boxer .floater {
	font-size:11px;
	min-width:210px;
	margin-right:10px;
}
.boxer h6 {
	margin:0;
	padding:0 6px;
	background:#AAA;
	line-height:28px;
	color:#FFF;
	font-size:12px;
	cursor:pointer;
}
.boxer h6:hover {
	background:#888;
}
.boxer p {
	margin:6px 5px;
}
.boxer label {
	margin:6px 0;
}
#zipper, #searchresults {
	overflow:auto;
}
#zipper {
	height:0;
	overflow:hidden;
	opacity:0;
}
#searchresults p, #searchstatus, #searchresults input {
	margin:0 0 8px;
}

</style>
<script language="javascript" type="text/javascript" src="js/mooZip/mooZip.js" ></script>
<script language="javascript" type="text/javascript" >
// $('searchresults')

var togglerElement = 'h6' ;
var zipControllerClass = 'zipcontroller' ;
var zipRegionClass = 'zipregion' ;
var originalHeights = new Array() ;
var zippedHeights = new Array() ;
var forcedZip = new Array() ;

function zipAll(){
	$$('.'+zipRegionClass).each(function(zipRegion){
		thisRegionName = zipRegion.id.replace('_options','') ;
		zipRegion.morph({'height':zippedHeights[thisRegionName]}) ;
		if($('type_'+thisRegionName).checked==true){
			forcedZip[thisRegionName] = true ;
		}
	});
}

function forceZip(region,forceClose){
	// only allow is controller is true
	if($('type_'+region)){
		if($('type_'+region).checked==true||forceClose==true){
			zipAll() ;
			if($(region+'_options')){
				if($(region+'_options').offsetHeight<originalHeights[region]&&forceClose!=true){
					$(region+'_options').morph({'height':originalHeights[region]}) ;
					forcedZip[region] = false ;
					(function(){ new Fx.Scroll(window).toElement(region+'_toggler') ; }).delay(500) ; // 
				} else {
					$(region+'_options').morph({'height':zippedHeights[region]}) ;
					forcedZip[region] = true ;
				}
			}
		} else {
			if(confirm(region.charAt(0).toUpperCase()+region.substr(1)+'s are not currently being searched, would you like to include '+region+'s in the search?')){
				zipAll() ;
				$('type_'+region).checked = true ;
				checkControlers() ;
				forceZip(region,false) ;
			}
		}
	}
}

function checkControlers(){
	$('zipper').morph({'height':0}) ;
	$('searchstatus').innerHTML = '<?php echo draw_icon(ICON_ALERT) ; ?>Filter modified, please run step 2 to view results.' ;
	$('searchstatus').morph('.notice') ;
	$$('.'+zipControllerClass).each(function(zipController){
		if(zipController.checked==true){
			if(forcedZip[zipController.id.replace('type_','')]==false){
			}
		} else {
			if($(zipController.id.replace('type_','')+'_options')){
				$(zipController.id.replace('type_','')+'_options').morph({'height':zippedHeights[zipController.id.replace('type_','')]}) ;
				forcedZip[zipController.id.replace('type_','')] = false ;
			}
		}
	});
}

window.addEvent('domready', function(){
	$$('.'+zipRegionClass).each(function(zipRegion){
		// remember zipped and expanded heights
		zippedHeights[zipRegion.id.replace('_options','')] = parseFloat(zipRegion.getElement(togglerElement).offsetHeight) ;
		originalHeights[zipRegion.id.replace('_options','')] = parseFloat(zipRegion.offsetHeight) ;
		// set up the toggler
		zipRegion.getElement(togglerElement).id = zipRegion.id.replace('_options','_toggler') ;
		zipRegion.getElement(togglerElement).addEvent('click',function(){
			checkControlers();
			forceZip(this.id.replace('_toggler',''),false) ;
		});
		// tell the system that a zip has not been forced
		forcedZip[zipRegion.id.replace('_options','')] = false ;
		// zip it
		zipRegion.setStyles({'height':zippedHeights[zipRegion.id.replace('_options','')]}) ;
	});
	// == == == == == == == == == == == == == == == == == == == == == == == == == == == == == == == == == == == [AJAX] = = = =
	$('filterSubmit').addEvent('click', function(event) {
		if(pauseSubmission==false){
			goAhead = false ;
			doGrads = false ;
			doEmployers = false ;
			doAdmin = false ;
			typeArray = new Array() ;
			// check a basic filter was selected
			i=0;
			$$('input[name="type"]').each(function(zipController){
				if(zipController.checked==true){
					thisType = zipController.id.replace('type_','') ;
					typeArray[i] = thisType ;
					goAhead = true ;
					if(thisType=='graduate'){ doGrads = true ; }
					if(thisType=='employer'){ doEmployers = true ; }
					if(thisType=='admin'){ doAdmin = true ; }
					if(thisType=='superuser'){ doSu = true ; }
					i++;
				}
			});
			// Graduates
			graduate_files_photo = new Array() ;
			graduate_files_cv = new Array() ;
			graduate_files_certificate = new Array() ;
			graduate_current_location = new Array() ;
			graduate_desired_location = new Array() ;
			graduate_subject_studied = new Array() ;
			graduate_job_category = new Array() ;
			graduate_education_level = new Array() ;
			graduate_education_grade = new Array() ;
			if(doGrads==true){	
			// check graduate_current_location
				i=0;
				$$('input[name="graduate_files_photo"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_files_photo[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_files_cv"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_files_cv[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_files_certificate"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_files_certificate[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_current_location"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_current_location[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_desired_location"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_desired_location[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_subject_studied"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_subject_studied[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_job_category"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_job_category[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_education_level"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_education_level[i] = thisFld.value ; i++ ; }
				});
				i=0;
				$$('input[name="graduate_education_grade"]').each(function(thisFld){
					if(thisFld.checked==true){ graduate_education_grade[i] = thisFld.value ; i++ ; }
				});
			}
			// Employers
			employer_industry_sector = new Array() ;
			if(doEmployers==true){
				// employer_industry_sector
				i=0;
				$$('input[name="employer_industry_sector"]').each(function(thisFld){
					if(thisFld.checked==true){ employer_industry_sector[i] = thisFld.value ; i++ ; }
				});
			}
			// Admins
			if(doAdmin==true){	
				// alert('doing admins') ;
			}
			// Superusers
			if(doAdmin==true){	
				// alert('doing admins') ;
			}
			//prevent the page from changing
			event.stop();
			if(goAhead==true){
				// now check the other variables
				//make the ajax call
				var req = new Request({
					method: 'post',
					url: 'ajax_mailfilter_results.php',
					data: { 
						'type' : typeArray,
						'graduate_files_photo' : graduate_files_photo,
						'graduate_files_cv' : graduate_files_cv,
						'graduate_files_certificate' : graduate_files_certificate,
						'graduate_current_location' : graduate_current_location,
						'graduate_desired_location' : graduate_desired_location,
						'graduate_subject_studied' : graduate_subject_studied,
						'graduate_job_category' : graduate_job_category,
						'graduate_education_level' : graduate_education_level,
						'graduate_education_grade' : graduate_education_grade,
						'employer_industry_sector' : employer_industry_sector
					},
					onRequest: function() { submission('stop') ; },
					onComplete: function(response) { (function(){responseToScreen(response);}).delay(750); }
				}).send();
			} else {
				alert('FILTER ERROR\nNo results could be returned as filters were selected') ;
			}
		}
	});

}) ;
var pauseSubmission = false ;
var totalRecipients = false ;
function submission(action){
	if(action=='start'){
		$('filterSubmit').value = '<?php echo $submit_button_txt ; ?>' ;
		$('searchstatus').innerHTML = '<?php echo draw_icon('tick_ring.png') ; ?>The filter returned a total of '+totalRecipients+' recipient'+(totalRecipients!=1 ? 's' : '')+'. Click the button below to send a system message to these users.' ;
		$('searchstatus').morph('.success') ;
	} else if (action=='stop'){
		forceZip('graduate',true) ;
		forceZip('employer',true) ;
		forceZip('admin',true) ;
		forceZip('superuser',true) ;
		$('zipper').morph({'height':0,'opacity':0}) ;
		$('filterSubmit').value = 'Processing filter...' ;
		$('searchstatus').innerHTML = '<?php echo draw_icon(ICON_ALERT) ; ?>Processing filter, please wait...' ;
		$('searchstatus').morph('.notice') ;
	} else {
		$('filterSubmit').value = '<?php echo $submit_button_txt ; ?>' ;
		$('searchstatus').innerHTML = '<?php echo draw_icon(ICON_BAD) ; ?>'+action ;
		$('searchstatus').morph('.error') ;
	}
}

function responseToScreen(response){
	if(response.toLowerCase().indexOf('error')>=0){
		submission(response) ;
	} else {
		screenTxt = '' ;
		idStr = '' ;
		totalRecipients = 0 ;
		responseSplit = response.split("|") ;
		for(i=0;i<responseSplit.length;i++){
			thisSplit = responseSplit[i].split(":") ;
			if(idStr!=''){ idStr = idStr +',' ; }
			idStr = idStr + thisSplit[3] ;
			screenTxt = screenTxt + '<p class="greynote" ><img src="images/icons/'+thisSplit[0]+'.png" width="16" height="16" border="0" class="ico" />The filter matched <b>'+thisSplit[1]+'</b> '+thisSplit[0]+(parseFloat(thisSplit[1])!=1 ? 's' : '')+' from a total of <b>'+thisSplit[2]+'</b> '+thisSplit[0]+(parseFloat(thisSplit[2])!=1 ? 's' : '')+'</p>\n' ;
			totalRecipients = totalRecipients + parseFloat(thisSplit[1]) ;
		}
		if(totalRecipients>0){
			submission('start') ;
			screenTxt = screenTxt + '<div align="center" ><input type="submit" value="Step 3 - Compose a message for the filtered users" /></div>\n' ;
			screenTxt = screenTxt + '<p align="center" >Alternatively, go back to step 1 and modify the filters</p>\n' ;
			$('searchresults').innerHTML = screenTxt ;
			$('recipients').value = idStr ;
			$('zipper').morph({'height':parseFloat($('searchresults').offsetHeight),'opacity':1}) ;
		} else {
			submission('No users matched the filters you entered') ;
		}
	}

}

</script>


<!-- No Script -->
<noscript>
<p class="error"><?php echo draw_icon(ICON_BAD) ; ?>The mass mailing feature requires a javascript enabled browser. Please enable this feature or consider using alternate browser software.</p>
</noscript>
