
window.addEvent('domready', function(){
	var box = new CeraBox();
	$$('.urlpop a').each(function(thisLink){
		this_fileExtARray = thisLink.href.split(".") ;
		this_fileExt = this_fileExtARray[this_fileExtARray.length-1] ;
		if(this_fileExt=='jpg'||this_fileExt=='jpeg'||this_fileExt=='png'||this_fileExt=='gif'){
			thisLink.addClass('urlpop') ;
		}
	});
	box.addItems('a[class="urlpop"]', {
		displayTitle: false,
		animation: 'ease',
		loaderAtItem: true,
		group: false
	});
});