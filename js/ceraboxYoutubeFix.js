
window.addEvent('domready', function(){
	var urlfix = 'http://youtube.com/embed/' ;
	$$('.youtube').each(function(youtubeVid){
		splitVar = youtubeVid.href.split('v=') ;
		splitVar = splitVar[1].split('&') ;
		youtubeVid.href = urlfix+splitVar[0]+'?rel=0&autoplay=1' ;
	});
});