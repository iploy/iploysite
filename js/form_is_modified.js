
var is_submition = false ;
var is_modified = false ;
function isSumbition(){
	is_submition = true ;
}
function isModified(){
	is_modified = true ;
}
window.onbeforeunload = function () {
	if(is_submition==false&&is_modified==true){
		return 'You may lose your data if you did not press the submit button at the bottom of this form after changing your profile information.' ;
	}
}