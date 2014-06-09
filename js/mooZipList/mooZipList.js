
var mooZipListClass = 'mooZipList' ;
var mooZipListheights = new Array() ;
var mooZipListTextClosed = 'Show Options' ;
var mooZipListTextOpen = 'Hide Options' ;
// init function
function mooZipListInit(){
	var mooZipListCount = 0 ;
	$$('.'+mooZipListClass).each(function(mooZipList){
		if(mooZipList.id==''){
			mooZipList.id = 'mzlid'+mooZipListCount ;
		}
		// save current height
		mooZipListheights[mooZipList.id] = parseFloat(mooZipList.offsetHeight) ;
		// inject the toggler div
		togglerDiv = new Element('div', {
			id:mooZipList.id+'_toggle',
			class:mooZipListClass+'_toggle'
		}) ;
		mooZipList.setStyles({
			'height':0,
			'overflow':'hidden'
		});
		togglerDiv.inject(mooZipList,'after');
		mooZipListCount++ ;
	});
	// add the toggler events and write the innerHTML
	$$('.'+mooZipListClass+'_toggle').each(function(mooZipListToggle){
		mooZipListToggle.innerHTML = mooZipListTextClosed ;
		mooZipListToggle.addEvent('click',function(){
			thisId = this.id.replace('_toggle','') ;
			if(parseFloat($(thisId).offsetHeight)<mooZipListheights[thisId]){
				$(thisId).morph({
					'height':mooZipListheights[thisId]
				});
				this.innerHTML = mooZipListTextOpen ;
				(function(){ new Fx.Scroll(window).toElement(thisId) ; }).delay(500) ;
			} else {
				$(thisId).morph({
					'height':0
				});
				this.innerHTML = mooZipListTextClosed ;
			}
		});
	});
}
window.addEvent('domready', function(){
	mooZipListInit() ;
});