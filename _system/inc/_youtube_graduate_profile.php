<style type="text/css" >
.vidEllipsis {
	text-overflow: ellipsis;
	white-space:nowrap;
	overflow:hidden;
	line-height:28px;
	height:28px;
	display:block;
	background:#DDD;
	padding:0 5px;
	color:#555;
	width:310px;
	border-radius:0 0 6px 6px;
}
.altVid {
	margin:4px 0 ;
	border-radius:6px;
}
.altVid:hover, .mainVid:hover .vidEllipsis {
	background:#CCC;
	text-decoration:none;
	color:#555;
}
.vidEllipsis .ico {
	position:static;
	margin:0 6px -4px 0;
}
.mainVid .vidEllipsis {
	padding-left:70px;
	width:245px;
}

.playlogo {
	position:absolute;
	z-index:99;
	margin:88px 0 0 128px;
	margin:198px 0 0 4px;
}
</style>
<?php

	include_once('_system/classes/youtube_graduates.php') ;

	$gy = new graduate_youtube ;
	$gy->setUserId($profile_id) ;
	$gy->setYoutbeUserName($youtube_id) ;
	$gy->setIsEnabled(1) ; // get only enabled videos
	$videos = $gy->getSelectedVideos() ; // gets IDs only

	$vcount = 0 ;
	if(sizeof($videos)>0){
		include_once('_system/functions/in_array_multidimensional.php') ;
		include_once('_system/classes/youtube.php') ;
		$yt = new youtube ;
		$yt->setUserId($profile_id) ;
		$yt->setUserName($youtube_id) ;
		$yt->setUserName($youtube_id) ;
		$yt->setForcedWidth(320) ;
		foreach($videos as $video){
			$vcount++ ;
			$yt->setVideoId($video['video_id']) ;
			$thisVideo = $yt->getVideoById() ;
			if($vcount==1){
				echo '<a href="'.$thisVideo['url'].'" class="youtube mainVid lytebox" target="_blank" >' ;
				echo '<img class="playlogo" src="images/icons/player_play.png" width="64" height="64" border="0" />' ;
				echo '<img src="'.$thisVideo['thumb'].'" width="'.$thisVideo['thumbWidth'].'" height="'.$thisVideo['thumbHeight'].'" border="0" />' ;
				echo '<span class="vidEllipsis" >'.$thisVideo['title'].'</span>' ;
				echo '</a>'."\n" ;
			} else {
				echo '<a href="'.$thisVideo['url'].'" target="_blank" class="youtube altVid vidEllipsis lytebox" >'.draw_icon('video.png').$thisVideo['title'].'</a>'."\n" ;
			}
		}
	} else {
		?>
		<p><?php echo draw_icon(ICON_ALERT) ; ?>This user has not added any videos</p>
		<?php
	}
?>