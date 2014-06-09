<?php

	session_start() ;

	$base_directory = '../userfiles/youtube_cache' ;

	if(!is_dir($base_directory)){
		mkdir($base_directory) ;
	}

	if($_SESSION['youtube_id']!=''){
		if(!is_dir($base_directory.'/'.$_SESSION['user_id'])){
			mkdir($base_directory.'/'.$_SESSION['user_id']) ;
		}
		$data = file_get_contents('http://gdata.youtube.com/feeds/api/users/'.$_SESSION['youtube_id'].'/uploads') ;
		$cacehFile = $base_directory.'/'.$_SESSION['user_id'].'/uploads.xml' ;
		if(file_exists($cacehFile)){
			unlink($cacehFile) ;
		}
		$handle = fopen($cacehFile, "w");
		fwrite($handle, $data); 
		fclose($handle) ;
		// loop videos
		include_once('../_system/classes/youtube.php') ;
		$yt = new youtube ;
		$yt->setUserName($_SESSION['youtube_id']) ;
		$yt->setUserId($_SESSION['user_id']) ;
		$ytVideos = $yt->setPathFix('../') ;
		$ytVideos = $yt->getVideoList() ;

		if(!is_dir($base_directory.'/'.$_SESSION['user_id'].'/videos')){
			mkdir($base_directory.'/'.$_SESSION['user_id'].'/videos') ;
		}
		foreach($ytVideos as $video){
			//$videoFeedUrl = 'http://gdata.youtube.com/feeds/api/videos/'.$this->videoId ;
			// echo $video['videoId'].'<br />' ;
			$data = file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$video['videoId']) ;
			$cacehFile = $base_directory.'/'.$_SESSION['user_id'].'/videos/'.$video['videoId'].'.xml' ;
			if(file_exists($cacehFile)){
				unlink($cacehFile) ;
			}
			$handle = fopen($cacehFile, "w");
			fwrite($handle, $data); 
			fclose($handle) ;
		}
		// sort the redirect
		if(strstr($_SERVER['HTTP_REFERER'],'action=youtube')){
			$redirect = '../home.php?action=youtube&subaction=manage' ;
			if(strstr($_SERVER['HTTP_REFERER'],"subaction=manage")){
				$redirect = $redirect.'&refresh=uploads' ;
			}
		} else {
			$redirect = $_SERVER['HTTP_REFERER'] ;
		}

	} else {
		// force logout
		$redirect = '../login.php?action=logout' ;
	}
	header("Location: ".$redirect) ;


?>