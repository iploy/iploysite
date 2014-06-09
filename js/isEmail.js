
function isEmail(ec_fldid) {
	var str = document.getElementById(ec_fldid).value ;
	var at="@" ;
	var dot="." ;
	var lat=str.indexOf(at) ;
	var lstr=str.length ;
	var ldot=str.indexOf(dot) ;
	if (str==''){
		return false ;
	} else if (str.indexOf(at)==-1){
		return false ;
	} else if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		return false ;
	} else if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		return false ;
	} else if (str.indexOf(at,(lat+1))!=-1){
		return false ;
	} else if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		return false ;
	} else if (str.indexOf(dot,(lat+2))==-1){
		return false ;
	} else if (str.indexOf(" ")!=-1){
		return false ;
	} else {
		return true	;	
	}
}