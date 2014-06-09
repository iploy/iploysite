<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Graduate Jobs, upload your digital CV | <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php $_SESSION['site_style'] = 'graduates' ; include('_global_head.php') ; ?>
<script src="mooview/mooview.js" language="javascript" type="text/javascript" ></script>
<link href="mooview/mooview.css" rel="stylesheet" type="text/css" />
<style type="text/css" >
#bmc_left {
	float:left;
	width:640px;
	min-height:300px;
}
#bmc_left .text .left, #bmc_left .text .right {
	width:49%;
}
#bmc_left .text .left {
	float:left;
}
#bmc_left .text .right {
	float:right;
}

#bmc_right {
	float:right;
	width:300px;
	min-height:300px;
}
.signups {
	overflow:auto;
	margin-bottom:10px;
}
.signups a {
	width:315px;
	height:120px;
	display:block;
	float:left;
	background-repeat:no-repeat;
	background-position:top left;
}
.signups .employers {
	background-image:url(images/graphics/signup/employers.png);
	background-color:#006FBE;
	margin-right:10px;
}
.signups .employers:hover {
	background-color:#008CEA;
}
.signups .graduates {
	background-image:url(images/graphics/signup/graduates.png);
	background-color:#E20514;
}
.signups .graduates:hover {
	background-color:#FF212F;
}
.searchlink {
	background:#006FBE url(images/graphics/signup/search.png) no-repeat;
	height:120px;
	display:block;
	margin-bottom:10px;
}
.searchlink:hover {
	background-color:#008CEA;
}
<?php
	$thumbW = 72 ;
	$thumbH = 60 ;
?>
.featured h4 {
	background:#333;
	color:#FFF;
	margin:0;
	padding:0 6px;
	line-height:32px;
	border-radius:6px;
}
.featured ul, .featured li {
	padding:0;
	margin:0;
	list-style:none;
}
.featured a {
	display:block;
	height:<?php echo $thumbH ; ?>px;
	padding:5px 0 5px <?php echo $thumbW+12 ; ?>px;
	color:#555;
	margin:6px 2px;
	background:#EEE;
	border-radius:6px;
	border:1px solid #999;
}
.featured:hover a {
}
.featured:hover a:hover {
	text-decoration:none;
	background:#FFFFCC;
}
.featured span {
	display:block;
	height:18px;
	line-height:18px;
	overflow:hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	padding-left:2px;
}
.featured span.title {
	height:20px;
	line-height:20px;
	margin-bottom:2px;
	padding-left:0;
}
.featured b {
	color:#FFF;
	background:#999;
	border-radius:4px 0 0 4px;
	padding:0 5px 0 4px;
	display:block;
}
.featured i {
	float:right;
	font-size:11px;
}
.featured .img {
	position:absolute;
	margin:-1px 0 0 -<?php echo $thumbW+8 ; ?>px;
	width:<?php echo $thumbW ; ?>px;
	height:<?php echo $thumbH ; ?>px;
	display:block;
	background-position:center;
	background-repeat:no-repeat;
	border-radius:4px;
	padding-left:0;
	border:1px solid #999;
}
.bigbullets {
	font-size:16px;
	line-height:21px;
	padding:0;
	margin:10px 0;
}
.bigbullets li {
	margin:0 10px 9px 0;
	background-repeat:no-repeat;
	background-position:4px 0;
	padding:0 0 0 34px;
}
.bigbullets .li01 { background-image:url(images/graphics/bullet_numbers/01.png); }
.bigbullets .li02 { background-image:url(images/graphics/bullet_numbers/02.png); }
.bigbullets .li03 { background-image:url(images/graphics/bullet_numbers/03.png); }
.bigbullets .li04 { background-image:url(images/graphics/bullet_numbers/04.png); }
.bigbullets .li05 { background-image:url(images/graphics/bullet_numbers/05.png); }
.bigbullets .li06 { background-image:url(images/graphics/bullet_numbers/06.png); }
.bigbullets .li07 { background-image:url(images/graphics/bullet_numbers/07.png); }
.bigbullets .li08 { background-image:url(images/graphics/bullet_numbers/08.png); }
.bigbullets .li09 { background-image:url(images/graphics/bullet_numbers/09.png); }
.bigbullets .li10 { background-image:url(images/graphics/bullet_numbers/10.png); }
.bigbullets b {
	color:#E20514;
}


.advicelist {
	padding:0;
	margin:10px 18px 0 0;
}
.advicelist li {
	margin:0 0 4px 20px;
	padding:0;
}
.ellipsis {
	width:100%;
	height:16px;
	overflow:hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	display:block;
}

