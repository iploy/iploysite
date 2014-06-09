<?php

	if(is_array($screen_message_array)&&sizeof($screen_message_array)>0){
		echo '<p class="error2" >' ;
			echo $screen_message."<br />" ;
			foreach($screen_message_array as $error){
				echo '- '.$error."<br />\n" ;
			}
		echo '</p>' ;
	} elseif($screen_message){
		if(!$screen_message_type){
			$screen_message_type = 'notice' ;
		}
		echo '<p class="'.$screen_message_type.'" >'.$screen_message.'</p>' ;
		$screen_message = '' ;
		$screen_message_type = '' ;
	}

?>