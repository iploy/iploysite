
<?php
	include_once('_system/inc/_include_mootools_head.php') ;
?>
<!-- Blueprint CSS v0.9 -->
<link rel="stylesheet" href="css/blueprint.css" type="text/css" media="screen" />
<!-- Core document css -->
<link rel="stylesheet" href="css/css.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/navh.css" type="text/css" media="screen" />
<!-- Lytebox 3 including IE7/IE8 Fix -->
<script type="text/javascript" language="javascript" src="js/cerabox/cerabox.js" ></script>
<script language="javascript" type="text/javascript" src="js/ceraboxYoutubeFix.js" ></script>
<link rel="stylesheet" href="js/cerabox/style/cerabox.css" type="text/css" media="screen" />
<script language="javascript" type="text/javascript" >
window.addEvent('domready', function(){
	// Only initiate the cerabox if there are links requiring the lytebox on the page
	// Cerabox for normal images
	/*
	if($$('a[rel="lytebox"]').length>0){
		var box = new CeraBox();
		box.addItems('a[rel="lytebox"]', {
			displayTitle: false,
			animation: 'ease',
			loaderAtItem: true,
			group: false
		});
	}
	*/
	if($$('.lytebox').length>0){
		var box = new CeraBox();
		box.addItems('.lytebox', {
			displayTitle: false,
			animation: 'ease',
			loaderAtItem: true,
			group: false
		});
	}
});
</script>

<style type="text/css" >
.bodymain_right .gradlist .img {
	width:<?php echo IMAGE_UPLOAD_MEDIUM_WIDTH ; ?>px;
	height:<?php echo IMAGE_UPLOAD_MEDIUM_HEIGHT ; ?>px;
}
<?php
	if(($_SESSION['site_style']=='admin'||$_SESSION['site_style']=='superuser')&&$_SESSION['user_level']==''){
		$_SESSION['site_style']='employers' ;
	}
	if($_SESSION['site_style']=='employers'){
		$color = COLOR_EMPLOYER_NORMAL ;
		$over_color = COLOR_EMPLOYER_OVER ;
		$favicon = COLOR_EMPLOYER_FAVICON ;
	} elseif($_SESSION['site_style']=='admin'){
		$color = COLOR_ADMIN_NORMAL ;
		$over_color = COLOR_ADMIN_OVER ;
		$favicon = COLOR_ADMIN_FAVICON ;
	}elseif($_SESSION['site_style']=='superuser'){
		$color = COLOR_SUPERUSER_NORMAL ;
		$over_color = COLOR_SUPERUSER_OVER ;
		$favicon = COLOR_SUPERUSER_FAVICON ;
	} else {
		$color = COLOR_GRADUATE_NORMAL ;
		$over_color = COLOR_GRADUATE_OVER ;
		$favicon = COLOR_GRADUATE_FAVICON ;
	}
?>
a, .Highlight, label, h5, h6, #homegraphics span, .bodymain_right a .username, .row .cell h2, .addresslist .address .Highlight {
	color:<?php echo $color ; ?> ;
}
a:hover, .bodymain_right a:hover .username {
	color:<?php echo $over_color ; ?> ;
}
.header, .li_buttons a, .li_buttons input, #subnav .su_link a, .divider, .search_results a:hover .name, .message_subjeect {
	background-color:<?php echo $color ; ?> ;
}
.li_buttons a:hover, .li_buttons input:hover, #subnav .su_link a:hover {
	background-color:<?php echo $over_color ; ?> ;
}
.search_results a:hover {
	border-color:<?php echo $color ; ?>;
}

.datepicker_carmemo {
	border: 2px solid <?php echo $color ; ?>;
}
.datepicker_carmemo .header {
	background: <?php echo $color ; ?>;
}
.message_container {
	border:1px solid <?php echo $color ; ?>;
}

</style>

<!--
<?php
	echo 'Dynamic config reload forced after '.DYNAMIC_CONFIG_FORCED_RELOAD."\n" ;
	if($force_reload==true){
		echo 'Reload of dynamic config forced'."\n" ;
	}
	echo 'Current Server Time: '.date(DM_PHP_DATE_FORMAT)."\n" ;
	echo 'Last Reload: '.$_SESSION['LAST_LOAD']."\n" ;
	echo 'Next Load at: '.date(DM_PHP_DATE_FORMAT,strtotime(date($_SESSION['LAST_LOAD']).' +'.DYNAMIC_CONFIG_FORCED_RELOAD))."\n" ;
?>
-->

<link rel="shortcut icon" href="images/<?php echo $favicon ; ?>" /> 

<!-- Farcebook Stuff -->
<meta property="og:title" content="<?php echo $_SESSION['APP_SITE_NAME'] ; ?>" />
<meta property="og:image" content="<?php echo SITE_DOMAIN ; ?>images/fb_img.png" />
<meta property="og:site_name " content="<?php echo $_SESSION['APP_SITE_NAME'] ; ?>" />
<meta property="og:url " content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'] ; ?>" />

<noscript>
<style type="text/css" >
.jsrequired {
	height:0px;
	overflow:hidden;
}
</style>
</noscript>