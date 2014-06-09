
var mooConfirmClass = 'mooConfirm' ;
var mooConfirmMsgArray = new Array() ;
// mooConfirm link confirmation by Rob Sheldrake @ Devmac Systems UK
function mooConfirmInit(){
	var mcCount = 0 ;
	$$('.'+mooConfirmClass).each(function(mooConfirm){
		if(mooConfirm.id==''){
			mooConfirm.id = 'mcAutoId'+mcCount ;
		}
		mooConfirmMsgArray[mooConfirm.id] = mooConfirm.title ;
		mooConfirm.title = '' ;
		mooConfirm.addEvent('click',function(event){
			event.stop() ;
			if(confirm('CONFIRMATION REQUIRED\n'+mooConfirmMsgArray[this.id]+'\nAre you sure you wish to continue?')){
				document.location = this.href ;
			}
		});
		mcCount++ ;
	});
}
window.addEvent('domready', function(){
	mooConfirmInit() ;
});