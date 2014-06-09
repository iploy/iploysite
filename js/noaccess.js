function noaccess(type,v1){
	if(type=='message'){
		v1 = typeof(v1) != 'undefined' ? v1 : 'this user' ;
		alert_message = 'PROFILE ACCESS REQUIRED\nYou must purchase '+v1+'\'s profile in order to send this user a message.' ;
	} else {
		// generic message
		alert_message = 'ACCESS REQUIRED\nYou do not have access to this feature.' ;
	}
	alert(alert_message) ;
}