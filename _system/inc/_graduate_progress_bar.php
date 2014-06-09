<style type="text/css" >
#graduateBar {
	line-height:32px;
	margin:10px 0;
	position:relative;
	border:solid #999 1px ;
	overflow:hidden;
}
#graduateBar ul, #graduateBar li {
	margin:0;
	padding:0;
	list-style:none;
}
#graduateBar li {
	float:left;
	width:25%;
}
#graduateBar a {
	display:block;
	color:#FF1D24;
	background-image:url(images/graphics/bg_gradient_40h.png);
	background-repeat:repeat-x;
	background-position:top left;
	background-color:#FFEFF0;
	border-left:solid #999 1px ;
	padding:0 8px;
	font-weight:bold;
	font-size:12px;
}
#graduateBar a:hover {
	text-decoration:none;
	background-color:#FFDBDB;
}
#graduateBar .label {
	width:80px;
	padding-left:8px;
}
#graduateBar .first a {
	border-left:none;
}
#graduateBar .good a {
	background-color:#F1FFD1;
	color:#090;
}
#graduateBar .good a:hover {
	background-color:#DEF79E;
}
#graduateBar .optional a {
	background-color:#FFFDBA;
	color:#CC6600;
}
#graduateBar .optional a:hover {
	background:#F9F06B;
}
#graduateBar .ico {
	margin-right:6px;
}
</style>
<div id="graduateBar" >
	<ul>
	<?php
        // personal information ====================================================================================================
        $personalFldsDone = 0 ;
        $personalFlds = array('first_name','surname','tel_mobile','date_of_birth','has_driving_licence','education_location') ;
        foreach($personalFlds as $fld){
			if($_SESSION[$fld]!=''){
	            $personalFldsDone++ ;
			}
        }
        if($personalFldsDone==sizeof($personalFlds)){
            $ico = draw_icon('tick.png') ;
            $class = 'good' ;
        } else {
            $ico = draw_icon('ico_1.png','images/graphics/employer_home/') ;
            $class = '' ;
        }
        echo '<li class="first '.$class.'" ><a href="?action=personal" >'.$ico.'Personal Information ('.$personalFldsDone.'/'.sizeof($personalFlds).')</a></li>'."\n" ;
		
        // education information ====================================================================================================
        $educationFldsDone = 0 ;
        $educationFlds = array('subject','education_level','education_end','education_institution') ;
        foreach($educationFlds as $fld){
			if($_SESSION[$fld]!=''){
            	$educationFldsDone++ ;
			}
        }
        if($educationFldsDone==sizeof($educationFlds)){
            $ico = draw_icon('tick.png') ;
            $class = 'good' ;
        } else {
            $ico = draw_icon('ico_2.png','images/graphics/employer_home/') ;
            $class = '' ;
        }
        echo '<li class="'.$class.'" ><a href="?action=education" >'.$ico.'Education Information ('.$educationFldsDone.'/'.sizeof($educationFlds).')</a></li>'."\n" ;
		
        // employment preferences ====================================================================================================
        $employmentFldsDone = 0 ;
        $employmentFlds = array('availability','will_do_antisocial','will_travel','hours','emploment_location','job_category') ;
        foreach($employmentFlds as $fld){
			if($_SESSION[$fld]!=''){
            	$employmentFldsDone++ ;
			}
        }
        if($employmentFldsDone==sizeof($employmentFlds)){
            $ico = draw_icon('tick.png') ;
            $class = 'good' ;
        } else {
            $ico = draw_icon('ico_2.png','images/graphics/employer_home/') ;
            $class = '' ;
        }
        echo '<li class="'.$class.'" ><a href="?action=employment" >'.$ico.'Employment Preferences ('.$employmentFldsDone.'/'.sizeof($employmentFlds).')</a></li>'."\n" ;
		
        // employment preferences ====================================================================================================
        $uploadFldsDone = 0 ;
        $uploadFlds = array('photo','certificate','cv') ;
        foreach($uploadFlds as $fld){
			$found = false ;
			$file_base = 'userfiles/'.$fld.'/'.$_SESSION['user_id'].'/' ;
			if(is_dir($file_base)){
				// get the original filename and type
				$this_dir = opendir($file_base) ;
				while(($file=readdir($this_dir))!= false){
					if(substr($file,0,1)!='.'&&substr($file,0,1)!='_'){ $found = true ; }
				}
				closedir($this_dir) ;
				if($found==true){ $uploadFldsDone++ ; }
			}
        }
        if($uploadFldsDone==sizeof($uploadFlds)){
            $ico = draw_icon('tick.png') ;
            $class = 'good' ;
        } else {
            $ico = draw_icon(ICON_ALERT) ;
            $class = 'optional' ;
        }
        echo '<li class="'.$class.'" ><a href="?action=upload" >'.$ico.'Upload Files ('.$uploadFldsDone.'/'.sizeof($uploadFlds).')</a></li>'."\n" ;
		
    ?>

    </ul>
</div>