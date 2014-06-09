<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Find Graduates, download their digital CV. Save time, Save Money | <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php $_SESSION['site_style'] = 'employers' ; include('_global_head.php') ; ?>
<!--
<script src="mooview/mooview.js" language="javascript" type="text/javascript" ></script>
<link href="mooview/mooview.css" rel="stylesheet" type="text/css" />
-->
<style type="text/css" >
.Highlight {
	color:<?php echo COLOR_GRADUATE_NORMAL ; ?>;
}
.lister li {
	padding-bottom:2px;
}
.prods, .prods li {
	margin:0;
	padding:0;
	list-style:none;
	color:#FFF;
	font-weight:bold;
}
.prods {
	margin:36px 12px 0 ;
}
.prods li {
	border-top:2px solid #FFF;
	line-height:26px;
	overflow:auto;
	padding:0 2px;
}
.prods .price, .prods .creds, .prods .buy {
	display:inline-block;
}
.prods .price {
	width:82px;
}
.prods .buy {
	float:right;
	background-color:#74C101;
	background-image:url(images/graphics/employer_home/buy_arrow.png);
	background-position:right center;
	background-repeat:no-repeat;
	color:#FFF;
	line-height:18px;
	margin-top:4px;
	padding:0 20px 0 4px;
	border-radius:4px;
	font-size:11px;
}
.prods .buy:hover {
	text-decoration:none;
	background-color:#5A9600;
}
</style>
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
                <div class="row" style="overflow:hidden;" >
                    <div class="left mooView" style="height:<?php echo $img_height ; ?>px; width:<?php echo $img_left_width ; ?>px; float:left;" >
                    	<a href="search.php" ><img title="iPad 2 Givaway" src="images/graphics/employer_home/ineed.jpg" width="660" height="338" border="0" alt="" /></a>
                    </div>
                    <div class="right" style="background:#EEE; height:<?php echo $img_height ; ?>px; width:<?php echo $page_width-($img_left_width+$padding) ; ?>px; float:right; overflow:hidden;" >
                        <a href="employer_signup.php" style="height:120px; background:url(images/graphics/employer_home/signup_new.png); display:block;" ></a>
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
                                <h4>Existing Employer Login</h4>
                                <?php include('_login_form.php') ; ?>
                                <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="cell" style="width:454px;" >
                    	<h2>Welcome to <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></h2>
                        <p>iPloy is a specialist graduate recruitment website with a difference. 
We give both the graduate &amp; the employer a new, fresh &amp; interactive 
path through the graduate recruitment journey.</p>
                        <p>iPloy provides the information &amp; tools you need to find the right employee. Employers use our interactive, searchable database to overcome the increasing difficulty of locating the graduates they require, despite application volumes increasing. </p>
                   		<p>iPloy offer you the opportunity to interact &amp; get to know your potential employees through our unique online service of text, email and video before even reaching the interview stage.</p>
                        <div class="lister" >
                            <ul>
                                <li><?php echo draw_icon('ico_1.png','images/graphics/employer_home/') ; ?><span class="Highlight" >DEFINE</span> your iPloy candidate</li>
                                <li><?php echo draw_icon('ico_2.png','images/graphics/employer_home/') ; ?><span class="Highlight" >SEARCH</span> the iPloy database</li>
                                <li><?php echo draw_icon('ico_3.png','images/graphics/employer_home/') ; ?><span class="Highlight" >SELECT</span> iPloy candidates from your search</li>
                                <li><?php echo draw_icon('ico_4.png','images/graphics/employer_home/') ; ?>Pay to <span class="Highlight" >CONTACT</span> the selected iPloy candidates</li>
                                <li><?php echo draw_icon('ico_5.png','images/graphics/employer_home/') ; ?>Use iPloy to Message, Email and Video Interview, then <span class="Highlight" >EMPLOY</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="cell" style="width:480px; margin-right:0;" >
                        <h2>How it Works</h2>
                        <div align="center" ><img src="images/graphics/employer_home/how_it_works.jpg" alt="" name="wordcloud" width="470" height="268" border="0" id="wordcloud" /></div>
                    </div>
                </div>
                <div class="row3" >
                    <div class="cell" >
                        <img src="images/graphics/employer_home/demo_profile_link.png" alt="" width="306" height="180" border="0" usemap="#Map" />
                        <map name="Map" id="Map"><area shape="rect" coords="133,144,301,176" href="view_profile.php?profileid=224070911105646" />
                        </map>
                    </div>
                    <div class="cell" style="background:url(images/graphics/employer_home/pay_to_view_bg.png) no-repeat center;" >
                        <?php
                            include_once('_system/classes/products.php') ;
                            $pf = new products ;
                            $pf->setProductCategoryId('1') ;
                            $products = $pf->getProductList() ;	
                            echo '<ul class="prods" >' ;
                            foreach($products as $product){
                                echo '<li><a href="home.php?action=credits&subaction=buy&product_id='.$product['id'].'" class="buy"  >Buy now</a> <span class="price" >&pound;'.number_format($product['price'],2).'</span> <span class="creds" >'.$product['number_of_credits'].' Graduate'.($product['number_of_credits']==1 ? '' : 's').'</span></li>' ;
                            }
                            echo '</ul>' ;
                        ?>
                    </div>
                    <div class="celllast" style="background:url(images/graphics/employer_home/know_your_grads_bg.png) no-repeat center;" ></div>
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
