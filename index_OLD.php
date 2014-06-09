<?php

	include_once('_system/_config/configure.php') ;
	include_once('_system/inc/app_top.php') ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Find Graduates / Graduate Jobs - <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<meta name="description" content="" />
<meta name="keywords" content="" />
<?php include('_global_head.php') ; ?>
<style type="text/css" >
body, html, .homecontain, a, a:hover {
	color:#FFF;
	text-decoration:none;
}
body, html, #homecontain {
	overflow:hidden;
	width:100%;
	height:100%;
	font-size:26px;
	line-height:32px;
	min-height:400px;
	position:relative;
}
#logo {
	position:absolute;
	background:url(images/graphics/index/logo.png) no-repeat;
	width:400px;
	height:151px;
	top:50%;
	left:50%;
	margin-top:-270px;
	margin-left:-210px;
	z-index:10;
}
#colorband {
	position:absolute;
	width:100%;
	top:50%;
	margin-top:-164px;
	height:360px;
	overflow:hidden;
	background:url(images/graphics/index/bg_stripe.jpg) center repeat-y;
}
#colorband .cblink {
	width:50%;
	height:240px;
	position:relative;
	display:block;
	margin-top:60px;
	line-height:0em;
	font-size:0em;
}
#colorband #cbleft {
	float:left;
}
#colorband #cbright {
	float:right;
}
#colorband .cblink .img {
	width:500px;
	height:180px;
	display:block;
	position:absolute;
	top:50%;
	margin-top:-90px;
}
#colorband #cbleft .img {
	background-image:url(images/graphics/index/img_left.png);
	background-position:top left;
	right:6%;
}
#colorband #cbright .img {
	background-image:url(images/graphics/index/img_right.png);
	background-position:top left;
	left:6%;
}
#colorband #cbleft:hover .img, #colorband #cbright:hover .img {
	background-position:bottom left;
}
#scroll_logo {
	position:absolute;
	background:url(images/graphics/index/home_label_new.png);
	left:50%;
	top:50%;
	width:340px;
	height:61px;
	margin-top:158px;
	margin-left:-170px;
	z-index:10;
}
#scrolling_mask {
	z-index:11;
}
#scrolling, #scrolling_mask {
	position:absolute;
	left:50%;
	top:50%;
	width:960px;
	height:50px;
	overflow:hidden;
	margin-top:225px;
	margin-left:-480px;
}
#scrolling {
	background:#9B9B9B;
	z-index:10;
	border-radius:10px;
}
#scrolling img {
	float:left;
}
#scrolling_content {
	position:absolute;
	margin-left:140px;
	/* margin-left:960px; */
}
</style>
</head>
<body>
<script language="javascript" type="text/javascript" >
var animationDuration = 150000 ;
var fadeInPage = true ;
var useMootoolsFadeInit = false ;
var fadeInScroller = true ;
var firstRun = true ;
function animateContent(){
	originalContentWidth = parseFloat($('scrolling_content').getStyle('width'))/2 ;
	if(firstRun==true){
		if(fadeInScroller==true){
			$('scrolling_content').getElements('img').set('morph', {duration: 1200});
			$('scrolling_content').getElements('img').morph({'opacity':1});
		}
		animationTime = ((parseFloat($('scrolling_content').getStyle('margin-left'))/originalContentWidth)*(animationDuration/100)).round()*100 ;
		animationMarginLeft = 0 ;
		firstRun = false ;
	} else {
		animationTime = animationDuration ;
		animationMarginLeft = 0-originalContentWidth ;
		$('scrolling_content').setStyles({'margin-left':0});
	}
	$('scrolling_content').set('morph', {duration: animationTime, transition: 'linear'});
	$('scrolling_content').morph({'margin-left':animationMarginLeft});
	animateContent.delay(animationTime) ;
}
window.addEvent('domready', function(){// Dom open
	$('scrolling_content').innerHTML = $('scrolling_content').innerHTML + $('scrolling_content').innerHTML ;
	var total_width = 0 ;
	if(fadeInScroller==true){
		$('scrolling_content').getElements('img').setStyle('opacity',0);
	}
	$('scrolling_content').getElements('img').each(function(img){
		total_width = total_width + parseFloat(img.getProperty('width'))
	});
	$('scrolling_content').setStyles({'width':total_width});
	animateContent.delay(1000) ;
	/*
	$$('.cblink').each(function(thisLink){
		thisLink.addEvent('mouseover',function(){
			fadeCbLinks(this.id);
		});
		thisLink.addEvent('mouseout',function(){
			$$('.cblink').morph({'opacity':1});
		});
	})
	*/
	if(fadeInPage==true){
		if(useMootoolsFadeInit==true){
			$('logo').setStyle('opacity',0);
			$('colorband').setStyle('opacity',0);
			$('scroll_logo').setStyle('opacity',0);
			$('scrolling').setStyle('opacity',0);
		}
		initPageEffects() ;
	}
});// Dom open

function initPageEffects(){
	$('logo').set('morph', {duration: 1200});
	$('colorband').set('morph', {duration: 1200});
	$('scroll_logo').set('morph', {duration: 1200});
	$('scrolling').set('morph', {duration: 1200});
	(function(){ $('logo').morph({'opacity':1}) }).delay(200) ;
	(function(){ $('colorband').morph({'opacity':1}) }).delay(600) ;
	(function(){ $('scroll_logo').morph({'opacity':1}) }).delay(1000) ;
	(function(){ $('scrolling').morph({'opacity':1}) }).delay(1000) ;
}

function fadeCbLinks(whichLink){
	//alert(whichLink) ;
	$$('.cblink').morph({'opacity':0.6});
	$(whichLink).morph({'opacity':1});
}

</script>
<?php include('_global_body_start.php') ; ?>
<div id="homecontain" align="center" ><!-- Alignment Div -->
	<div id="logo" ></div>
    <div id="scroll_logo" ></div>
    <div id="scrolling_mask" >
    	<img src="images/graphics/index/scroll_mask.png" width="960" height="50" border="0" alt="" />
    </div>
    <div id="scrolling" class="mooScroll" >
    	<div id="scrolling_content" ><img src="images/graphics/index/scrollcontent1.jpg" width="4801" height="50" border="0" alt="" /><img src="images/graphics/index/scrollcontent2.jpg" width="4801" height="50" border="0" alt=""  /></div>
    </div>
    <div id="colorband" >
    	<a href="employers.php" id="cbleft" class="cblink" >
        	<span class="img" >Employers Find Graduates</span>
        </a>
        <a href="graduates.php" id="cbright" class="cblink" >
        	<span class="img" >Graduates Get Jobs</span>
        </a>
    </div>
    <!--
    <div class="homeleft" >
        <div class="img" >
            <a href="employers.php" >
            Employers <br />
            Find <br />
            Graduates</a>
        </div>
  </div>
    <div class="homeright" >
    	<div class="img" >
            <a href="graduates.php" >
            Graduates <br />
            Get <br />
            Jobs</a>
        </div>
  </div>
  -->
</div><!-- Alignment Div End -->
<script language="javascript" type="text/javascript" >
if(fadeInPage==true&&useMootoolsFadeInit==false){
	document.getElementById('logo').style.opacity = 0 ;
	document.getElementById('colorband').style.opacity = 0 ;
	document.getElementById('scroll_logo').style.opacity = 0 ;
	document.getElementById('scrolling').style.opacity = 0 ;
}
</script>
<?php include('_global_body_end.php') ; ?>
</body>
</html>
<?php include_once('_system/inc/app_bottom.php') ; ?>
