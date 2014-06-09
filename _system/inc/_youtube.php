<style type="text/css" >
.vids {
	overflow:auto;
	margin:0;
}
.vid {
	float:left;
	margin:0 0 5px 5px;
	position:relative;
	border-width:2px;
	border-style:solid;
	border-color:#222;
}
.vids .added {
	border-color:#FF663A;
}
.vids .notadded {
	border-color:#22B71A;
}
.vid .options .ico {
	margin:2px;
}
.vid .options .middleicon .ico {
	margin:2px 6px 2px 2px;
}
.vid .options {
	right:4px;
	padding:2px 4px;
}
.vid .remove, .vid .add, .vid .options {
	position:absolute;
	z-index:9;
	bottom:4px;
	background:#FFF;
	border-radius:6px ;
}
.vid .remove, .vid .add {
	right:14px;
	padding:0 0 0 10px;
	border-radius:6px 0 0  6px ;
	line-height:21px;
	color:#555;
	border:1px solid #222;
}
.vid .remove:hover, .vid .add:hover {
	text-decoration:none;
}
.vid .remove {
	width:61px;
}
.vid .add {
	width:37px;
}
.vid .remove:hover {
	color:#FF663A;
}
.vid .add:hover {
	color:#22B71A;
}
.vid .add .icon, .vid .remove .icon {
	z-index:10;
	position:absolute;
}
.vid .remove .icon {
	margin:-1px 0 0 50px;
}
.vid .add .icon {
	margin:-1px 0 0 26px;
}
</style>
<?php

	include_once('_system/classes/youtube_graduates.php') ;

	$gy = new graduate_youtube ;
	$gy->setUserId($_SESSION['user_id']) ;
	$gy->setYoutbeUserName($_SESSION['youtube_id']) ;

	$vidModAction = false ;

	if($_SESSION['youtube_id']!=''){
		include_once('_system/classes/youtube.php') ;
		$yt = new youtube ;
		$yt->setUserName($_SESSION['youtube_id']) ;
		$yt->setUserId($_SESSION['user_id']) ;
		$yt->setForcedWidth(180) ;
	}

	// process submissions
	if($_GET['subaction']=='changeusername'){
		$gy->setYoutbeUserName($_POST['username']) ;
		$update = $gy->updateYoutubeUserName(true) ;
		if($update['status']==true){
			$screen_message_type = 'success' ;
			$screen_message = draw_icon(ICON_GOOD).$update['statustxt'] ;
		} else {
		$screen_message_type = 'error' ;
			$screen_message = draw_icon(ICON_BAD).$update['statustxt'] ;
		}
		$vidModAction = 'blank' ;
	}

	// process add/remove
	if($_GET['removevidid']!=''||$_GET['addvidid']!=''){
		if($_GET['removevidid']!=''){
			$gy->setVideoId($_GET['removevidid']) ;
			$action = $gy->removeProfileVideo($_GET['removevidid']) ;
			$vidModAction = 'remove' ;
		} elseif ($_GET['addvidid']!=''){
			$gy->setVideoId($_GET['addvidid']) ;
			$action = $gy->addProfileVideo($_GET['addvidid']) ;
			$vidModAction = 'add' ;
		}
		$screen_message = $action['statustxt'] ;
		if($action['status']==true){
			$screen_message = draw_icon(ICON_GOOD).$screen_message ;
			$screen_message_type = 'success' ;
		} else {
			$screen_message = draw_icon(ICON_BAD).$screen_message ;
			$screen_message_type = 'error' ;
		}
	}

	// process moves
	if($_GET['vidid']!=''&&$_GET['newloc']!=''&&is_numeric($_GET['newloc'])&&$_GET['newloc']>0&&($_GET['dir']=='up'||$_GET['dir']=='down')){
		$gy->setVideoId($_GET['vidid']) ;
		$action = $gy->changeOrder($_GET['newloc'],$_GET['dir']) ;
		$screen_message = $action['statustxt'] ;
		if($action['status']==true){
			$screen_message = draw_icon(ICON_GOOD).$screen_message ;
			$screen_message_type = 'success' ;
		} else {
			$screen_message = draw_icon(ICON_BAD).$screen_message ;
			$screen_message_type = 'error' ;
		}
	}


	// get the list of selected videos
	$videos = $gy->getSelectedVideos() ; // gets IDs only

	include_once('_system/inc/_graduate_progress_bar.php') ;

	if($_GET['subaction']=='manage'){
		include('_system/inc/_youtube_manage_videos.php') ;

	}elseif($_GET['subaction']=='username'||($_GET['subaction']=='changeusername'&&$update['status']!=true)){
		include('_system/inc/_youtube_user_settings.php') ;

	} else {
		?>
		<h1>Youtube Video Administration</h1>
        <?php include('_system/inc/_screen_message_handler.php') ; ?>
		<div class="lister">
			<ul>
				<li><?php echo draw_icon('youtube.gif') ; ?><b>Youtube Profile ID</b>: <?php echo $_SESSION['youtube_id']=='' ? '<a href="?action='.$_GET['action'].'&amp;subaction=username" >Please enter your profile ID</a>' : $_SESSION['youtube_id'].' [<a href="?action='.$_GET['action'].'&amp;subaction=username" >change</a>]' ; ?></li>
			</ul>
		</div>
		
		<h3>Current Videos</h3>
        <?php
		if(sizeof($videos)>0){
			// get the info for each video
			?>
            <div class="vids" >
            <?php
				$vcount = 0 ;
				foreach($videos as $video){
					$vcount ++ ;
					$yt->setVideoId($video['video_id']) ;
					$thisVideo = $yt->getVideoById() ;
					// fix order if required
					if($vcount!=$video['list_order']){
						$gy->fixOrder($video['id'],$vcount) ;
					}
					// now draw
					if($vcount>1){
						$up_icon = 'up.png' ;
						$up_link_prefix = '<a href="?action='.$_GET['action'].'&amp;subaction='.$_GET['subaction'].'&amp;vidid='.$video['video_id'].'&amp;newloc='.($vcount-1).'&amp;dir=up" >' ;
						$up_link_postfix = '</a>' ;
					} else {
						$up_icon = 'up_grey.png' ;
						$up_link_prefix = '' ;
						$up_link_postfix = '' ;
					}
					if($vcount<sizeof($videos)){
						$down_icon = 'down.png' ;
						$down_link_prefix = '<a href="?action='.$_GET['action'].'&amp;subaction='.$_GET['subaction'].'&amp;vidid='.$video['video_id'].'&amp;newloc='.($vcount+1).'&amp;dir=down" >' ;
						$down_link_postfix = '</a>' ;
					} else {
						$down_icon = 'down_grey.png' ;
						$down_link_prefix = '' ;
						$down_link_postfix = '' ;
					}
					echo '<div class="vid" title="'.htmlspecialchars($thisVideo['title']).'" >'."\n" ; 
					echo '<div class="options" >' ;
					echo $up_link_prefix.draw_icon($up_icon).$up_link_postfix ; 
					echo '<span class="middleicon" >'.$down_link_prefix.draw_icon($down_icon).$down_link_postfix.'</span>' ;
					echo '<a href="'.$thisVideo['url'].'" title="'.htmlspecialchars($thisVideo['title']).'" target="_blank" class="lytebox youtube"  >'.draw_icon('play.png').'</a>' ; 
					echo '</div>' ;
					echo '<div class="img" ><img src="'.$thisVideo['thumb'].'" width="'.$thisVideo['thumbWidth'].'" height="'.$thisVideo['thumbHeight'].'" border="0" /></div>'."\n" ;
					echo '</div>'."\n" ; 
				}
			?>
            </div>
            <?php
		} else {
			?>
			<p class="notice"><?php echo draw_icon(ICON_ALERT) ; ?>You have not yet added any videos</p>
			<?php
		}


		if($_SESSION['youtube_id']!=''){
			?>
			<p><?php echo draw_icon('add.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=manage" >Click here to Add / Remove videos featured on your <?php echo $_SESSION['APP_CLIENT_NAME'] ; ?> profile from your Youtube feed</a></p>
			<?php
		} else {
			?>
			<p><?php echo draw_icon('add.png') ; ?><a href="?action=<?php echo $_GET['action'] ; ?>&amp;subaction=username" >Click here to enter your Youtube profile ID and start adding videos</a></p>
			<?php
		}	
	}
?>