</style>
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" style="overflow:auto; padding:10px 0 0;" >
            	<!-- left -->
                <div id="bmc_left" >
                	<!-- slider -->
                	<div class="mooView" style="height:280px; margin-bottom:10px;" >
                    	<a href="graduate_signup.php" ><img src="images/graphics/home_slider/slider_01.jpg" width="640" height="280" border="0" title="Register as a graduate today!" alt="" /></a>
                    	<a href="employer_signup.php" ><img src="images/graphics/home_slider/slider_02.jpg" width="640" height="280" border="0" title="Save money AND find the perfect graduate!" alt="" /></a>
                        <img src="images/graphics/home_slider/slider_03.jpg" width="640" height="280" border="0" title="Express yourself with video on your profile!" alt="" />
                        <img src="images/graphics/home_slider/slider_04.jpg" width="640" height="280" border="0" title="Employers can create shortlists of potential graduates" alt="" />
                    </div>
                	<!-- signup links -->
                    <div class="signups" >
                        <a href="employer_signup.php" class="employers" ></a>
                    	<a href="graduate_signup.php" class="graduates" ></a>
                    </div>
                    <!-- text -->
                    <div class="text" >
                    	<!-- left -->
                    	<div class="left" >
                        	<ol class="bigbullets" >
                            	<li class="li01" ><b>Register Now</b><br />
                                <a href="graduate_signup.php" >Create a graduate account</a> and be listed on iPloy within minutes</li>
                            	<li class="li02" ><b>Complete Your Profile</b><br />
                                Tell employers about yourself, your education and desired role</li>
                            	<li class="li03" ><b>Upload Your CV</b><br />
                                Potential employers can view your curriculum vitae online</li>
                            	<li class="li04" ><b>Upload Your Photo</b><br />
                                Add a personal touch to your profile and show employers who you are</li>
                            	<li class="li05" ><b>Get a Job Today</b><br />
                                Employers are looking for graduates now, make sure they find YOU!</li>
                            </ol>
                        </div>
                        <!-- right -->
                        <div class="right" >
                        	<?php
							// grab the graduate advice RSS feed
							include_once('_system/classes/rss_feed.php') ;
							$rss = new rssFeed ;
							$rss->setUrl('http://www.iploy.co.uk/advice/feed/topten/rss.php') ;
							$rss->setItems(10) ;
							$rss = $rss->getFeed() ;
							if($rss['success']==true){
								if(sizeof($rss['data']['items'])>0){
									echo '<ul class="advicelist" >'."\n" ;
									foreach($rss['data']['items'] as $item){
										$titleSplit = explode("(",$item['title']) ;
										$title = trim(str_replace("...","",$titleSplit[0])) ;
										$titleSplit = explode("view",$titleSplit[1]) ;
										$views = $titleSplit[0] ;
										echo '<li><a href="'.$item['link'].'" ><b class="ellipsis" >'.$title.'<br /></b></a>'.$views.' view'.($views!=1 ? 's' : '').'</li>'."\n" ;
									}
									echo '</ul>'."\n" ;
								}
							} else {
								echo '<p class="error" >'.$rss['status'].'</p>'."\n" ;
							}
							?>
                        </div>
                    </div>
                </div>
                <!-- right -->
				<div id="bmc_right" >
                	<!-- find grads banner -->
                	<a href="search.php" class="searchlink" ></a>
                    <!-- featured graduates -->
                    <div class="featured" >
                    <h4>Featured Students</h4>
                    <?php
					include_once('_system/classes/graduate_frontend.php') ;
					$drawTotal = 8 ;
					$gd = new graduate_data ;
					$gd->setCustomWhere("graduates.has_photo = '1'") ;
					$count = $gd->getGraduatesList(true) ;
					$pages = floor($count/$drawTotal) ;
					$gd->setListPageSize($drawTotal) ;
					$thisPage = rand(1,$pages) ;
					$gd->setListPage($thisPage) ;
					$gd->setListOrderBy('login_id') ;
					$graduates = $gd->getGraduatesList() ;
					// go
					echo '<ul>'."\n" ;
					foreach($graduates as $graduate){
						// loc
						$location = ucwords(strtolower($graduate['education_location'])) ;
						$location = str_replace('Northern ','N. ',$location) ;
						$location = str_replace('North East ','NE. ',$location) ;
						$location = str_replace('South East ','SE. ',$location) ;
						$location = str_replace('North West ','NW. ',$location) ;
						$location = str_replace('South West ','SW. ',$location) ;
						$location = str_replace('North ','N. ',$location) ;
						$location = str_replace('South  ','S. ',$location) ;
						$location = str_replace('East ','E. ',$location) ;
						$location = str_replace('West ','W. ',$location) ;
						//draw
						echo '<li><a href="view_profile.php?profileid='.$graduate['login_id'].'" >' ;
						echo '<span class="img" style="background-image:url(\''.SITE_FOLDER.'user_image.php?userid='.$graduate['login_id'].'&height='.$thumbH.'\'); " ></span>' ;
						echo '<span class="title" ><b><i>'.$location.'</i>'.ucwords(strtolower($graduate['first_name'])).'</b></span> ' ;
						echo '<span>Studying '.$graduate['subject'].'</span> ' ;
						if(strtotime($graduate['availability'])<time()){
							$availability = '<strong>Available Now!</strong>' ;
						} else {
							$availability = '<strong>Available '.date('jS F Y',strtotime($graduate['availability'])).'</strong>' ;
						}
						echo '<span>'.$availability.'</span> ' ;
						echo '</a></li>'."\n" ;
					}
					echo '</ul>'."\n" ;
					?>
                    </div>
                </div>
            </div>
		</div>

	</div><!-- Container Div End -->
		<?php include('_footer.php') ; ?>


</div><!-- Alignment Div End -->
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
