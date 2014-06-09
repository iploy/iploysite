

window.addEvent('domready', function(){
	var mooZipMasterClass = 'mooZip' ; // Container Class
	var mooZipElementClass = 'zip' ; // Class for the zipable regions
	var mooZipOpenClass = 'mooZipOpen' ; // Class to have elements start open
	var mooZipUseZipAll = true ; // Should mooZip zip the other elemnts when opening a new one
	var mooZipUseScrollTo = true ; // Should mooZip zip the other elemnts when opening a new one
	var i = 0 ;
	var mooZipOrigHeights = new Array() ;
	var mooZippedHeights = new Array() ;
	var togglerElement = 'h4' ; // can be a class or specific tag type
	var mooZipBgCache ;
	function mooZipAll(mooZipId){
		$(mooZipMasterClass+'_'+mooZipId).getElements('.'+mooZipElementClass).each(function(zip){
			thisArray = zip.id.split('_') ;
			zip.morph({'height':mooZippedHeights[thisArray[1]][thisArray[2]]}) ;
		}) ;
	}
	function mooZipMe(mooZipId,zipId){
		if(mooZipUseZipAll==true){ mooZipAll(mooZipId) ; }
		if(parseFloat($(mooZipMasterClass+'_'+mooZipId+'_'+zipId).getStyle('height')).round()<mooZipOrigHeights[mooZipId][zipId]){
			$(mooZipMasterClass+'_'+mooZipId+'_'+zipId).morph({'height':mooZipOrigHeights[mooZipId][zipId]}) ;
			if(mooZipUseScrollTo==true){
				(function(){ new Fx.Scroll(window).toElement(mooZipMasterClass+'_'+mooZipId+'_'+zipId) ; }).delay(500) ; // 
			}
		} else {
			$(mooZipMasterClass+'_'+mooZipId+'_'+zipId).morph({'height':mooZippedHeights[mooZipId][zipId]}) ;
		}
	}
	$$('.'+mooZipMasterClass).each(function(mooZip){
		mooZipOrigHeights[i] = new Array() ;
		mooZippedHeights[i] = new Array() ;
		mooZip.id = mooZipMasterClass+'_'+i ;
		// setup togglers
		z = 0 ;
		mooZip.getElements('.'+mooZipElementClass).each(function(zip){
			mooZipOrigHeights[i][z] = parseFloat(zip.getStyle('height')).round() ;
			mooZippedHeights[i][z] = parseFloat(zip.getElement(togglerElement).getStyle('height')).round() ;
			if(zip.getAttribute('class').indexOf(mooZipOpenClass)==-1){
				zip.setStyles({'height':mooZippedHeights[i][z]}) ;
			}
			zip.id = mooZip.id+'_'+z ;
			zip.getElement(togglerElement).id = mooZip.id+'_'+z+'_toggler' ;
			// add zip event
			zip.getElement(togglerElement).addEvent('click',function(){
				thisArray = this.id.split('_') ;
				mooZipMe(thisArray[1],thisArray[2]) ;
			});
			z++ ;
		});
		i++ ;
	}) ;
});
