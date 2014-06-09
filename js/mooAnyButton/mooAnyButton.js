var mooAnyArray = new Array() ;
function mooAnyListInit(){
	var inject_str = '<ul><li><input type="checkbox" id="mooAnyButton_{NUM}" name="any" value="1" onclick="isModified(); mooAnyFunction(\'{NUM}\');" /><span onclick="isModified(); check_me(\'mooAnyButton_{NUM}\',\'multi\'); mooAnyFunction(\'{NUM}\');" id="mooAnyLabel_{NUM}" >Any</span></li></ul>' ;
	var mooAnyCounter = 0 ;
	$$('.mooAnyList').each(function(mooAnyList){
		mooAnyArray[mooAnyCounter] = new Array() ;
		mooAnyList.id = 'mooAnyList_'+mooAnyCounter ;
		mooAnyArray[mooAnyCounter] = mooAnyList.offsetHeight ;
		mooAnyList.setStyle('overflow','hidden') ;
		mooAnyCounter++ ;
	});
	var mooAnyCounter = 0 ;
	$$('.mooAnyButton').each(function(mooAnyButton){
		mooAnyButton.setStyles({'border-top':'1px solid #CCC','border-bottom':'1px solid #CCC','margin-top':'2px','padding-top':'5px','width':'540px'}) ;
		mooAnyButton.innerHTML = inject_str.replace(/{NUM}/g,mooAnyCounter) ;
		mooAllChecked = true ;
		$('mooAnyList_'+mooAnyCounter).getElements('input').each(function(mooAnyField){
			if(mooAnyField.checked==false){ mooAllChecked = false ; }
		}); 
		if(mooAllChecked==true){
			check_me('mooAnyButton_'+mooAnyCounter,'multi');
			mooAnyFunction(mooAnyCounter,'instant') ;
		}
		mooAnyCounter++ ;
	});
}
function mooAnyFunction(mooAnyId,mooAnytype){
	if($('mooAnyButton_'+mooAnyId).checked){
		new_height = 0 ;
		new_label = 'Any (uncheck to view options)' ;
		new_checked = true ;
	} else {
		new_height = mooAnyArray[mooAnyId] ;
		new_label = 'Any' ;
		new_checked = false ;
	}
	// alert(mooAnyId) ;
	$('mooAnyLabel_'+mooAnyId).innerHTML = new_label ;
	if(mooAnytype=='instant'){
		$('mooAnyList_'+mooAnyId).setStyles({'height':new_height}) ;
	} else {
		$('mooAnyList_'+mooAnyId).morph({'height':new_height}) ;
	}
	$('mooAnyList_'+mooAnyId).getElements('input').each(function(mooAnyField){
		mooAnyField.checked = new_checked ;
	}) ;
	
}
window.addEvent('domready', function(){
	mooAnyListInit() ;
});