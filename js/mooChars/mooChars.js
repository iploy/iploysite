
// Form Field (required) - class="mooChars" (requires an ID to use a remaining count)
// Remaining Count Container (optional) - id="mooChars_{{insert exact form field Id}}" 
var mooCharsPrefix = 'mooChars' ;
var mooCharsMax = 650 ;
var mooCharsTextPrefixGood = '' ;
var mooCharsTextPostfixGood = 'characters remaining' ;
var mooCharsTextPrefixBad = '' ;
var mooCharsTextPostfixBad = 'characters over the limit (please reduce length)' ;
var MooCharsTextUseNegatives = false ; // if true will show negative numbers in the remaining count
var mooCharsMonitorFunctionArray = new Array() ;
var mooCharsMonitorLengthArray = new Array() ;
var mooCharsMaxLengthArray = new Array() ;
var periodical ;

function mooCharsUpdateCount(fldId){
	if(mooCharsMonitorLengthArray[fldId]!=$(fldId).value.length){
		mooCharsRemaining = (mooCharsMaxLengthArray[fldId]-$(fldId).value.length) ;
		if(MooCharsTextUseNegatives==false&&mooCharsRemaining>0){
			mooCharsRemaining = mooCharsRemaining.toString() ;
			mooCharsRemaining = mooCharsRemaining.replace("-","") ;
		}
		if($(fldId).value.length>mooCharsMaxLengthArray[fldId]||$(fldId).value.length==mooCharsMaxLengthArray[fldId]){
			$(mooCharsPrefix+'_'+fldId).addClass('red') ;
		} else {
			$(mooCharsPrefix+'_'+fldId).removeClass('red') ;
		}
		if($(fldId).value.length>mooCharsMaxLengthArray[fldId]){
			mooCharsToScrn = mooCharsTextPrefixBad+' '+mooCharsRemaining+' '+mooCharsTextPostfixBad ;
		} else {
			mooCharsToScrn = mooCharsTextPrefixGood+' '+mooCharsRemaining+' '+mooCharsTextPostfixGood ;
		}
		$(mooCharsPrefix+'_'+fldId).innerHTML = mooCharsToScrn.trim() ;
		mooCharsMonitorLengthArray[fldId] = $(fldId).value.length ;
	}
}

function mooCharsMonitor(fldId,monitorType){
	if(monitorType=='start'){
		mooCharsMonitorFunctionArray[fldId] = function(){
			mooCharsUpdateCount(fldId) ;
		}
		periodical = mooCharsMonitorFunctionArray[fldId].periodical(600) ;
	} else {
		$clear(periodical) ;
		mooCharsMonitorFunctionArray[fldId] = '' ;
	}
}

// DOM, D-DOM, DOM, DOOOOOOOMMMMMMMM.
window.addEvent('domready', function(){
	$$('.'+mooCharsPrefix).each(function(mooChar){
		if(mooChar.getAttribute('maxlength')){
			mooCharsMaxLengthArray[mooChar.id] = parseFloat(mooChar.getAttribute('maxlength')) ;
		} else {
			mooCharsMaxLengthArray[mooChar.id] = mooCharsMax ;
		}
		mooChar.addEvent('focus',function(){
			mooCharsMonitor(this.id,'start') ;
		});
		mooChar.addEvent('blur',function(){
			mooCharsMonitor(this.id,'stop') ;
		});
		mooCharsMonitorLengthArray[mooChar.id] = 0 ;
		mooCharsUpdateCount(mooChar.id);
	});
});
