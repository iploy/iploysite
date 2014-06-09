function check_me(cm_id,cm_type){
	if(cm_type=='multi'){
		if(document.getElementById(cm_id).checked){
			document.getElementById(cm_id).checked = false ;
		} else {
			document.getElementById(cm_id).checked = true ;
		}
	} else {
		document.getElementById(cm_id).checked = true ;
	}
}

function checkall(checkall_id,checkall_count){
	if(document.getElementById(checkall_id+0).checked==true){
		check_value = false ;
	} else {
		check_value = true ;
	}
	for(i=0;i<checkall_count;i++){
		document.getElementById(checkall_id+i).checked = check_value ;
	}
}
