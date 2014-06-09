<?php

function progress_colour($the_number){

	if($the_number<=0){
		return '#FFF' ;
	}elseif($the_number<=20){
		return '#990000' ;
	}elseif($the_number<=40){
		return '#7B1400' ;
	}elseif($the_number<=60){
		return '#5C2800' ;
	}elseif($the_number<=80){
		return '#3E3C00' ;
	}elseif($the_number<100){
		return '#205100' ;
	}elseif($the_number=100){
		return '#006600' ;
	}

}

?>