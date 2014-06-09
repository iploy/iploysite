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
</head>
<body>
<?php include('_global_body_start.php') ; ?>
<div align="center" ><!-- Alignment Div -->

	<?php include('_header.php') ; ?>

	<div class="container" align="left" ><!-- Container Div -->
        <div class="bodymain">
			<div class="bodymain_center" >
                <h1 align="center"><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?>. Join the Graduate Recruitment Revolution...</h1>
                <?php
					$page_width = 950 ;
					$padding = 15 ;
					$img_left_width = 660 ;
					$img_height = 338 ;
				?>
                <div class="row" >
                    <div class="left mooView" style="height:<?php echo $img_height ; ?>px; width:<?php echo $img_left_width ; ?>px; float:left;" >
                    	<?php // <a href="ipad_competition_info.php" ><img title="iPad 2 Givaway" src="images/graphics/grad_home/iploy_giveaway01.jpg" width="660" height="306" border="0" alt="" /></a> ?>
                        <a href="graduate_signup.php" ><img title="Register with <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?>" src="images/graphics/grad_home/imfound_ijoin_combined.jpg" width="660" height="306" border="0" alt="" />
                        <img title="<?php echo $_SESSION['APP_SITE_NAME'] ; ?>" src="images/graphics/grad_home/iploy.jpg" width="660" height="306" border="0" alt="" /></a>
                    </div>
                    <div class="right" style="background:#EEE; height:<?php echo $img_height ; ?>px; width:<?php echo $page_width-($img_left_width+$padding) ; ?>px; float:right; overflow:hidden;" >
                        <a href="graduate_signup.php" style="height:120px; background:url(images/graphics/grad_home/signup_new.png); display:block;" ></a>
                        <div class="smallform" >
						<?php
                            if($_SESSION['user_id']!=''){
								if($profile_progress==''){
									if($_SESSION['user_level']==1){
										include_once('_system/functions/graduate_profile_progress.php') ;
										$profile_progress = graduate_profile_progress($graduate_required_fields,$_SESSION) ;
										$profile_progress_color = progress_colour($profile_progress) ;
									} elseif($_SESSION['user_level']==2){
										include_once('_system/functions/employer_profile_progress.php') ;
										$profile_progress = employer_profile_progress($employer_required_fields,$_SESSION) ;
										$profile_progress_color = progress_colour($profile_progress) ;
									}
								}
                                ?>
                                <h4>Logged In As:</h4>
                                <ul>
	                                <li><b>User</b>: <?php echo '<span class="Highlight" >'.$_SESSION['email'].'</span>' ; ?></li>
	                                <li><b>Joined</b>: <?php echo date(DM_PHP_SCREENDATE_FORMAT,strtotime($_SESSION['date_created'])) ; ?></li>
								</ul>
                                <?php
								if($_SESSION['user_level']==1||$_SESSION['user_level']==2){
									?>
									<h4 class="secondh4">Profile Progress</h4>
									<div class="percentage" >
										<div class="text" ><span><?php echo $profile_progress ; ?>%</span></div>
										<div class="bar" style="width:<?php echo $profile_progress ; ?>%; background:<?php echo $profile_progress_color ; ?>;" ></div>
									</div>
									<p><?php echo draw_icon(ICON_ACCOUNT) ; ?><a href="home.php" >Edit your profile</a></p>
									<p align="right" ><a href="login.php?action=logout" >Logout</a></p>
									<?php
								} else {
									echo '<p class="notice" >'.draw_icon('admin.png').'<b>Welcome '.$_SESSION['APP_CLIENT_NAME'].' Administrator</b></p>' ;
									?>
									<p><a href="login.php?action=logout" >Logout</a></p>
									<?php
								}
                            } else {
                                ?>
                                <h4>Existing Graduate Login</h4>
                                <?php include('_login_form.php') ; ?>
                                <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="cell" style="width:424px;" >
                    	<h2>Welcome to <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></h2>
                        <div style="overflow:auto;">
                            <div id="shares" style="height:24px; line-height:24px; float:left; " >
                                <script type="text/javascript">var switchTo5x=true;</script>
                                <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'42565833-76e9-4a0d-b3bc-e052a4198203'});</script>
                                <b>Share This</b>: <span  class='st_twitter_button' displayText='Tweet'></span><span  class='st_gbuzz_button' displayText='Google Buzz'></span><span  class='st_facebook_button' displayText='Facebook'></span>
                            </div>
                            <div style="float:left; padding-top:2px;" ><div id="fb-root"></div>
							<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                            <fb:like href="http://www.facebook.com/iPloy.co.uk" send="false" layout="button_count" width="90" show_faces="false" font=""></fb:like></div>
                        </div>
                        <p><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> is a specialist graduate recruitment website with a difference. We give both the graduate and the employer a new, fresh and interactive path through the graduate recruitment journey.</p>
                        <p>Employers are finding it increasingly difficult to find the right graduates they require, despite application volumes increasing. We provide all the information and tools you need to land you the perfect job.</p>
                        <p><?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> graduate recruitment offers you the opportunity to interact and get to know your potential employer through our unique online service, before even reaching the interview stage!</p>
                        <p><?php echo draw_icon(ICON_ACCOUNT) ; ?><a href="view_profile.php?profileid=<?php echo DEMO_PROFILE_ID ; ?>" >Click here to view our demo graduate profile</a></p>
                    </div>
                    <div class="cell" style="width:510px; margin-right:0; padding-top:48px" >
                        <div align="center" ><img src="images/graphics/registeringoniploy.jpg" alt="" name="wordcloud" width="500" height="191" border="0" id="wordcloud" /></div>
					</div>
                </div>
                <div class="row2" >
                    <div class="cell1" >
                    	<div><a href="http://www.facebook.com/pages/iPloy/261519700538406" target="_blank" ><img src="images/graphics/home_graphics/facebook_bar.jpg" width="258" height="80" border="0" alt="Facebook Logo" title="Follow us on Facebook" /></a></div>
                    	<div><a href="https://twitter.com/#!/iPloyGrads" target="_blank" ><img src="images/graphics/home_graphics/twitter_bar.jpg" width="258" height="80" border="0" alt="Twitter Logo" title="Follow us on Twitter" /></a></div>
                    </div>
                    <div class="cell2" >
                    	<img src="images/graphics/home_graphics/were_free.png" alt="We're Free Graphic" width="320" height="210" border="0" usemap="#Map" title="We're Free!" />
<map name="Map" id="Map"><area shape="rect" coords="218,183,312,205" href="graduate_payment_incentive.php" /></map>
                    </div>
      <div class="cell2" >
                    	<a href="./blog/" target="_blank" ><img src="images/graphics/grad_home/BlogButton.PNG" width="320" height="210" border="0" alt="We're Free Graphic" title="We're Free!" /></a>                    </div>
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
