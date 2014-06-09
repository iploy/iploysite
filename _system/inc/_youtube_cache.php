<?php

	if($_SESSION['user_id']!=''&&$_GET['action']=='youtube'&&$_GET['subaction']=='manage'){
		$videoFeedUrl = 'userfiles/youtube_cache/'.$_SESSION['user_id'].'/uploads.xml' ;
		if(!file_exists($videoFeedUrl)){
			header("Location: xml_cache/youtube_uploads.php");
		}
	}
?>