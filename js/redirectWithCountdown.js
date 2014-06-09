
var cd_loc = '' ;
var cd_time = 0 ;
var cd_span_id = 0 ;
var first_run = true ;
var cd_periodical = '' ;

function countdown(){
	if(first_run!=true){ cd_time = cd_time - 1 ; }// if not first run, take off 1
	postfix = ' second' ;
	if(cd_time!=1){ postfix = postfix+'s' ; }
	$(cd_span_id).innerHTML = cd_time+postfix ;// update the text
	if(cd_time==0){ $clear(cd_periodical) ; window.location = cd_loc ; }// if 0, redirect
	first_run = false ;
}

function redirectWithCountdown(redirect_loc,redirect_time,redirect_countdown_span_id){
	cd_loc = redirect_loc ;
	cd_time = redirect_time ;
	cd_span_id = redirect_countdown_span_id ;
	cd_periodical = countdown.periodical(1000);
}