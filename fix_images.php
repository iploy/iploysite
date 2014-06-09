<?php

	include('_system/_config/configure.php') ;
	include('_system/classes/image_manipulation.php') ;

	$imgLoc = 'userfiles/photo/' ;

	foreach(scandir($imgLoc) as $item){
		if(is_dir($imgLoc.$item)&&substr($item,0,1)!='.') {
			echo $item."<br />\n" ;
			foreach(scandir($imgLoc.$item) as $img){
				$oldLoc = $imgLoc.$item.'/'.$img ;
				$newLoc = $imgLoc.$item.'/_large/'.$img ;
				if(file_exists($newLoc)&&!is_dir($newLoc)){
					unlink($newLoc) ;
				}
				if(!is_dir($oldLoc)&&substr($img,0,1)!='.') {
					echo '<b>OLD</b>:'.$oldLoc."<br />" ;
					echo '<img src="'.$oldLoc.'" width="100" />'."<br />\n" ;
					// Get the upload size
					$image_info = getimagesize($oldLoc);
					$upload_width = $image_info[0] ;
					$upload_height = $image_info[1] ;
					echo '<b>Original Size</b>: '.$upload_width.'x'.$upload_height."<br />\n" ;;
					// Work out which side of the LARGE image to maintain
					if(($upload_width/$upload_height)>($upload_height/$upload_width)&&($upload_width/$upload_height)>(IMAGE_UPLOAD_LARGE_WIDTH/IMAGE_UPLOAD_LARGE_HEIGHT)){
						$large_scale_type = 'height_max' ;
					} else {
						$large_scale_type = 'width_max' ;
					}
					echo '<b>Scale Type</b>: '.$large_scale_type."<br />\n" ;
					echo '<b>NEW</b>:'.$newLoc."<br />" ;
					echo '<div style="width:'.IMAGE_UPLOAD_LARGE_WIDTH.'px;height:'.IMAGE_UPLOAD_LARGE_HEIGHT.'px;background:url('.$newLoc.') center;" ></div>'."\n" ;
					// Create the images
					$image = new SimpleImage();
					$image->load($oldLoc);
					// large
					if($large_scale_type=='width_max'){
						$image->resizeToWidth(IMAGE_UPLOAD_LARGE_WIDTH);
					} else {
						$image->resizeToHeight(IMAGE_UPLOAD_LARGE_HEIGHT);
					}
					$image->save($newLoc);
				}
			}
			echo "<hr />\n" ;
		}
	}

?>