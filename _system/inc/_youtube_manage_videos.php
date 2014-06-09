<?php

	// update the graduate profile table to show videos are or arent present
	// Open
	$conn = new ConnectionSimple ;
	$conn->connect(DM_DB_NAME) ;
	if($vidModAction!=false){
		$newVal = false ;
		if($vidModAction=='blank'||($vidModAction=='remove'&&sizeof($videos)==0)){
			$newVal = '0' ;
		} elseif($vidModAction=='add'&&sizeof($videos)==1){
			$newVal = '1' ;
		}
		if($newVal!==false){
			$sql = "UPDATE graduates SET has_video='".$newVal."' WHERE login_id='".$_SESSION['user_id']."' ; " ;
			mysql_query($sql) ;
		}
	}
	// Close
	$conn->disconnect(DM_DB_NAME) ;


	// continue

	if($_GET['refresh']=='uploads'){
		$screen_message = draw_icon(ICON_GOOD).'You Youtube video feed was successfully updated' ;
		$screen_message_type = 'success' ;
	}

	if($screen_message==''){
		$screen_message = draw_icon(ICON_ALERT).'If you have recently modified your videos on Youtube, please <a href="xml_cache/youtube_uploads.php" >click here to reload this list</a>' ;
		$screen_message_type = 'notice' ;
	}

	$ytVideos = $yt->getVideoList() ;

?>
<h1>Manage Youtube Videos</h1>
<a name="vids" ></a>
<?php include('_system/inc/_screen_message_handler.php') ; ?>

<p><span style="float:right;"><?php echo draw_icon('refresh.png') ; ?><a href="xml_cache/youtube_uploads.php" >Click here to refresh video list</a></span> <?php echo draw_icon('youtube.gif') ; ?><a href="http://www.youtube.com/my_videos" target="_blank" >Manage your youtube videos at www.youtube.com</a></p>
<?php

    if(sizeof($ytVideos)==0){
        ?>
        <p class="notice" ><?php echo draw_icon(ICON_ALERT) ; ?>You have not yet added any videos to your Youtube account. <a href="http://www.youtube.com/my_videos" target="_blank" >Click here to add them now</a></p>
        <div class="greydivider" ></div>
        <?php
    } else {
		include_once('_system/functions/in_array_multidimensional.php') ;
		echo '<div class="vids" >'."\n" ;
		foreach($ytVideos as $ytVideo){
			if(in_array_multidimensional($ytVideo['videoId'],$videos)){
				echo '<div class="vid added" title="'.htmlspecialchars($ytVideo['title']).'" >'."\n" ; 
				// delete link
				echo '<a href="?action='.$_GET['action'].'&amp;subaction='.$_GET['subaction'].'&amp;removevidid='.$ytVideo['videoId'].'&w=w#vids" class="remove" ><img class="icon" src="images/icons/24/delete.png" width="24" height="24" border="0" />Remove</a>' ;
			} else {
				echo '<div class="vid notadded" title="'.htmlspecialchars($ytVideo['title']).'" >'."\n" ; 
				// add link
				echo '<a href="?action='.$_GET['action'].'&amp;subaction='.$_GET['subaction'].'&amp;addvidid='.$ytVideo['videoId'].'&w=w#vids" class="add" ><img class="icon" src="images/icons/24/add.png" width="24" height="24" border="0" />Add</a>' ;
			}
			echo '<div class="img" ><img src="'.$ytVideo['thumb'].'" width="'.$ytVideo['thumbWidth'].'" height="'.$ytVideo['thumbHeight'].'" border="0" /></div>'."\n" ;
			echo '</div>'."\n" ; 
		}
		echo '</div>'."\n" ;
	}

?>
<p><?php echo draw_icon(ICON_BACK) ; ?><a href="?action=<?php echo $_GET['action'] ; ?>" >Back to profile video settings</a></p>