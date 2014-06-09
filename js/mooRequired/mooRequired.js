var mooRequiredClass = 'mooRequired' ;
var mooRequiredListClass = 'mooRequiredList' ;
var mooRequiredHighlightColor = '#F00' ;
var mooRequiredBorder = new Array() ;
function mooRequiredCheck(mrFld,mrType){
	if(mrType=='list'){
		// check what type of list it is
		var foundOne = false ;
		if($(mrFld).getElements('select').length>0){
			if($(mrFld).getElement('select').value==''){
				$(mrFld).setStyle('border',mooRequiredHighlightColor+' 1px solid');
			} else {
				$(mrFld).setStyle('border',mooRequiredBorder[mrFld]['color']+' 1px solid');
			}
			//alert(mrFld+' is a select') ;
		} else if($(mrFld).getElements('input[type="checkbox"]').length>0||$(mrFld).getElements('input[type="radio"]').length>0){
			$(mrFld).getElements('input').each(function(mrInput){
				if(mrInput.checked==true){
					foundOne = true ;
				}
			});
			if(foundOne==false){
				$(mrFld).setStyles({
					'border':mooRequiredHighlightColor+' 1px solid',
					'padding':10
				});
			} else {
				$(mrFld).setStyles({
					'border':mooRequiredBorder[mrFld]['color']+' 1px solid',
					'padding':mooRequiredBorder[mrFld]['padding']
				});
			}
		}
	} else {
		// simple check for basic fields
		if($(mrFld).value==''){
			$(mrFld).setStyle('border-color',mooRequiredHighlightColor);
		} else {
			$(mrFld).setStyle('border-color',mooRequiredBorder[mrFld]['color']);
		}
	}
}
function mooRequiredInit(){
	var mrCount = 0 ;
	// basic form fields
	$$('.'+mooRequiredClass).each(function(mooRequired){
		if(mooRequired.id==''){
			mooRequired.id = mooRequiredClass+'id'+mrCount ;
			mrCount++ ;
		}
		mooRequiredBorder[mooRequired.id] = new Array() ;
		mooRequiredBorder[mooRequired.id]['width'] = mooRequired.getStyle('border-width') ;
		mooRequiredBorder[mooRequired.id]['style'] = mooRequired.getStyle('border-style') ;
		mooRequiredBorder[mooRequired.id]['color'] = mooRequired.getStyle('border-color') ;
		mooRequiredBorder[mooRequired.id]['padding'] = mooRequired.getStyle('padding') ;
		mooRequiredCheck(mooRequired.id,'');
	});
	// list regions
	$$('.'+mooRequiredListClass).each(function(mooRequired){
		mooRequired.setStyles({
			'display':'inline-block'
		});
		if(mooRequired.id==''){
			mooRequired.id = mooRequiredClass+'id'+mrCount ;
			mrCount++ ;
		}
		mooRequiredBorder[mooRequired.id] = new Array() ;
		mooRequiredBorder[mooRequired.id]['width'] = mooRequired.getStyle('border-width') ;
		mooRequiredBorder[mooRequired.id]['style'] = mooRequired.getStyle('border-style') ;
		mooRequiredBorder[mooRequired.id]['color'] = mooRequired.getStyle('border-color') ;
		mooRequiredBorder[mooRequired.id]['padding'] = mooRequired.getStyle('padding') ;
		//mooRequired.addEvent('keyup',function(){
			mooRequiredCheck(mooRequired.id,'list');
		//});
	});
}
window.addEvent('domready', function(){
	mooRequiredInit() ;
});